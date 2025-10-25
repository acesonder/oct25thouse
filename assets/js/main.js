/**
 * Main JavaScript File
 * Core functionality and utilities
 */

$(document).ready(function() {
    
    // Mobile menu toggle
    $('.navbar-toggle').click(function() {
        $('.navbar-menu').slideToggle();
    });
    
    // Show loading overlay
    window.showLoading = function() {
        $('#loading-overlay').fadeIn();
    };
    
    // Hide loading overlay
    window.hideLoading = function() {
        $('#loading-overlay').fadeOut();
    };
    
    // Show alert
    window.showAlert = function(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} fade-in" role="alert">
                <i class="fas fa-${getAlertIcon(type)}"></i>
                ${message}
            </div>
        `;
        
        const $alert = $(alertHtml);
        $('body').prepend($alert);
        
        setTimeout(() => {
            $alert.fadeOut(() => $alert.remove());
        }, 5000);
    };
    
    function getAlertIcon(type) {
        const icons = {
            'success': 'check-circle',
            'danger': 'exclamation-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
    
    // AJAX form submission
    window.submitForm = function(formId, callback) {
        const $form = $('#' + formId);
        const formData = new FormData($form[0]);
        
        showLoading();
        
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoading();
                if (callback) callback(response);
            },
            error: function(xhr, status, error) {
                hideLoading();
                showAlert('An error occurred. Please try again.', 'danger');
            }
        });
    };
    
    // Modal functions
    window.showModal = function(modalId) {
        $('#' + modalId).fadeIn();
    };
    
    window.hideModal = function(modalId) {
        $('#' + modalId).fadeOut();
    };
    
    // Close modal on click outside
    $(document).on('click', '.modal', function(e) {
        if ($(e.target).hasClass('modal')) {
            $(this).fadeOut();
        }
    });
    
    // Close modal on close button
    $(document).on('click', '.close', function() {
        $(this).closest('.modal').fadeOut();
    });
    
    // Form validation
    window.validateForm = function(formId) {
        const $form = $('#' + formId);
        let isValid = true;
        
        $form.find('[required]').each(function() {
            const $field = $(this);
            
            if (!$field.val().trim()) {
                $field.addClass('is-invalid');
                isValid = false;
            } else {
                $field.removeClass('is-invalid');
            }
        });
        
        // Email validation
        $form.find('input[type="email"]').each(function() {
            const $field = $(this);
            const email = $field.val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                $field.addClass('is-invalid');
                isValid = false;
            }
        });
        
        // Password matching
        const $password = $form.find('input[name="password"]');
        const $confirmPassword = $form.find('input[name="confirm_password"]');
        
        if ($password.length && $confirmPassword.length) {
            if ($password.val() !== $confirmPassword.val()) {
                $confirmPassword.addClass('is-invalid');
                showAlert('Passwords do not match', 'danger');
                isValid = false;
            }
        }
        
        return isValid;
    };
    
    // Remove validation on input
    $(document).on('input', '.is-invalid', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Password strength indicator
    $(document).on('input', 'input[type="password"]', function() {
        const password = $(this).val();
        const strength = calculatePasswordStrength(password);
        
        // You can add a password strength indicator here
    });
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        return strength;
    }
    
    // Auto-logout on inactivity
    let inactivityTimer;
    const inactivityTimeout = 30 * 60 * 1000; // 30 minutes
    
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            window.location.href = '/logout.php?reason=inactivity';
        }, inactivityTimeout);
    }
    
    // Reset timer on user activity
    $(document).on('mousemove keypress click scroll', resetInactivityTimer);
    resetInactivityTimer();
    
    // Confirm before leaving page with unsaved changes
    let hasUnsavedChanges = false;
    
    $(document).on('input change', 'form input, form textarea, form select', function() {
        hasUnsavedChanges = true;
    });
    
    $(document).on('submit', 'form', function() {
        hasUnsavedChanges = false;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });
    
    // Smooth scroll
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Auto-resize textarea
    $(document).on('input', 'textarea', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // File upload preview
    $(document).on('change', 'input[type="file"]', function() {
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            const $preview = $(this).siblings('.file-preview');
            
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    $preview.html(`<img src="${e.target.result}" alt="Preview" style="max-width: 200px;">`);
                } else {
                    $preview.html(`<p>${file.name} (${formatFileSize(file.size)})</p>`);
                }
            };
            
            reader.readAsDataURL(file);
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    // Copy to clipboard
    window.copyToClipboard = function(text) {
        const $temp = $('<input>');
        $('body').append($temp);
        $temp.val(text).select();
        document.execCommand('copy');
        $temp.remove();
        showAlert('Copied to clipboard!', 'success');
    };
    
    // Format date
    window.formatDate = function(date, format = 'Y-m-d H:i:s') {
        const d = new Date(date);
        
        const formats = {
            'Y': d.getFullYear(),
            'm': String(d.getMonth() + 1).padStart(2, '0'),
            'd': String(d.getDate()).padStart(2, '0'),
            'H': String(d.getHours()).padStart(2, '0'),
            'i': String(d.getMinutes()).padStart(2, '0'),
            's': String(d.getSeconds()).padStart(2, '0')
        };
        
        return format.replace(/[YmdHis]/g, match => formats[match]);
    };
    
    // Time ago
    window.timeAgo = function(date) {
        const seconds = Math.floor((new Date() - new Date(date)) / 1000);
        
        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60,
            second: 1
        };
        
        for (const [name, value] of Object.entries(intervals)) {
            const interval = Math.floor(seconds / value);
            
            if (interval >= 1) {
                return interval + ' ' + name + (interval > 1 ? 's' : '') + ' ago';
            }
        }
        
        return 'just now';
    };
});
