<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the selected value from the form
        $selectedValue = $_POST['type'];

        // Redirect to the next page with the selected value as a query parameter
        header("Location: food_menu.php?id=" . urlencode($selectedValue));
        exit();
    }
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
        
        <div>
           <?php include "navbar.php"; ?>
        </div>
        <div class="search">
            <form action="" method="post" class="form1">
                <!-- <div class="bar"> -->
                    <label><h1>Hotels</h1></label></br>
                    <select id="type" name="type">
                        <option value="">Select</option>
                        <?php
                        require_once 'database.php';
                        $sql = "SELECT id, hotel_name FROM hotels";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['hotel_name']; ?></option>
                        <?php } ?>
                    </select></br>
                <!-- </div> -->
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
