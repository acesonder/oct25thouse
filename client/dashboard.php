<?php
/**
 * Client Dashboard
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

Auth::requireLogin();

$db = getDB();
$userId = $_SESSION['user_id'];

// Get user info
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Get recent messages count
$stmt = $db->prepare("SELECT COUNT(*) as count FROM messages WHERE receiver_id = ? AND is_read = 0");
$stmt->execute([$userId]);
$unreadMessages = $stmt->fetch()['count'];

// Get recent notifications count
$stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$stmt->execute([$userId]);
$unreadNotifications = $stmt->fetch()['count'];

$showWelcomeTour = isset($_GET['welcome']) || isset($_SESSION['show_welcome_tour']);
if (isset($_SESSION['show_welcome_tour'])) {
    unset($_SESSION['show_welcome_tour']);
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="container">
    <?php if ($showWelcomeTour): ?>
    <div class="alert alert-success fade-in">
        <i class="fas fa-check-circle"></i>
        <strong>Welcome to <?php echo APP_NAME; ?>!</strong><br>
        Your account has been created successfully. Your User ID is: <strong><?php echo htmlspecialchars($user['user_id']); ?></strong>
        <button class="btn btn-sm btn-primary" style="margin-left: 10px;" onclick="copyToClipboard('<?php echo htmlspecialchars($user['user_id']); ?>')">
            <i class="fas fa-copy"></i> Copy User ID
        </button>
    </div>
    <?php endif; ?>
    
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-home"></i> Dashboard
            </h1>
        </div>
        <div class="card-body">
            <h2>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
            <p>Your User ID: <strong><?php echo htmlspecialchars($user['user_id']); ?></strong></p>
            <p>Account Status: <span class="badge" style="background: var(--success-color); color: white; padding: 5px 10px; border-radius: 5px;">
                <?php echo ucfirst($user['account_status']); ?>
            </span></p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <!-- Messages Card -->
        <div class="card slide-in-left">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-envelope" style="font-size: 48px; color: var(--primary-color);"></i>
                <h3 style="margin: 15px 0 10px;">Messages</h3>
                <p style="font-size: 32px; font-weight: bold; color: var(--primary-color);"><?php echo $unreadMessages; ?></p>
                <p style="color: var(--text-secondary);">Unread Messages</p>
                <button class="btn btn-primary btn-block" onclick="$('#messenger-toggle').click()">
                    <i class="fas fa-comments"></i> Open Messenger
                </button>
            </div>
        </div>
        
        <!-- Notifications Card -->
        <div class="card slide-in-up">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-bell" style="font-size: 48px; color: var(--warning-color);"></i>
                <h3 style="margin: 15px 0 10px;">Notifications</h3>
                <p style="font-size: 32px; font-weight: bold; color: var(--warning-color);"><?php echo $unreadNotifications; ?></p>
                <p style="color: var(--text-secondary);">New Notifications</p>
                <button class="btn btn-primary btn-block" onclick="$('#notifications-toggle').click()">
                    <i class="fas fa-bell"></i> View Notifications
                </button>
            </div>
        </div>
        
        <!-- Profile Card -->
        <div class="card slide-in-right">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-user-circle" style="font-size: 48px; color: var(--info-color);"></i>
                <h3 style="margin: 15px 0 10px;">Profile</h3>
                <p style="color: var(--text-secondary);">Manage your account</p>
                <a href="/profile.php" class="btn btn-primary btn-block" style="margin-top: 38px;">
                    <i class="fas fa-user"></i> View Profile
                </a>
            </div>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Quick Guide</h3>
        </div>
        <div class="card-body">
            <h4>Getting Started</h4>
            <ul>
                <li><strong>Messages:</strong> Click on the Messages icon in the navigation bar to start conversations. Search for users by their User ID.</li>
                <li><strong>Notifications:</strong> Stay updated with important alerts and messages.</li>
                <li><strong>Profile:</strong> Update your personal information and preferences.</li>
                <li><strong>Settings:</strong> Customize your experience with themes, colors, and layout options.</li>
            </ul>
            
            <h4>Privacy & Security</h4>
            <ul>
                <li>Your data is encrypted and HIPAA compliant</li>
                <li>Keep your User ID and password secure</li>
                <li>Log out when using shared computers</li>
                <li>Report any security concerns immediately</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Mark body as logged in for notifications
$('body').attr('data-logged-in', 'true');
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
