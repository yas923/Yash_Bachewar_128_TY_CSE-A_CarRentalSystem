<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Car Rental Management'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $baseUrl ?? '..'; ?>/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $baseUrl ?? '..'; ?>/index.php">
                <i class="fas fa-car"></i> Car Rental
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/admin/index.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/admin/cars.php">Cars</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/admin/bookings.php">Bookings</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/admin/maintenance.php">Maintenance</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/admin/users.php">Users</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/user/index.php">Browse Cars</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/user/history.php">My Bookings</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/logout.php">Logout (<?php echo $_SESSION['name']; ?>)</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?? '..'; ?>/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
