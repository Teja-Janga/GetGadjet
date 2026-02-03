<?php
    session_start();
    // Security: Only allow admin
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: login.php');
        exit();
    }

    require 'database.php';

    if (!isset($_GET['ID'])) {
        header('Location: admin-products.php');
        exit();
    }

    $prodID = intval($_GET['ID']);

    // Handle Update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['Title'];
        $price = $_POST['Price'];
        $brand = $_POST['BrandName'];
        $image = $_POST['Image'];
        $desc = $_POST['Description'];
        $featured = isset($_POST['Featured']) ? 1 : 0;
        $category = $_POST['Category'];

        $stmt = $conn->prepare("UPDATE products SET Title=?, Price=?, BrandName=?, Image=?, Description=?, Featured=?, Category=? WHERE ID=?");
        $stmt->bind_param('sdsssssi', $title, $price, $brand, $image, $desc, $featured, $category, $prodID);
        $stmt->execute();
        $stmt->close();

        header('Location: admin-products.php');
        exit();
    }

    // Fetch current product data to fill the form
    $stmt = $conn->prepare("SELECT * FROM products WHERE ID=?");
    $stmt->bind_param('i', $prodID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Product</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    </head>
    <body>
        <div class="container my-4">
            <h2 class="mb-3">Edit Product: <?= htmlspecialchars($product['Title']); ?></h2>
            <form method="post" class="card p-4 border-secondary" style="max-width:540px;">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="Title" class="form-control mb-3 border-secondary" value="<?= htmlspecialchars($product['Title']); ?>" required>
                    <label class="form-label">Price</label>
                    <input type="number" name="Price" class="form-control mb-3 border-secondary" value="<?= htmlspecialchars($product['Price']); ?>" step="0.01" required>
                    <label class="form-label">Brand Name</label>
                    <input type="text" name="BrandName" class="form-control mb-3 border-secondary" value="<?= htmlspecialchars($product['BrandName']); ?>">
                    <label class="form-label">Image URL</label>
                    <input type="text" name="Image" class="form-control mb-3 border-secondary" value="<?= htmlspecialchars($product['Image']); ?>">
                    <label class="form-label">Description</label>
                    <textarea name="Description" class="form-control border-secondary" rows="3"><?= htmlspecialchars($product['Description']); ?></textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="Featured" id="featuredCheck" <?= $product['Featured'] ? 'checked' : '' ?>>
                    <label class="form-check-label mb-3" for="featuredCheck">
                        Featured Product
                    </label><br>
                    <label class="form-label">Category</label>
                    <input type="text" name="Category" class="form-control border-secondary" value="<?= htmlspecialchars($product['Category']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="admin-products.php" class="btn btn-link">Back to Admin Panel</a>
            </form>
        </div>
    </body>
</html>
