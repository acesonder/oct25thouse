<?php
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'getSecurityQuestions':
            echo json_encode(['success' => true, 'questions' => getSecurityQuestions()]);
            break;
            
        case 'register':
            $data = [
                'firstName' => $_POST['firstName'] ?? '',
                'lastName' => $_POST['lastName'] ?? '',
                'dob' => $_POST['dob'] ?? '',
                'password' => $_POST['password'] ?? '',
                'securityQuestionId' => $_POST['securityQuestionId'] ?? '',
                'securityAnswer' => $_POST['securityAnswer'] ?? '',
                'consentShare' => $_POST['consentShare'] ?? '',
                'consentTerms' => $_POST['consentTerms'] ?? ''
            ];
            echo json_encode(registerUser($data));
            break;
            
        case 'findUserForRecovery':
            $firstName = $_POST['firstName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $dob = $_POST['dob'] ?? '';
            echo json_encode(findUserForRecovery($firstName, $lastName, $dob));
            break;
            
        case 'getSecurityQuestion':
            $questionID = $_POST['questionId'] ?? '';
            echo json_encode(getSecurityQuestionByID($questionID));
            break;
            
        case 'verifySecurityAnswer':
            $userID = $_POST['userID'] ?? '';
            $securityAnswer = $_POST['securityAnswer'] ?? '';
            echo json_encode(verifySecurityAnswer($userID, $securityAnswer));
            break;
            
        case 'resetPassword':
            $userID = $_POST['userID'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            echo json_encode(resetPassword($userID, $newPassword));
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
