<?php
    session_start();
    require 'database.php';
    include 'price.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
        header('Location: login.php');
        exit();
    }

    if (isset($_POST['update_status'])) {
        $order_id = intval($_POST['order_id']);
        $new_status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE orders SET Status = ? WHERE ID = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin-orders.php");
        exit();
    }

    $sql = "SELECT o.*, SUM(oi.Quantity * oi.Price) as GrandTotal
            FROM orders o
            LEFT JOIN order_items oi ON o.ID = oi.Order_id
            GROUP BY o.ID
            ORDER BY o.ID DESC";

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
        <style>
            .status-Pending { background-color: #ffc107; color: #000; }
            .status-Shipped { background-color: #0dcaf0; color: #fff; }
            .status-Delivered { background-color: #198754; color: #fff; }
            .status-Cancelled { background-color: #dc3545; color: #fff; }
        </style>
    </head>
    <body class="bg-light">
        <div class="container py-4">
            <h2 style="font-family: Georgia;" class="text-center fs-1">Admin Order Management</h2> 
            <div>
                <a href="admin-products.php" class="btn btn-secondary">Manage Products</a>
                <a href="index.php" class="btn btn-outline-primary">🏠 Home</a>
            </div>

            <div class="table-responsive bg-white p-3 shadow-sm rounded">
                <table class="table table-hover table-bordered border-dark">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Details</th>
                            <th>Total Amount</th>
                            <th>Current Status</th>
                            <th>Update Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($order = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $order['ID'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($order['Customer_Name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($order['Phone']) ?></small><br>
                                    <small><?= htmlspecialchars($order['Address']) ?></small>
                                </td>
                                <td><strong>₹. <?= formatIndianCurrency($order['GrandTotal'] ?? 0) ?></strong></td>
                                <td>
                                    <span class="badge status-<?= $order['Status'] ?>">
                                        <?= $order['Status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="order_id" value="<?= $order['ID'] ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="Pending" <?= $order['Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Shipped" <?= $order['Status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                            <option value="Delivered" <?= $order['Status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                            <option value="Cancelled" <?= $order['Status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-dark">Save</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="view-order-details.php?id=<?= $order['ID'] ?>" class="btn btn-sm btn-primary">Items</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>