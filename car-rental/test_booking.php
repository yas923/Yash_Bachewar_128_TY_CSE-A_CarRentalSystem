<?php
/**
 * Test script to check booking functionality
 * Access: http://localhost/car-rental/test_booking.php
 */

require_once 'config/database.php';

echo "<h2>Booking System Test</h2>";

// Check if bookings table exists and has data
$bookings = $conn->query("SELECT b.*, c.brand, c.model, c.status as car_status 
    FROM bookings b 
    JOIN cars c ON b.car_id = c.id 
    ORDER BY b.id DESC LIMIT 5");

echo "<h3>Recent Bookings:</h3>";
if ($bookings && $bookings->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Booking ID</th><th>Car</th><th>Car Status</th><th>Booking Status</th><th>Start Date</th><th>End Date</th></tr>";
    while ($booking = $bookings->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $booking['id'] . "</td>";
        echo "<td>" . $booking['brand'] . " " . $booking['model'] . "</td>";
        echo "<td><strong>" . $booking['car_status'] . "</strong></td>";
        echo "<td>" . $booking['status'] . "</td>";
        echo "<td>" . $booking['start_date'] . "</td>";
        echo "<td>" . $booking['end_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No bookings found.</p>";
}

// Check all cars and their status
echo "<h3>All Cars Status:</h3>";
$cars = $conn->query("SELECT id, brand, model, status FROM cars ORDER BY id");
if ($cars && $cars->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Car ID</th><th>Brand</th><th>Model</th><th>Status</th></tr>";
    while ($car = $cars->fetch_assoc()) {
        $color = $car['status'] === 'available' ? 'green' : ($car['status'] === 'rented' ? 'orange' : 'gray');
        echo "<tr style='background-color: " . $color . "; color: white;'>";
        echo "<td>" . $car['id'] . "</td>";
        echo "<td>" . $car['brand'] . "</td>";
        echo "<td>" . $car['model'] . "</td>";
        echo "<td><strong>" . $car['status'] . "</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No cars found.</p>";
}

echo "<hr>";
echo "<p><a href='user/index.php'>Go to Browse Cars</a> | <a href='admin/cars.php'>Go to Admin Cars</a></p>";
echo "<p style='color: red;'><strong>Delete this file after testing!</strong></p>";

$conn->close();
?>
