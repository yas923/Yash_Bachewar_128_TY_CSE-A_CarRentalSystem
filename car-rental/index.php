<?php
require_once 'config/database.php';

// Redirect based on role
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/index.php');
    } else {
        redirect('user/index.php');
    }
} else {
    redirect('login.php');
}
?>
