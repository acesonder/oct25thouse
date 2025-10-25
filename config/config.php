<?php
/**
 * Database Configuration File
 * HIPAA Compliant - Secure Connection Settings
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'healthcare_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application settings
define('APP_NAME', 'Healthcare Management System');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost');

// Security settings
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes

// File upload settings
define('UPLOAD_MAX_SIZE', 10485760); // 10MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/mpeg', 'video/quicktime']);

// Timezone
date_default_timezone_set('America/Toronto'); // Canadian timezone

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration for HIPAA compliance
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
?>
