<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Client Registration</h1>
        
        <div id="message" class="message" style="display: none;"></div>
        
        <form id="registrationForm" onsubmit="submitRegistration(event)">
            <div class="form-group">
                <label for="firstName">First Name *</label>
                <input type="text" id="firstName" name="firstName" required>
            </div>
            
            <div class="form-group">
                <label for="lastName">Last Name *</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>
            
            <div class="form-group">
                <label for="dob">Date of Birth *</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            
            <div class="form-group">
                <label for="securityQuestion">Security Question *</label>
                <select id="securityQuestion" name="securityQuestion" required>
                    <option value="">-- Select a Security Question --</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="securityAnswer">Security Answer *</label>
                <input type="text" id="securityAnswer" name="securityAnswer" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
                <div id="passwordStrength" class="password-strength"></div>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirm Password *</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <div id="passwordMatch" style="font-size: 12px; margin-top: 5px;"></div>
            </div>
            
            <div class="checkbox-group">
                <label>
                    <input type="checkbox" id="consentShare" name="consentShare" required>
                    I consent to share my information
                </label>
            </div>
            
            <div class="checkbox-group">
                <label>
                    <input type="checkbox" id="consentTerms" name="consentTerms" required>
                    I agree to the
                    <span class="modal-link" onclick="openModal('termsModal')">Terms of Service</span>
                </label>
            </div>
            
            <button type="submit" id="submitBtn">Register</button>
        </form>
        
        <div class="link-text">
            Already have an account? <a href="recovery.php">Recover Account</a>
        </div>
    </div>
    
    <!-- Terms of Service Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Terms of Service</h2>
                <span class="close" onclick="closeModal('termsModal')">&times;</span>
            </div>
            <div class="modal-body">
                <h3>1. Acceptance of Terms</h3>
                <p>By registering for this service, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our service.</p>
                
                <h3>2. User Account</h3>
                <p>You are responsible for maintaining the confidentiality of your account credentials. You agree to accept responsibility for all activities that occur under your account.</p>
                
                <h3>3. Privacy and Data Protection</h3>
                <p>We are committed to protecting your privacy. Your personal information will be handled in accordance with our Privacy Policy and applicable data protection laws.</p>
                
                <h3>4. User Conduct</h3>
                <p>You agree to use this service in a lawful manner and not to engage in any activity that may harm the service or other users.</p>
                
                <h3>5. Service Availability</h3>
                <p>We strive to maintain continuous service availability but do not guarantee uninterrupted access. We reserve the right to modify or discontinue the service at any time.</p>
                
                <h3>6. Limitation of Liability</h3>
                <p>To the fullest extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of the service.</p>
                
                <h3>7. Changes to Terms</h3>
                <p>We reserve the right to modify these terms at any time. Continued use of the service after changes constitutes acceptance of the modified terms.</p>
                
                <h3>8. Contact</h3>
                <p>If you have any questions about these Terms of Service, please contact our support team.</p>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
