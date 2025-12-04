# Car Rental Management System

A complete web-based car rental management system with separate admin and user interfaces. This system allows administrators to manage a fleet of rental cars, track bookings, monitor maintenance, and generate reports, while customers can browse, book, and manage their car rentals.

---

## 📋 Table of Contents

1. [Features](#features)
2. [System Requirements](#system-requirements)
3. [Installation Guide](#installation-guide)
4. [Configuration](#configuration)
5. [User Guide](#user-guide)
6. [Admin Guide](#admin-guide)
7. [Troubleshooting](#troubleshooting)
8. [Technical Details](#technical-details)

---

## ✨ Features

### Admin Features
- **Dashboard**: View real-time statistics (total cars, available cars, active bookings, revenue)
- **Car Management**: Add, edit, and delete cars with image uploads (up to 10MB)
- **Availability Tracking**: Mark cars as available, rented, or under maintenance
- **Booking Management**: View all bookings and update their status
- **Maintenance Tracking**: Record service dates, costs, and remarks for each vehicle
- **User Management**: View and manage customer accounts
- **Analytics**: Visual charts showing monthly booking trends
- **Reports**: Export data to PDF/Excel (optional feature)

### User Features
- **User Registration & Login**: Secure account creation and authentication
- **Browse Cars**: View all available vehicles with images and details
- **Search & Filter**: Find cars by brand, model, or price range
- **Book Cars**: Select rental dates and see automatic price calculation
- **Booking History**: View all past and current bookings
- **Cancel Bookings**: Cancel active reservations

---

## 💻 System Requirements

### Required Software
- **XAMPP** (v7.4 or higher) - Includes:
  - Apache Web Server
  - MySQL Database
  - PHP (v7.4 or higher)
- **Web Browser**: Chrome, Firefox, Edge, or Safari (latest version)
- **Operating System**: Windows, macOS, or Linux

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache 2.4 or higher
- Minimum 100MB free disk space
- File upload limit: 10MB (configured in php.ini)

---

## 🚀 Installation Guide

### Step 1: Download and Install XAMPP

1. **Download XAMPP**:
   - Visit: https://www.apachefriends.org/
   - Download the version for your operating system
   - Choose PHP 7.4 or higher

2. **Install XAMPP**:
   - Run the installer
   - Choose installation directory (default: `C:\xampp` on Windows)
   - Select components: Apache, MySQL, PHP, phpMyAdmin
   - Complete the installation

3. **Start XAMPP**:
   - Open XAMPP Control Panel
   - Click "Start" for Apache
   - Click "Start" for MySQL
   - Both should show green "Running" status

### Step 2: Configure PHP for Large File Uploads

1. **Locate php.ini file**:
   - Windows: `C:\xampp\php\php.ini`
   - macOS/Linux: `/Applications/XAMPP/etc/php.ini`

2. **Edit php.ini**:
   - Open php.ini in a text editor (Notepad, VS Code, etc.)
   - Find and modify these lines:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 12M
   max_execution_time = 300
   memory_limit = 256M
   ```

3. **Save and Restart Apache**:
   - Save the php.ini file
   - In XAMPP Control Panel, click "Stop" then "Start" for Apache

### Step 3: Set Up the Project Files

1. **Copy Project to htdocs**:
   - Navigate to XAMPP's htdocs folder:
     - Windows: `C:\xampp\htdocs\`
     - macOS: `/Applications/XAMPP/htdocs/`
     - Linux: `/opt/lampp/htdocs/`
   
   - Create a new folder named `car-rental`
   - Copy all project files into this folder

2. **Create Uploads Folder**:
   - Inside `car-rental` folder, create a folder named `uploads`
   - Right-click the `uploads` folder → Properties
   - Ensure it has write permissions (Windows: uncheck "Read-only")

3. **Verify Folder Structure**:
   ```
   C:\xampp\htdocs\car-rental\
   ├── admin/
   │   ├── index.php
   │   ├── cars.php
   │   ├── bookings.php
   │   ├── maintenance.php
   │   └── users.php
   ├── user/
   │   ├── index.php
   │   ├── booking.php
   │   └── history.php
   ├── assets/
   │   ├── css/
   │   │   └── style.css
   │   └── js/
   │       └── main.js
   ├── config/
   │   └── database.php
   ├── includes/
   │   ├── header.php
   │   └── footer.php
   ├── uploads/          ← Create this folder
   ├── database.sql
   ├── index.php
   ├── login.php
   ├── register.php
   ├── logout.php
   └── README.md
   ```

### Step 4: Create the Database

1. **Open phpMyAdmin**:
   - Open your web browser
   - Go to: `http://localhost/phpmyadmin`
   - You should see the phpMyAdmin interface

2. **Create Database**:
   - Click on "New" in the left sidebar
   - Database name: `car_rental`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"

3. **Import Database Schema**:
   - Click on the `car_rental` database in the left sidebar
   - Click the "Import" tab at the top
   - Click "Choose File"
   - Navigate to: `C:\xampp\htdocs\car-rental\database.sql`
   - Click "Go" at the bottom
   - You should see "Import has been successfully finished"

4. **Set Admin Password**:
   - Open your browser and go to: `http://localhost/car-rental/update_admin_password.php`
   - You should see a success message
   - **IMPORTANT**: Delete the `update_admin_password.php` file after use for security

5. **Verify Tables Created**:
   - Click on the `car_rental` database
   - You should see 4 tables:
     - `users`
     - `cars`
     - `bookings`
     - `maintenance`

### Step 5: Access the Application

1. **Open the Application**:
   - Open your web browser
   - Go to: `http://localhost/car-rental`
   - You should be redirected to the login page

2. **Test Admin Login**:
   - Email: `admin@carrental.com`
   - Password: `admin@12345`
   - Click "Login"
   - You should see the Admin Dashboard

3. **Test User Registration**:
   - Click "Register" on the login page
   - Fill in the registration form
   - Create a new user account
   - Login with your new credentials

---

## ⚙️ Configuration

### Database Configuration

If you need to change database settings, edit `config/database.php`:

```php
define('DB_HOST', 'localhost');     // Database host
define('DB_USER', 'root');          // Database username
define('DB_PASS', '');              // Database password (empty by default)
define('DB_NAME', 'car_rental');    // Database name
```

### File Upload Settings

Maximum file upload size is set to 10MB. To change this:

1. Edit `php.ini` (see Step 2 above)
2. Modify `upload_max_filesize` and `post_max_size`
3. Restart Apache

---

## 👤 User Guide

### For Customers

#### 1. Registration
1. Go to `http://localhost/car-rental`
2. Click "Register"
3. Fill in your details:
   - Full Name
   - Email Address
   - Phone Number
   - Password (minimum 6 characters)
4. Click "Register"
5. You'll see a success message

#### 2. Login
1. Go to the login page
2. Enter your email and password
3. Click "Login"
4. You'll be redirected to the car browsing page

#### 3. Browse and Search Cars
1. After login, you'll see all available cars
2. Use the search bar to find specific brands or models
3. Filter by:
   - Brand (dropdown)
   - Maximum price per day
4. Click "Filter" to apply

#### 4. Book a Car
1. Click "Book Now" on any available car
2. Select your rental dates:
   - Start Date (cannot be in the past)
   - End Date (must be after start date)
3. Review the automatic price calculation
4. Click "Confirm Booking"
5. You'll see a success message

#### 5. View Booking History
1. Click "My Bookings" in the navigation
2. See all your bookings with status:
   - **Active**: Currently rented
   - **Completed**: Rental finished
   - **Canceled**: Booking canceled
3. Cancel active bookings if needed

#### 6. Cancel a Booking
1. Go to "My Bookings"
2. Find the active booking
3. Click "Cancel Booking"
4. Confirm the cancellation
5. The car becomes available again

---

## 👨‍💼 Admin Guide

### Admin Dashboard

After logging in as admin, you'll see:
- Total number of cars
- Available cars count
- Active bookings count
- Total revenue
- Monthly bookings chart
- Recent bookings list

### 1. Manage Cars

#### Add a New Car
1. Click "Cars" in the navigation
2. Click "Add Car" button
3. Fill in the form:
   - **Brand**: e.g., Toyota, Honda, BMW
   - **Model**: e.g., Camry, Civic, X5
   - **Year**: e.g., 2023
   - **Color**: e.g., Black, White, Red
   - **Price Per Day**: e.g., 50.00
   - **Status**: Available, Rented, or Maintenance
   - **Image**: Upload car photo (max 10MB, JPG/PNG)
4. Click "Save"

#### Edit a Car
1. Go to "Cars" page
2. Find the car in the table
3. Click "Edit" button
4. Modify the details
5. Optionally upload a new image
6. Click "Save"

#### Delete a Car
1. Go to "Cars" page
2. Find the car in the table
3. Click "Delete" button
4. Confirm the deletion
5. Car and all related bookings will be removed

#### Change Car Status
- **Available**: Car is ready to rent
- **Rented**: Car is currently rented
- **Maintenance**: Car is under service

### 2. Manage Bookings

1. Click "Bookings" in the navigation
2. View all bookings with details:
   - Customer name and email
   - Car details
   - Rental dates
   - Total price
   - Current status

#### Update Booking Status
1. Find the booking in the table
2. Select new status from dropdown:
   - **Active**: Rental is ongoing
   - **Completed**: Rental finished
   - **Canceled**: Booking canceled
3. Click "Update"
4. Car status updates automatically

### 3. Track Maintenance

#### Add Maintenance Record
1. Click "Maintenance" in the navigation
2. Click "Add Maintenance" button
3. Fill in the form:
   - **Car**: Select from dropdown
   - **Service Date**: Date of service
   - **Cost**: Service cost in dollars
   - **Remarks**: Description of service (e.g., "Oil change and tire rotation")
4. Click "Save"
5. Car status automatically changes to "Maintenance"

#### View Maintenance History
- See all maintenance records in a sortable table
- Filter and search using DataTables features
- Track total maintenance costs per vehicle

### 4. Manage Users

1. Click "Users" in the navigation
2. View all registered users
3. See user details:
   - Name, email, phone
   - Role (Admin or User)
   - Registration date
4. Delete user accounts (except admins)

### 5. Using DataTables Features

All admin tables support:
- **Search**: Type in the search box to filter results
- **Sort**: Click column headers to sort
- **Pagination**: Navigate through pages
- **Show entries**: Change number of rows displayed

---

## 🔧 Troubleshooting

### Common Issues and Solutions

#### 1. "Connection failed" Error
**Problem**: Cannot connect to database

**Solutions**:
- Ensure MySQL is running in XAMPP Control Panel
- Check database credentials in `config/database.php`
- Verify database `car_rental` exists in phpMyAdmin

#### 2. Login Page Not Loading
**Problem**: Blank page or 404 error

**Solutions**:
- Verify Apache is running in XAMPP
- Check URL: `http://localhost/car-rental` (not `car_rental`)
- Ensure all files are in `htdocs/car-rental/` folder

#### 3. Image Upload Fails
**Problem**: "File too large" or upload error

**Solutions**:
- Check `uploads` folder exists and has write permissions
- Verify php.ini settings (upload_max_filesize = 10M)
- Restart Apache after changing php.ini
- Ensure image is under 10MB

#### 4. Images Not Displaying
**Problem**: Broken image icons

**Solutions**:
- Check images are in `uploads` folder
- Verify file permissions on `uploads` folder
- Check image file names in database match actual files

#### 5. "Access Denied" Error
**Problem**: Cannot access admin pages

**Solutions**:
- Ensure you're logged in as admin
- Check session is active (try logging out and in again)
- Verify role in database is set to 'admin'

#### 6. DataTables Not Working
**Problem**: Tables not sortable/searchable

**Solutions**:
- Check internet connection (CDN libraries)
- Open browser console (F12) for JavaScript errors
- Clear browser cache and reload

#### 7. Booking Dates Not Calculating
**Problem**: Total price shows $0.00

**Solutions**:
- Ensure both dates are selected
- End date must be after start date
- Check browser console for JavaScript errors

---

## 🛠️ Technical Details

### Tech Stack

**Frontend**:
- HTML5
- CSS3
- Bootstrap 5.3.0 (responsive framework)
- JavaScript (ES6)
- jQuery 3.7.0
- Font Awesome 6.4.0 (icons)

**Backend**:
- PHP 7.4+
- MySQL 5.7+

**Libraries**:
- DataTables 1.13.4 (table features)
- Chart.js (analytics visualization)

### Database Schema

#### users Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR 100)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255, hashed)
- phone (VARCHAR 20)
- role (ENUM: 'admin', 'user')
- created_at (TIMESTAMP)
```

#### cars Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- brand (VARCHAR 50)
- model (VARCHAR 50)
- year (INT)
- color (VARCHAR 30)
- price_per_day (DECIMAL 10,2)
- status (ENUM: 'available', 'rented', 'maintenance')
- image (VARCHAR 255)
- created_at (TIMESTAMP)
```

#### bookings Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- user_id (INT, FOREIGN KEY → users.id)
- car_id (INT, FOREIGN KEY → cars.id)
- start_date (DATE)
- end_date (DATE)
- total_price (DECIMAL 10,2)
- status (ENUM: 'active', 'completed', 'canceled')
- created_at (TIMESTAMP)
```

#### maintenance Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- car_id (INT, FOREIGN KEY → cars.id)
- service_date (DATE)
- cost (DECIMAL 10,2)
- remarks (TEXT)
- created_at (TIMESTAMP)
```

### Security Features

1. **Password Security**:
   - Passwords hashed using bcrypt (PASSWORD_DEFAULT)
   - Minimum 6 characters required

2. **SQL Injection Prevention**:
   - Input sanitization using `real_escape_string()`
   - Prepared statements for sensitive queries

3. **Session Management**:
   - Secure session handling
   - Session timeout on logout
   - Role-based access control

4. **Access Control**:
   - Admin pages check for admin role
   - User pages check for authentication
   - Automatic redirects for unauthorized access

### File Structure

```
car-rental/
├── admin/                  # Admin panel
│   ├── index.php          # Dashboard with statistics
│   ├── cars.php           # Car management (CRUD)
│   ├── bookings.php       # Booking management
│   ├── maintenance.php    # Maintenance tracking
│   └── users.php          # User management
├── user/                   # User panel
│   ├── index.php          # Browse cars
│   ├── booking.php        # Book a car
│   └── history.php        # Booking history
├── assets/
│   ├── css/
│   │   └── style.css      # Custom styles
│   └── js/
│       └── main.js        # JavaScript functions
├── config/
│   └── database.php       # DB config & helper functions
├── includes/
│   ├── header.php         # Common header
│   └── footer.php         # Common footer
├── uploads/               # Car images storage
├── database.sql           # Database schema
├── index.php              # Entry point (redirects)
├── login.php              # Login page
├── register.php           # Registration page
├── logout.php             # Logout handler
└── README.md              # This file
```

### Browser Compatibility

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Edge 90+
- ✅ Safari 14+
- ✅ Opera 76+

### Responsive Design

The system is fully responsive and works on:
- Desktop (1920px and above)
- Laptop (1366px - 1919px)
- Tablet (768px - 1365px)
- Mobile (320px - 767px)

---

## 📞 Support

### Default Admin Credentials
- **Email**: admin@carrental.com
- **Password**: admin@12345

⚠️ **Important**: 
1. Run `update_admin_password.php` after database import to set the admin password
2. Delete `update_admin_password.php` file after use for security
3. Change the admin password after first login for additional security

### Tips for Best Experience

1. Use Chrome or Firefox for best compatibility
2. Keep XAMPP updated to the latest version
3. Regularly backup your database
4. Test on localhost before deploying to production
5. Use high-quality images (recommended: 1200x800px)
6. Keep image files under 5MB for faster loading

---

## 📝 License

This project is open-source and available for educational purposes.

---

## 🎉 Enjoy Your Car Rental Management System!

For questions or issues, refer to the Troubleshooting section above.
