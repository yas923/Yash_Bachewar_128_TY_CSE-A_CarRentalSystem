<?php
require_once '../config/database.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

// Get search filters
$search = isset($_GET['search']) ? trim($conn->real_escape_string($_GET['search'])) : '';
$brand = isset($_GET['brand']) ? trim($conn->real_escape_string($_GET['brand'])) : '';
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? floatval($_GET['max_price']) : 0;

// Get all brands for dropdown (before filtering)
$brands = $conn->query("SELECT DISTINCT brand FROM cars ORDER BY brand");

// Build query - Show all cars with their status
$sql = "SELECT * FROM cars WHERE 1=1";
if (!empty($search)) {
    $sql .= " AND (brand LIKE '%$search%' OR model LIKE '%$search%')";
}
if (!empty($brand)) {
    $sql .= " AND brand='$brand'";
}
if ($maxPrice > 0) {
    $sql .= " AND price_per_day <= $maxPrice";
}
$sql .= " ORDER BY status='available' DESC, brand, model";

$cars = $conn->query($sql);

$pageTitle = 'Browse Cars';
$baseUrl = '..';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Cars</h2>
    <?php if (!empty($search) || !empty($brand) || $maxPrice > 0): ?>
        <span class="badge bg-info">Filters Active</span>
    <?php endif; ?>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by brand or model" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Brand</label>
                <select name="brand" class="form-control">
                    <option value="">All Brands</option>
                    <?php while ($b = $brands->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($b['brand']); ?>" <?php echo $brand === $b['brand'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($b['brand']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Max Price (₹/day)</label>
                <input type="number" name="max_price" class="form-control" placeholder="Enter max price" min="0" step="100" value="<?php echo $maxPrice > 0 ? $maxPrice : ''; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2"><i class="fas fa-times"></i> Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Cars Grid -->
<div class="row">
    <?php if ($cars->num_rows > 0): ?>
        <?php while ($car = $cars->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card car-card h-100">
                <?php if ($car['image'] && file_exists("../uploads/" . $car['image'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'card-img-top bg-secondary d-flex align-items-center justify-content-center\' style=\'height:200px;\'><i class=\'fas fa-car fa-4x text-white\'></i></div>';">
                <?php else: ?>
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
                    <i class="fas fa-car fa-4x text-white"></i>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $car['brand'] . ' ' . $car['model']; ?></h5>
                    <p class="card-text">
                        <strong>Year:</strong> <?php echo $car['year']; ?><br>
                        <strong>Color:</strong> <?php echo $car['color']; ?><br>
                        <strong>Price:</strong> ₹<?php echo number_format($car['price_per_day'], 2); ?>/day<br>
                        <strong>Status:</strong> 
                        <?php if ($car['status'] === 'available'): ?>
                            <span class="badge bg-success">Available</span>
                        <?php elseif ($car['status'] === 'rented'): ?>
                            <span class="badge bg-warning">Rented</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Maintenance</span>
                        <?php endif; ?>
                    </p>
                    <?php if ($car['status'] === 'available'): ?>
                        <a href="booking.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">Book Now</a>
                    <?php else: ?>
                        <button class="btn btn-secondary w-100" disabled>Not Available</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">No cars found matching your criteria.</div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
