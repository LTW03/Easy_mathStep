<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./Css_folder/forget_pass.css">
    <script src="https://smtpjs.com/v3/smtp.js"></script>
</head>
<body>
    <div class="wrapper">
        <form id="forgot-password-form" action="forget_pass_emalVerify.php" method="POST">
            <h1>Forgot Password?</h1>
            <div class="input-box">
                <input name="user_email" id="email-otp" type="email" placeholder="Enter Email" required>
                <i class='bx bxl-gmail'></i>
            </div>
            <button name="otp-btn" type="submit" class="sendOtpbtn">Send OTP</button>

            <div class="register-link">
                <p>Return to login page? <a href="Login_page.php">Return</a></p>
            </div>
        </form>
        
        <div id="otp-form" style="display: none;">
            <h1>Verify OTP</h1>
            <div class="otpVerify">
                <input class="otp-inp-field" id="otp_inp" type="number" placeholder="Enter OTP" required>
            </div>
            <button  type="button" class="verifyOTPbutton" onclick="verifyOtp()">Verify OTP</button>
            <button type="button" class="resend-btn" onclick="resendOTP()">Resend OTP</button>
            <div class="register-link">
                <p>Return to login page? <a href="Login_page.php">Return</a></p>
            </div>
        </div>
        <div id="update-password-form" style="display: none;" >
            <h1>Update Password</h1>
            <div class="input-box">
                <input id="new-password" type="password" placeholder="Enter New Password" required>
            </div>
            <button type="button" class="updatePassBtn" onclick="updatePassword()">Update Password</button>
            
        </div>
    </div>
    <img class="rocket-pic" id="rocketPic" src="./src/forgetpass/forgot-removebg.png" width="1000" height="400">
    <script src="./JS_Folder/forget_pass.js"></script>
</body>
</html>
