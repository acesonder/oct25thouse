# Testing Guide for User Registration System

## Manual Testing Checklist

### 1. Registration Flow Test

#### Test Case 1.1: Successful Registration
1. Navigate to `index.php`
2. Fill in the form:
   - First Name: Michael
   - Last Name: Brown
   - DOB: 1984-06-05
   - Security Question: Select "What was the name of your first pet?"
   - Security Answer: Fluffy
   - Password: TestPass123
   - Confirm Password: TestPass123
   - Check both consent checkboxes
3. Click "Register"
4. Expected Result:
   - Success page displays with User ID: MICBRO050684
   - User is automatically logged in
   - Can access dashboard

#### Test Case 1.2: Password Strength Validation
1. Navigate to `index.php`
2. Enter various passwords and verify strength indicator:
   - "test" → Should show "Weak password"
   - "testpass" → Should show "Weak password"
   - "TestPass" → Should show "Medium password"
   - "TestPass123" → Should show "Strong password"

#### Test Case 1.3: Password Mismatch
1. Navigate to `index.php`
2. Fill in form with mismatched passwords
3. Expected Result: Error message "Passwords do not match"

#### Test Case 1.4: Missing Consent
1. Navigate to `index.php`
2. Fill in form but don't check consent boxes
3. Expected Result: Error message about missing consent

#### Test Case 1.5: Terms of Service Modal
1. Navigate to `index.php`
2. Click "Terms of Service" link
3. Expected Result: Modal opens with terms
4. Click X or outside modal to close
5. Expected Result: Modal closes

### 2. Recovery Flow Test

#### Test Case 2.1: Successful Recovery
1. Register a user first (e.g., Michael Brown, DOB: 1984-06-05)
2. Navigate to `recovery.php`
3. Enter:
   - First Name: Michael
   - Last Name: Brown
   - DOB: 1984-06-05
4. Click "Continue"
5. Expected Result: Redirected to security question page
6. Answer the security question correctly
7. Click "Verify Answer"
8. Expected Result: User ID displayed (MICBRO050684), password reset form shown
9. Enter new password and confirm
10. Click "Reset Password"
11. Expected Result: Password reset successful, redirected to login page

#### Test Case 2.2: Invalid User Lookup
1. Navigate to `recovery.php`
2. Enter non-existent user information
3. Expected Result: Error message "No user found with provided information"

#### Test Case 2.3: Wrong Security Answer
1. Start recovery process with valid user
2. Enter incorrect security answer
3. Expected Result: Error message "Incorrect security answer"

### 3. User ID Generation Test

Test various name and DOB combinations:

| First Name | Last Name | DOB | Expected User ID |
|------------|-----------|-----|------------------|
| Michael | Brown | 05/06/1984 | MICBRO050684 |
| John | Doe | 12/31/1990 | JOHDOE123190 |
| A | B | 01/01/2000 | AB010100 |
| Christopher | Washington | 07/04/1976 | CHRWAS070476 |

### 4. Security Tests

#### Test Case 4.1: SQL Injection Prevention
- Try entering SQL injection strings in form fields
- Expected Result: Inputs are safely escaped, no SQL errors

#### Test Case 4.2: XSS Prevention
- Try entering `<script>alert('xss')</script>` in name fields
- Expected Result: Content is properly escaped in display

#### Test Case 4.3: Password Security
- Passwords should be hashed in database
- Security answers should be hashed in database
- Session should be properly managed

### 5. AJAX Functionality Test

#### Test Case 5.1: Security Questions Loading
1. Open index.php
2. Check browser console
3. Expected Result: Security questions loaded via AJAX

#### Test Case 5.2: Form Submission
1. Fill registration form
2. Check browser Network tab
3. Expected Result: POST request to api.php with action=register

### 6. Browser Compatibility

Test in:
- Chrome
- Firefox
- Safari
- Edge

### 7. Responsive Design

Test at different screen sizes:
- Desktop (1920x1080)
- Tablet (768x1024)
- Mobile (375x667)

## Automated Testing (Future Enhancement)

Consider adding:
- PHPUnit tests for backend functions
- Jest tests for JavaScript functions
- Selenium for end-to-end testing

## Known Limitations

1. No email verification (future enhancement)
2. No CAPTCHA (future enhancement)
3. No rate limiting (future enhancement)
4. Database credentials in config.php should be secured
5. HTTPS should be enabled in production
