<?php
/**
 * HIPAA Notice Page
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
                <i class="fas fa-shield-alt"></i> HIPAA Notice of Privacy Practices
            </h1>
        </div>
        <div class="card-body">
            <p><strong>Effective Date:</strong> <?php echo date('F d, Y'); ?></p>
            
            <div class="alert alert-info">
                <strong>THIS NOTICE DESCRIBES HOW MEDICAL INFORMATION ABOUT YOU MAY BE USED AND DISCLOSED AND HOW YOU CAN GET ACCESS TO THIS INFORMATION. PLEASE REVIEW IT CAREFULLY.</strong>
            </div>
            
            <h2>Our Commitment to Your Privacy</h2>
            <p><?php echo APP_NAME; ?> is committed to protecting the privacy of your health information. We are required by law to:</p>
            <ul>
                <li>Maintain the privacy of your protected health information (PHI)</li>
                <li>Provide you with this notice of our legal duties and privacy practices</li>
                <li>Follow the terms of this notice currently in effect</li>
                <li>Notify you if we are unable to agree to a requested restriction</li>
                <li>Accommodate reasonable requests to communicate health information by alternative means or locations</li>
            </ul>
            
            <h2>How We May Use and Disclose Your Health Information</h2>
            
            <h3>1. Treatment</h3>
            <p>We may use and disclose your health information to provide, coordinate, or manage your healthcare and related services.</p>
            <p><strong>Example:</strong> Sharing your health information with doctors, nurses, technicians, or other personnel involved in your care.</p>
            
            <h3>2. Payment</h3>
            <p>We may use and disclose your health information to obtain payment for services provided.</p>
            <p><strong>Example:</strong> Submitting claims to your health insurance company or billing you for services.</p>
            
            <h3>3. Healthcare Operations</h3>
            <p>We may use and disclose your health information for our healthcare operations, including:</p>
            <ul>
                <li>Quality assessment and improvement activities</li>
                <li>Reviewing the competence of healthcare professionals</li>
                <li>Conducting training programs</li>
                <li>Accreditation, certification, licensing activities</li>
                <li>Business planning and management</li>
            </ul>
            
            <h3>4. Other Uses and Disclosures</h3>
            <p>We may use and disclose your health information without your authorization for:</p>
            
            <h4>Public Health Activities</h4>
            <ul>
                <li>Reporting diseases, injuries, or disabilities</li>
                <li>Reporting child abuse or neglect</li>
                <li>Reporting adverse reactions to medications</li>
                <li>Product recalls or tracking</li>
            </ul>
            
            <h4>Health Oversight Activities</h4>
            <ul>
                <li>Audits, investigations, inspections</li>
                <li>Licensure or disciplinary actions</li>
                <li>Civil, administrative, or criminal proceedings</li>
            </ul>
            
            <h4>Judicial and Administrative Proceedings</h4>
            <ul>
                <li>In response to court orders or subpoenas</li>
                <li>To defend against lawsuits</li>
            </ul>
            
            <h4>Law Enforcement</h4>
            <ul>
                <li>In response to valid law enforcement requests</li>
                <li>To report crimes on our premises</li>
                <li>To report crimes in emergencies</li>
            </ul>
            
            <h4>To Avert Serious Threat to Health or Safety</h4>
            <p>When necessary to prevent a serious threat to your health and safety or the health and safety of others.</p>
            
            <h4>Specialized Government Functions</h4>
            <ul>
                <li>Military and veterans activities</li>
                <li>National security and intelligence</li>
                <li>Protective services for the President</li>
                <li>Correctional institutions</li>
            </ul>
            
            <h4>Workers' Compensation</h4>
            <p>For workers' compensation or similar programs providing benefits for work-related injuries or illness.</p>
            
            <h2>Uses and Disclosures Requiring Your Authorization</h2>
            <p>We will obtain your written authorization before using or disclosing your health information for purposes other than those described above. You may revoke your authorization at any time in writing.</p>
            
            <h3>Specific Authorizations Required For:</h3>
            <ul>
                <li>Marketing purposes</li>
                <li>Sale of protected health information</li>
                <li>Most uses and disclosures of psychotherapy notes</li>
                <li>Other uses and disclosures not described in this notice</li>
            </ul>
            
            <h2>Your Rights Regarding Your Health Information</h2>
            
            <h3>1. Right to Access</h3>
            <p>You have the right to inspect and obtain a copy of your health information. To request access:</p>
            <ul>
                <li>Submit a written request to our Privacy Officer</li>
                <li>We will respond within 30 days</li>
                <li>We may charge a reasonable fee for copying costs</li>
            </ul>
            
            <h3>2. Right to Amend</h3>
            <p>You may request an amendment to your health information if you believe it is incorrect or incomplete.</p>
            <ul>
                <li>Submit a written request with reasons for the amendment</li>
                <li>We may deny your request if the information is accurate and complete</li>
                <li>You may submit a statement of disagreement</li>
            </ul>
            
            <h3>3. Right to an Accounting of Disclosures</h3>
            <p>You have the right to receive a list of disclosures we have made of your health information (with certain exceptions).</p>
            <ul>
                <li>Request must be in writing</li>
                <li>First accounting in a 12-month period is free</li>
                <li>We will respond within 60 days</li>
            </ul>
            
            <h3>4. Right to Request Restrictions</h3>
            <p>You may request restrictions on certain uses and disclosures of your health information.</p>
            <ul>
                <li>We are not required to agree to your request</li>
                <li>If we agree, we will comply unless information is needed for emergency treatment</li>
            </ul>
            
            <h3>5. Right to Request Confidential Communications</h3>
            <p>You may request that we communicate with you about your health information by alternative means or at alternative locations.</p>
            <ul>
                <li>Request must be in writing</li>
                <li>We will accommodate reasonable requests</li>
            </ul>
            
            <h3>6. Right to Notification of Breach</h3>
            <p>You have the right to be notified if there is a breach of your unsecured protected health information.</p>
            
            <h3>7. Right to Obtain Paper Copy</h3>
            <p>You have the right to obtain a paper copy of this notice upon request.</p>
            
            <h2>Changes to This Notice</h2>
            <p>We reserve the right to change this notice. Any changes will apply to health information we already have as well as information we receive in the future.</p>
            <ul>
                <li>We will post the current notice on our website</li>
                <li>We will provide the notice upon request</li>
                <li>The effective date will be shown at the top</li>
            </ul>
            
            <h2>Complaints</h2>
            <p>If you believe your privacy rights have been violated, you may file a complaint with:</p>
            
            <h3>Our Privacy Officer</h3>
            <ul>
                <li><strong>Email:</strong> privacy@healthcare.com</li>
                <li><strong>Phone:</strong> 1-800-XXX-XXXX</li>
                <li><strong>Mail:</strong> Privacy Officer, <?php echo APP_NAME; ?>, Toronto, ON, Canada</li>
            </ul>
            
            <h3>Office for Civil Rights</h3>
            <p>U.S. Department of Health and Human Services<br>
            200 Independence Avenue, S.W.<br>
            Washington, D.C. 20201<br>
            Phone: 1-877-696-6775</p>
            
            <p><strong>You will not be penalized or retaliated against for filing a complaint.</strong></p>
            
            <h2>Contact Information</h2>
            <p>For more information about this notice or our privacy practices:</p>
            <ul>
                <li><strong>Privacy Officer:</strong> privacy@healthcare.com</li>
                <li><strong>Phone:</strong> 1-800-XXX-XXXX</li>
                <li><strong>Hours:</strong> Monday - Friday, 9 AM - 5 PM EST</li>
            </ul>
            
            <div class="alert alert-warning" style="margin-top: 30px;">
                <strong>Acknowledgment:</strong> By using our services, you acknowledge that you have received and reviewed this Notice of Privacy Practices.
            </div>
            
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
