<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Register | E.M.S</title>
    <link rel="short icon" type= "x-icon" href="src/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./Css_folder/register.css">
    <script src="./JS_Folder/Login_page.js"></script>
</head>
<body>
    
    <div class="container-main-grid">
        <div class="register-switch-container">
            <div class="shape-purple-top"></div>
            <div class="shape-purple-bottom"></div>
            <div class="register-button">
                <p class="dont-have-acc">Already have an Account?</p>
                <button class="register-btn" type="button" onclick="window.location.href=('./Login_page.php')">Login</button>
            </div>
        </div>
        
        <div class="wrapper">
        
            <form id="registerForm" action="register_process.php" method="POST">
                <h1>Register</h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required >
                    <i class='bx bx-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="phone-number" placeholder="Mobile Number" required >
                    <i class='bx bx-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="email" placeholder="Email" required >
                    <i class='bx bxl-gmail'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Password" required >
                    <i class='bx bx-show' onclick="togglePasswordVisibility('password', this)"></i>
                </div>
                <div class="input-box">
                    <input type="password" id="repeat-password" name="repeat-password" placeholder="Re-enter Password" required >
                    <i class='bx bx-show' onclick="togglePasswordVisibility('repeat-password', this)"></i>
                </div>
                <button type="submit" class="btn" value="Register" name="submit">Register</button>
                <div class="register-link">
                    <p>Already have an Account?<a href="./Login_page.php">Login!</a></p>
                </div>
            </form>
        </div>
        <?php
        if (isset($_SESSION['errors'])) {
            echo "<div class='alert-container'>";
            foreach ($_SESSION['errors'] as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
            echo "</div>";
            unset($_SESSION['errors']); // Clear errors after displaying
        }
        ?>
        <img class="rocket-pic" src="./src/login_register_page/image 15.png" width="1000" height="400">
    </div>

    <script>
        function togglePasswordVisibility(passwordId, icon) {
            const passwordField = document.getElementById(passwordId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.replace('bx-show', 'bx-hide');
            } else {
                passwordField.type = "password";
                icon.classList.replace('bx-hide', 'bx-show');
            }
        }
    </script>

</body>
</html>
