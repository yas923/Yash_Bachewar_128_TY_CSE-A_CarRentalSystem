<?php
/**
 * MariaDB Permission Fix Script
 * This script fixes the "Host 'localhost' is not allowed to connect" error
 */

echo "=== MariaDB Permission Fix Script ===\n\n";

// Try to connect directly to MariaDB without selecting a database
$conn = @new mysqli('127.0.0.1', 'root', '', '', 3306);

if ($conn->connect_error) {
    echo "❌ Connection failed: " . $conn->connect_error . "\n\n";
    echo "Please try these manual steps:\n";
    echo "1. Open your XAMPP/WAMP control panel\n";
    echo "2. Click 'Shell' or 'MySQL Console'\n";
    echo "3. Run these commands:\n\n";
    echo "   GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;\n";
    echo "   GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;\n";
    echo "   FLUSH PRIVILEGES;\n\n";
    exit(1);
}

echo "✓ Connected to MariaDB successfully!\n\n";

// Fix permissions for both localhost and 127.0.0.1
$queries = [
    "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION",
    "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION",
    "FLUSH PRIVILEGES"
];

$success = true;
foreach ($queries as $query) {
    echo "Executing: " . substr($query, 0, 50) . "...\n";
    if ($conn->query($query)) {
        echo "✓ Success\n";
    } else {
        echo "❌ Error: " . $conn->error . "\n";
        $success = false;
    }
}

if ($success) {
    echo "\n✓ All permissions fixed successfully!\n";
    echo "✓ You can now access phpMyAdmin\n";
    echo "✓ Your application should work properly\n\n";
    echo "You can delete this file now: fix_mariadb_permissions.php\n";
} else {
    echo "\n⚠ Some queries failed. You may need to run them manually.\n";
}

$conn->close();
?>
