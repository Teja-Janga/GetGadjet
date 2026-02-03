
<?php
    session_start();
    include('database.php');

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $success_msg = "";
    $error_msg = "";

    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE ID = $user_id");
    $user_data = mysqli_fetch_assoc($user_query);

    if (isset($_POST['update_profile'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Using a prepared statement for the profile update
        $stmt = $conn->prepare("UPDATE users SET Name = ?, Email = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);

        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name; 
            $success_msg = "Profile updated successfully!";
        }
        else {
            $error_msg = "Failed to update profile.";
        }
    }

    if (isset($_POST['change_password'])) {
        $current_p = $_POST['current_password'];
        $new_p = $_POST['new_password'];
        $confirm_p = $_POST['confirm_password'];

        // 1. Verify Current Password
        if (password_verify($current_p, $user_data['Password_Hash'])) {
            
            // 2. SAFETY CHECK: Is the new password long enough?
            if (strlen($new_p) < 8) {
                $error_msg = "New password must be at least 8 characters long!";
            } 
            // 3. SAFETY CHECK: Is it different from the current one?
            elseif ($current_p === $new_p) {
                $error_msg = "New password cannot be the same as the current one!";
            }
            // 4. Check if New Passwords match
            elseif ($new_p === $confirm_p) {
                $hashed_p = password_hash($new_p, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET Password_Hash = '$hashed_p' WHERE ID = $user_id");
                $success_msg = "Password updated successfully!";
            } else {
                $error_msg = "New passwords do not match!";
            }
        } else {
            $error_msg = "Current password is incorrect!";
        }
    }

    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Profile - GetGadjet</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <h2 class="mb-4 text-center">Account Settings</h2>
                    <?php if($success_msg): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $success_msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if($error_msg): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $error_msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <div class="accordion shadow" id="profileAccordion">
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalInfo">
                                    <i class="fas fa-user-edit me-2"></i> Edit Personal Information
                                </button>
                            </h2>
                            <div id="personalInfo" class="accordion-collapse collapse show" data-bs-parent="#profileAccordion">
                                <div class="accordion-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user_data['Name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_data['Email']) ?>" required>
                                        </div>
                                        <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#securitySettings">
                                    <i class="fas fa-lock me-2"></i> Change Password
                                </button>
                            </h2>
                            <div id="securitySettings" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                                <div class="accordion-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" name="confirm_password" class="form-control" required>
                                        </div>
                                        <button type="submit" name="change_password" class="btn btn-warning">Update Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div> <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-link text-decoration-none text-muted">← Return to Shop</a>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>