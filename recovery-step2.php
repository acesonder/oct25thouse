<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Question</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Security Question</h1>
        <p style="text-align: center; color: #666; margin-bottom: 20px;">
            Answer your security question to continue
        </p>
        
        <div id="message" class="message" style="display: none;"></div>
        
        <form id="securityForm" onsubmit="submitSecurityAnswer(event)">
            <div class="form-group">
                <label id="questionLabel">Loading question...</label>
                <input type="text" id="securityAnswer" name="securityAnswer" placeholder="Your answer" required>
            </div>
            
            <button type="submit" id="submitBtn">Verify Answer</button>
        </form>
        
        <div class="link-text">
            <a href="recovery.php">Start Over</a>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
        // Load the security question
        document.addEventListener('DOMContentLoaded', function() {
            const questionId = sessionStorage.getItem('recovery_question_id');
            
            if (!questionId) {
                showMessage('Session expired. Please start over.', 'error');
                setTimeout(() => window.location.href = 'recovery.php', 2000);
                return;
            }
            
            fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=getSecurityQuestion&questionId=' + questionId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('questionLabel').textContent = data.question_text;
                } else {
                    showMessage('Error loading security question', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred', 'error');
            });
        });
    </script>
</body>
</html>
