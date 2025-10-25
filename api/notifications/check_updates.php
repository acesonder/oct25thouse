<?php
/**
 * Check for Updates API
 * Returns unread notifications and messages count
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

$db = getDB();
$userId = $_SESSION['user_id'];

try {
    // Get unread notifications count
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    $unreadNotifications = $stmt->fetch()['count'];
    
    // Get unread messages count
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM messages WHERE receiver_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    $unreadMessages = $stmt->fetch()['count'];
    
    // Get latest notification
    $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$userId]);
    $latestNotification = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'unread_notifications' => $unreadNotifications,
        'unread_messages' => $unreadMessages,
        'latest_notification' => $latestNotification ?: null
    ]);
    
} catch (Exception $e) {
    error_log("Check updates error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to check updates'
    ]);
}
?>
