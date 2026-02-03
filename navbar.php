
<?php
// Get the current filename (e.g., 'index.php')
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark align-items-center">
    <div class="container-fluid">
        <span class="navbar-brand fs-2">GetGadjet</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($current_page == 'laptops.php' || $current_page == 'phones.php') ? 'active' : '' ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        Products
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="laptops.php">Laptops 💻</a></li>
                        <li><a class="dropdown-item" href="phones.php">Mobile Phones 📱</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'cart.php') ? 'active' : '' ?>" href="cart.php">
                        Cart(<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($current_page == 'profile.php' || $current_page == 'order-history.php') ? 'active' : '' ?>" href="#" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="order-history.php">Order History</a></li>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                <li><a class="dropdown-item" href="admin-products.php">Admin Panel</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link <?= ($current_page == 'login.php') ? 'active' : '' ?>">Login</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link <?= ($current_page == 'register.php') ? 'active' : '' ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>