# User Registration System

A complete user registration and recovery system built with MySQL, PHP, HTML, CSS, JavaScript, and AJAX.

## Features

- **Client Registration**: Collects First Name, Last Name, and Date of Birth
- **Auto-generated User ID**: Format: `first3(first)+first3(last)+MMDDYY` (e.g., MICBRO050684)
- **Security Features**:
  - Security question selection and answer
  - Password creation with strength validation
  - Password confirmation
- **Consent Management**:
  - Consent to Share Information (required)
  - Terms of Service with modal display (required)
- **Post-Registration**:
  - Displays the generated User ID
  - Automatic login after registration
- **Account Recovery**:
  - Enter name and date of birth
  - Answer security question
  - Reveals User ID and allows password reset
- **Role-Based System**: Supports Client, Volunteer, Staff, and SysAdmin roles

## Installation

### Prerequisites
- Apache/Nginx web server
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/acesonder/oct25thouse.git
   cd oct25thouse
   ```

2. **Configure the database**:
   - Open `config.php` and update the database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'user_registration_db');
     ```

3. **Create the database**:
   - Import the SQL schema:
     ```bash
     mysql -u your_username -p < database.sql
     ```
   - Or manually run the SQL commands in `database.sql` through phpMyAdmin or MySQL command line

4. **Configure web server**:
   - Point your web server document root to the project directory
   - Ensure PHP is enabled and configured

5. **Access the application**:
   - Open your browser and navigate to `http://localhost/index.php` (or your configured domain)

## File Structure

```
oct25thouse/
├── index.php              # Main registration page
├── success.php            # Post-registration success page
├── dashboard.php          # User dashboard (after login)
├── recovery.php           # Account recovery - Step 1 (Enter name & DOB)
├── recovery-step2.php     # Account recovery - Step 2 (Security question)
├── recovery-step3.php     # Account recovery - Step 3 (Reset password & show User ID)
├── logout.php             # Logout handler
├── api.php                # AJAX API endpoints
├── config.php             # Database configuration
├── functions.php          # Core functions (User ID generation, validation, etc.)
├── script.js              # JavaScript for AJAX and form handling
├── style.css              # CSS styling
├── database.sql           # Database schema
└── README.md              # This file
```

## Usage

### Registration Flow

1. Navigate to `index.php`
2. Fill in:
   - First Name
   - Last Name
   - Date of Birth
   - Select a security question and provide an answer
   - Create a password (minimum 8 characters, must include uppercase, lowercase, and number)
   - Confirm password
   - Check "Consent to Share Information"
   - Check "I agree to the Terms of Service" (click to view modal)
3. Click "Register"
4. Upon success, you'll be redirected to `success.php` showing your User ID
5. You're automatically logged in and can access the dashboard

### Recovery Flow

1. Navigate to `recovery.php`
2. Enter your First Name, Last Name, and Date of Birth
3. Click "Continue"
4. Answer your security question
5. Click "Verify Answer"
6. Your User ID will be displayed, and you can reset your password
7. Set a new password and confirm it
8. Click "Reset Password"
9. You'll be redirected to the login page

## Security Features

- **Password Hashing**: All passwords are hashed using PHP's `password_hash()` with bcrypt
- **Security Answer Hashing**: Security answers are also hashed for additional security
- **Password Strength Validation**: Enforces strong passwords (8+ characters, uppercase, lowercase, numbers)
- **Input Validation**: Server-side validation for all user inputs
- **SQL Injection Prevention**: Uses prepared statements for all database queries
- **Session Management**: Secure session handling for logged-in users

## Database Schema

### Tables

1. **roles**: Stores user roles (Client, Volunteer, Staff, SysAdmin)
2. **security_questions**: Predefined security questions
3. **users**: Main user table with all registration information

### User ID Generation

The system automatically generates a unique User ID based on:
- First 3 characters of first name (uppercase)
- First 3 characters of last name (uppercase)
- Date of birth in MMDDYY format

Example: Michael Brown born on 05/06/1984 → `MICBRO050684`

## Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **AJAX**: Vanilla JavaScript Fetch API
- **Security**: Password hashing, prepared statements, input validation

## License

This project is open source and available under the MIT License.
