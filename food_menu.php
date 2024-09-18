<?php
session_start();
$message="Out of stock";
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $_SESSION['id']=$id;
    require_once "database.php";
    $sql="SELECT hotel_name,hotel_logo_url,hotel_location FROM hotels WHERE id=$id";
    $result=mysqli_query($conn,$sql)->fetch_assoc(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $food_img_url = $_POST['food_img_url'];
    $food_price = $_POST['food_price'];
    $food_cuisine = $_POST['food_cuisine'];
    $food_rating = $_POST['food_rating'];
    $quantity = $_POST['quantity'];
    $available_quantity = $_POST['quantity_available'];

    if ($quantity > $available_quantity) {
        echo "<script>alert('Cannot add more items than available');</script>";
    } else {
        // Reduce the available quantity in the database
        require_once "database.php";
        $new_quantity = $available_quantity - $quantity;
        $sql = "UPDATE foods SET quantity_available=$new_quantity WHERE id=$food_id";
        mysqli_query($conn, $sql);

        // Create an associative array with the food details
        $food_item = [
            'food_id' => $food_id,
            'food_name' => $food_name,
            'food_img_url' => $food_img_url,
            'food_price' => $food_price,
            'food_cuisine' => $food_cuisine,
            'food_rating' => $food_rating,
            'quantity' => $quantity
        ];

        // Add the food item to the session cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = $food_item;
        // print_r($_SESSION['cart']);
        // Redirect to the cart page
        header("Location: cart.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function increaseQuantity(id, maxQuantity) {
            var quantityInput = document.getElementById('quantity-' + id);
            var currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity < maxQuantity) {
                quantityInput.value = currentQuantity + 1;
            }
        }

        function decreaseQuantity(id) {
            var quantityInput = document.getElementById('quantity-' + id);
            var currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <nav>
            <div class="logo1"> 
                <img src="<?php echo $result['hotel_logo_url']; ?>" alt="">
            </div>
            <div class="field">
                <h1><?php echo $result['hotel_name']; ?></h1>
            </div>
            <div class="field">
                <h1><?php echo $result['hotel_location']; ?></h1>
                <div class="add">
                    <button><a href="./cart.php">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Add to cart</a>
                    </button>
                </div>
            </div>
        </nav>
        <div class="">
            <div class="card">
                <?php
                if(isset($_GET['id'])){
                    $id=$_GET['id'];
                }
                require_once "database.php";
                $sql="SELECT f.id, f.food_name, f.food_img_url, f.food_price, f.food_cuisine, f.food_rating, f.quantity_available 
                      FROM foods f 
                      WHERE f.hotel_id = '$id'";
                $result=mysqli_query($conn,$sql);
                while ($row = mysqli_fetch_array($result)) {
                ?>
                <div class="card_design">
                    <div class="h1_place">
                        <div class="img_carier">
                            <img src="<?php echo htmlspecialchars($row['food_img_url']); ?>" alt="pic">
                            <h1 class="img_up"><?php echo htmlspecialchars($row['food_cuisine']); ?></h1>
                        </div>
                    </div>
                    <div class="card_content">
                        <h1><?php echo htmlspecialchars($row['food_name']); ?></h1>
                        <div class="rating">
                            <i class="fa-solid fa-heart"></i>
                            <span>Rating: <?php echo htmlspecialchars($row['food_rating']); ?></span>
                        </div>
                        <h2>Price :$ <?php echo htmlspecialchars($row['food_price']); ?></h2>
                        <h2>Available: <?php echo htmlspecialchars($row['quantity_available']==0)? $message:($row['quantity_available']); ?></h2>
                        <form action="food_menu.php" method="POST" >
                            <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="food_name" value="<?php echo htmlspecialchars($row['food_name']); ?>">
                            <input type="hidden" name="food_img_url" value="<?php echo htmlspecialchars($row['food_img_url']); ?>">
                            <input type="hidden" name="food_price" value="<?php echo htmlspecialchars($row['food_price']); ?>">
                            <input type="hidden" name="food_cuisine" value="<?php echo htmlspecialchars($row['food_cuisine']); ?>">
                            <input type="hidden" name="food_rating" value="<?php echo htmlspecialchars($row['food_rating']); ?>">
                            <input type="hidden" name="quantity_available" value="<?php echo htmlspecialchars($row['quantity_available']); ?>">
                            
                            <div class="inc"> 
                                <button type="button" onclick="decreaseQuantity(<?php echo htmlspecialchars($row['id']); ?>)">-</button>
                                <input type="number" name="quantity" id="quantity-<?php echo htmlspecialchars($row['id']); ?>" min="1" value="1" max="<?php echo htmlspecialchars($row['quantity_available']); ?>" required>
                                <button type="button" onclick="increaseQuantity(<?php echo htmlspecialchars($row['id']); ?>, <?php echo htmlspecialchars($row['quantity_available']); ?>)">+</button>
                            </div>
                            <button type="submit"><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                        </form>

                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
