<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Link & Page Validation</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .test-result {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .test-pass {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .test-fail {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .test-section {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <i class="fas fa-check-circle"></i> System Validation Report
                </h1>
            </div>
            <div class="card-body">
                <div class="test-section">
                    <h2>Page Existence Tests</h2>
                    <?php
                    $pages = [
                        'Landing Page' => '/index.php',
                        'Admin Portal' => '/admin/admin.php',
                        'Client Dashboard' => '/client/dashboard.php',
                        'Staff Dashboard' => '/staff/dashboard.php',
                        'Service Provider Dashboard' => '/service_provider/dashboard.php',
                        'Profile Page' => '/profile.php',
                        'Settings Page' => '/settings.php',
                        'Password Reset' => '/reset-password.php',
                        'Logout Handler' => '/logout.php',
                        'Health Check' => '/health-check.php',
                        'Installation Guide' => '/INSTALL.html',
                        'Privacy Policy' => '/privacy-policy.php',
                        'Terms of Service' => '/terms-of-service.php',
                        'HIPAA Notice' => '/hipaa-notice.php',
                        'Support Page' => '/support.php',
                    ];
                    
                    foreach ($pages as $name => $path) {
                        $fullPath = __DIR__ . $path;
                        $exists = file_exists($fullPath);
                        $class = $exists ? 'test-pass' : 'test-fail';
                        $icon = $exists ? 'fa-check-circle' : 'fa-times-circle';
                        echo "<div class='test-result $class'>";
                        echo "<i class='fas $icon'></i>";
                        echo "<strong>$name</strong>: $path - ";
                        echo $exists ? 'EXISTS' : 'MISSING';
                        echo "</div>";
                    }
                    ?>
                </div>
                
                <div class="test-section">
                    <h2>API Endpoint Tests</h2>
                    <?php
                    $apis = [
                        'User Registration' => '/api/register.php',
                        'Account Recovery' => '/api/recover.php',
                        'Get Conversations' => '/api/messenger/get_conversations.php',
                        'Search User' => '/api/messenger/search_user.php',
                        'Get User' => '/api/messenger/get_user.php',
                        'Get Messages' => '/api/messenger/get_messages.php',
                        'Send Message' => '/api/messenger/send_message.php',
                        'Get Notifications' => '/api/notifications/get_notifications.php',
                        'Mark Notification Read' => '/api/notifications/mark_read.php',
                        'Check Updates' => '/api/notifications/check_updates.php',
                    ];
                    
                    foreach ($apis as $name => $path) {
                        $fullPath = __DIR__ . $path;
                        $exists = file_exists($fullPath);
                        $class = $exists ? 'test-pass' : 'test-fail';
                        $icon = $exists ? 'fa-check-circle' : 'fa-times-circle';
                        echo "<div class='test-result $class'>";
                        echo "<i class='fas $icon'></i>";
                        echo "<strong>$name</strong>: $path - ";
                        echo $exists ? 'EXISTS' : 'MISSING';
                        echo "</div>";
                    }
                    ?>
                </div>
                
                <div class="test-section">
                    <h2>Component Files Tests</h2>
                    <?php
                    $components = [
                        'Header Component' => '/includes/header.php',
                        'Footer Component' => '/includes/footer.php',
                        'Navbar Component' => '/includes/navbar.php',
                        'Auth Component' => '/includes/auth.php',
                        'Database Config' => '/config/database.php',
                        'App Config' => '/config/config.php',
                    ];
                    
                    foreach ($components as $name => $path) {
                        $fullPath = __DIR__ . $path;
                        $exists = file_exists($fullPath);
                        $class = $exists ? 'test-pass' : 'test-fail';
                        $icon = $exists ? 'fa-check-circle' : 'fa-times-circle';
                        echo "<div class='test-result $class'>";
                        echo "<i class='fas $icon'></i>";
                        echo "<strong>$name</strong>: $path - ";
                        echo $exists ? 'EXISTS' : 'MISSING';
                        echo "</div>";
                    }
                    ?>
                </div>
                
                <div class="test-section">
                    <h2>Asset Files Tests</h2>
                    <?php
                    $assets = [
                        'Main CSS' => '/assets/css/style.css',
                        'Messenger CSS' => '/assets/css/messenger.css',
                        'Themes CSS' => '/assets/css/themes.css',
                        'Main JS' => '/assets/js/main.js',
                        'Messenger JS' => '/assets/js/messenger.js',
                        'Notifications JS' => '/assets/js/notifications.js',
                    ];
                    
                    foreach ($assets as $name => $path) {
                        $fullPath = __DIR__ . $path;
                        $exists = file_exists($fullPath);
                        $class = $exists ? 'test-pass' : 'test-fail';
                        $icon = $exists ? 'fa-check-circle' : 'fa-times-circle';
                        echo "<div class='test-result $class'>";
                        echo "<i class='fas $icon'></i>";
                        echo "<strong>$name</strong>: $path - ";
                        echo $exists ? 'EXISTS' : 'MISSING';
                        echo "</div>";
                    }
                    ?>
                </div>
                
                <div class="test-section">
                    <h2>Database Configuration Test</h2>
                    <?php
                    try {
                        require_once __DIR__ . '/config/database.php';
                        $db = getDB();
                        echo "<div class='test-result test-pass'>";
                        echo "<i class='fas fa-check-circle'></i>";
                        echo "<strong>Database Connection</strong>: SUCCESS";
                        echo "</div>";
                        
                        // Test tables
                        $tables = ['users', 'user_settings', 'messages', 'notifications', 'audit_log', 'security_questions'];
                        foreach ($tables as $table) {
                            try {
                                $stmt = $db->query("SHOW TABLES LIKE '$table'");
                                $exists = $stmt->rowCount() > 0;
                                $class = $exists ? 'test-pass' : 'test-fail';
                                $icon = $exists ? 'fa-check-circle' : 'fa-times-circle';
                                echo "<div class='test-result $class'>";
                                echo "<i class='fas $icon'></i>";
                                echo "<strong>Table: $table</strong> - ";
                                echo $exists ? 'EXISTS' : 'MISSING';
                                echo "</div>";
                            } catch (Exception $e) {
                                echo "<div class='test-result test-fail'>";
                                echo "<i class='fas fa-times-circle'></i>";
                                echo "<strong>Table: $table</strong> - ERROR: " . $e->getMessage();
                                echo "</div>";
                            }
                        }
                    } catch (Exception $e) {
                        echo "<div class='test-result test-fail'>";
                        echo "<i class='fas fa-times-circle'></i>";
                        echo "<strong>Database Connection</strong>: FAILED - " . $e->getMessage();
                        echo "</div>";
                    }
                    ?>
                </div>
                
                <div class="test-section">
                    <h2>User ID Generation Test</h2>
                    <?php
                    try {
                        require_once __DIR__ . '/includes/auth.php';
                        
                        // Test User ID format
                        $testUserID = Auth::generateUserID('Michael', 'Brown', '1984-05-06');
                        $expectedPattern = '/^[A-Z]{3}[A-Z]{3}\d{6}$/'; // 3 letters + 3 letters + 6 digits (MMDDYY)
                        
                        $matches = preg_match($expectedPattern, $testUserID);
                        $class = $matches ? 'test-pass' : 'test-fail';
                        $icon = $matches ? 'fa-check-circle' : 'fa-times-circle';
                        
                        echo "<div class='test-result $class'>";
                        echo "<i class='fas $icon'></i>";
                        echo "<strong>User ID Format Test</strong>: Generated '$testUserID' ";
                        echo $matches ? '(CORRECT FORMAT)' : '(INCORRECT FORMAT - Expected FIRST3+LAST3+MMDDYY)';
                        echo "</div>";
                        
                        // Expected: MICBRO050684 (MIC + BRO + 050684)
                        $expected = 'MICBRO050684';
                        $isCorrect = ($testUserID === $expected || strpos($testUserID, $expected) === 0);
                        $class = $isCorrect ? 'test-pass' : 'test-fail';
                        $icon = $isCorrect ? 'fa-check-circle' : 'fa-times-circle';
                        
                        echo "<div class='test-result $class'>";
                        echo "<i class='fas $icon'></i>";
                        echo "<strong>User ID Exact Match Test</strong>: Expected '$expected', Got '$testUserID' ";
                        echo $isCorrect ? '(MATCH)' : '(MISMATCH)';
                        echo "</div>";
                        
                    } catch (Exception $e) {
                        echo "<div class='test-result test-fail'>";
                        echo "<i class='fas fa-times-circle'></i>";
                        echo "<strong>User ID Generation</strong>: ERROR - " . $e->getMessage();
                        echo "</div>";
                    }
                    ?>
                </div>
                
                <div class="test-section">
                    <h2>Summary</h2>
                    <?php
                    $totalTests = 0;
                    $passedTests = 0;
                    
                    // Count all tests
                    $allTests = array_merge($pages, $apis, $components, $assets);
                    $totalTests = count($allTests) + 7; // +7 for DB tables
                    
                    foreach ($allTests as $path) {
                        if (file_exists(__DIR__ . $path)) {
                            $passedTests++;
                        }
                    }
                    
                    // Add DB table tests
                    try {
                        $db = getDB();
                        $tables = ['users', 'user_settings', 'messages', 'notifications', 'audit_log', 'security_questions'];
                        foreach ($tables as $table) {
                            $stmt = $db->query("SHOW TABLES LIKE '$table'");
                            if ($stmt->rowCount() > 0) {
                                $passedTests++;
                            }
                        }
                    } catch (Exception $e) {
                        // Database connection failed
                    }
                    
                    $percentage = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;
                    $class = $percentage === 100 ? 'test-pass' : ($percentage >= 80 ? 'alert alert-warning' : 'test-fail');
                    
                    echo "<div class='test-result $class' style='font-size: 18px; font-weight: bold;'>";
                    echo "<i class='fas fa-chart-bar'></i>";
                    echo "<strong>Overall Status</strong>: $passedTests / $totalTests tests passed ($percentage%)";
                    echo "</div>";
                    ?>
                </div>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="/index.php" class="btn btn-primary">
                        <i class="fas fa-home"></i> Go to Landing Page
                    </a>
                    <a href="/health-check.php" class="btn btn-secondary">
                        <i class="fas fa-heartbeat"></i> System Health Check
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
