
<?php
    session_start();
    include('database.php');
    include('price.php');

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
        $products = mysqli_query($conn, "SELECT * FROM products WHERE Category = 'Laptop'");
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <?php include 'navbar.php'; ?>

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
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="p-3">
                            <img src="<?= htmlspecialchars($product['Image']); ?>" 
                                class="card-img-top rounded" 
                                alt="<?= htmlspecialchars($product['Title']); ?>"
                                style="height: 250px; object-fit: contain;">
                        </div>
                        
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title mb-2"><?= htmlspecialchars($product['Title']); ?></h5>
                            <p class="text-success fw-bold fs-5 mb-3">
                                ₹ <?= formatIndianCurrency($product['Price']); ?>/-
                            </p>
                            
                            <div class="mt-auto">
                                <a href="details.php?id=<?= $product['ID'] ?>" class="btn btn-outline-primary w-100 mb-2">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </body>
</html>

<?php mysqli_close($conn); ?>