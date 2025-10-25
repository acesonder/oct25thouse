# Healthcare Management System

A comprehensive HIPAA-compliant healthcare management system built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

### User Management
- **Multiple User Types**: Client, Volunteer, Staff, Service Provider, System Admin
- **Auto-Generated User IDs**: Format: FIRST3+LAST3+MMDDYY (e.g., JOHNDOE010190)
- **Security**: Password hashing, security questions, account recovery
- **HIPAA Compliance**: Encrypted data, audit logging, secure sessions
- **Canadian Standards**: Compliant with Canadian privacy legislation

### Authentication
- Client registration with consent and terms of service
- Account approval system for staff and service providers
- Password recovery via security questions
- Session management with auto-logout on inactivity

### Admin Portal
- **Access**: /admin/admin.php with passcode 079777
- Create and manage all account types
- Approve/suspend/activate user accounts
- Login as any user for testing
- View system statistics and audit logs

### Instant Messenger
- Facebook-style real-time messaging
- User search by User ID
- Message types: Text, images, videos, location sharing
- Unread message badges and notifications
- Sound and browser notifications

### Customization
- Light/Dark theme modes
- Custom color schemes
- Font family and size options
- Layout styles (Default, Compact, Wide, Full Width)
- Animation and transition controls
- Notification preferences

### User Portals
- **Client Dashboard**: Access to messages, notifications, profile
- **Staff Dashboard**: Client management, approval system
- **Service Provider Dashboard**: Service offerings, client communication
- **Admin Dashboard**: Full system control and monitoring

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- phpMyAdmin (recommended)

### Setup Steps

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd oct25thouse
   ```

2. **Configure Database**
   - Open phpMyAdmin
   - Import `database/schema.sql`
   - This creates the `healthcare_system` database with all tables
   - Default admin account is created:
     - User ID: SYSADM000000
     - Password: Admin@123

3. **Update Database Configuration**
   - Edit `config/config.php`
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'healthcare_system');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     ```

4. **Set Permissions**
   ```bash
   chmod 755 uploads/
   chmod 755 sessions/
   chmod 755 tmp/
   ```

5. **Configure Web Server**
   - Point document root to the project directory
   - Enable mod_rewrite (Apache)
   - Ensure PHP extensions are enabled: PDO, PDO_MySQL, session

6. **Access the Application**
   - Navigate to your web server URL
   - Landing page will show portal options
   - Admin access: /admin/admin.php (passcode: 079777)

## Default Accounts

### System Admin
- **User ID**: SYSADM000000
- **Password**: Admin@123
- **Role**: System Administrator

## Security Features

### HIPAA Compliance
- Encrypted password storage (bcrypt)
- Secure session management
- Audit logging for all actions
- Protected health information (PHI) encryption
- Access controls and user authentication

### Data Protection
- SQL injection prevention (prepared statements)
- XSS protection (output escaping)
- CSRF protection (session tokens)
- Secure file uploads
- HTTPS recommended for production

## Usage

### For Clients
1. Click "Client Portal" on landing page
2. Register with personal information
3. Accept consent and terms of service
4. Receive auto-generated User ID
5. Access dashboard, messaging, and services

### For Staff/Service Providers
1. Register through appropriate portal
2. Wait for admin approval
3. Login after approval
4. Access specialized dashboards and tools

### For Administrators
1. Access /admin/admin.php
2. Enter passcode: 079777
3. Manage all users and system settings
4. Approve pending accounts
5. Login as any user for testing

## File Structure

```
oct25thouse/
├── admin/              # Admin portal
├── api/                # API endpoints
│   ├── messenger/      # Messaging APIs
│   └── notifications/  # Notification APIs
├── assets/             # Static assets
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   └── images/         # Images
├── client/             # Client portal
├── config/             # Configuration files
├── database/           # Database schema
├── includes/           # Reusable PHP components
├── service_provider/   # Service provider portal
├── sessions/           # Session storage
├── staff/              # Staff portal
├── tmp/                # Temporary files
├── uploads/            # User uploads
└── index.php           # Landing page
```

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **Icons**: Font Awesome 6.4
- **Security**: bcrypt, PDO prepared statements
- **Session Management**: PHP sessions with security headers

## Customization

Users can customize their experience through the Settings page:
- Theme (Light/Dark)
- Primary and secondary colors
- Font family and size
- Layout style
- Animations and transitions
- Notification preferences

## Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Modern mobile browsers

## Security Recommendations

1. **Production Deployment**:
   - Enable HTTPS
   - Update `session.cookie_secure` to 1 in config.php
   - Set `display_errors` to 0
   - Use strong database passwords
   - Regularly update PHP and MySQL

2. **Regular Maintenance**:
   - Review audit logs
   - Monitor failed login attempts
   - Update security questions periodically
   - Backup database regularly

3. **HIPAA Compliance**:
   - Ensure encrypted connections
   - Implement data retention policies
   - Train users on privacy practices
   - Conduct regular security audits

## Support

For issues, questions, or feature requests, please contact the system administrator.

## License

Proprietary - All rights reserved

## Compliance

This system is designed to meet:
- HIPAA (Health Insurance Portability and Accountability Act)
- Canadian privacy legislation
- Healthcare data protection standards

---

**Note**: This is a healthcare management system handling sensitive health information. Ensure all security best practices are followed and compliance requirements are met before deployment.
