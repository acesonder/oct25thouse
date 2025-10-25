<?php
/**
 * Logout Handler
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

Auth::logout();

$reason = $_GET['reason'] ?? '';
$message = 'You have been logged out successfully.';

if ($reason === 'inactivity') {
    $message = 'You have been logged out due to inactivity.';
}

header('Location: /index.php?message=' . urlencode($message));
exit;
?>
