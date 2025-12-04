# Quick Installation Guide

Follow these steps to install and run the Car Rental Management System.

---

## Step-by-Step Installation

### 1. Install XAMPP
- Download from: https://www.apachefriends.org/
- Install with Apache, MySQL, PHP, and phpMyAdmin
- Start Apache and MySQL from XAMPP Control Panel

### 2. Configure PHP for 10MB Uploads
- Open `C:\xampp\php\php.ini`
- Find and change:
  ```ini
  upload_max_filesize = 10M
  post_max_size = 12M
  ```
- Save and restart Apache

### 3. Copy Project Files
- Copy all project files to: `C:\xampp\htdocs\car-rental\`
- Create `uploads` folder inside `car-rental`

### 4. Create Database
- Open: http://localhost/phpmyadmin
- Create new database: `car_rental`
- Import `database.sql` file
- Go to: http://localhost/car-rental/update_admin_password.php
- **Delete `update_admin_password.php` after use!**

### 5. Access the System
- Open: http://localhost/car-rental
- Login with:
  - **Email**: admin@carrental.com
  - **Password**: admin@12345

---

## Validation Rules Summary

### Registration Requirements:

**Full Name:**
- 3-100 characters
- Letters and spaces only

**Email:**
- Valid email format
- Must be unique

**Phone Number:**
- Exactly 10 digits
- Numbers only (e.g., 1234567890)

**Password:**
- Minimum 6 characters
- Must contain at least one special character: !@#$%^&*(),.?":{}|<>
- Examples: pass@123, admin@12345, Test#2024

---

## Important Security Notes

1. ✅ Users CANNOT book cars without login
2. ✅ All passwords are encrypted with bcrypt
3. ✅ Delete `update_admin_password.php` after first use
4. ✅ Change admin password after first login
5. ✅ All user inputs are validated and sanitized

---

## Quick Test

1. Register a new user account
2. Browse available cars
3. Try to book a car (requires login)
4. Login as admin to manage cars and bookings

---

## Need Help?

Check the full README.md for detailed documentation and troubleshooting.
