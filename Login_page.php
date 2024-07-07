<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login | E.M.S</title>
    <link rel="stylesheet" href="./Css_folder/login_page.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
    <div class="container-main-grid">
        <div class="register-switch-container">
            <div class="shape-purple-top"></div>
            <div class="shape-purple-bottom"></div>
            <div class="register-button">
                <p class="dont-have-acc">Dont have an Account?</p>
                <button class="register-btn" type="button" onclick="window.location.href='./Register_page.php'">Register</button>
            </div>
        </div>


        
        <div class="wrapper">
            <form action="login_process.php" method="POST">
                <h1>Login</h1>
                <div class="input-box">
                    <input name="user_email" type="text" placeholder="Email" required>
                    <i class='bx bxl-gmail'></i>
                </div>
                <div class="input-box">
                    <input name="user_password" type="password" placeholder="Password" required>
                    <i id="togglePassword" class='bx bx-show'></i>
                </div>
                <div class="remember-me-forgot">
                    <label> <input type="checkbox">Remember Me</label>
                    <a href="#">Forgot password?</a>
                </div>
                <button name="_login" type="submit" class="btn">Login</button>
                <div class="register-link">
                    <p>Dont Have an Account?<a href="./Register_page.php">Register!</a></p>
                    
                </div>
            </form>
        </div>
        <img class="rocket-pic" id="rocketPic" src="./src/login_register_page/image 15.png" width="1000" height="400">
    </div>
    <script src="./JS_Folder/Login_page.js"></script>
</body>






</html>