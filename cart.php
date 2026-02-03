
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
<script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>

<?php
    session_start();
    include 'database.php';
    include 'price.php';

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
        $result = mysqli_query($conn, "SELECT ID FROM products WHERE ID IN ($ids)");
        
        $found_ids = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $found_ids[] = (int)$row['ID'];
        }

        // Remove ghosts immediately
        foreach ($_SESSION['cart'] as $session_id => $qty) {
            if (!in_array((int)$session_id, $found_ids)) {
                unset($_SESSION['cart'][$session_id]);
            }
        }
    }

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

    // 2. Refresh the count for the current page load
    $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>    


<!-- 
• Imagine $_SESSION['cart'] is like this:
• ["101" => 2, "102" => 3, "103" => 1] (i.e., Product ID => number of items(quantity))
• So array_keys($_SESSION['cart']) get only "keys"(product ID) and ignore the quantities.
• i.e., [101, 102, 103, ...]
• Then array_map('intval', ...), array_map applies a function to every item in
the array, here we apply intval (Interger Value).
• It ensures every ID is completely a number. If something malicious is about to 
and if any suspect tries to inject text into session kkeys, intval would turn it 
into a 0 or any harmless number.
• So array_map(...) and array_keys(...) both results the same array but array_map(...)
is an extra protection layer.
• And then implode(",", ...) this function takes an array, joins the elements
together into a single string, seperated by whatever character we put in the
quotes (Here in this case, it's a comma)
• So the result is "101, 102, 103"
• Here the main part. SQL cannot read PHP arrays directly like:
    SELECT * FROM products WHERE ID = $array    X
• But we've converted that into a string like "01, 102, 103...", we can use it
into SQL with a command called IN.  
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Your Cart - GetGadjet</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/df61ee4812.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">
        <?php include 'navbar.php'; ?>

        <div class="container my-2">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2">
                    <h2 class="mb-4 text-center">Shopping Cart 🛒</h2>

                    <?php if ($cart_count > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
                                    $result = mysqli_query($conn, "SELECT * FROM products WHERE ID IN ($ids)");
                                    $grand_total = 0;
                                    while ($item = mysqli_fetch_assoc($result)):
                                        $pid = $item['ID'];
                                        $qty = $_SESSION['cart'][$pid];
                                        $subtotal = $item['Price'] * $qty;
                                        $grand_total += $subtotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($item['Title']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($item['BrandName']) ?></small>
                                        </td>
                                        <td class="text-center"><?= formatIndianCurrency($item['Price']) ?></td>
                                        <td class="text-center">
                                            <form method="post" class="d-flex justify-content-center align-items-center">
                                                <input type="hidden" name="product_id" value="<?= $pid ?>">
                                                <button type="submit" name="decrease_qty" class="btn btn-sm btn-outline-secondary">-</button>
                                                <span class="mx-1 fw-bold"><?= $qty ?></span>
                                                <button type="submit" name="increase_qty" class="btn btn-sm btn-outline-secondary">+</button>
                                            </form>
                                        </td>
                                        <td class="fw-bold"><?= formatIndianCurrency($subtotal) ?></td>
                                        <td class="text-center">
                                            <form method="post">
                                                <input type="hidden" name="remove_id" value="<?= $pid ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold fs-5">Grand Total:</td>
                                        <td class="text-end fw-bold fs-6 text-success">₹ <?= formatIndianCurrency($grand_total) ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php" class="btn btn-outline-dark px-4">← Continue Shopping</a>
                            <a href="checkout.php" class="btn btn-primary px-5 shadow-sm">Proceed to Checkout <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>

                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Your cart is feeling light...</h4>
                            <a href="index.php" class="btn btn-primary mt-3 px-5">Go Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>