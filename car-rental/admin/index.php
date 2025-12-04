<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
$totalCars = $conn->query("SELECT COUNT(*) as count FROM cars")->fetch_assoc()['count'];
$availableCars = $conn->query("SELECT COUNT(*) as count FROM cars WHERE status='available'")->fetch_assoc()['count'];
$activeBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status='active'")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE status IN ('active', 'completed')")->fetch_assoc()['total'] ?? 0;

// Get monthly bookings for chart
$monthlyBookings = $conn->query("SELECT MONTH(created_at) as month, COUNT(*) as count FROM bookings WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at)");

$pageTitle = 'Admin Dashboard';
$baseUrl = '..';
include '../includes/header.php';
?>

<h2 class="mb-4">Dashboard</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <h5 class="card-title">Total Cars</h5>
                <h2><?php echo $totalCars; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <h5 class="card-title">Available Cars</h5>
                <h2><?php echo $availableCars; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <h5 class="card-title">Active Bookings</h5>
                <h2><?php echo $activeBookings; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <h2>₹<?php echo number_format($totalRevenue, 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Monthly Bookings</h5>
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent Bookings</h5>
                <?php
                $recentBookings = $conn->query("SELECT b.*, u.name as user_name, c.brand, c.model 
                    FROM bookings b 
                    JOIN users u ON b.user_id = u.id 
                    JOIN cars c ON b.car_id = c.id 
                    ORDER BY b.created_at DESC LIMIT 5");
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Car</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $recentBookings->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $booking['user_name']; ?></td>
                            <td><?php echo $booking['brand'] . ' ' . $booking['model']; ?></td>
                            <td><span class="badge bg-<?php echo $booking['status'] === 'active' ? 'success' : 'secondary'; ?>"><?php echo $booking['status']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('bookingsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Bookings',
            data: [
                <?php
                $months = array_fill(1, 12, 0);
                $monthlyBookings = $conn->query("SELECT MONTH(created_at) as month, COUNT(*) as count FROM bookings WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at)");
                while ($row = $monthlyBookings->fetch_assoc()) {
                    $months[$row['month']] = $row['count'];
                }
                echo implode(',', $months);
                ?>
            ],
            backgroundColor: 'rgba(13, 110, 253, 0.5)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1
        }]
    }
});
</script>

<?php include '../includes/footer.php'; ?>
