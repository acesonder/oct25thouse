<?php
/**
 * Profile Page
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

Auth::requireLogin();

$db = getDB();
$userId = $_SESSION['user_id'];

// Get user info
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<div class="container">
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-user"></i> Profile
            </h1>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
                <div style="text-align: center;">
                    <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-size: 64px; font-weight: bold; margin: 0 auto 20px;">
                        <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                    </div>
                    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p style="color: var(--text-secondary);"><?php echo ucfirst($user['role']); ?></p>
                </div>
                
                <div>
                    <h3>Account Information</h3>
                    
                    <table class="table">
                        <tr>
                            <td><strong>User ID:</strong></td>
                            <td>
                                <?php echo htmlspecialchars($user['user_id']); ?>
                                <button class="btn btn-sm btn-primary" onclick="copyToClipboard('<?php echo htmlspecialchars($user['user_id']); ?>')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>First Name:</strong></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Last Name:</strong></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date of Birth:</strong></td>
                            <td><?php echo date('F d, Y', strtotime($user['dob'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td><?php echo ucfirst($user['role']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Account Status:</strong></td>
                            <td>
                                <span class="badge" style="background: var(--success-color); color: white; padding: 5px 10px; border-radius: 5px;">
                                    <?php echo ucfirst($user['account_status']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Member Since:</strong></td>
                            <td><?php echo date('F d, Y', strtotime($user['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Last Login:</strong></td>
                            <td><?php echo $user['last_login'] ? date('F d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                        </tr>
                    </table>
                    
                    <div style="margin-top: 20px;">
                        <a href="/settings.php" class="btn btn-primary">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <a href="/reset-password.php" class="btn btn-secondary">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('body').attr('data-logged-in', 'true');
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
