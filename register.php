<?php
    session_start();
    include 'database.php';

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $password = $_POST['password'];

        if (strlen($password) < 8) {
            $message = "Password must be at least 8 characters long!";
            $alert_type = "danger";
        } else {
            // 2. Check if email already exists
            // Pro-tip: Use prepared statements even for the SELECT for max security
            $stmt_check = $conn->prepare("SELECT ID FROM users WHERE Email = ?");
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result && $result->num_rows > 0) {
                $message = "Email already registered!";
                $alert_type = "warning";
            } else {
                // 3. Hash password and insert
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Make sure this column matches your DB (is it Password or Password_Hash?)
                $stmt = $conn->prepare("INSERT INTO users (Email, Password, Name) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $password_hash, $name);
                
                if ($stmt->execute()) {
                    $message = "Success! <a href='login.php' class='alert-link'>Login here</a> to start shopping.";
                    $alert_type = "success";
                } else {
                    $message = "Registration failed: " . $stmt->error;
                    $alert_type = "danger";
                }
            }
            $stmt_check->close();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - GetGadjet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 450px;">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Create Account</h2>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?= $alert_type ?> alert-dismissible fade show" role="alert">
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="example@mail.com" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Sign Up</button>
                </form>
                
                <div class="mt-4 text-center">
                    <span class="text-muted">Already have an account?</span> <a href="login.php" class="text-decoration-none">Login</a>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="index.php" class="text-muted text-decoration-none small">← Back to Home</a>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
