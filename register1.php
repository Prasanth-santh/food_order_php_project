<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="overall">
        <?php
        // Initialize variables to store form data
        $username = $email = $mobile = $password = $re_password = "";
        $errors = array();

      
        if (isset($_POST["submit"])) {
            $username = $_POST["uname"];
            $email = $_POST["email"];
            $mobile = $_POST["mobile_no"];
            $password = $_POST["password"];
            $re_password = $_POST["re_password"];

            if (empty($username) || empty($email) || empty($mobile) || empty($password) || empty($re_password)) {
                array_push($errors, "All fields are required");
            }

            if (strlen($mobile) !== 10) {
                array_push($errors, "Mobile number is not valid");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }

            if ($password !== $re_password) {
                array_push($errors, "Passwords dose not match");
            }

            if (strlen($username) < 4) {
                array_push($errors, "Name must have at least 4 characters");
            }

            if (strlen($password) < 8) {
                array_push($errors, "Password must have at least 8 characters");
            }
            require_once "database.php";
                $sql="SELECT * FROM users WHERE email='$email'";
                $result=mysqli_query($conn,$sql);
                $row=mysqli_num_rows($result);
                if($row>0){
                    array_push($errors,"Email already exist");
                }

        
            if (count($errors) == 0) {
                $option=[
                    'cost'=>9,
                ];
                $hashed_password = password_hash($password, PASSWORD_DEFAULT,$option);
                require_once "database.php";
                $sql = "INSERT INTO users (username, email, mobile_no, u_password) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $preparestmt = mysqli_stmt_prepare($stmt, $sql);
                if ($preparestmt) {
                    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $mobile, $hashed_password);
                    mysqli_stmt_execute($stmt);
                   
                    echo "<div> You are registered successfully </div>";
                    header("Location:index.php");
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="register1.php" method="post" class="form">
            <div class="container1">
                <div class="form_register">
                    <label for="uname">User Name:</label>
                    <input type="text" name="uname" placeholder="Enter your name" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="form_register">
                    <label for="email">Email:</label>
                    <input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form_register">
                    <label for="mobile_no">Mobile no:</label>
                    <input type="text" name="mobile_no" placeholder="Enter your mobile number" pattern="[0-9]{10}" title="Only numeric values" minlength="10" maxlength="10" value="<?php echo htmlspecialchars($mobile); ?>" required>
                </div>
                <div class="form_register">
                    <label for="password">Password:</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="form_register">
                    <label for="re_password">Confirm Password:</label>
                    <input type="password" name="re_password" placeholder="Re-enter password" required>
                </div>
                <div class="form_register1">
                    <input type="submit" class="submit_btn" value="Register" name="submit">
                </div>
                <div>
                    <?php
                   
                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo "<div class='alert'>$error</div>";
                        }
                    }
                    ?>
                </div>
                <p>Already registered? <a href="./index.php">Login here</a></p>
            </div>
        </form>
    </div>
</body>
</html>
