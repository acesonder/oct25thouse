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
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>User Dashboard</h1>
        
        <div class="success-card">
            <p class="info-text">Welcome, <strong><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></strong>!</p>
            
            <div class="user-id-display">
                <?php echo htmlspecialchars($userID); ?>
            </div>
            
            <p class="info-text">This is your dashboard. You are successfully logged in.</p>
            
            <div style="margin-top: 30px;">
                <button onclick="window.location.href='logout.php'" style="background: #f44336;">Logout</button>
            </div>
        </div>
    </div>
</body>
</html>
