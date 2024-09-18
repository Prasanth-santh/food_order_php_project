<?php
session_start(); // Start the session
$id = $_SESSION['id'];

if (isset($_GET['clear']) && $_GET['clear'] === 'true') {
    // Clear the cart
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

// Initialize total amount and count
$totalAmount = 0;
$totalCount = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalCount += $item['quantity'];
        $totalAmount += $item['quantity'] * $item['food_price'];
    }

    // Store total amount in session for use in buy.php
    $_SESSION['totalAmount'] = $totalAmount;
    $_SESSION['totalCount'] = $totalCount;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['food_id'] == $removeId) {
            // Update available quantity in database
            require_once 'database.php';
            $sql = "UPDATE foods SET quantity_available = quantity_available + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $item['quantity'], $removeId);
            $stmt->execute();
            $stmt->close();

            // Remove the item from the cart
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
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
        <nav>
            <div class="logo1"> 
                <h1>Cart Summary</h1>
            </div>
            <div class="field">
                <div class="add">
                    <button><a href="./cart.php"><i class="fa-solid fa-cart-shopping"></i> Add to cart</a></button>
                </div>
            </div>
        </nav>
        <h1 class="change">Shopping Cart</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="card1">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="card_design">
                        <div class="h1_place">
                            <div class="img_carier">
                                <img src="<?php echo htmlspecialchars($item['food_img_url']); ?>" alt="<?php echo htmlspecialchars($item['food_name']); ?>">
                                <h1 class="img_up"><?php echo htmlspecialchars($item['food_cuisine']); ?></h1>
                            </div>
                        </div>
                        <div class="card_content">
                            <h1><?php echo htmlspecialchars($item['food_name']); ?></h1>
                            <div class="rating">
                                <i class="fa-solid fa-heart"></i>
                                <span>Rating: <?php echo htmlspecialchars($item['food_rating']); ?></span>
                            </div>
                            <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                            <h2>$ <?php echo htmlspecialchars($item['quantity'] * $item['food_price']); ?></h2>
                            <a href="cart.php?remove=<?php echo $item['food_id']; ?>" class="remove-button">Remove from Cart</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="shop">
                <div class="center">
                    <div class="summary">
                        <h3>Total Items: <?php echo $totalCount; ?></h3>
                        <h3>Total Amount: $<?php echo $totalAmount; ?></h3>
                    </div>
                    <div class="butt1">
                        <!-- <a href="cart.php?clear=true"><button>Clear Cart</button></a><br/> -->
                        <a href="food_menu.php?id=<?php echo $id ?>"><button><i class="fa-solid fa-cart-shopping"></i>Continue Shopping</button></a><br/>
                        <a href="buy.php"><button><i class="fa-solid fa-truck-fast"></i>Buy</button></a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="butt1">
                <h3>Your cart is empty.</h3>
                <a href="food_menu.php?id=<?php echo $id ?>"><button>Go to Food Menu</button></a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
