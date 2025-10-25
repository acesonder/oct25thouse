# Healthcare Management System - Quick Start Guide

## 🚀 Getting Started in 3 Steps

### 1. Import Database
- Open phpMyAdmin
- Import `database/schema.sql`
- Database `healthcare_system` will be created automatically

### 2. Configure
- Edit `config/config.php`
- Update database credentials (DB_USER and DB_PASS)

### 3. Access
- Open browser: `http://localhost/`
- Or run health check: `http://localhost/health-check.php`

## 🔑 Default Credentials

**System Admin**
- User ID: `SYSADM000000`
- Password: `Admin@123`

**Admin Portal**
- URL: `/admin/admin.php`
- Passcode: `079777`

## 📚 Key URLs

- **Landing Page**: `/index.php`
- **Client Registration**: Click "Client Portal" on landing page
- **Staff Login**: Click "Staff Portal" on landing page
- **Provider Login**: Click "Service Provider" on landing page
- **Admin Panel**: `/admin/admin.php` (passcode required)
- **Health Check**: `/health-check.php`
- **Installation Guide**: `/INSTALL.html`

## ✨ Features at a Glance

### User Management
- ✅ Auto-generated User IDs (e.g., JOHNDOE010190)
- ✅ Multiple user types (Client, Staff, Provider, Admin)
- ✅ Account approval workflow
- ✅ Security questions for recovery

### Messaging
- ✅ Real-time instant messenger
- ✅ Text, image, video, and location sharing
- ✅ User search by User ID
- ✅ Unread message badges

### Customization
- ✅ Light/Dark themes
- ✅ Custom colors (primary/secondary)
- ✅ Font customization
- ✅ Layout options
- ✅ Animation controls

### Security
- ✅ HIPAA compliant
- ✅ Canadian standards
- ✅ Password encryption (bcrypt)
- ✅ Audit logging
- ✅ Session management

## 📱 Testing the System

1. **Register a Client**
   - Go to landing page
   - Click "Client Portal" → "Register as Client"
   - Fill in the form and submit
   - You'll receive an auto-generated User ID
   - Account is activated immediately

2. **Access Admin Panel**
   - Go to `/admin/admin.php`
   - Enter passcode: `079777`
   - Create additional users
   - Approve staff/provider accounts
   - Test "Login as User" feature

3. **Test Messaging**
   - Login as two different users (use two browsers)
   - Click messenger icon
   - Search for user by User ID
   - Send messages and test real-time updates

4. **Customize Settings**
   - Login to any account
   - Click Settings in navigation
   - Change theme to dark mode
   - Modify colors and fonts
   - Test different layouts

## 🛠️ Troubleshooting

**Database Connection Error**
- Check credentials in `config/config.php`
- Verify database exists in phpMyAdmin
- Ensure MySQL service is running

**Permission Errors**
- Run: `chmod 755 uploads/ sessions/ tmp/`
- Check web server user has write access

**Blank Page**
- Enable error display in `config/config.php`
- Check PHP error logs
- Verify PHP version is 7.4+

**Can't Login**
- Verify database was imported correctly
- Check User ID format (case-sensitive)
- Use default admin: SYSADM000000 / Admin@123

## 📖 Documentation

- **Full Documentation**: `README.md`
- **Installation Guide**: `INSTALL.html`
- **Health Check**: `health-check.php`

## 🔐 Security Notes

**For Production:**
1. Change default admin password
2. Enable HTTPS
3. Update `session.cookie_secure` to 1
4. Set `display_errors` to 0
5. Use strong database passwords
6. Regular backups

## 💡 Tips

- User IDs are auto-generated: FIRST3+LAST3+MMDDYY
- Clients and volunteers get instant access
- Staff and providers need admin approval
- Admins can login as any user for testing
- All actions are logged in audit_log table
- Messages support text, images, videos, locations
- Real-time notifications every 10 seconds

## 📞 Support

For issues or questions:
1. Check `health-check.php` for system status
2. Review `README.md` for detailed documentation
3. Check PHP error logs
4. Verify database connection

## 🎯 Next Steps

1. ✅ Complete installation
2. ✅ Run health check
3. ✅ Login as admin
4. ✅ Create test accounts
5. ✅ Test all features
6. ✅ Customize for your needs
7. ✅ Deploy to production

---

**System Status**: Ready for deployment
**Version**: 1.0.0
**Compliance**: HIPAA, Canadian Standards
**Technology**: PHP, MySQL, jQuery, HTML5, CSS3
