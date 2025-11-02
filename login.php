<?php
    session_start();
    include 'database.php';

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Fetch user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['Password_Hash'])) {
            // Login success: set session
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['is_admin'] = $user['Is_Admin'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid email or password!";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - GetGadjet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <h2>Login</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <div class="mt-3">
        New user? <a href="register.php">Register here</a>
    </div>
</div>
</body>
</html>
