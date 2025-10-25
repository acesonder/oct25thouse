<?php
/**
 * Get User API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

$userId = $_GET['user_id'] ?? 0;

$db = getDB();

try {
    $stmt = $db->prepare("
        SELECT id, user_id, CONCAT(first_name, ' ', last_name) as name, role
        FROM users 
        WHERE id = ? AND account_status = 'active'
    ");
    
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
    
} catch (Exception $e) {
    error_log("Get user error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load user'
    ]);
}
?>
