<?php
/**
 * Service Provider Dashboard
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

Auth::requireRole('service_provider');

$db = getDB();
$userId = $_SESSION['user_id'];

// Get user info
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="container">
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-user-md"></i> Service Provider Dashboard
            </h1>
        </div>
        <div class="card-body">
            <h2>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
            <p>Service Provider - <?php echo htmlspecialchars($user['user_id']); ?></p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <!-- Services Card -->
        <div class="card slide-in-left">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-briefcase-medical" style="font-size: 48px; color: var(--primary-color);"></i>
                <h3 style="margin: 15px 0 10px;">Services</h3>
                <p style="color: var(--text-secondary);">Manage your services</p>
                <button class="btn btn-primary btn-block" style="margin-top: 38px;">
                    <i class="fas fa-plus"></i> Add Service
                </button>
            </div>
        </div>
        
        <!-- Clients Card -->
        <div class="card slide-in-up">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-users" style="font-size: 48px; color: var(--success-color);"></i>
                <h3 style="margin: 15px 0 10px;">Clients</h3>
                <p style="color: var(--text-secondary);">View your clients</p>
                <button class="btn btn-primary btn-block" style="margin-top: 38px;">
                    <i class="fas fa-users"></i> View Clients
                </button>
            </div>
        </div>
        
        <!-- Messages Card -->
        <div class="card slide-in-right">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-comments" style="font-size: 48px; color: var(--info-color);"></i>
                <h3 style="margin: 15px 0 10px;">Messages</h3>
                <button class="btn btn-primary btn-block" onclick="$('#messenger-toggle').click()" style="margin-top: 38px;">
                    <i class="fas fa-comments"></i> Open Messenger
                </button>
            </div>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Provider Resources</h3>
        </div>
        <div class="card-body">
            <ul>
                <li>Manage your service offerings and availability</li>
                <li>Communicate securely with clients</li>
                <li>Access client information and case notes</li>
                <li>Submit service reports and documentation</li>
            </ul>
        </div>
    </div>
</div>

<script>
$('body').attr('data-logged-in', 'true');
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
