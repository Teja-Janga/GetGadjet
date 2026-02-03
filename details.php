
<?php
    session_start();
    include('database.php'); // Connect to DB
    include 'price.php';

    if (isset($_POST['add_to_cart'])) {
        if (!isset($_SESSION['user_id'])) {
            $current_id = intval($_POST['add_to_cart']);
            header("Location: login.php?msg=Please login to add items to your cart&redirect_id=$current_id");
            exit;
        }

        $pid = intval($_POST['add_to_cart']);
        $check = mysqli_query($conn, "SELECT ID FROM products WHERE ID = $pid");
        
        if(mysqli_num_rows($check) > 0) {
            if(!isset($_SESSION['cart'][$pid])) {
                $_SESSION['cart'][$pid] = 1;
                $success_message = "Product added to cart successfully!";
            }
            else {
                $info_message = "This product is already in your cart.";
            }
        }
        else {
            $info_message = "There's no product exist with such Product ID";
        }
    }

    $id = $_GET['id'] ?? null;
    if ($id) {
        $id = (int)$id;
        $query = "SELECT * FROM products WHERE ID = $id LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($product = mysqli_fetch_assoc($result)) {
            // Product found.  
        }
        else {
            echo "<p>No product found!</p>";
        }
    } else {
        echo "<p>Invalid product ID!</p>";
    }
    $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($product['Title']) ?> - GetGadjet</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <span class="navbar-brand fs-2">GetGadjet</span>
                <div class="ms-auto">
                    <a href="index.php" class="btn btn-outline-light me-2">🏠 Home</a>
                        <a href="cart.php" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i>Cart(<?= $cart_count ?>)
                        </a>
                </div>
            </div>
        </nav>

        <div class="container mt-2">
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_GET['msg']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <!-- Success Message -->
            <?php if (isset($success_message)): ?>
                <div class= "alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <strong><i class="fas fa-check-circle"></i>Success!</strong> <?= $success_message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <!-- Info Message -->
            <?php if (isset($info_message)): ?>
                <div class= "alert alert-info alert-dismissible fade show alert-custom" role="alert">
                    <strong><i class="fas fa-info-circle"></i>Info:</strong> <?= $info_message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="container py-2">
            <div class="card shadow-sm border-0">
                <div class="row g-0">
                    <div class="col-md-6 bg-white d-flex align-items-center justify-content-center">
                        <img src="<?= htmlspecialchars($product['Image']) ?>" 
                             alt="<?= htmlspecialchars($product['Title']) ?>" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 400px; object-fit: contain;">
                    </div>

                    <div class="col-md-6 p-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Shop</a></li>
                                <li class="breadcrumb-item active"><?= htmlspecialchars($product['BrandName']) ?></li>
                            </ol>
                        </nav>

                        <h1 class="display-5 fw-bold text-dark"><?= htmlspecialchars($product['Title']) ?></h1>
                        
                        <?php if ($product['Featured'] == 1): ?>
                            <span class="badge rounded-pill bg-warning text-dark mb-3 px-3 py-2">
                                <i class="fas fa-star me-1"></i> Featured Product
                            </span>
                        <?php endif; ?>

                        <p class="text-muted mb-4"><?= htmlspecialchars($product['Description']) ?></p>

                        <div class="h3 fw-bold text-success mb-4">
                            ₹. <?= formatIndianCurrency($product['Price']) ?>/-
                        </div>

                        <form method="post" action="">
                            <input type="hidden" name="add_to_cart" value="<?= $product['ID'] ?>">
                            <div class="d-grid gap-2 d-md-block">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">
                        
                        <div class="d-flex align-items-center">
                            <i class="fas fa-truck text-primary me-3 fa-2x"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Free Delivery</h6>
                                <small class="text-muted">On all orders above ₹. 999</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="index.php" class="text-decoration-none text-secondary small">
                    <i class="fas fa-chevron-left mb-2"></i> Continue Shopping
                </a>
            </div>
        </div>

        <script>
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert-custom');
                alerts.forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 2000);
        </script>
    </body>
</html>

<?php mysqli_close($conn); ?>