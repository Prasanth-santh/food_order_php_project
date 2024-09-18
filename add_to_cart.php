<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $food_img_url = $_POST['food_img_url'];
    $food_price = $_POST['food_price'];
    $food_cuisine = $_POST['food_cuisine'];
    $food_rating = $_POST['food_rating'];
    $quantity = $_POST['quantity'];

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

    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}
?>
