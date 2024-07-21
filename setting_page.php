<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting Page | E.M.S</title>
    <link rel="stylesheet" href="./Css_folder/teacher_base.css">
    <link rel="stylesheet" href="./Css_folder/SettingPage.css">



    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<script defer src="JS_Folder/teacher_base.js"></script> 
</head>
<body>
    <?php 
        include('teacher_base.php'); 
    ?>

<div class="setting_container">
        <h2>Settings</h2>
        <form action="settingUpdate_profile.php" method="post">
            <div class="form_setting">
                <h4>Account Name</h4>
            </div>
            <div class="form_setting">
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form_setting">
                <label for="phone">Phone Number <span class="optional">(Optional)</span></label>
                <input type="tel" id="phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <form style="margin-top: 20px;" action="settingUpdate_pass.php" method="post">
            <div class="form_setting">
                <label for="password">Old Password</label>
                <input id="password" name="password">
            </div>

            <div class="form_setting">
                <label for="new_password">New Password</label>
                <input id="new_password" name="new_password" onkeyup="validatePassword()">
            </div>

            <div class="form_setting">
                <label for="re_password">Re-enter Password</label>
                <input id="re_password" name="re_password">
            </div>

            <ul class="password_rules">
                <li id="char_rule" class="invalid">* New password must contain 6 characters or more</li>
                <li id="upper_rule" class="invalid">* Password must contain an upper case character</li>
                <li id="special_rule" class="invalid">* Password must contain a special character</li>
            </ul>
            <button type="submit" class="btn btn-primary" id="updatePasswordBtn" disabled>Update Password</button>
        </form>

        <button class="btn btn-danger">Remove Account</button>
    </div>
    
    <script src="./JS_Folder/setting.js"></script>
</body>
</html>