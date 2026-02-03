<?php
    session_start();
    include('database.php');
    include 'price.php';
    $session_id = session_id();

    // Fetch all orders by this session/user
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $orders = $conn->query("SELECT * FROM Orders WHERE User_ID = $user_id ORDER BY Created_at DESC");
    } else {
        $session_id = session_id();
        $orders = $conn->query("SELECT * FROM Orders WHERE Session_id = '$session_id' AND User_ID IS NULL ORDER BY Created_at DESC");
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Your Order History</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    </head>
    <body style="font-family: Arial;">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark align-items-center">
            <div class="container-fluid">
                <span class="navbar-brand fs-2">GetGadjet</span>
                <a href="index.php" class="btn btn-outline-light ms-auto">🏠 Home</a>
            </div>
        </nav>
        <div class="container my-2">
            <h1 class="mb-0 text-center">Your Order History📜</h1>
            <?php if ($orders->num_rows === 0): ?>
                <div class="alert alert-info my-4">No orders yet! Go shop and place one!</div>
            <?php else: ?>
                <?php while ($order = $orders->fetch_assoc()): 
                    $order_id = $order['ID'];
                    
                    // 1. Fetch items for this order
                    $itemq = "SELECT oi.*, p.Title FROM order_items oi JOIN products p ON oi.Product_id=p.ID WHERE Order_id=$order_id";
                    $items_result = $conn->query($itemq);
                    
                    // 2. Calculate the order total manually since it's not in the 'orders' table
                    $order_total = 0;
                    $items_array = []; 
                    while ($item = $items_result->fetch_assoc()) {
                        $order_total += ($item['Price'] * $item['Quantity']);
                        $items_array[] = $item;
                    }
                ?>
                    <div class="card my-4 border-secondary shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Order #<?= $order['ID'] ?></strong><br>
                                <small class="text-muted"><i class="far fa-calendar-alt"></i> <?= date('d M Y, h:i A', strtotime($order['Created_at'])) ?></small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success mb-1">Placed</span><br>
                                <strong class="text-primary fs-5">₹ <?= formatIndianCurrency($order_total) ?>/-</strong>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Items in this order:</h6>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($items_array as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>
                                            <strong><?= htmlspecialchars($item['Title']) ?></strong> 
                                            <span class="text-muted">x <?= $item['Quantity'] ?></span>
                                        </span>
                                        <span>₹ <?= formatIndianCurrency($item['Price'] * $item['Quantity']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="mt-3">
                                <small><strong>Shipping to:</strong> <?= htmlspecialchars($order['Address']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </body>
</html>
