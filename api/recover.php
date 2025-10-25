<?php
/**
 * Account Recovery API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$result = Auth::recoverAccount(
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['dob'],
    $_POST['security_question'],
    $_POST['security_answer']
);

echo json_encode($result);
?>
