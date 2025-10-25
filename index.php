<?php
/**
 * Landing Page
 * Portal selection for CLIENTS, STAFF, and SERVICE PROVIDERS
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

// If already logged in, redirect to appropriate dashboard
if (Auth::isLoggedIn()) {
    header('Location: ' . Auth::getRedirectByRole($_SESSION['role']));
    exit;
}

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $result = Auth::login($_POST['user_id'], $_POST['password']);
    
    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Welcome</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/themes.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- JavaScript -->
    <script src="/assets/js/main.js"></script>
</head>
<body>
    <div class="landing-page">
        <div class="landing-header fade-in">
            <h1><i class="fas fa-heartbeat"></i> <?php echo APP_NAME; ?></h1>
            <p>HIPAA Compliant Healthcare Management System</p>
            <p style="font-size: 14px; margin-top: 10px;">
                <i class="fas fa-shield-alt"></i> Secure | 
                <i class="fas fa-lock"></i> Encrypted | 
                <i class="fas fa-flag"></i> Canadian Standards
            </p>
        </div>
        
        <div class="portal-cards">
            <div class="cards-container">
                <!-- Client Portal -->
                <div class="portal-card slide-in-left" onclick="showLoginModal('client')">
                    <i class="fas fa-user-injured"></i>
                    <h2>Client Portal</h2>
                    <p>Access your healthcare records, appointments, and communicate with providers</p>
                    <button class="btn btn-primary btn-block">Login / Register</button>
                </div>
                
                <!-- Staff Portal -->
                <div class="portal-card slide-in-up" onclick="showLoginModal('staff')">
                    <i class="fas fa-user-nurse"></i>
                    <h2>Staff Portal</h2>
                    <p>Manage client cases, update records, and collaborate with team members</p>
                    <button class="btn btn-primary btn-block">Staff Login</button>
                </div>
                
                <!-- Service Provider Portal -->
                <div class="portal-card slide-in-right" onclick="showLoginModal('service_provider')">
                    <i class="fas fa-user-md"></i>
                    <h2>Service Provider</h2>
                    <p>Provide services, manage appointments, and communicate with clients</p>
                    <button class="btn btn-primary btn-block">Provider Login</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Login Modal -->
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-title">Login</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="login-form">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="portal_type" id="portal-type" value="">
                    
                    <div class="form-group">
                        <label class="form-label" for="user_id">User ID</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" required placeholder="e.g., JOHNDOE010190">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="#" onclick="showRecoveryModal(); return false;" style="color: var(--primary-color);">
                        <i class="fas fa-question-circle"></i> Forgot User ID or Password?
                    </a>
                </div>
                
                <div style="margin-top: 15px; text-align: center;" id="register-link">
                    <hr>
                    <p>Don't have an account?</p>
                    <button type="button" class="btn btn-secondary btn-block" onclick="showRegisterModal()">
                        <i class="fas fa-user-plus"></i> Register as Client
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Registration Modal -->
    <div id="register-modal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2 class="modal-title">Client Registration</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="register-form" action="/api/register.php" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="first_name">First Name *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="last_name">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="dob">Date of Birth *</label>
                        <input type="date" class="form-control" id="dob" name="dob" required max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="security_question">Security Question *</label>
                        <select class="form-control" id="security_question" name="security_question" required>
                            <option value="">Select a security question</option>
                            <?php
                            $db = getDB();
                            $stmt = $db->query("SELECT question FROM security_questions ORDER BY id");
                            while ($q = $stmt->fetch()) {
                                echo '<option value="' . htmlspecialchars($q['question']) . '">' . htmlspecialchars($q['question']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="security_answer">Security Answer *</label>
                        <input type="text" class="form-control" id="security_answer" name="security_answer" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="reg_password">Password *</label>
                        <input type="password" class="form-control" id="reg_password" name="password" required minlength="8">
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm Password *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="consent" name="consent" value="true" required>
                        <label class="form-check-label" for="consent">
                            I consent to share information *
                            <a href="#" onclick="showConsentModal(); return false;">(View Details)</a>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" value="true" required>
                        <label class="form-check-label" for="terms">
                            I accept the Terms of Service *
                            <a href="#" onclick="showTermsModal(); return false;">(View Terms)</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Recovery Modal -->
    <div id="recovery-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Account Recovery</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>Enter your information to recover your User ID and reset your password.</p>
                
                <form id="recovery-form" action="/api/recover.php" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="rec_first_name">First Name</label>
                        <input type="text" class="form-control" id="rec_first_name" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="rec_last_name">Last Name</label>
                        <input type="text" class="form-control" id="rec_last_name" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="rec_dob">Date of Birth</label>
                        <input type="date" class="form-control" id="rec_dob" name="dob" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="rec_security_question">Security Question</label>
                        <select class="form-control" id="rec_security_question" name="security_question" required>
                            <option value="">Select your security question</option>
                            <?php
                            $stmt = $db->query("SELECT question FROM security_questions ORDER BY id");
                            while ($q = $stmt->fetch()) {
                                echo '<option value="' . htmlspecialchars($q['question']) . '">' . htmlspecialchars($q['question']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="rec_security_answer">Security Answer</label>
                        <input type="text" class="form-control" id="rec_security_answer" name="security_answer" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-key"></i> Recover Account
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Consent Modal -->
    <div id="consent-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Consent to Share Information</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <h3>HIPAA Authorization for Release of Health Information</h3>
                <p>By checking the consent box, you authorize <?php echo APP_NAME; ?> to:</p>
                <ul>
                    <li>Use and disclose your protected health information (PHI) for treatment, payment, and healthcare operations</li>
                    <li>Share your information with authorized healthcare providers and staff members</li>
                    <li>Store your health information in a secure, HIPAA-compliant database</li>
                    <li>Communicate with you regarding your healthcare through secure messaging</li>
                </ul>
                
                <h4>Your Rights</h4>
                <ul>
                    <li>You have the right to revoke this authorization at any time</li>
                    <li>You have the right to inspect and obtain copies of your health information</li>
                    <li>You have the right to request amendments to your health information</li>
                    <li>You have the right to receive an accounting of disclosures</li>
                </ul>
                
                <p><strong>This consent complies with Canadian privacy legislation and HIPAA regulations.</strong></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="hideModal('consent-modal')">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Terms Modal -->
    <div id="terms-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Terms of Service</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <h3>Terms and Conditions</h3>
                
                <h4>1. Acceptance of Terms</h4>
                <p>By using <?php echo APP_NAME; ?>, you agree to be bound by these terms and conditions.</p>
                
                <h4>2. User Responsibilities</h4>
                <ul>
                    <li>Maintain the confidentiality of your User ID and password</li>
                    <li>Notify us immediately of any unauthorized use of your account</li>
                    <li>Provide accurate and complete information</li>
                    <li>Use the system only for lawful purposes</li>
                </ul>
                
                <h4>3. Privacy and Data Protection</h4>
                <ul>
                    <li>We comply with HIPAA and Canadian privacy legislation</li>
                    <li>Your health information is encrypted and stored securely</li>
                    <li>We will not share your information without your consent, except as required by law</li>
                </ul>
                
                <h4>4. Account Termination</h4>
                <p>We reserve the right to terminate accounts that violate these terms or engage in inappropriate behavior.</p>
                
                <h4>5. Limitation of Liability</h4>
                <p>We are not liable for any indirect, incidental, or consequential damages arising from your use of the system.</p>
                
                <p><strong>Last Updated: <?php echo date('F Y'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="hideModal('terms-modal')">Close</button>
            </div>
        </div>
    </div>
    
    <script>
    function showLoginModal(portalType) {
        $('#portal-type').val(portalType);
        
        const titles = {
            'client': 'Client Login',
            'staff': 'Staff Login',
            'service_provider': 'Service Provider Login'
        };
        
        $('#modal-title').text(titles[portalType] || 'Login');
        
        // Only show register link for clients
        if (portalType === 'client') {
            $('#register-link').show();
        } else {
            $('#register-link').hide();
        }
        
        showModal('login-modal');
    }
    
    function showRegisterModal() {
        hideModal('login-modal');
        showModal('register-modal');
    }
    
    function showRecoveryModal() {
        hideModal('login-modal');
        showModal('recovery-modal');
    }
    
    function showConsentModal() {
        showModal('consent-modal');
    }
    
    function showTermsModal() {
        showModal('terms-modal');
    }
    
    // Handle registration
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm('register-form')) {
            return;
        }
        
        showLoading();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    if (response.status === 'active') {
                        // Auto-login successful, redirect to dashboard
                        window.location.href = '/client/dashboard.php?welcome=1';
                    } else {
                        showAlert('Registration submitted! Your account is pending approval.', 'success');
                        setTimeout(() => {
                            window.location.href = '/index.php';
                        }, 2000);
                    }
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                hideLoading();
                showAlert('Registration failed. Please try again.', 'danger');
            }
        });
    });
    
    // Handle recovery
    $('#recovery-form').on('submit', function(e) {
        e.preventDefault();
        
        showLoading();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    hideModal('recovery-modal');
                    
                    // Show User ID and password reset form
                    alert('Your User ID is: ' + response.user_id + '\n\nYou can now reset your password.');
                    
                    // Optionally redirect to password reset
                    window.location.href = '/reset-password.php?user_id=' + response.user_id;
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                hideLoading();
                showAlert('Recovery failed. Please try again.', 'danger');
            }
        });
    });
    </script>
</body>
</html>
