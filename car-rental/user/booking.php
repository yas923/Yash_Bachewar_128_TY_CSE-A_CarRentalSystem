<?php
require_once '../config/database.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;
$car = $conn->query("SELECT * FROM cars WHERE id=$car_id AND status='available'")->fetch_assoc();

if (!$car) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    // Validate dates
    if (strtotime($start_date) < strtotime(date('Y-m-d'))) {
        $error = 'Start date cannot be in the past';
    } elseif (strtotime($end_date) <= strtotime($start_date)) {
        $error = 'End date must be after start date';
    } else {
        // Calculate total price
        $days = ceil((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24));
        $total_price = $days * $car['price_per_day'];
        
        // Create booking
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price) 
                VALUES ($user_id, $car_id, '$start_date', '$end_date', $total_price)";
        
        if ($conn->query($sql)) {
            // Update car status to rented
            $update_sql = "UPDATE cars SET status='rented' WHERE id=$car_id";
            if ($conn->query($update_sql)) {
                $success = 'Booking successful! Car status updated to rented. Redirecting to your bookings...';
            } else {
                $success = 'Booking created but failed to update car status: ' . $conn->error;
            }
            header("refresh:3;url=history.php");
        } else {
            $error = 'Booking failed. Please try again. Error: ' . $conn->error;
        }
    }
}

$pageTitle = 'Book Car';
$baseUrl = '..';
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <?php if ($car['image']): ?>
            <img src="../uploads/<?php echo $car['image']; ?>" class="card-img-top" alt="<?php echo $car['brand'] . ' ' . $car['model']; ?>">
            <?php else: ?>
            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:300px;">
                <i class="fas fa-car fa-5x text-white"></i>
            </div>
            <?php endif; ?>
            <div class="card-body">
                <h3><?php echo $car['brand'] . ' ' . $car['model']; ?></h3>
                <p>
                    <strong>Year:</strong> <?php echo $car['year']; ?><br>
                    <strong>Color:</strong> <?php echo $car['color']; ?><br>
                    <strong>Price per day:</strong> ₹<?php echo number_format($car['price_per_day'], 2); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>Book This Car</h4>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" id="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                    <div class="alert alert-info">
                        <strong>Rental Duration:</strong> <span id="days">-</span> days<br>
                        <strong>Total Price:</strong> <span id="total_price">₹0.00</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
