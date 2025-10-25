<?php
/**
 * Support Page
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

Auth::startSession();

$isLoggedIn = Auth::isLoggedIn();

// Handle support request submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_support') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required';
    } else {
        // In a real application, you would send an email or create a support ticket
        // For now, we'll just show a success message
        $success = 'Your support request has been submitted. We will respond within 24-48 hours.';
        
        // If logged in, log the support request
        if ($isLoggedIn) {
            try {
                $db = getDB();
                Auth::logAudit($_SESSION['user_id'], 'SUPPORT_REQUEST', 'support', null, "Subject: $subject");
            } catch (Exception $e) {
                error_log("Support request logging error: " . $e->getMessage());
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
if ($isLoggedIn) {
    include __DIR__ . '/includes/navbar.php';
}
?>

<div class="container" style="margin-top: <?php echo $isLoggedIn ? '20px' : '50px'; ?>">
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-life-ring"></i> Support Center
            </h1>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div class="card">
                    <div class="card-body" style="text-align: center;">
                        <i class="fas fa-phone" style="font-size: 48px; color: var(--primary-color);"></i>
                        <h3 style="margin: 15px 0;">Phone Support</h3>
                        <p><strong>1-800-XXX-XXXX</strong></p>
                        <p style="color: var(--text-secondary); font-size: 14px;">
                            Monday - Friday<br>
                            9:00 AM - 5:00 PM EST
                        </p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body" style="text-align: center;">
                        <i class="fas fa-envelope" style="font-size: 48px; color: var(--success-color);"></i>
                        <h3 style="margin: 15px 0;">Email Support</h3>
                        <p><strong>support@healthcare.com</strong></p>
                        <p style="color: var(--text-secondary); font-size: 14px;">
                            Response within<br>
                            24-48 hours
                        </p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body" style="text-align: center;">
                        <i class="fas fa-clock" style="font-size: 48px; color: var(--info-color);"></i>
                        <h3 style="margin: 15px 0;">Office Hours</h3>
                        <p style="color: var(--text-secondary); font-size: 14px;">
                            Monday - Friday: 9 AM - 5 PM<br>
                            Saturday: 10 AM - 2 PM<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>
            </div>
            
            <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>
            
            <div style="margin: 20px 0;">
                <h3>How do I reset my password?</h3>
                <p>If you've forgotten your password, click "Forgot User ID or Password?" on the login page. You'll need to provide your name, date of birth, and answer your security question to reset your password.</p>
            </div>
            
            <div style="margin: 20px 0;">
                <h3>What is my User ID?</h3>
                <p>Your User ID is automatically generated when you register. It follows the format: first 3 letters of first name + first 3 letters of last name + date of birth (MMDDYYYY). For example: JOHNDOE01011990.</p>
            </div>
            
            <div style="margin: 20px 0;">
                <h3>How do I send a message to a healthcare provider?</h3>
                <p>Click the Messages icon in the navigation bar. For clients, you'll need to search for the provider using their User ID. Staff and providers can see all available users.</p>
            </div>
            
            <div style="margin: 20px 0;">
                <h3>Is my health information secure?</h3>
                <p>Yes! Our system is HIPAA-compliant and uses industry-standard encryption to protect your data. All actions are logged for security purposes. Review our <a href="/privacy-policy.php">Privacy Policy</a> and <a href="/hipaa-notice.php">HIPAA Notice</a> for more details.</p>
            </div>
            
            <div style="margin: 20px 0;">
                <h3>How long does it take to approve my staff/provider account?</h3>
                <p>Staff and Service Provider accounts typically require 1-3 business days for administrative approval. You'll receive a notification once your account is approved.</p>
            </div>
            
            <div style="margin: 20px 0;">
                <h3>Can I customize the appearance of my dashboard?</h3>
                <p>Yes! Go to Settings (accessible from your user menu) to customize colors, fonts, themes, and more.</p>
            </div>
            
            <h2><i class="fas fa-envelope"></i> Submit a Support Request</h2>
            
            <?php if (!$success): ?>
            <form method="POST" action="">
                <input type="hidden" name="action" value="submit_support">
                
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" class="form-control" name="name" required 
                           value="<?php echo $isLoggedIn ? htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subject *</label>
                    <input type="text" class="form-control" name="subject" required placeholder="Brief description of your issue">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message *</label>
                    <textarea class="form-control" name="message" rows="6" required placeholder="Please provide details about your issue or question"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </form>
            <?php endif; ?>
            
            <div style="margin-top: 30px; text-align: center;">
                <?php if ($isLoggedIn): ?>
                <a href="<?php echo Auth::getRedirectByRole($_SESSION['role']); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <?php else: ?>
                <a href="/index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($isLoggedIn): ?>
<script>
$('body').attr('data-logged-in', 'true');
</script>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
