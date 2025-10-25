<?php
/**
 * Mark Notification as Read API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$notificationId = $_POST['notification_id'] ?? 0;
$userId = $_SESSION['user_id'];

$db = getDB();

try {
    $stmt = $db->prepare("
        UPDATE notifications 
        SET is_read = 1 
        WHERE id = ? AND user_id = ?
    ");
    
    $stmt->execute([$notificationId, $userId]);
    
    echo json_encode([
        'success' => true
    ]);
    
} catch (Exception $e) {
    error_log("Mark notification read error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to mark notification as read'
    ]);
}
?>
