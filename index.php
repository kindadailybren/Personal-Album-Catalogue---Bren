<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="media/vinyl.png">
    <title>Album Gallery</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik+Mono+One&family=VT323&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="login-container">
        <h1 class="title">Personal Album Catalogue</h1>
        <div class="logins">
            <div class="login-side">
                <p>Login</p>
                <form action="index.php" method="post" class="formz">
                    <div class="each">
                        <label for="username-login">Username:</label><br>
                        <input type="text" id="username-login" name="username">
                    </div>
                    <div class="each">
                        <label for="password-login">Password:</label><br>
                        <input type="password" id="password-login" name="password">
                    </div>
                    <p id="login_dis"><?php
                                        if (isset($_POST["login"])) {
                                            include("database.php"); // This is for starting the database

                                            $_SESSION["username-store"] = strtoupper(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS));
                                            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

                                            $username = $_SESSION["username-store"];
                                            $sql_query = "SELECT username_info, password_info FROM user_info WHERE username_info = '$username'";

                                            $result = mysqli_query($conn, $sql_query);
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);

                                                if (password_verify($password, $row["password_info"])) {
                                                    echo "Login successful!";
                                                    header("Location: albums.php"); // Redirect after registration
                                                    exit();
                                                } else {
                                                    echo "Login Credentials Wrong";
                                                }
                                            } else {
                                                echo "User not found!";
                                            }


                                            mysqli_close($conn);
                                        }
                                        ?></p>
                    <button type="submit" id="login" name="login">LOGIN</button>
                </form>
            </div>
            <div class="register-side">
                <p>Register</p>
                <form action="index.php" method="post" class="formz">
                    <div class="each">
                        <label name="username">Username:<br>
                            <input type="text" id="username-register" name="username">
                    </div>
                    <div class="each">
                        <label name="username">Password:<br>
                            <input type="password" id="password-register" name="password">
                    </div>
                    <p id="register_dis">
                        <?php
                        if (isset($_POST["register"])) {
                            include("database.php"); // This is for starting the database

                            $_SESSION["username-store"] = strtoupper(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS));
                            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

                            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

                            // SQL query
                            $sql_query = "INSERT INTO user_info (username_info, password_info) VALUES (?, ?)";

                            // Use prepared statements to prevent SQL injection
                            $stmt = mysqli_prepare($conn, $sql_query);
                            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["username-store"], $hashed_pass);

                            if (mysqli_stmt_execute($stmt)) {
                                echo "Registration successful!";
                                header("Location: albums.php"); // Redirect after registration
                                exit();
                            } else {
                                echo "Register Failed, Try Again";
                            }

                            mysqli_close($conn);
                        }
                        ?>
                    </p>
                    <button type="submit" id="register" name="register">REGISTER</button>
                </form>
            </div>
        </div>
        <p class="acknowledge">Made By: <i class="fa-brands fa-github"></i> <a href="https://github.com/kindadailybren"> kindadailybren</a> </p>
    </div>
</body>

</html>