
<?php
    session_start();
    include('price.php');
    include('database.php');

    if (isset($_POST['add_to_cart'])) {
        $pid = $_POST['add_to_cart'];
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] = 1; // Add new product with quantity 1
        }
    }

    $search = $_GET['search'] ?? '';
    // $products = null;
    if ($search == '') {
        $products = mysqli_query($conn, "SELECT * FROM products WHERE Category = 'Mobile Phone'");
    } else {
        include 'search-bar.php';
        $products = $searchResults;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GetGadjet</title>
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <span class="navbar-brand">GetGadjet</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">    <!-- List Item - 1 -->
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">    <!-- List Item - 3 -->
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Products
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="laptops.php">Laptops</a></li>
                                <li><hr class="dropdown-divider"> </li>
                                <li><a class="dropdown-item" href="phones.php">Mobile Phones</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- In your navbar (replace current username/logout with this) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="profile.php">Profile </a></li>
                                <li><a class="dropdown-item" href="order-history.php">Order History</a></li>
                                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                    <li><a class="dropdown-item" href="admin-products.php">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>

                        <?php else: ?>
                            <li class="nav-item">
                                <a href="login.php" class="nav-link">Login</a>
                            </li>
                            <li class="nav-item">
                                <a href="register.php" class="nav-link">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container my-4">
            <?php if ($search == ''): ?>
            <form method="GET" action="index.php" class="d-flex justify-content-center">
                <input type="text" name="search" class="form-control w-50 me-2 border-secondary" placeholder="Search products..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-primary col-md-1" type="submit">Search</button>
            </form>
            <?php else: ?> 
            <?php include 'search-bar-form.php'; ?>
            <?php endif; ?>
        </div>

        <div class="container">
            <div class="row">
                <?php while($product = mysqli_fetch_assoc($products)): ?>
                <div class="col-md-4 d-flex flex-column justify-content-center align-items-center mb-4">
                    <h4 class="text-center"><?= htmlspecialchars($product['Title']);?></h4>
                    <div class="product-img mb-2">
                    <img
                        src="<?= htmlspecialchars($product['Image']); ?>"
                        alt="<?= htmlspecialchars($product['Title']); ?>"
                        class="img-thumbnail"
                        style="width: 300px; height: 100%; object-fit: cover; border-radius:8px;">
                    </div>
                    <p class="lprice"><strong>Price: </strong>Rs. <?= formatIndianCurrency($product['Price']); ?> /-</p>
                    <a href="details.php?id=<?= $product['ID']?>" class="mb-2">
                    <button class="btn btn-success">Details</button>
                    </a>
                </div>
                <?php endwhile; mysqli_close($conn); ?>
            </div>
        </div>
    </body>
</html>