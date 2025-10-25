<?php
/**
 * Privacy Policy Page
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

Auth::startSession();

$isLoggedIn = Auth::isLoggedIn();

include __DIR__ . '/includes/header.php';
if ($isLoggedIn) {
    include __DIR__ . '/includes/navbar.php';
}
?>

<div class="container" style="margin-top: <?php echo $isLoggedIn ? '20px' : '50px'; ?>">
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-shield-alt"></i> Privacy Policy
            </h1>
        </div>
        <div class="card-body">
            <p><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>
            
            <h2>Introduction</h2>
            <p>This Privacy Policy describes how <?php echo APP_NAME; ?> collects, uses, and protects your personal health information in compliance with HIPAA and Canadian privacy legislation.</p>
            
            <h2>Information We Collect</h2>
            <h3>Personal Information</h3>
            <ul>
                <li>Name (First and Last)</li>
                <li>Date of Birth</li>
                <li>User ID (auto-generated)</li>
                <li>Contact information</li>
            </ul>
            
            <h3>Health Information</h3>
            <ul>
                <li>Medical records and healthcare data</li>
                <li>Communications with healthcare providers</li>
                <li>Appointment history</li>
                <li>Service provider interactions</li>
            </ul>
            
            <h3>Technical Information</h3>
            <ul>
                <li>IP address and login timestamps</li>
                <li>Device and browser information</li>
                <li>Usage patterns and system logs</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <p>We use your information for:</p>
            <ul>
                <li><strong>Treatment:</strong> To provide and coordinate healthcare services</li>
                <li><strong>Payment:</strong> To process billing and insurance claims</li>
                <li><strong>Healthcare Operations:</strong> To improve quality of care and system functionality</li>
                <li><strong>Communication:</strong> To facilitate secure messaging between users</li>
                <li><strong>Audit and Compliance:</strong> To maintain HIPAA-compliant audit logs</li>
            </ul>
            
            <h2>Information Sharing</h2>
            <p>We do NOT share your information except:</p>
            <ul>
                <li>With your explicit consent</li>
                <li>With authorized healthcare providers for treatment purposes</li>
                <li>As required by law or legal process</li>
                <li>To prevent serious harm to health or safety</li>
            </ul>
            
            <h2>Data Security</h2>
            <p>We protect your information through:</p>
            <ul>
                <li>Encryption of data in transit and at rest</li>
                <li>Secure password hashing (bcrypt)</li>
                <li>Access controls and user authentication</li>
                <li>Comprehensive audit logging</li>
                <li>Regular security assessments</li>
                <li>Staff training on privacy and security</li>
            </ul>
            
            <h2>Your Rights</h2>
            <p>Under HIPAA and Canadian privacy legislation, you have the right to:</p>
            <ul>
                <li><strong>Access:</strong> Request copies of your health information</li>
                <li><strong>Amendment:</strong> Request corrections to your information</li>
                <li><strong>Accounting:</strong> Receive a list of disclosures</li>
                <li><strong>Restriction:</strong> Request limits on use and disclosure</li>
                <li><strong>Confidential Communication:</strong> Request communication by alternative means</li>
                <li><strong>Revocation:</strong> Withdraw consent (with some exceptions)</li>
            </ul>
            
            <h2>Data Retention</h2>
            <p>We retain your information for:</p>
            <ul>
                <li>Medical records: As required by law (typically 7-10 years)</li>
                <li>Audit logs: Minimum 6 years for HIPAA compliance</li>
                <li>Inactive accounts: 3 years after last activity</li>
            </ul>
            
            <h2>Cookies and Tracking</h2>
            <p>We use essential cookies for:</p>
            <ul>
                <li>Session management and authentication</li>
                <li>User preferences and settings</li>
                <li>System functionality</li>
            </ul>
            <p>We do NOT use tracking cookies or share data with third-party advertisers.</p>
            
            <h2>Children's Privacy</h2>
            <p>Our services are not intended for individuals under 18 years of age without parental/guardian consent. We comply with all applicable laws regarding minors' health information.</p>
            
            <h2>Breach Notification</h2>
            <p>In the event of a data breach affecting your protected health information, we will notify you within 60 days as required by HIPAA.</p>
            
            <h2>Changes to This Policy</h2>
            <p>We may update this Privacy Policy to reflect changes in our practices or legal requirements. We will post the updated policy with a new "Last Updated" date.</p>
            
            <h2>Contact Us</h2>
            <p>For privacy concerns or to exercise your rights:</p>
            <ul>
                <li><strong>Email:</strong> privacy@healthcare.com</li>
                <li><strong>Phone:</strong> 1-800-XXX-XXXX</li>
                <li><strong>Mail:</strong> Privacy Officer, <?php echo APP_NAME; ?>, Toronto, ON, Canada</li>
            </ul>
            
            <h2>Compliance</h2>
            <p>This Privacy Policy is designed to comply with:</p>
            <ul>
                <li>Health Insurance Portability and Accountability Act (HIPAA)</li>
                <li>Personal Information Protection and Electronic Documents Act (PIPEDA)</li>
                <li>Provincial health information privacy legislation</li>
            </ul>
            
            <div style="margin-top: 30px; text-align: center;">
                <?php if ($isLoggedIn): ?>
                <a href="<?php echo Auth::getRedirectByRole($_SESSION['role']); ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <?php else: ?>
                <a href="/index.php" class="btn btn-primary">
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
