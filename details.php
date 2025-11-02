
<?php
    session_start();
    include('database.php'); // Connect to DB
    include 'price.php';

    if (isset($_POST['add_to_cart'])) {
        $pid = $_POST['add_to_cart'];
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] = 1; // Add new product with quantity 1
            $success_message = "Product added to cart successfully!";
        }
        else {
            $info_message = "This product is already in your cart.";
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
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <span class="navbar-brand">GetGadjet</span>
                <div class="ms-auto">
                    <a href="index.php" class="btn btn-outline-light me-2">🏠 Home</a>
                        <a href="cart.php" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i>Cart(<?= $cart_count ?>)
                        </a>
                </div>
            </div>
        </nav>
        <!-- Success Message -->
        <?php if (isset($success_message)): ?>
            <div class= "alert alert-success alert-dismissible fade show alert-custom" role="alert">
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

        <!-- Product Details -->
        <div class="container py-5">
            <div class="row">
                <!-- Product Image -->
                <div class="col-md-6 text-center">
                    <img src="<?= htmlspecialchars($product['Image']) ?>" 
                        alt="<?= htmlspecialchars($product['Title']) ?>" 
                        class="img-thumbnail">
                </div>

                <!-- Product Info -->
                <div class="col-md-6">
                    <h1 class="product-title"><?= htmlspecialchars($product['Title']) ?></h1>
                    <p class="product-brand"><strong>Brand:</strong> <?= htmlspecialchars($product['BrandName']) ?></p>
                    
                    <?php if ($product['Featured'] == 1): ?>
                        <span class="feature-badge"><i class="fas fa-star"></i>
                            Featured Product
                        </span>
                    <?php endif; ?>
                    
                    <p class="product-description"><?= htmlspecialchars($product['Description']) ?></p>
                    <div class="product-price fw-bold">
                        ₹. <?= formatIndianCurrency($product['Price']) ?>/-
                    </div>
                    <form method="post" action="">
                        <input type="hidden" name="add_to_cart" value="<?= $product['ID'] ?>">
                        <button type="submit" class="add-to-cart-btn">
                            <i class="fas fa-cart-shopping"></i> Add to Cart
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
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