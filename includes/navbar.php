<?php
/**
 * Navigation Bar Component
 */

if (!Auth::isLoggedIn()) {
    return; // Don't show navbar if not logged in
}

$db = getDB();

// Get unread message count
$stmt = $db->prepare("SELECT COUNT(*) as count FROM messages WHERE receiver_id = ? AND is_read = 0");
$stmt->execute([$_SESSION['user_id']]);
$unreadMessages = $stmt->fetch()['count'];

// Get unread notification count
$stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$stmt->execute([$_SESSION['user_id']]);
$unreadNotifications = $stmt->fetch()['count'];

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="<?php echo Auth::getRedirectByRole($_SESSION['role']); ?>">
                <i class="fas fa-heartbeat"></i>
                <span><?php echo APP_NAME; ?></span>
            </a>
        </div>
        
        <div class="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                    <a href="<?php echo Auth::getRedirectByRole($_SESSION['role']); ?>" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <?php if ($_SESSION['role'] === 'sysadmin'): ?>
                <li class="nav-item <?php echo $currentPage === 'admin.php' ? 'active' : ''; ?>">
                    <a href="/admin/admin.php" class="nav-link">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Panel</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a href="#" class="nav-link" id="messenger-toggle">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                        <?php if ($unreadMessages > 0): ?>
                        <span class="badge"><?php echo $unreadMessages; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link" id="notifications-toggle">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <?php if ($unreadNotifications > 0): ?>
                        <span class="badge"><?php echo $unreadNotifications; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/profile.php"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="/settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        
        <div class="navbar-toggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>

<!-- Messenger Panel -->
<div id="messenger-panel" class="messenger-panel">
    <div class="messenger-header">
        <h3><i class="fas fa-comments"></i> Messages</h3>
        <button class="close-btn" id="close-messenger"><i class="fas fa-times"></i></button>
    </div>
    <div class="messenger-search">
        <input type="text" id="search-user" placeholder="Search by User ID...">
        <button id="search-user-btn"><i class="fas fa-search"></i></button>
    </div>
    <div class="messenger-conversations" id="conversations-list">
        <!-- Conversations loaded via AJAX -->
    </div>
    <div class="messenger-chat" id="chat-window" style="display: none;">
        <div class="chat-header">
            <button id="back-to-conversations"><i class="fas fa-arrow-left"></i></button>
            <span id="chat-user-name"></span>
        </div>
        <div class="chat-messages" id="chat-messages">
            <!-- Messages loaded via AJAX -->
        </div>
        <div class="chat-input">
            <button class="attach-btn" id="attach-file"><i class="fas fa-paperclip"></i></button>
            <button class="location-btn" id="share-location"><i class="fas fa-map-marker-alt"></i></button>
            <input type="text" id="message-input" placeholder="Type a message...">
            <button class="send-btn" id="send-message"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<!-- Notifications Panel -->
<div id="notifications-panel" class="notifications-panel">
    <div class="notifications-header">
        <h3><i class="fas fa-bell"></i> Notifications</h3>
        <button class="close-btn" id="close-notifications"><i class="fas fa-times"></i></button>
    </div>
    <div class="notifications-list" id="notifications-list">
        <!-- Notifications loaded via AJAX -->
    </div>
</div>
