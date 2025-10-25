<?php
/**
 * Get Notifications API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

$db = getDB();
$userId = $_SESSION['user_id'];

try {
    $stmt = $db->prepare("
        SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 50
    ");
    
    $stmt->execute([$userId]);
    $notifications = $stmt->fetchAll();
    
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    $unreadCount = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => $unreadCount
    ]);
    
} catch (Exception $e) {
    error_log("Get notifications error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load notifications'
    ]);
}
?>
