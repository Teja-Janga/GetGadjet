<?php
    session_start();
    require 'database.php';
    include 'price.php';

    // 1. Admin Security Check
    if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
        header('Location: login.php');
        exit();
    }

    // 2. Get Order ID from the URL
    if (!isset($_GET['id'])) {
        header('Location: admin-orders.php');
        exit();
    }
    $order_id = intval($_GET['id']);

    // 3. Fetch Order & Customer Details
    $orderQuery = $conn->prepare("SELECT * FROM orders WHERE ID = ?");
    $orderQuery->bind_param("i", $order_id);
    $orderQuery->execute();
    $orderInfo = $orderQuery->get_result()->fetch_assoc();

    if (!$orderInfo) {
        die("Order not found!");
    }

    // 4. Fetch the specific items in this order
    $itemsQuery = $conn->prepare("
        SELECT oi.*, p.Title, p.Image 
        FROM order_items oi 
        JOIN products p ON oi.Product_id = p.ID 
        WHERE oi.Order_id = ?
    ");
    $itemsQuery->bind_param("i", $order_id);
    $itemsQuery->execute();
    $itemsResult = $itemsQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Order Details - #<?= $order_id ?></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="mb-4">
                <a href="admin-orders.php" class="btn btn-sm btn-outline-secondary">← Back to Orders</a>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">Customer Details</div>
                        <div class="card-body">
                            <h5><?= htmlspecialchars($orderInfo['Customer_Name']) ?></h5>
                            <p class="mb-1 text-muted">📞 <?= htmlspecialchars($orderInfo['Phone']) ?></p>
                            <p class="mb-1">📍 <?= nl2br(htmlspecialchars($orderInfo['Address'])) ?></p>
                            <hr>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-primary"><?= $orderInfo['Status'] ?></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white"><strong>Items in Order #<?= $order_id ?></strong></div>
                        <div class="card-body">
                            <table class="table align-middle table-bordered border-dark">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $grandTotal = 0;
                                        while($item = $itemsResult->fetch_assoc()): 
                                            $subtotal = $item['Price'] * $item['Quantity'];
                                            $grandTotal += $subtotal;
                                    ?>
                                    <tr>
                                        <td><img src="<?= htmlspecialchars($item['Image']) ?>" width="50" class="rounded"></td>
                                        <td><?= htmlspecialchars($item['Title']) ?></td>
                                        <td>₹. <?= formatIndianCurrency($item['Price']) ?></td>
                                        <td><?= $item['Quantity'] ?></td>
                                        <td><strong>₹. <?= formatIndianCurrency($subtotal) ?></strong></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                        <td><h5 class="mb-0 text-success">₹. <?= formatIndianCurrency($grandTotal) ?></h5></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>