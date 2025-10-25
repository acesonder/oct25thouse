<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$userID = $_SESSION['user_id'];
$firstName = $_SESSION['first_name'];
$lastName = $_SESSION['last_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="success-card">
            <div class="success-icon">✓</div>
            <h1>Registration Successful!</h1>
            
            <p class="info-text">Welcome, <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>!</p>
            
            <p class="info-text">Your account has been created successfully.</p>
            
            <div class="user-id-display">
                <?php echo htmlspecialchars($userID); ?>
            </div>
            
            <p class="info-text"><strong>Please save your User ID for future reference.</strong></p>
            <p class="info-text">You have been automatically logged in.</p>
            
            <div style="margin-top: 30px;">
                <button onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
            </div>
        </div>
    </div>
</body>
</html>
