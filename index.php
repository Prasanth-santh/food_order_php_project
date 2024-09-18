<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="overall">
    <?php
    $username=$passsword="";
        if(isset($_POST["login"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            
            require_once "database.php";

            // Use prepared statements to prevent SQL injection
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);

                if ($user) {
                    // Verify the password
                    if (password_verify($password, $user["u_password"])) {
                        // Start a session and store user info
                        session_start();
                        $_SESSION["id"] = $user["id"];
                        $_SESSION["uname"] = $user["username"];
                        $_SESSION['email'] = $user["email"];
                        $_SESSION['mobile']=$user["mobile_no"];
                        // Redirect to welcome page
                        header("Location: welcome.php");
                        exit();
                    } else {
                        echo "<div class='alert'>Password does not match</div>";
                    }
                } else {
                    echo "<div class='alert'>Username does not match</div>";
                }
            } else {
                echo "<div class='alert'>Database query failed</div>";
            }
        }
        ?>
         <form action="index.php" method="post" class="form">
        <div class="container1">
                <div class="form_register">
                    <label for="username"> User Name:</label>
                    <input type="text" name="username"   placeholder="Enter your user name" id="username">
                </div>
                <div class="form_register">
                    <label for="password"> Password :</label>
                    <input type="password" name="password" placeholder="Enter your password">
                </div>
                <div class="form_register1">
                    <input type="submit" class="submit_btn" value="login" name="login">
                </div>
                <p> New user <a href="./register1.php">Register here</a></p>
            </div>
        </form>
    </div>
</body>
</html>