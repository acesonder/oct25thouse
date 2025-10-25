<?php
/**
 * Send Message API
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$receiverId = $_POST['receiver_id'] ?? 0;
$message = $_POST['message'] ?? '';
$messageType = $_POST['message_type'] ?? 'text';
$senderId = $_SESSION['user_id'];

$db = getDB();

try {
    $filePath = null;
    $locationLat = null;
    $locationLng = null;
    
    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/messages/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $filePath = '/uploads/messages/' . $fileName;
        
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
            echo json_encode([
                'success' => false,
                'message' => 'File upload failed'
            ]);
            exit;
        }
    }
    
    // Handle location
    if ($messageType === 'location') {
        $locationLat = $_POST['location_lat'] ?? null;
        $locationLng = $_POST['location_lng'] ?? null;
    }
    
    // Insert message
    $stmt = $db->prepare("
        INSERT INTO messages (sender_id, receiver_id, message, message_type, file_path, location_lat, location_lng)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $senderId,
        $receiverId,
        $message,
        $messageType,
        $filePath,
        $locationLat,
        $locationLng
    ]);
    
    $messageId = $db->lastInsertId();
    
    // Create notification for receiver
    $stmt = $db->prepare("
        INSERT INTO notifications (user_id, type, title, message, link)
        VALUES (?, 'message', 'New Message', ?, '/client/dashboard.php')
    ");
    
    $stmt->execute([
        $receiverId,
        'You have a new message from ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name']
    ]);
    
    // Log audit
    Auth::logAudit($senderId, 'MESSAGE_SENT', 'messages', $messageId);
    
    echo json_encode([
        'success' => true,
        'message_id' => $messageId
    ]);
    
} catch (Exception $e) {
    error_log("Send message error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send message'
    ]);
}
?>
