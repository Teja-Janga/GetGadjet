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
    <body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4" style="font-family: Georgia;">Login</h2>
                        
                        <?php if ($message): ?>
                            <div class="alert alert-danger py-2"><?= $message ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required autocomplete="email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">Login to GetGadjet</button>
                        </form>

                        <div class="mt-4 text-center">
                            <span class="text-muted small">New here?</span> 
                            <a href="register.php" class="text-decoration-none small fw-bold">Create an account</a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="index.php" class="text-muted text-decoration-none small">🏠 Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
