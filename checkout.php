<?php
    session_start();
    include 'database.php';
    include 'price.php';

    $order_placed = false;

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        if (!$order_placed) { 
            header("Location: index.php");
            exit;
        }
    }

    if (isset($_POST['place_order'])) {
        $session_id = session_id();
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $stmt = $conn->prepare("INSERT INTO orders (Session_id, Customer_Name, Address, Phone, User_ID) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $session_id, $name, $address, $phone, $user_id);
        $stmt->execute();
        $order_id = $conn->insert_id;

        foreach ($_SESSION['cart'] as $pid => $qty) {
            $result = $conn->query("SELECT Price FROM products WHERE ID = " .intval($pid));
            $product = $result->fetch_assoc();
            $price = $product['Price'];
            $stmt2 = $conn->prepare("INSERT INTO order_items (Order_id, Product_id, Quantity, Price) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiid", $order_id, $pid, $qty, $price);
            $stmt2->execute();
        }

        $_SESSION['cart'] = [];
        $order_placed = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Checkout - GetGadjet</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
    </head>
    <body style="font-family:Arial;">
        <div class="container text-center">
            <h1 class="my-3" style="font-family:Georgia;"><b>Checkout</b></h1>

            <?php if ($order_placed): ?>
                <div class="alert alert-success mt-4">
                    <h3>Thank you for your order!</h3>
                    <p>Your order has been placed successfully.</p>
                    <a href="order-history.php" class="btn btn-outline-primary mt-3"> See Order History</a>
                    <a href="index.php" class="btn btn-outline-primary mt-3">🏠 Home</a>
                </div>
            <?php elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <table class="table table-bordered border-secondary">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
                            $result = mysqli_query($conn, "SELECT * FROM products WHERE ID IN ($ids)");
                            $total = 0;
                            while ($item = mysqli_fetch_assoc($result)) {
                                $pid = $item['ID'];
                                $qty = $_SESSION['cart'][$pid];
                                $subtotal = $item['Price'] * $qty;
                                $total += $subtotal;
                                echo "<tr>
                                    <td>".htmlspecialchars($item['Title'])."</td>
                                    <td>₹. ".formatIndianCurrency($item['Price'])."</td>
                                    <td>$qty</td>
                                    <td>₹. ".formatIndianCurrency($subtotal)."</td>
                                </tr>";
                            }
                        ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                            <td><strong>₹. <?= formatIndianCurrency($total) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <form method="post" class="border border-secondary">
                    <div class="m-3">
                        <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
                        <input type="text" name="phone" class="form-control mb-3" placeholder="Phone" required>
                        <textarea name="address" class="form-control mb-3" placeholder="Address" required></textarea>
                    </div>
                    <button type="submit" name="place_order" class="btn btn-success btn-lg mb-2">
                        <i class="fa fa-shopping-bag"></i> Place Order
                    </button>
                </form>
                <a href="cart.php" class="btn btn-outline-dark mt-3">Back to Cart</a>
            <?php else: ?>
                <div class="mt-5">
                    Your cart is empty!<br>
                    <a href="index.php" class="btn btn-outline-primary mt-3">🏠 Home</a>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>
<?php mysqli_close($conn); ?>
