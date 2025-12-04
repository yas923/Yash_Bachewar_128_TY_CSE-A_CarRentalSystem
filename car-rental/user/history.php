<?php
require_once '../config/database.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $user_id = $_SESSION['user_id'];
    
    // Verify booking belongs to user
    $booking = $conn->query("SELECT * FROM bookings WHERE id=$booking_id AND user_id=$user_id")->fetch_assoc();
    if ($booking && $booking['status'] === 'active') {
        $conn->query("UPDATE bookings SET status='canceled' WHERE id=$booking_id");
        $conn->query("UPDATE cars SET status='available' WHERE id=" . $booking['car_id']);
    }
    redirect('history.php');
}

$user_id = $_SESSION['user_id'];
$bookings = $conn->query("SELECT b.*, c.brand, c.model, c.image 
    FROM bookings b 
    JOIN cars c ON b.car_id = c.id 
    WHERE b.user_id = $user_id 
    ORDER BY b.created_at DESC");

$pageTitle = 'My Bookings';
$baseUrl = '..';
include '../includes/header.php';
?>

<h2 class="mb-4">My Bookings</h2>

<?php if ($bookings->num_rows > 0): ?>
    <div class="row">
        <?php while ($booking = $bookings->fetch_assoc()): ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if ($booking['image']): ?>
                            <img src="../uploads/<?php echo $booking['image']; ?>" class="img-fluid rounded" alt="Car">
                            <?php else: ?>
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height:100px;">
                                <i class="fas fa-car fa-2x text-white"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h5><?php echo $booking['brand'] . ' ' . $booking['model']; ?></h5>
                            <p class="mb-1"><strong>Start:</strong> <?php echo date('M d, Y', strtotime($booking['start_date'])); ?></p>
                            <p class="mb-1"><strong>End:</strong> <?php echo date('M d, Y', strtotime($booking['end_date'])); ?></p>
                            <p class="mb-1"><strong>Total:</strong> ₹<?php echo number_format($booking['total_price'], 2); ?></p>
                            <p class="mb-2">
                                <span class="badge bg-<?php echo $booking['status'] === 'active' ? 'success' : ($booking['status'] === 'completed' ? 'primary' : 'secondary'); ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </p>
                            <?php if ($booking['status'] === 'active'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <button type="submit" name="cancel_booking" class="btn btn-sm btn-danger delete-btn">Cancel Booking</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        You don't have any bookings yet. <a href="index.php">Browse available cars</a> to make your first booking.
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
