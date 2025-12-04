<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $brand = $conn->real_escape_string($_POST['brand']);
            $model = $conn->real_escape_string($_POST['model']);
            $year = intval($_POST['year']);
            $color = $conn->real_escape_string($_POST['color']);
            $price = floatval($_POST['price_per_day']);
            $status = $conn->real_escape_string($_POST['status']);
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // Check file size (10MB = 10485760 bytes)
                if ($_FILES['image']['size'] > 10485760) {
                    die("Error: File size exceeds 10MB limit");
                }
                
                // Check file type
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed)) {
                    die("Error: Only JPG, JPEG, PNG & GIF files are allowed");
                }
                
                $target_dir = "../uploads/";
                $image = time() . '_' . basename($filename);
                move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
            }
            
            if ($_POST['action'] === 'add') {
                $sql = "INSERT INTO cars (brand, model, year, color, price_per_day, status, image) 
                        VALUES ('$brand', '$model', $year, '$color', $price, '$status', '$image')";
            } else {
                $id = intval($_POST['id']);
                $sql = "UPDATE cars SET brand='$brand', model='$model', year=$year, color='$color', 
                        price_per_day=$price, status='$status'";
                if ($image) $sql .= ", image='$image'";
                $sql .= " WHERE id=$id";
            }
            $conn->query($sql);
        } elseif ($_POST['action'] === 'delete') {
            $id = intval($_POST['id']);
            $conn->query("DELETE FROM cars WHERE id=$id");
        }
        redirect('cars.php');
    }
}

$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");

$pageTitle = 'Manage Cars';
$baseUrl = '..';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Cars</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
        <i class="fas fa-plus"></i> Add Car
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Color</th>
                    <th>Price/Day</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($car = $cars->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $car['id']; ?></td>
                    <td><?php echo $car['brand']; ?></td>
                    <td><?php echo $car['model']; ?></td>
                    <td><?php echo $car['year']; ?></td>
                    <td><?php echo $car['color']; ?></td>
                    <td>₹<?php echo number_format($car['price_per_day'], 2); ?></td>
                    <td><span class="badge badge-<?php echo $car['status']; ?>"><?php echo $car['status']; ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editCar(<?php echo htmlspecialchars(json_encode($car)); ?>)">Edit</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Car</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="add">
                    <input type="hidden" name="id" id="carId">
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" id="brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" name="model" id="model" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" id="year" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price Per Day</label>
                        <input type="number" step="0.01" name="price_per_day" id="price_per_day" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="available">Available</option>
                            <option value="rented">Rented</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCar(car) {
    document.getElementById('modalTitle').textContent = 'Edit Car';
    document.getElementById('action').value = 'edit';
    document.getElementById('carId').value = car.id;
    document.getElementById('brand').value = car.brand;
    document.getElementById('model').value = car.model;
    document.getElementById('year').value = car.year;
    document.getElementById('color').value = car.color;
    document.getElementById('price_per_day').value = car.price_per_day;
    document.getElementById('status').value = car.status;
    new bootstrap.Modal(document.getElementById('addCarModal')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
