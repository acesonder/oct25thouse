<?php
/**
 * Header Component
 * Included in all pages for consistent styling and functionality
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';

Auth::startSession();

// Get user settings if logged in
$userSettings = null;
if (Auth::isLoggedIn()) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userSettings = $stmt->fetch();
}

$theme = $userSettings['theme'] ?? 'light';
$primaryColor = $userSettings['primary_color'] ?? '#007bff';
$secondaryColor = $userSettings['secondary_color'] ?? '#6c757d';
$fontFamily = $userSettings['font_family'] ?? 'Arial';
$fontSize = $userSettings['font_size'] ?? 14;
$enableAnimations = $userSettings['enable_animations'] ?? true;
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($theme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/messenger.css">
    <link rel="stylesheet" href="/assets/css/themes.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom User Styles -->
    <style>
        :root {
            --primary-color: <?php echo htmlspecialchars($primaryColor); ?>;
            --secondary-color: <?php echo htmlspecialchars($secondaryColor); ?>;
            --font-family: <?php echo htmlspecialchars($fontFamily); ?>;
            --font-size: <?php echo htmlspecialchars($fontSize); ?>px;
        }
        
        body {
            font-family: var(--font-family);
            font-size: var(--font-size);
        }
        
        <?php if (!$enableAnimations): ?>
        *, *::before, *::after {
            animation-duration: 0s !important;
            transition-duration: 0s !important;
        }
        <?php endif; ?>
    </style>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- JavaScript Files -->
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/messenger.js"></script>
    <script src="/assets/js/notifications.js"></script>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>
