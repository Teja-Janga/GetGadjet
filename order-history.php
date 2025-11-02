<?php
    session_start();
    include('database.php');
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
            <span class="navbar-brand mb-0 h1">GetGadjet</span>
            <a href="index.php" class="btn btn-outline-light ms-auto">🏠 Home</a>
        </div>
    </nav>
    <div class="container my-5">
        <h2>Your Order History</h2>
        <?php if ($orders->num_rows === 0): ?>
            <div class="alert alert-info my-4">No orders yet! Go shop and place one!</div>
        <?php else: ?>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <div class="card my-4 border-secondary">
                    <div class="card-header">
                        <strong>Order #<?= $order['ID'] ?></strong> - <?= $order['Created_at'] ?><br>
                        <b>Name: </b><?= htmlspecialchars($order['Customer_Name']) ?><br>
                        <b>Phone: </b><?= htmlspecialchars($order['Phone']) ?>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php
                            $order_id = $order['ID'];
                            $itemq = "SELECT oi.*, p.Title FROM order_items oi JOIN products p ON oi.Product_id=p.ID WHERE Order_id=$order_id";
                            $items = $conn->query($itemq);
                            while ($item = $items->fetch_assoc()):
                            ?>
                            <li>
                                <strong><?= htmlspecialchars($item['Title']) ?>
                                x <?= $item['Quantity'] ?></strong>
                                = Rs.<?= number_format($item['Price'],2) ?> each
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    </body>
</html>
