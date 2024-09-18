<?php
session_start();
$uname = $_SESSION["uname"];

require_once "database.php"; // Ensure this is included for database connection

// Handle Clear Cart Logic (Cancel Order)
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] === 'true') {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $food_id = $item['food_id'];
            $quantity = $item['quantity'];
            
            // Debugging: Check if food_id and quantity are correct
            echo "Food ID: $food_id, Quantity: $quantity<br>";
            // Increase the available quantity in the database
            require_once "database.php"; 
            $sql = "UPDATE foods SET quantity_available = quantity_available + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $stmt->bind_param('ii', $quantity, $food_id);
            
            if (!$stmt->execute()) {
                die('Execute failed: ' . htmlspecialchars($stmt->error));
            }
            
            $stmt->close();
        }
    } else {
        echo "No items in the cart to clear.<br>";
    }

    unset($_SESSION['cart']); // Clear the cart
    echo $food_id,$quantity;
    echo "<script>alert('Order has been canceled and quantities have been updated.'); window.location.href = 'welcome.php';</script>"; // Alert and redirect
    exit();
}

// Initialize variables
$totalAmount = isset($_SESSION['totalAmount']) ? $_SESSION['totalAmount'] : 0;
$finalAmount = $totalAmount;
$discountPercentage = 0;
$deliveryCharge = 0;
$kilometers = ''; // Initialize kilometers

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if kilometers is set in the POST request
    if (isset($_POST['kilometers'])) {
        $kilometers = $_POST['kilometers'];

        // Determine delivery charge based on kilometers
        if ($kilometers <= 5) {
            $deliveryCharge = 50;
        } elseif ($kilometers <= 10) {
            $deliveryCharge = 100;
        } elseif ($kilometers <= 20) {
            $deliveryCharge = 200;
        } else {
            $deliveryCharge = 300;
        }

        // Apply discount based on total amount
        if ($totalAmount > 5000) {
            $discountPercentage = 0.40;
        } elseif ($totalAmount > 3000) {
            $discountPercentage = 0.30;
        } elseif ($totalAmount > 2000) {
            $discountPercentage = 0.20;
        } elseif ($totalAmount > 1000) {
            $discountPercentage = 0.10;
        }

        // Calculate final amount
        $discountAmount = $totalAmount * $discountPercentage;
        $finalAmount = $totalAmount - $discountAmount + $deliveryCharge;
        $discount = $discountPercentage * 100;
    }
}

if (isset($_POST['submit'])) {
    $discount1 = $_POST['discount'];
    $finalamount = $_POST['total'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    if (empty($mobile) || empty($address)) {
        echo "<div class='alert'>All fields are required </div>";
    } else {
        // Properly include the discount value
        $sql = "INSERT INTO orders (username, food_amount, food_discount, food_total_amount, mobile_no, address ) 
                VALUES ('$uname', '$totalAmount', '$discount1', '$finalamount', '$mobile', '$address')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            // Decrease the available quantity in the database for each item in the cart
            foreach ($_SESSION['cart'] as $item) {
                $food_id = $item['id'];
                $quantity = $item['quantity'];

                $sql = "UPDATE foods SET quantity_available = quantity_available - ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt->bind_param("ii", $quantity, $food_id);
                $stmt->execute();
                if ($stmt->error) {
                    die('Execute failed: ' . htmlspecialchars($stmt->error));
                }
            }

            unset($_SESSION['cart']); // Clear the cart after purchase
            echo "<script>alert('Your order has been placed successfully. We will deliver it soon.'); window.location.href = 'welcome.php';</script>"; // Alert and redirect
            // header("Location: welcome.php");  
            exit();
        } else {
            die("Something went wrong: " . mysqli_error($conn));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function handlePurchase1(event) {
                window.location.href = 'buy.php?clear_cart=true'; // Redirect to clear cart
            
        }
    </script>
</head>
<body>
    <nav>
        <div class="logo1"> 
            <h1>Order Summary</h1>
        </div>
        <div class="field">
            <div class="add">
                <button><a href="./cart.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Add to cart</a>
                </button>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="overall1">
            <div class="card_design1"> 
                <h1>Enter Delivery Distance</h1>
                <form method="post" action="buy.php">
                    <label for="kilometers">Enter kilometers:</label>
                    <input type="number" name="kilometers" id="kilometers" required>
                    <button type="submit" class="butt">Calculate</button>
                </form>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <div class="summary">
                        <h3>Total Amount: $<?php echo $totalAmount; ?></h3>
                        <h3>Discount: <?php echo $discount ?>%</h3>
                        <h3>Delivery Charge: $<?php echo $deliveryCharge; ?></h3>
                        <h3>Final Amount: $<?php echo $finalAmount; ?></h3>
                        <div class="butt1">
                            <div>
                                <label for="fr">
                                    <div class="click">
                                        <i class="fa-solid fa-cart-shopping">Purchase</i>
                                    </div>
                                </label>
                                <input type="checkbox" id="fr" />
                                <form class="show" method="post" action="buy.php">
                                    <label for="uname">User Name :</label>
                                    <input type="text" name="uname" value="<?php echo $uname; ?>" />
                                    
                                    <label for="amount">Food Amount :</label>
                                    <input type="text" name="amount" value="<?php echo $totalAmount; ?>" />
                                    
                                    <label for="discount">Discount :</label>
                                    <input type="text" name="discount" value="<?php echo $discountPercentage * 100;?>%" />
                                    
                                    <label for="total">Total Amount :</label>
                                    <input type="text" name="total" value="<?php echo $finalAmount; ?>" />
                                    
                                    <label for="mobile">Mobile No :</label>
                                    <input type="text" name="mobile" maxlength="10" placeholder="Enter your number" required />

                                    <label for="address">Address :</label>
                                    <textarea name="address" id="address" required></textarea>

                                    <input type="submit" name="submit" value="Purchase" id="submit">
                                    <!-- Button with confirm dialog -->
                                    <button type="button" onclick="handlePurchase1(event)">
                                        Cancel order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
