<?php
/**
 * Admin Portal
 * Passcode: 079777
 * Features: Account management, approval system, login as any user
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

Auth::startSession();

// Check if passcode is entered
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passcode'])) {
        if ($_POST['passcode'] === '079777') {
            $_SESSION['admin_access'] = true;
            header('Location: /admin/admin.php');
            exit;
        } else {
            $error = 'Invalid passcode';
        }
    }
    
    // Show passcode entry page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Portal - Access</title>
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card" style="width: 400px;">
            <div class="card-header" style="text-align: center;">
                <h2 class="card-title">
                    <i class="fas fa-shield-alt"></i> Admin Portal Access
                </h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="passcode">Enter Admin Passcode</label>
                        <input type="password" class="form-control" id="passcode" name="passcode" required autofocus>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-unlock"></i> Access Admin Portal
                    </button>
                </form>
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="/index.php" style="color: var(--primary-color);">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Admin access granted - proceed with admin panel
$db = getDB();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_user':
                $result = Auth::register($_POST);
                if ($result['success']) {
                    // Update status if staff or service provider
                    if (isset($_POST['role']) && in_array($_POST['role'], ['staff', 'service_provider', 'sysadmin'])) {
                        $stmt = $db->prepare("UPDATE users SET account_status = 'active', approved_by = ?, approved_at = NOW() WHERE user_id = ?");
                        $stmt->execute([$_SESSION['user_id'] ?? 1, $result['user_id']]);
                    }
                    $success = 'User created successfully: ' . $result['user_id'];
                } else {
                    $error = $result['message'];
                }
                break;
                
            case 'approve_user':
                $stmt = $db->prepare("UPDATE users SET account_status = 'active', approved_by = ?, approved_at = NOW() WHERE id = ?");
                $stmt->execute([$_SESSION['user_id'] ?? 1, $_POST['user_id']]);
                $success = 'User approved successfully';
                break;
                
            case 'suspend_user':
                $stmt = $db->prepare("UPDATE users SET account_status = 'suspended' WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                $success = 'User suspended successfully';
                break;
                
            case 'activate_user':
                $stmt = $db->prepare("UPDATE users SET account_status = 'active' WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                $success = 'User activated successfully';
                break;
                
            case 'delete_user':
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                $success = 'User deleted successfully';
                break;
                
            case 'login_as_user':
                $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                $targetUser = $stmt->fetch();
                
                if ($targetUser) {
                    // Save admin session
                    $_SESSION['admin_original_user_id'] = $_SESSION['user_id'] ?? null;
                    $_SESSION['admin_original_role'] = $_SESSION['role'] ?? null;
                    
                    // Login as target user
                    $_SESSION['user_id'] = $targetUser['id'];
                    $_SESSION['user_uid'] = $targetUser['user_id'];
                    $_SESSION['role'] = $targetUser['role'];
                    $_SESSION['first_name'] = $targetUser['first_name'];
                    $_SESSION['last_name'] = $targetUser['last_name'];
                    
                    Auth::logAudit($targetUser['id'], 'ADMIN_LOGIN_AS_USER', 'users', $targetUser['id']);
                    
                    header('Location: ' . Auth::getRedirectByRole($targetUser['role']));
                    exit;
                }
                break;
        }
    }
}

// Get all users
$stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Get pending approvals
$stmt = $db->query("SELECT * FROM users WHERE account_status = 'pending' ORDER BY created_at DESC");
$pendingUsers = $stmt->fetchAll();

// Get statistics
$stmt = $db->query("SELECT role, account_status, COUNT(*) as count FROM users GROUP BY role, account_status");
$stats = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="/admin/admin.php">
                <i class="fas fa-shield-alt"></i>
                <span>Admin Portal</span>
            </a>
        </div>
        
        <div class="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/admin/admin.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/index.php" class="nav-link">
                        <i class="fas fa-arrow-left"></i>
                        <span>Exit Admin</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php if (isset($success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-shield-alt"></i> Admin Dashboard
            </h1>
        </div>
        <div class="card-body">
            <p>Welcome to the Admin Portal. Manage all accounts and system settings.</p>
        </div>
    </div>
    
    <!-- Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        <?php
        $roleCounts = ['client' => 0, 'staff' => 0, 'service_provider' => 0, 'sysadmin' => 0];
        $statusCounts = ['active' => 0, 'pending' => 0, 'suspended' => 0, 'inactive' => 0];
        
        foreach ($stats as $stat) {
            if (isset($roleCounts[$stat['role']])) {
                $roleCounts[$stat['role']] += $stat['count'];
            }
            if (isset($statusCounts[$stat['account_status']])) {
                $statusCounts[$stat['account_status']] += $stat['count'];
            }
        }
        ?>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-users" style="font-size: 36px; color: var(--primary-color);"></i>
                <h3 style="margin: 10px 0;"><?php echo $roleCounts['client']; ?></h3>
                <p style="color: var(--text-secondary);">Clients</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-user-nurse" style="font-size: 36px; color: var(--success-color);"></i>
                <h3 style="margin: 10px 0;"><?php echo $roleCounts['staff']; ?></h3>
                <p style="color: var(--text-secondary);">Staff</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-user-md" style="font-size: 36px; color: var(--info-color);"></i>
                <h3 style="margin: 10px 0;"><?php echo $roleCounts['service_provider']; ?></h3>
                <p style="color: var(--text-secondary);">Providers</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-user-clock" style="font-size: 36px; color: var(--warning-color);"></i>
                <h3 style="margin: 10px 0;"><?php echo $statusCounts['pending']; ?></h3>
                <p style="color: var(--text-secondary);">Pending Approval</p>
            </div>
        </div>
    </div>
    
    <!-- Pending Approvals -->
    <?php if (count($pendingUsers) > 0): ?>
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-clock"></i> Pending Approvals</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Date Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="approve_user">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Create User -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-plus"></i> Create New User</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="action" value="create_user">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select class="form-control" name="role" required>
                            <option value="client">Client</option>
                            <option value="volunteer">Volunteer</option>
                            <option value="staff">Staff</option>
                            <option value="service_provider">Service Provider</option>
                            <option value="sysadmin">System Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Security Question</label>
                        <select class="form-control" name="security_question" required>
                            <?php
                            $stmt = $db->query("SELECT question FROM security_questions ORDER BY id");
                            while ($q = $stmt->fetch()) {
                                echo '<option value="' . htmlspecialchars($q['question']) . '">' . htmlspecialchars($q['question']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Security Answer</label>
                        <input type="text" class="form-control" name="security_answer" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </div>
                
                <input type="hidden" name="consent" value="true">
                <input type="hidden" name="terms" value="true">
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create User
                </button>
            </form>
        </div>
    </div>
    
    <!-- All Users -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> All Users</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                            <span class="badge" style="background: <?php 
                                echo $user['account_status'] === 'active' ? 'var(--success-color)' : 
                                     ($user['account_status'] === 'pending' ? 'var(--warning-color)' : 
                                     ($user['account_status'] === 'suspended' ? 'var(--danger-color)' : 'var(--secondary-color)')); 
                            ?>; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                                <?php echo ucfirst($user['account_status']); ?>
                            </span>
                        </td>
                        <td><?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="login_as_user">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-info btn-sm" title="Login as this user">
                                    <i class="fas fa-sign-in-alt"></i>
                                </button>
                            </form>
                            
                            <?php if ($user['account_status'] === 'suspended'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="activate_user">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm" title="Activate user">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <?php else: ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="suspend_user">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-warning btn-sm" title="Suspend user">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if ($user['user_id'] !== 'SYSADM000000'): ?>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete user">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
