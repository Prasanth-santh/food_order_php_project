<?php
session_start(); // Start the session
$id=$_SESSION['id'];
if (isset($_GET['clear']) && $_GET['clear'] === 'true') {
    // Clear the cart
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <h1>Shopping Cart</h1>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p>Cuisine: <?php echo htmlspecialchars($item['cuisine']); ?></p>
                        <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                        <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="cart.php?clear=true">
                <button>Clear Cart</button>
            </a>
            <a href='food_menu.php?id=<?php echo $id ?>'>
                <button>Continue Shopping</button>
            </a>
        <?php else: ?>
            <p>Your cart is empty.</p>
            <a href="food_menu.php">
                <button>Go to Food Menu</button>
            </a>
        <?php endif; ?>
    </div>
</body>
</html>
