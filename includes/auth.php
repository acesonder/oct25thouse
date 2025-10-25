<?php
/**
 * Authentication and Security Functions
 * HIPAA Compliant Security Implementation
 */

require_once __DIR__ . '/../config/database.php';

class Auth {
    
    /**
     * Generate unique user ID based on name and DOB
     */
    public static function generateUserID($firstName, $lastName, $dob) {
        $first3 = strtoupper(substr($firstName, 0, 3));
        $last3 = strtoupper(substr($lastName, 0, 3));
        $dobFormatted = date('mdy', strtotime($dob));
        
        $baseUserID = $first3 . $last3 . $dobFormatted;
        
        // Check if user ID exists, add suffix if needed
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE user_id LIKE ?");
        $stmt->execute([$baseUserID . '%']);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            $baseUserID .= ($count + 1);
        }
        
        return $baseUserID;
    }
    
    /**
     * Hash password securely
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Start secure session
     */
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            
            // Regenerate session ID to prevent fixation
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }
            
            // Check session timeout
            if (isset($_SESSION['last_activity']) && 
                (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
                self::logout();
                return false;
            }
            
            $_SESSION['last_activity'] = time();
        }
        
        return true;
    }
    
    /**
     * Login user
     */
    public static function login($userID, $password) {
        $db = getDB();
        
        try {
            // Get user
            $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$userID]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'message' => 'Invalid User ID or password'];
            }
            
            // Check if account is active
            if ($user['account_status'] !== 'active') {
                return ['success' => false, 'message' => 'Account is not active. Please contact support.'];
            }
            
            // Verify password
            if (!self::verifyPassword($password, $user['password_hash'])) {
                // Log failed attempt
                self::logAudit($user['id'], 'LOGIN_FAILED', 'users', $user['id']);
                return ['success' => false, 'message' => 'Invalid User ID or password'];
            }
            
            // Set session variables
            self::startSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_uid'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            
            // Update last login
            $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Log successful login
            self::logAudit($user['id'], 'LOGIN_SUCCESS', 'users', $user['id']);
            
            return [
                'success' => true, 
                'user' => $user,
                'redirect' => self::getRedirectByRole($user['role'])
            ];
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred. Please try again.'];
        }
    }
    
    /**
     * Get redirect URL based on user role
     */
    public static function getRedirectByRole($role) {
        switch($role) {
            case 'client':
                return '/client/dashboard.php';
            case 'staff':
                return '/staff/dashboard.php';
            case 'service_provider':
                return '/service_provider/dashboard.php';
            case 'sysadmin':
                return '/admin/admin.php';
            case 'volunteer':
                return '/client/dashboard.php';
            default:
                return '/client/dashboard.php';
        }
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Log logout
            if (isset($_SESSION['user_id'])) {
                self::logAudit($_SESSION['user_id'], 'LOGOUT', 'users', $_SESSION['user_id']);
            }
            
            session_unset();
            session_destroy();
        }
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        self::startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }
    
    /**
     * Require login (redirect if not logged in)
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /index.php');
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            header('Location: ' . self::getRedirectByRole($_SESSION['role']));
            exit;
        }
    }
    
    /**
     * Log audit trail for HIPAA compliance
     */
    public static function logAudit($userId, $action, $tableName = null, $recordId = null, $details = null) {
        try {
            $db = getDB();
            $stmt = $db->prepare("
                INSERT INTO audit_log (user_id, action, table_name, record_id, ip_address, user_agent, details)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $userId,
                $action,
                $tableName,
                $recordId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $details
            ]);
        } catch (Exception $e) {
            error_log("Audit log error: " . $e->getMessage());
        }
    }
    
    /**
     * Register new user
     */
    public static function register($data) {
        $db = getDB();
        
        try {
            // Validate required fields
            $required = ['first_name', 'last_name', 'dob', 'password', 'confirm_password', 
                        'security_question', 'security_answer', 'consent', 'terms'];
            
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return ['success' => false, 'message' => 'All fields are required'];
                }
            }
            
            // Validate password match
            if ($data['password'] !== $data['confirm_password']) {
                return ['success' => false, 'message' => 'Passwords do not match'];
            }
            
            // Validate password strength
            if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
                return ['success' => false, 'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters'];
            }
            
            // Check consent and terms
            if ($data['consent'] !== 'true' || $data['terms'] !== 'true') {
                return ['success' => false, 'message' => 'You must accept consent and terms'];
            }
            
            // Generate user ID
            $userID = self::generateUserID($data['first_name'], $data['last_name'], $data['dob']);
            
            // Hash password and security answer
            $passwordHash = self::hashPassword($data['password']);
            $answerHash = self::hashPassword(strtolower(trim($data['security_answer'])));
            
            // Determine role and status
            $role = $data['role'] ?? 'client';
            $status = ($role === 'client' || $role === 'volunteer') ? 'active' : 'pending';
            
            // Insert user
            $stmt = $db->prepare("
                INSERT INTO users (user_id, first_name, last_name, dob, role, password_hash, 
                                  security_question, security_answer_hash, consent_to_share, 
                                  terms_accepted, account_status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $userID,
                $data['first_name'],
                $data['last_name'],
                $data['dob'],
                $role,
                $passwordHash,
                $data['security_question'],
                $answerHash,
                true,
                true,
                $status
            ]);
            
            $newUserId = $db->lastInsertId();
            
            // Create default settings
            $stmt = $db->prepare("INSERT INTO user_settings (user_id) VALUES (?)");
            $stmt->execute([$newUserId]);
            
            // Log registration
            self::logAudit($newUserId, 'USER_REGISTERED', 'users', $newUserId);
            
            // Auto-login for clients and volunteers
            if ($status === 'active') {
                self::startSession();
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['user_uid'] = $userID;
                $_SESSION['role'] = $role;
                $_SESSION['first_name'] = $data['first_name'];
                $_SESSION['last_name'] = $data['last_name'];
                $_SESSION['show_welcome_tour'] = true;
            }
            
            return [
                'success' => true,
                'user_id' => $userID,
                'status' => $status,
                'message' => $status === 'active' ? 'Registration successful!' : 'Registration submitted for approval'
            ];
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }
    
    /**
     * Recover account (get userID)
     */
    public static function recoverAccount($firstName, $lastName, $dob, $securityQuestion, $securityAnswer) {
        $db = getDB();
        
        try {
            $stmt = $db->prepare("
                SELECT * FROM users 
                WHERE first_name = ? AND last_name = ? AND dob = ? AND security_question = ?
            ");
            $stmt->execute([$firstName, $lastName, $dob, $securityQuestion]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'message' => 'No account found with provided information'];
            }
            
            // Verify security answer
            if (!self::verifyPassword(strtolower(trim($securityAnswer)), $user['security_answer_hash'])) {
                self::logAudit($user['id'], 'RECOVERY_FAILED', 'users', $user['id']);
                return ['success' => false, 'message' => 'Incorrect security answer'];
            }
            
            self::logAudit($user['id'], 'ACCOUNT_RECOVERED', 'users', $user['id']);
            
            return [
                'success' => true,
                'user_id' => $user['user_id']
            ];
            
        } catch (Exception $e) {
            error_log("Account recovery error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Recovery failed. Please try again.'];
        }
    }
    
    /**
     * Reset password
     */
    public static function resetPassword($userID, $newPassword, $confirmPassword) {
        if ($newPassword !== $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }
        
        if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            return ['success' => false, 'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters'];
        }
        
        $db = getDB();
        
        try {
            $stmt = $db->prepare("SELECT id FROM users WHERE user_id = ?");
            $stmt->execute([$userID]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }
            
            $passwordHash = self::hashPassword($newPassword);
            
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $stmt->execute([$passwordHash, $userID]);
            
            self::logAudit($user['id'], 'PASSWORD_RESET', 'users', $user['id']);
            
            return ['success' => true, 'message' => 'Password reset successful'];
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Password reset failed. Please try again.'];
        }
    }
}
?>
