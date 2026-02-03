<?php
    session_start();
    include 'price.php';
    // Check admin authentication here
    require 'database.php'; // DB connection

    if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
        header('Location: login.php');
        exit();
    }


    // Handle trash/delete or restore actions
    if (isset($_GET['action']) && isset($_GET['ID'])) {
        $productId = intval($_GET['ID']);
        if ($_GET['action'] == 'trash') {
            $stmt = $conn->prepare("UPDATE products SET isTrashed=1 WHERE ID=?");
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            $stmt->close();
        } elseif ($_GET['action'] == 'restore') {
            $stmt = $conn->prepare("UPDATE products SET isTrashed=0 WHERE ID=?");
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            $stmt->close();
        } elseif ($_GET['action'] == 'delete') { // <-- NEW for hard delete
            $stmt = $conn->prepare("DELETE FROM products WHERE ID=?");
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            $stmt->close();
        }

        header('Location: admin-products.php');
        exit();
    }

    

    // Toggle to show trashed products if requested
    $showTrashed = isset($_GET['show']) && $_GET['show'] == 'trashed';
    $where = " WHERE isTrashed=" . ($showTrashed ? 1 : 0);
    $params = [];
    $types = '';

    if (!empty($_GET['title'])) {
        $where .= " AND Title LIKE ?";
        $params[] = '%' . $_GET['title'] . '%';
        $types .= 's';
    }
    if (!empty($_GET['category'])) {
        $where .= " AND Category = ?";
        $params[] = $_GET['category'];
        $types .= 's';
    }

    $sql = "SELECT * FROM products" . $where;
    $stmt = $conn->prepare($sql);

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Panel - Products</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />

    </head>
    <body>
        <div class="container">
            <h1 class="text-center mb-3">Product Management</h1>
            <div class="d-flex justify-content-between align-items-center my-3">
                <div>
                    <a href="add-product.php" class="btn btn-success">Add Product</a>
                    <a href="admin-orders.php" class="btn btn-info">📦 View Orders</a>
                    <?php if ($showTrashed): ?>
                        <a href="admin-products.php" class="btn btn-secondary">Show Active Products</a>
                    <?php else: ?>
                        <a href="admin-products.php?show=trashed" class="btn btn-warning">Show Trashed Products</a>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-outline-primary" title="Home">🏠</a>
                </div>
                <!-- We can also put filter form here for quick access -->
            </div>

            <!-- Filter form inserted below for clarity -->
            <form class="row gy-2 gx-3 align-items-center mb-3" method="GET" action="admin-products.php">
                <input type="hidden" name="show" value="<?= $showTrashed ? 'trashed' : '' ?>">
                <div class="col-auto">
                    <input type="text" name="title" class="form-control" placeholder="Search by Title..." value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '' ?>">
                </div>
                <div class="col-auto">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <!-- Use dynamic options for future-proof -->
                        <?php $catRes = $conn->query("SELECT DISTINCT Category FROM products");
                        while ($cat = $catRes->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($cat['Category']) ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['Category']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['Category']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <?php if (isset($_GET['title']) || isset($_GET['category'])): ?>
                        <a href="admin-products.php<?= $showTrashed ? '?show=trashed' : '' ?>" class="btn btn-secondary">Clear Filters</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered border-secondary align-middle mb-5">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>BrandName</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Featured</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ID']) ?></td>
                            <td><?= htmlspecialchars($row['Title']) ?></td>
                            <td>₹.<?= htmlspecialchars($row['Price']) ?>/-</td>
                            <td><?= htmlspecialchars($row['BrandName']) ?></td>
                            <td><img src="<?= htmlspecialchars($row['Image']) ?>" alt="Image" width="100"></td>
                            <!-- <td><?= htmlspecialchars($row['Description']) ?></td> -->
                             <td>
                                <?php
                                    $desc = htmlspecialchars($row['Description']);
                                    echo (strlen($desc) > 45) ? substr($desc, 0, 45) . "..." : $desc;
                                ?>
                             </td>
                            <td>
                                <span class="badge <?= $row['Featured'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $row['Featured'] ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['Category']) ?></td>
                            <td>
                                <a href="edit-product.php?ID=<?= $row['ID'] ?>"
                                class="btn btn-sm btn-primary mb-1">Edit</a><br>
                                <?php if ($showTrashed): ?>
                                    <a href="admin-products.php?action=restore&ID=<?= $row['ID'] ?>"
                                        onclick="return confirm('Restore this product?')"
                                        class="btn btn-sm btn-outline-success mb-1">Restore</a>
                                    <a href="admin-products.php?action=delete&ID=<?= $row['ID'] ?>"
                                        onclick="return confirm('Delete PERMANENTLY? This cannot be undone.')"
                                        class="btn btn-sm btn-danger mb-1">Delete Permanently</a>
                                <?php else: ?>
                                <a href="admin-products.php?action=trash&ID=<?= $row['ID'] ?>"
                                    onclick="return confirm('Are you sure you want to trash this product?')"
                                    class="btn btn-sm btn-warning mb-1">Trash
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>