<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Recovery</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Account Recovery</h1>
        <p style="text-align: center; color: #666; margin-bottom: 20px;">
            Enter your information to recover your account
        </p>
        
        <div id="message" class="message" style="display: none;"></div>
        
        <form id="recoveryForm" onsubmit="submitFindUser(event)">
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
            
            <button type="submit" id="submitBtn">Continue</button>
        </form>
        
        <div class="link-text">
            Remember your credentials? <a href="index.php">Back to Registration</a>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
