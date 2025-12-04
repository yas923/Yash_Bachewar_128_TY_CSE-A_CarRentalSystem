<?php
/**
 * Run this file ONCE after importing database.sql to set the admin password
 * Access: http://localhost/car-rental/update_admin_password.php
 */

require_once 'config/database.php';

// New admin password
$new_password = 'admin@12345';
$email = 'admin@carrental.com';

// Generate hash
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Update admin password
$email_escaped = $conn->real_escape_string($email);
$sql = "UPDATE users SET password = '$password_hash' WHERE email = '$email_escaped' AND role = 'admin'";

if ($conn->query($sql)) {
    if ($conn->affected_rows > 0) {
        echo "<h2>✅ Success!</h2>";
        echo "<p>Admin password has been updated successfully.</p>";
        echo "<p><strong>Login Credentials:</strong></p>";
        echo "<ul>";
        echo "<li>Email: <strong>admin@carrental.com</strong></li>";
        echo "<li>Password: <strong>admin@12345</strong></li>";
        echo "</ul>";
        echo "<p><a href='login.php'>Go to Login Page</a></p>";
        echo "<hr>";
        echo "<p style='color: red;'><strong>IMPORTANT:</strong> Delete this file (update_admin_password.php) after use for security!</p>";
    } else {
        echo "<h2>⚠️ Warning</h2>";
        echo "<p>No admin user found or password already updated.</p>";
        echo "<p>Admin credentials should be:</p>";
        echo "<ul>";
        echo "<li>Email: <strong>admin@carrental.com</strong></li>";
        echo "<li>Password: <strong>admin@12345</strong></li>";
        echo "</ul>";
    }
} else {
    echo "<h2>❌ Error</h2>";
    echo "<p>Failed to update password: " . $conn->error . "</p>";
}

$conn->close();
?>
