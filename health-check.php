<?php
/**
 * System Health Check
 * Validates installation and configuration
 */

$checks = [];
$errors = [];
$warnings = [];

// Check PHP version
$phpVersion = phpversion();
$checks['PHP Version'] = [
    'status' => version_compare($phpVersion, '7.4', '>='),
    'message' => $phpVersion,
    'required' => '7.4+'
];

// Check required PHP extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'session', 'json'];
foreach ($requiredExtensions as $ext) {
    $checks["PHP Extension: $ext"] = [
        'status' => extension_loaded($ext),
        'message' => extension_loaded($ext) ? 'Loaded' : 'Not loaded',
        'required' => 'Required'
    ];
}

// Check configuration file
$checks['Config File'] = [
    'status' => file_exists(__DIR__ . '/config/config.php'),
    'message' => file_exists(__DIR__ . '/config/config.php') ? 'Found' : 'Missing',
    'required' => 'Required'
];

// Check database connection
try {
    require_once __DIR__ . '/config/database.php';
    $db = getDB();
    $checks['Database Connection'] = [
        'status' => true,
        'message' => 'Connected',
        'required' => 'Required'
    ];
    
    // Check if tables exist
    $tables = ['users', 'user_settings', 'messages', 'notifications', 'audit_log'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        $checks["Table: $table"] = [
            'status' => $exists,
            'message' => $exists ? 'Exists' : 'Missing',
            'required' => 'Required'
        ];
    }
} catch (Exception $e) {
    $checks['Database Connection'] = [
        'status' => false,
        'message' => 'Failed: ' . $e->getMessage(),
        'required' => 'Required'
    ];
}

// Check writable directories
$writableDirs = ['uploads', 'sessions', 'tmp'];
foreach ($writableDirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    $checks["Writable: $dir"] = [
        'status' => is_writable($path),
        'message' => is_writable($path) ? 'Writable' : 'Not writable',
        'required' => 'Required'
    ];
}

// Count errors and warnings
foreach ($checks as $check) {
    if (!$check['status']) {
        $errors[] = $check;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Health Check</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: #f5f5f5;">
    <div class="container" style="margin-top: 50px;">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <i class="fas fa-heartbeat"></i> System Health Check
                </h1>
            </div>
            <div class="card-body">
                <?php if (count($errors) === 0): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>All checks passed!</strong> Your system is ready to use.
                </div>
                <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Issues detected!</strong> Please resolve the following errors before using the system.
                </div>
                <?php endif; ?>
                
                <h3 style="margin-top: 30px;">System Requirements</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Check</th>
                            <th>Status</th>
                            <th>Current</th>
                            <th>Required</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($checks as $name => $check): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td>
                                <?php if ($check['status']): ?>
                                <span style="color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i> Pass
                                </span>
                                <?php else: ?>
                                <span style="color: var(--danger-color);">
                                    <i class="fas fa-times-circle"></i> Fail
                                </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($check['message']); ?></td>
                            <td><?php echo htmlspecialchars($check['required']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if (count($errors) === 0): ?>
                <div style="margin-top: 30px; text-align: center;">
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Go to Application
                    </a>
                </div>
                <?php else: ?>
                <div style="margin-top: 30px;">
                    <h3>Next Steps</h3>
                    <ul>
                        <li>Review the failed checks above</li>
                        <li>Ensure database is created and schema is imported</li>
                        <li>Check directory permissions for uploads, sessions, and tmp</li>
                        <li>Verify PHP extensions are installed</li>
                        <li>Update database credentials in config/config.php</li>
                        <li>Refresh this page after making changes</li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Quick Links
                </h3>
            </div>
            <div class="card-body">
                <ul>
                    <li><a href="INSTALL.html">Installation Guide</a></li>
                    <li><a href="index.php">Landing Page</a></li>
                    <li><a href="admin/admin.php">Admin Portal (Passcode: 079777)</a></li>
                    <li><a href="README.md">Documentation</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
