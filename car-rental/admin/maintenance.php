<?php
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_maintenance'])) {
    $car_id = intval($_POST['car_id']);
    $service_date = $conn->real_escape_string($_POST['service_date']);
    $cost = floatval($_POST['cost']);
    $remarks = $conn->real_escape_string($_POST['remarks']);
    
    $conn->query("INSERT INTO maintenance (car_id, service_date, cost, remarks) 
                  VALUES ($car_id, '$service_date', $cost, '$remarks')");
    $conn->query("UPDATE cars SET status='maintenance' WHERE id=$car_id");
    redirect('maintenance.php');
}

$maintenance = $conn->query("SELECT m.*, c.brand, c.model 
    FROM maintenance m 
    JOIN cars c ON m.car_id = c.id 
    ORDER BY m.service_date DESC");

$cars = $conn->query("SELECT id, brand, model FROM cars");

$pageTitle = 'Maintenance Tracking';
$baseUrl = '..';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Maintenance Tracking</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
        <i class="fas fa-plus"></i> Add Maintenance
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car</th>
                    <th>Service Date</th>
                    <th>Cost</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($record = $maintenance->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $record['id']; ?></td>
                    <td><?php echo $record['brand'] . ' ' . $record['model']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($record['service_date'])); ?></td>
                    <td>₹<?php echo number_format($record['cost'], 2); ?></td>
                    <td><?php echo $record['remarks']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Maintenance Modal -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Maintenance Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Car</label>
                        <select name="car_id" class="form-control" required>
                            <option value="">Select Car</option>
                            <?php while ($car = $cars->fetch_assoc()): ?>
                            <option value="<?php echo $car['id']; ?>"><?php echo $car['brand'] . ' ' . $car['model']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Date</label>
                        <input type="date" name="service_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cost</label>
                        <input type="number" step="0.01" name="cost" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_maintenance" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
