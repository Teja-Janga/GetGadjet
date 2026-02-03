<?php
    session_start();
    // Check admin authentication here

    require 'database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Grab all POST fields
        $title = $_POST['Title'];
        $price = $_POST['Price'];
        $brand = $_POST['BrandName'];
        $image = $_POST['Image'];
        $desc = $_POST['Description'];
        $featured = isset($_POST['Featured']) ? 1 : 0;
        $category = $_POST['Category'];

        // Prepare & run insert
        $stmt = $conn->prepare("INSERT INTO products (Title, Price, BrandName, Image, Description, Featured, Category, isTrashed) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param('sdsssis', $title, $price, $brand, $image, $desc, $featured, $category);
        $stmt->execute();
        $stmt->close();

        header('Location: admin-products.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Product</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
    </head>
    <body>
        <div class="container my-5">
            <h1 class="mb-4">Add New Product</h1>
            <form method="post" class="card p-4 border-secondary" style="max-width:540px;">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="Title" class="form-control border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="Price" class="form-control border-secondary" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand Name</label>
                    <input type="text" name="BrandName" class="form-control border-secondary">
                </div>
                <div class="mb-3">
                    <label class="form-label">Image URL</label>
                    <input type="text" name="Image" class="form-control border-secondary">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="Description" class="form-control border-secondary" rows="3"></textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input border-secondary" type="checkbox" name="Featured" id="featuredCheck">
                    <label class="form-check-label" for="featuredCheck">
                        Featured Product
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="Category" class="form-control border-secondary">
                </div>
                <button type="submit" class="btn btn-success">Add Product</button>
                <a href="admin-products.php" class="btn btn-link">Back to Admin Panel</a>
            </form>
        </div>
    </body>
</html>
