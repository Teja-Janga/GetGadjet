
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
<script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>

<?php
    session_start();
    include 'database.php';
    include 'price.php';

    // Handle Quantity Increase
    if (isset($_POST['increase_qty'], $_POST['product_id'])) {
        $pid = $_POST['product_id'];
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]++;
            header("Location: cart.php");
            exit;
        } 
    }

    // Handle Quantity Decrease
    if (isset($_POST['decrease_qty'], $_POST['product_id'])) {
        $pid = $_POST['product_id'];
        if (isset($_SESSION['cart'][$pid]) && $_SESSION['cart'][$pid] > 1) {
            $_SESSION['cart'][$pid]--;
            header("Location: cart.php");
            exit;
        }
    }

    // Handle Remove Item
    if (isset($_POST['remove_id'])) {
        $pid = $_POST['remove_id'];
        if (isset($_SESSION['cart'][$pid])) {
            unset($_SESSION['cart'][$pid]);
            header("Location: cart.php");
            exit;
        }
    }

    echo "<body style='font-family:Arial;'>
            <div class='text-center'>
                <h1 style='font-family:Georgia;'><b>Your Cart 🛒</b></h1>";

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
        $result = mysqli_query($conn, "SELECT * FROM products WHERE ID IN ($ids)");
        echo '  <table class="table table-bordered">
                    <thead class="b table-dark">
                        <tr class="text-center">
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-secondary" style="font-size: 15px">';
        $grand_total = 0;

        while ($item = mysqli_fetch_assoc($result)) {
            $pid = $item['ID'];
            $qty = $_SESSION['cart'][$pid];
            $subtotal = $item['Price'] * $qty;
            $grand_total += $subtotal;

            echo '  
                <tr class="text-center">
                    <td>' . htmlspecialchars($item["Title"]) . '</td>
                    <td>₹. '. formatIndianCurrency($item['Price']) .'/-</td>
                    <td>
                        <form method="post" action="cart.php" style="display: inline;">
                            <input type="hidden" name="product_id" value="'. $pid .'">
                            <button type="submit" name="decrease_qty" class="btn btn-sm btn-outline-primary">-</button>
                        </form>
                        <strong> ' . $qty . '</strong>
                        <form method="post" action="cart.php" style="display: inline;">
                            <input type="hidden" name="product_id" value="'. $pid .'">
                            <button type="submit" name="increase_qty" class="btn btn-sm btn-outline-primary">+</button>
                        </form>
                    </td>
                    <td>₹. '. formatIndianCurrency($subtotal) .' /-</td>
                    
                    <td>
                        <form method="post" action="" style="display: inline;">
                            <input type="hidden" name="remove_id" value="'. $pid .'">
                            <button type="submit" class="btn btn-danger btn-sm">
                                Remove <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
        }
        echo '  <tr>
                    <td colspan="3" class="text-end"><strong>Grand Total: </strong></td>
                    <td colspan="2">₹. '. formatIndianCurrency($grand_total) .' /-</td>
                </tr>
                </tbody></table>
                <a href="checkout.php" class="btn btn-primary">
                    <i class="fa fa-shopping-bag"></i> Proceed to Checkout
                </a>
                <a href="index.php" class="btn btn-success">Continue Shopping</a>
            </div>';
    } 
    else {
        echo "Your cart is empty! ";
        echo '</div>';
        echo '  <div class="text-center">
                    <a href="index.php" class="btn btn-outline-primary mt-3">
                    🏠 Home<a>
                </div>';
        
    }
    echo '</body>';
?>
