<?php
/**
 * Get Messages API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

$otherUserId = $_GET['user_id'] ?? 0;
$currentUserId = $_SESSION['user_id'];

$db = getDB();

try {
    // Get messages between current user and other user
    $stmt = $db->prepare("
        SELECT 
            m.id,
            m.sender_id,
            m.receiver_id,
            m.message,
            m.message_type,
            m.file_path,
            m.location_lat,
            m.location_lng,
            m.is_read,
            m.created_at,
            CONCAT(u.first_name, ' ', u.last_name) as sender_name,
            CASE WHEN m.sender_id = ? THEN 1 ELSE 0 END as is_sent
        FROM messages m
        JOIN users u ON u.id = m.sender_id
        WHERE (m.sender_id = ? AND m.receiver_id = ?) 
           OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.created_at ASC
    ");
    
    $stmt->execute([$currentUserId, $currentUserId, $otherUserId, $otherUserId, $currentUserId]);
    $messages = $stmt->fetchAll();
    
    // Mark received messages as read
    $stmt = $db->prepare("
        UPDATE messages 
        SET is_read = 1, read_at = NOW() 
        WHERE sender_id = ? AND receiver_id = ? AND is_read = 0
    ");
    $stmt->execute([$otherUserId, $currentUserId]);
    
    echo json_encode([
        'success' => true,
        'messages' => $messages
    ]);
    
} catch (Exception $e) {
    error_log("Get messages error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load messages'
    ]);
}
?>
