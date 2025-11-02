<?php
    session_start();
    include 'database.php';

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $password = $_POST['password'];

        // Check if email already exists
        $result = $conn->query("SELECT * FROM users WHERE Email='$email'");
        if ($result && $result->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            // Hash password and insert
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (Email, Password_Hash, Name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $password_hash, $name);
            if ($stmt->execute()) {
                $message = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $message = "Registration failed: " . $stmt->error;
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - GetGadjet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <h2>Register Account</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <div class="mt-3">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>
</body>
</html>
