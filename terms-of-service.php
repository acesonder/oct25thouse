<?php
/**
 * Terms of Service Page
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
                <i class="fas fa-file-contract"></i> Terms of Service
            </h1>
        </div>
        <div class="card-body">
            <p><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>
            
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using <?php echo APP_NAME; ?>, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>
            
            <h2>2. Description of Service</h2>
            <p><?php echo APP_NAME; ?> provides a HIPAA-compliant healthcare management platform that includes:</p>
            <ul>
                <li>Patient portal for accessing health records</li>
                <li>Secure messaging between clients, staff, and service providers</li>
                <li>Appointment scheduling and management</li>
                <li>Healthcare provider collaboration tools</li>
                <li>Administrative and reporting features</li>
            </ul>
            
            <h2>3. User Accounts</h2>
            
            <h3>3.1 Account Creation</h3>
            <ul>
                <li>You must provide accurate and complete information during registration</li>
                <li>You are responsible for maintaining the confidentiality of your User ID and password</li>
                <li>You must notify us immediately of any unauthorized use of your account</li>
                <li>Staff and Service Provider accounts require administrative approval</li>
            </ul>
            
            <h3>3.2 Account Security</h3>
            <ul>
                <li>You are solely responsible for all activities under your account</li>
                <li>Use strong, unique passwords and change them regularly</li>
                <li>Do not share your login credentials with others</li>
                <li>Log out when using shared or public computers</li>
            </ul>
            
            <h3>3.3 Account Types</h3>
            <ul>
                <li><strong>Client:</strong> Access to personal health records and services</li>
                <li><strong>Volunteer:</strong> Limited access to support services</li>
                <li><strong>Staff:</strong> Access to client management and administrative tools (requires approval)</li>
                <li><strong>Service Provider:</strong> Access to provide healthcare services (requires approval)</li>
                <li><strong>System Administrator:</strong> Full system access and control</li>
            </ul>
            
            <h2>4. Acceptable Use</h2>
            
            <h3>4.1 Permitted Use</h3>
            <p>You may use our services only for lawful purposes and in accordance with these Terms. You agree to:</p>
            <ul>
                <li>Use the system only for healthcare-related purposes</li>
                <li>Respect the privacy and confidentiality of other users</li>
                <li>Provide accurate information in all communications</li>
                <li>Comply with all applicable laws and regulations</li>
            </ul>
            
            <h3>4.2 Prohibited Use</h3>
            <p>You agree NOT to:</p>
            <ul>
                <li>Use the system for any illegal or unauthorized purpose</li>
                <li>Attempt to gain unauthorized access to any part of the system</li>
                <li>Upload malicious code, viruses, or harmful content</li>
                <li>Harass, abuse, or harm other users</li>
                <li>Share protected health information without authorization</li>
                <li>Use automated systems to access the platform (bots, scrapers)</li>
                <li>Impersonate another person or entity</li>
                <li>Interfere with or disrupt the service</li>
            </ul>
            
            <h2>5. Privacy and Data Protection</h2>
            <p>Your use of our services is also governed by our <a href="/privacy-policy.php">Privacy Policy</a>, which is incorporated into these Terms by reference.</p>
            
            <h2>6. Healthcare Disclaimer</h2>
            <p><strong>Important:</strong> This system is a communication and management tool. It does not replace professional medical advice, diagnosis, or treatment.</p>
            <ul>
                <li>Always seek the advice of qualified healthcare providers</li>
                <li>Never disregard professional medical advice based on system content</li>
                <li>Call emergency services (911) for medical emergencies</li>
                <li>The system is not intended for urgent medical communications</li>
            </ul>
            
            <h2>7. User Content</h2>
            
            <h3>7.1 Your Responsibilities</h3>
            <p>You are responsible for all content you submit, including:</p>
            <ul>
                <li>Messages and communications</li>
                <li>Health information and records</li>
                <li>Files, images, and documents uploaded</li>
            </ul>
            
            <h3>7.2 Content Standards</h3>
            <p>All content must:</p>
            <ul>
                <li>Be accurate and truthful</li>
                <li>Comply with applicable laws</li>
                <li>Respect intellectual property rights</li>
                <li>Not contain offensive, harmful, or inappropriate material</li>
            </ul>
            
            <h2>8. Intellectual Property</h2>
            <p>All content, features, and functionality of <?php echo APP_NAME; ?> are owned by us and protected by copyright, trademark, and other intellectual property laws.</p>
            
            <h2>9. System Availability</h2>
            <ul>
                <li>We strive to provide 99.9% uptime but cannot guarantee uninterrupted access</li>
                <li>Scheduled maintenance may cause temporary unavailability</li>
                <li>We are not liable for damages resulting from system downtime</li>
            </ul>
            
            <h2>10. Limitation of Liability</h2>
            <p>To the fullest extent permitted by law:</p>
            <ul>
                <li>We are not liable for indirect, incidental, or consequential damages</li>
                <li>Our total liability shall not exceed the amount you paid for services</li>
                <li>We are not responsible for third-party actions or content</li>
            </ul>
            
            <h2>11. Indemnification</h2>
            <p>You agree to indemnify and hold harmless <?php echo APP_NAME; ?> from any claims, damages, or expenses arising from:</p>
            <ul>
                <li>Your violation of these Terms</li>
                <li>Your use of the services</li>
                <li>Your violation of any rights of another party</li>
            </ul>
            
            <h2>12. Account Termination</h2>
            
            <h3>12.1 By You</h3>
            <p>You may terminate your account at any time by contacting support.</p>
            
            <h3>12.2 By Us</h3>
            <p>We reserve the right to suspend or terminate your account if:</p>
            <ul>
                <li>You violate these Terms</li>
                <li>You engage in fraudulent or illegal activity</li>
                <li>Your account has been inactive for an extended period</li>
                <li>Required by law or legal process</li>
            </ul>
            
            <h2>13. Modifications to Terms</h2>
            <p>We may modify these Terms at any time. We will notify you of significant changes via:</p>
            <ul>
                <li>Email notification</li>
                <li>System notification upon login</li>
                <li>Updated "Last Updated" date</li>
            </ul>
            <p>Continued use of the service after changes constitutes acceptance of the new Terms.</p>
            
            <h2>14. Governing Law</h2>
            <p>These Terms are governed by the laws of Canada and the province of Ontario, without regard to conflict of law provisions.</p>
            
            <h2>15. Dispute Resolution</h2>
            <p>Any disputes arising from these Terms shall be resolved through:</p>
            <ul>
                <li>Good faith negotiation</li>
                <li>Mediation (if negotiation fails)</li>
                <li>Binding arbitration in Toronto, Ontario</li>
            </ul>
            
            <h2>16. Severability</h2>
            <p>If any provision of these Terms is found to be unenforceable, the remaining provisions will remain in full effect.</p>
            
            <h2>17. Entire Agreement</h2>
            <p>These Terms, together with our Privacy Policy, constitute the entire agreement between you and <?php echo APP_NAME; ?>.</p>
            
            <h2>18. Contact Information</h2>
            <p>For questions about these Terms:</p>
            <ul>
                <li><strong>Email:</strong> legal@healthcare.com</li>
                <li><strong>Phone:</strong> 1-800-XXX-XXXX</li>
                <li><strong>Mail:</strong> Legal Department, <?php echo APP_NAME; ?>, Toronto, ON, Canada</li>
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
