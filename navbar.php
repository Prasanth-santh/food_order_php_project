<?php 
session_start();
$uname=$_SESSION["uname"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcome</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <nav>
            <div class="logo"> 
            </div>
            <div class="field">
                <h1><?php echo $uname?></h1>
                <div class="add">
                    <button><a href="./cart.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Add to cart</a>
                    </button>
                    <button><a href="./index.php">logout</a></button>
                </div>
            </div>
        </nav>
    </div>
</body>
</html>