// Main JavaScript for User Registration System

// Load security questions on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('securityQuestion')) {
        loadSecurityQuestions();
    }
    
    // Initialize password validation
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', validatePasswordStrength);
    }
    
    // Initialize password confirmation
    const confirmPasswordInput = document.getElementById('confirmPassword');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
});

// Load security questions via AJAX
function loadSecurityQuestions() {
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=getSecurityQuestions'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('securityQuestion');
            select.innerHTML = '<option value="">-- Select a Security Question --</option>';
            data.questions.forEach(question => {
                const option = document.createElement('option');
                option.value = question.question_id;
                option.textContent = question.question_text;
                select.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading security questions:', error);
    });
}

// Validate password strength
function validatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (!strengthIndicator) return;
    
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

// Check password match
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const matchIndicator = document.getElementById('passwordMatch');
    
    if (!matchIndicator) return;
    
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

// Registration form submission
function submitRegistration(event) {
    event.preventDefault();
    
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const dob = document.getElementById('dob').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const securityQuestionId = document.getElementById('securityQuestion').value;
    const securityAnswer = document.getElementById('securityAnswer').value.trim();
    const consentShare = document.getElementById('consentShare').checked;
    const consentTerms = document.getElementById('consentTerms').checked;
    
    // Validate password match
    if (password !== confirmPassword) {
        showMessage('Passwords do not match', 'error');
        return;
    }
    
    // Validate consents
    if (!consentShare) {
        showMessage('You must consent to share information', 'error');
        return;
    }
    
    if (!consentTerms) {
        showMessage('You must agree to the terms of service', 'error');
        return;
    }
    
    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registering...';
    
    // Submit registration via AJAX
    const formData = new URLSearchParams({
        action: 'register',
        firstName: firstName,
        lastName: lastName,
        dob: dob,
        password: password,
        securityQuestionId: securityQuestionId,
        securityAnswer: securityAnswer,
        consentShare: consentShare ? 'true' : 'false',
        consentTerms: consentTerms ? 'true' : 'false'
    });
    
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to success page with userID
            window.location.href = 'success.php';
        } else {
            showMessage(data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Register';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register';
    });
}

// Recovery - Step 1: Find user
function submitFindUser(event) {
    event.preventDefault();
    
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const dob = document.getElementById('dob').value;
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Searching...';
    
    const formData = new URLSearchParams({
        action: 'findUserForRecovery',
        firstName: firstName,
        lastName: lastName,
        dob: dob
    });
    
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Store userID and load security question
            sessionStorage.setItem('recovery_user_id', data.user_id);
            sessionStorage.setItem('recovery_question_id', data.security_question_id);
            window.location.href = 'recovery-step2.php';
        } else {
            showMessage(data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Continue';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Continue';
    });
}

// Recovery - Step 2: Answer security question
function submitSecurityAnswer(event) {
    event.preventDefault();
    
    const securityAnswer = document.getElementById('securityAnswer').value.trim();
    const userID = sessionStorage.getItem('recovery_user_id');
    
    if (!userID) {
        showMessage('Session expired. Please start over.', 'error');
        setTimeout(() => window.location.href = 'recovery.php', 2000);
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Verifying...';
    
    const formData = new URLSearchParams({
        action: 'verifySecurityAnswer',
        userID: userID,
        securityAnswer: securityAnswer
    });
    
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'recovery-step3.php';
        } else {
            showMessage(data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Verify Answer';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Verify Answer';
    });
}

// Recovery - Step 3: Reset password
function submitPasswordReset(event) {
    event.preventDefault();
    
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const userID = sessionStorage.getItem('recovery_user_id');
    
    if (!userID) {
        showMessage('Session expired. Please start over.', 'error');
        setTimeout(() => window.location.href = 'recovery.php', 2000);
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showMessage('Passwords do not match', 'error');
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Resetting...';
    
    const formData = new URLSearchParams({
        action: 'resetPassword',
        userID: userID,
        newPassword: newPassword
    });
    
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear session storage
            sessionStorage.removeItem('recovery_user_id');
            sessionStorage.removeItem('recovery_question_id');
            
            // Show success and redirect
            showMessage('Password reset successfully!', 'success');
            setTimeout(() => window.location.href = 'index.php', 2000);
        } else {
            showMessage(data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Reset Password';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Reset Password';
    });
}

// Show message helper
function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    if (messageDiv) {
        messageDiv.textContent = message;
        messageDiv.className = 'message ' + type;
        messageDiv.style.display = 'block';
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
    }
}

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
