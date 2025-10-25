<?php
require_once 'config.php';

/**
 * Generate userID from first name, last name, and DOB
 * Format: first3(first)+first3(last)+MMDDYY
 * Example: Michael Brown, 05/06/1984 -> MICBRO050684
 */
function generateUserID($firstName, $lastName, $dob) {
    // Extract first 3 characters of first name (uppercase)
    $first3 = strtoupper(substr($firstName, 0, 3));
    
    // Extract first 3 characters of last name (uppercase)
    $last3 = strtoupper(substr($lastName, 0, 3));
    
    // Extract MMDDYY from DOB
    $date = new DateTime($dob);
    $mmddyy = $date->format('mdY');
    
    // Combine to create userID
    $userID = $first3 . $last3 . $mmddyy;
    
    return $userID;
}

/**
 * Check if userID already exists
 */
function userIDExists($userID) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    if (strlen($password) < 8) {
        return ['valid' => false, 'message' => 'Password must be at least 8 characters long'];
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one uppercase letter'];
    }
    if (!preg_match('/[a-z]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one lowercase letter'];
    }
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one number'];
    }
    return ['valid' => true, 'message' => 'Password is valid'];
}

/**
 * Get all security questions
 */
function getSecurityQuestions() {
    $conn = getDBConnection();
    $result = $conn->query("SELECT question_id, question_text FROM security_questions ORDER BY question_id");
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    $conn->close();
    return $questions;
}

/**
 * Register a new user
 */
function registerUser($data) {
    $conn = getDBConnection();
    
    // Validate required fields
    if (empty($data['firstName']) || empty($data['lastName']) || empty($data['dob']) || 
        empty($data['password']) || empty($data['securityQuestionId']) || 
        empty($data['securityAnswer'])) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    // Validate consent
    if (!isset($data['consentShare']) || $data['consentShare'] !== 'true') {
        return ['success' => false, 'message' => 'You must consent to share information'];
    }
    if (!isset($data['consentTerms']) || $data['consentTerms'] !== 'true') {
        return ['success' => false, 'message' => 'You must agree to terms of service'];
    }
    
    // Validate password
    $passwordValidation = validatePassword($data['password']);
    if (!$passwordValidation['valid']) {
        return ['success' => false, 'message' => $passwordValidation['message']];
    }
    
    // Generate userID
    $userID = generateUserID($data['firstName'], $data['lastName'], $data['dob']);
    
    // Check if userID already exists
    if (userIDExists($userID)) {
        return ['success' => false, 'message' => 'User with this name and date of birth already exists'];
    }
    
    // Hash password and security answer
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $securityAnswerHash = password_hash(strtolower(trim($data['securityAnswer'])), PASSWORD_DEFAULT);
    
    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (user_id, first_name, last_name, dob, password_hash, security_question_id, security_answer_hash, consent_share_info, consent_terms_service) VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1)");
    $stmt->bind_param("sssssis", $userID, $data['firstName'], $data['lastName'], $data['dob'], $passwordHash, $data['securityQuestionId'], $securityAnswerHash);
    
    if ($stmt->execute()) {
        // Auto-login: set session
        $_SESSION['user_id'] = $userID;
        $_SESSION['first_name'] = $data['firstName'];
        $_SESSION['last_name'] = $data['lastName'];
        
        $stmt->close();
        $conn->close();
        return ['success' => true, 'userID' => $userID];
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'Registration failed. Please try again.'];
    }
}

/**
 * Find user by name and DOB for recovery
 */
function findUserForRecovery($firstName, $lastName, $dob) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT user_id, security_question_id FROM users WHERE first_name = ? AND last_name = ? AND dob = ?");
    $stmt->bind_param("sss", $firstName, $lastName, $dob);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return ['success' => true, 'user_id' => $user['user_id'], 'security_question_id' => $user['security_question_id']];
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'No user found with provided information'];
    }
}

/**
 * Verify security answer for recovery
 */
function verifySecurityAnswer($userID, $securityAnswer) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT security_answer_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        
        if (password_verify(strtolower(trim($securityAnswer)), $user['security_answer_hash'])) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Incorrect security answer'];
        }
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'User not found'];
    }
}

/**
 * Reset password
 */
function resetPassword($userID, $newPassword) {
    // Validate password
    $passwordValidation = validatePassword($newPassword);
    if (!$passwordValidation['valid']) {
        return ['success' => false, 'message' => $passwordValidation['message']];
    }
    
    $conn = getDBConnection();
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $passwordHash, $userID);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return ['success' => true, 'message' => 'Password reset successfully'];
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'Password reset failed'];
    }
}

/**
 * Get security question by ID
 */
function getSecurityQuestionByID($questionID) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT question_text FROM security_questions WHERE question_id = ?");
    $stmt->bind_param("i", $questionID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return ['success' => true, 'question_text' => $question['question_text']];
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'Question not found'];
    }
}
?>
