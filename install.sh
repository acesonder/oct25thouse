#!/bin/bash
# Installation script for User Registration System

echo "=================================="
echo "User Registration System Installer"
echo "=================================="
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "ERROR: PHP is not installed. Please install PHP 7.4 or higher."
    exit 1
fi

echo "✓ PHP is installed: $(php -v | head -n 1)"

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "WARNING: MySQL command not found. Please ensure MySQL is installed."
else
    echo "✓ MySQL is installed: $(mysql --version)"
fi

echo ""
echo "Step 1: Database Configuration"
echo "--------------------------------"
read -p "Enter MySQL host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -p "Enter MySQL username [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -s -p "Enter MySQL password: " DB_PASS
echo ""

read -p "Enter database name [user_registration_db]: " DB_NAME
DB_NAME=${DB_NAME:-user_registration_db}

# Update config.php with user input
cat > config.php << EOF
<?php
// Database configuration
define('DB_HOST', '$DB_HOST');
define('DB_USER', '$DB_USER');
define('DB_PASS', '$DB_PASS');
define('DB_NAME', '$DB_NAME');

// Create database connection
function getDBConnection() {
    \$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (\$conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . \$conn->connect_error]));
    }
    
    return \$conn;
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
EOF

echo ""
echo "✓ Configuration file updated"

echo ""
echo "Step 2: Database Setup"
echo "----------------------"
read -p "Would you like to create the database now? (y/n): " CREATE_DB

if [ "$CREATE_DB" = "y" ] || [ "$CREATE_DB" = "Y" ]; then
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" < database.sql
    if [ $? -eq 0 ]; then
        echo "✓ Database created successfully"
    else
        echo "ERROR: Database creation failed. Please run database.sql manually."
    fi
else
    echo "Please run the following command to create the database:"
    echo "mysql -h $DB_HOST -u $DB_USER -p < database.sql"
fi

echo ""
echo "=================================="
echo "Installation Complete!"
echo "=================================="
echo ""
echo "Next Steps:"
echo "1. Configure your web server to point to this directory"
echo "2. Ensure PHP is enabled"
echo "3. Access the application at: http://localhost/index.php"
echo ""
echo "For testing, you can use PHP's built-in server:"
echo "php -S localhost:8000"
echo ""
