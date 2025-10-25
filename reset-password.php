<?php
/**
 * Password Reset Page
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Check if accessed from logged-in state or recovery
$isLoggedIn = Auth::isLoggedIn();
$userIDFromRecovery = $_GET['user_id'] ?? '';

if (!$isLoggedIn && !$userIDFromRecovery) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $isLoggedIn ? $_SESSION['user_uid'] : $_POST['user_id'];
    $result = Auth::resetPassword($userID, $_POST['new_password'], $_POST['confirm_password']);
    
    if ($result['success']) {
        $success = $result['message'];
        
        if (!$isLoggedIn) {
            // Redirect to login after 3 seconds
            header('refresh:3;url=/index.php');
        }
    } else {
        $error = $result['message'];
    }
}

include __DIR__ . '/includes/header.php';
if ($isLoggedIn) {
    include __DIR__ . '/includes/navbar.php';
}
?>

<div class="container">
    <div class="card fade-in" style="max-width: 600px; margin: 50px auto;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-key"></i> Reset Password
            </h2>
        </div>
        <div class="card-body">
            <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                <?php if (!$isLoggedIn): ?>
                <br>Redirecting to login...
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <?php if (!isset($success)): ?>
            <form method="POST" action="">
                <?php if (!$isLoggedIn): ?>
                <div class="form-group">
                    <label class="form-label">User ID</label>
                    <input type="text" class="form-control" name="user_id" value="<?php echo htmlspecialchars($userIDFromRecovery); ?>" required readonly>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="new_password" required minlength="8">
                    <small class="text-muted">Minimum 8 characters</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-key"></i> Reset Password
                </button>
                
                <?php if ($isLoggedIn): ?>
                <a href="<?php echo Auth::getRedirectByRole($_SESSION['role']); ?>" class="btn btn-secondary btn-block" style="margin-top: 10px;">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <?php endif; ?>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($isLoggedIn): ?>
<script>
$('body').attr('data-logged-in', 'true');
</script>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
