<?php
/**
 * Search User API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$userID = $_POST['user_id'] ?? '';

if (empty($userID)) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$db = getDB();

try {
    $stmt = $db->prepare("
        SELECT id, user_id, CONCAT(first_name, ' ', last_name) as name, role, account_status
        FROM users 
        WHERE user_id = ? AND account_status = 'active'
    ");
    
    $stmt->execute([$userID]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found or not active'
        ]);
        exit;
    }
    
    if ($user['id'] == $_SESSION['user_id']) {
        echo json_encode([
            'success' => false,
            'message' => 'Cannot message yourself'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
    
} catch (Exception $e) {
    error_log("Search user error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Search failed'
    ]);
}
?>
