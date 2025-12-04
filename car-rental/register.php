<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    // Validation
    $errors = [];
    
    // Validate Full Name
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 3) {
        $errors[] = 'Name must be at least 3 characters long';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Name must not exceed 100 characters';
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = 'Name can only contain letters and spaces';
    }
    
    // Validate Email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    } elseif (strlen($email) > 100) {
        $errors[] = 'Email must not exceed 100 characters';
    }
    
    // Validate Phone Number (exactly 10 digits)
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } else {
        // Remove all non-digit characters for validation
        $phone_digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone_digits) != 10) {
            $errors[] = 'Phone number must be exactly 10 digits';
        }
    }
    
    // Validate Password (minimum 6 characters + at least 1 special symbol)
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    } elseif (strlen($password) > 255) {
        $errors[] = 'Password must not exceed 255 characters';
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = 'Password must contain at least one special character (!@#$%^&*(),.?":{}|<>)';
    }
    
    // If no validation errors, proceed with registration
    if (empty($errors)) {
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if email already exists
        $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $error = 'Email already exists';
        } else {
            $sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password_hash')";
            if ($conn->query($sql)) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    } else {
        $error = implode('<br>', $errors);
    }
}

$pageTitle = 'Register';
$baseUrl = '.';
include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Register</h3>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" id="registerForm">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                               required minlength="3" maxlength="100" 
                               pattern="[a-zA-Z\s]+" 
                               title="Name must contain only letters and spaces (3-100 characters)">
                        <small class="text-muted">Only letters and spaces (3-100 characters)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               required maxlength="100"
                               title="Enter a valid email address">
                        <small class="text-muted">Valid email format (e.g., user@example.com)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="phone" class="form-control" 
                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                               required pattern="[0-9]{10}"
                               maxlength="10"
                               title="Enter exactly 10 digits">
                        <small class="text-muted">Exactly 10 digits (e.g., 1234567890)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" 
                               required minlength="6" maxlength="255"
                               pattern=".*[!@#$%^&*(),.?&quot;:{}|<>].*"
                               title="Password must be at least 6 characters and contain at least one special character">
                        <small class="text-muted">Minimum 6 characters with at least one special character (!@#$%^&*)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" 
                               required minlength="6"
                               title="Please confirm your password">
                        <small class="text-muted">Re-enter your password</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                
                <script>
                // Client-side validation
                document.getElementById('registerForm').addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;
                    
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Passwords do not match!');
                        return false;
                    }
                    
                    const name = document.getElementById('name').value.trim();
                    if (name.length < 3) {
                        e.preventDefault();
                        alert('Name must be at least 3 characters long');
                        return false;
                    }
                    
                    const phone = document.getElementById('phone').value.trim();
                    const phoneDigits = phone.replace(/[^0-9]/g, '');
                    if (phoneDigits.length !== 10) {
                        e.preventDefault();
                        alert('Phone number must be exactly 10 digits');
                        return false;
                    }
                    
                    // Check for special character in password
                    const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;
                    if (!specialCharRegex.test(password)) {
                        e.preventDefault();
                        alert('Password must contain at least one special character (!@#$%^&*(),.?":{}|<>)');
                        return false;
                    }
                });
                
                // Real-time password match indicator
                document.getElementById('confirm_password').addEventListener('input', function() {
                    const password = document.getElementById('password').value;
                    const confirmPassword = this.value;
                    
                    if (confirmPassword.length > 0) {
                        if (password === confirmPassword) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                            this.classList.add('is-invalid');
                        }
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
                </script>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
