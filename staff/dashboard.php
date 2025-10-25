<?php
/**
 * Staff Dashboard
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

Auth::requireRole('staff');

$db = getDB();
$userId = $_SESSION['user_id'];

// Get user info
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Get client count
$stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'client' AND account_status = 'active'");
$clientCount = $stmt->fetch()['count'];

// Get pending approvals (if admin)
$stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE account_status = 'pending'");
$pendingApprovals = $stmt->fetch()['count'];

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="container">
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-user-nurse"></i> Staff Dashboard
            </h1>
        </div>
        <div class="card-body">
            <h2>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
            <p>Staff Member - <?php echo htmlspecialchars($user['user_id']); ?></p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <!-- Clients Card -->
        <div class="card slide-in-left">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-users" style="font-size: 48px; color: var(--primary-color);"></i>
                <h3 style="margin: 15px 0 10px;">Active Clients</h3>
                <p style="font-size: 32px; font-weight: bold; color: var(--primary-color);"><?php echo $clientCount; ?></p>
                <p style="color: var(--text-secondary);">Total Active Clients</p>
            </div>
        </div>
        
        <!-- Messages Card -->
        <div class="card slide-in-up">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-comments" style="font-size: 48px; color: var(--info-color);"></i>
                <h3 style="margin: 15px 0 10px;">Messages</h3>
                <button class="btn btn-primary btn-block" onclick="$('#messenger-toggle').click()" style="margin-top: 38px;">
                    <i class="fas fa-comments"></i> Open Messenger
                </button>
            </div>
        </div>
        
        <!-- Approvals Card -->
        <div class="card slide-in-right">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-user-check" style="font-size: 48px; color: var(--warning-color);"></i>
                <h3 style="margin: 15px 0 10px;">Pending Approvals</h3>
                <p style="font-size: 32px; font-weight: bold; color: var(--warning-color);"><?php echo $pendingApprovals; ?></p>
                <p style="color: var(--text-secondary);">Awaiting Review</p>
            </div>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Staff Resources</h3>
        </div>
        <div class="card-body">
            <ul>
                <li>Access client records and case management tools</li>
                <li>Communicate securely with clients and team members</li>
                <li>Review and approve pending service provider accounts</li>
                <li>Generate reports and analytics</li>
            </ul>
        </div>
    </div>
</div>

<script>
$('body').attr('data-logged-in', 'true');
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
