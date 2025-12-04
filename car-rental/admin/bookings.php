<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['booking_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE bookings SET status='$status' WHERE id=$id");
    
    // Update car status if booking is completed or canceled
    if ($status === 'completed' || $status === 'canceled') {
        $booking = $conn->query("SELECT car_id FROM bookings WHERE id=$id")->fetch_assoc();
        $conn->query("UPDATE cars SET status='available' WHERE id=" . $booking['car_id']);
    }
    redirect('bookings.php');
}

$bookings = $conn->query("SELECT b.*, u.name as user_name, u.email, c.brand, c.model 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN cars c ON b.car_id = c.id 
    ORDER BY b.created_at DESC");

$pageTitle = 'Manage Bookings';
$baseUrl = '..';
include '../includes/header.php';
?>

<h2 class="mb-4">Manage Bookings</h2>

<div class="card">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $booking['id']; ?></td>
                    <td><?php echo $booking['user_name']; ?><br><small><?php echo $booking['email']; ?></small></td>
                    <td><?php echo $booking['brand'] . ' ' . $booking['model']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($booking['start_date'])); ?></td>
                    <td><?php echo date('M d, Y', strtotime($booking['end_date'])); ?></td>
                    <td>₹<?php echo number_format($booking['total_price'], 2); ?></td>
                    <td><span class="badge bg-<?php echo $booking['status'] === 'active' ? 'success' : ($booking['status'] === 'completed' ? 'primary' : 'secondary'); ?>"><?php echo $booking['status']; ?></span></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                            <select name="status" class="form-select form-select-sm" style="width:auto;display:inline;">
                                <option value="active" <?php echo $booking['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="canceled" <?php echo $booking['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
