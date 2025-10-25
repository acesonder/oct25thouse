<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        
        <div id="message" class="message" style="display: none;"></div>
        
        <!-- Display User ID -->
        <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0; color: #666;">Your User ID:</p>
            <div class="user-id-display" id="userIdDisplay" style="font-size: 20px; margin: 10px 0 0 0;">
                Loading...
            </div>
        </div>
        
        <form id="resetForm" onsubmit="submitPasswordReset(event)">
            <div class="form-group">
                <label for="newPassword">New Password *</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <div id="passwordStrength" class="password-strength"></div>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirm Password *</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <div id="passwordMatch" style="font-size: 12px; margin-top: 5px;"></div>
            </div>
            
            <button type="submit" id="submitBtn">Reset Password</button>
        </form>
        
        <div class="link-text">
            <a href="recovery.php">Start Over</a>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
        // Display the recovered User ID
        document.addEventListener('DOMContentLoaded', function() {
            const userId = sessionStorage.getItem('recovery_user_id');
            
            if (!userId) {
                showMessage('Session expired. Please start over.', 'error');
                setTimeout(() => window.location.href = 'recovery.php', 2000);
                return;
            }
            
            document.getElementById('userIdDisplay').textContent = userId;
            
            // Initialize password validation
            const passwordInput = document.getElementById('newPassword');
            passwordInput.addEventListener('input', validatePasswordStrength);
            
            const confirmPasswordInput = document.getElementById('confirmPassword');
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        });
        
        function validatePasswordStrength() {
            const password = document.getElementById('newPassword').value;
            const strengthIndicator = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthIndicator.textContent = '';
                return;
            }
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            strengthIndicator.classList.remove('weak', 'medium', 'strong');
            
            if (strength <= 2) {
                strengthIndicator.textContent = 'Weak password';
                strengthIndicator.classList.add('weak');
            } else if (strength <= 3) {
                strengthIndicator.textContent = 'Medium password';
                strengthIndicator.classList.add('medium');
            } else {
                strengthIndicator.textContent = 'Strong password';
                strengthIndicator.classList.add('strong');
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchIndicator = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchIndicator.textContent = '';
                return;
            }
            
            if (password === confirmPassword) {
                matchIndicator.textContent = 'Passwords match';
                matchIndicator.style.color = '#4caf50';
            } else {
                matchIndicator.textContent = 'Passwords do not match';
                matchIndicator.style.color = '#f44336';
            }
        }
    </script>
</body>
</html>
