<?php
/**
 * Database Connection Troubleshooter
 * Access: http://localhost/car-rental/fix_database_connection.php
 */

echo "<h2>Database Connection Troubleshooter</h2>";

// Test different connection methods
$hosts = ['127.0.0.1', 'localhost', '::1'];
$user = 'root';
$pass = '';
$db = 'car_rental';
$port = 3306;

echo "<h3>Testing Connections:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Host</th><th>Status</th><th>Message</th></tr>";

foreach ($hosts as $host) {
    echo "<tr>";
    echo "<td><strong>$host</strong></td>";
    
    $conn = @new mysqli($host, $user, $pass, $db, $port);
    
    if ($conn->connect_error) {
        echo "<td style='color: red;'>❌ Failed</td>";
        echo "<td>" . $conn->connect_error . "</td>";
    } else {
        echo "<td style='color: green;'>✅ Success</td>";
        echo "<td>Connected successfully!</td>";
        $conn->close();
    }
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>Solution:</h3>";
echo "<p>If all connections failed, you need to fix MariaDB user permissions:</p>";
echo "<ol>";
echo "<li>Open phpMyAdmin or MySQL command line</li>";
echo "<li>Run these SQL commands:</li>";
echo "</ol>";
echo "<pre style='background: #f4f4f4; padding: 15px;'>";
echo "-- Grant permissions to root user\n";
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '' WITH GRANT OPTION;\n";
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY '' WITH GRANT OPTION;\n";
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' IDENTIFIED BY '' WITH GRANT OPTION;\n";
echo "FLUSH PRIVILEGES;\n";
echo "</pre>";

echo "<p><strong>OR</strong> restart your XAMPP MySQL/MariaDB service:</p>";
echo "<ol>";
echo "<li>Open XAMPP Control Panel</li>";
echo "<li>Stop MySQL/MariaDB</li>";
echo "<li>Start MySQL/MariaDB again</li>";
echo "<li>Refresh this page</li>";
echo "</ol>";

echo "<hr>";
echo "<p><a href='index.php'>Try accessing the application</a></p>";
echo "<p style='color: red;'><strong>Delete this file after fixing!</strong></p>";
?>
