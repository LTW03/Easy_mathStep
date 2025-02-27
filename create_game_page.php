<?php
include('Teacher_loginValidate.php')
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Css_folder/create_game_page.css">
    <link rel="short icon" type= "x-icon" href="src/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <title>Create Game | E.M.S</title>
</head>
<body>
    <div class="header">
        <div class="back_btn">
            <a href="./library_page.php">
                <button class="backBtn_icon">
                    <i class="fas fa-chevron-left"></i>
                </button>
                back
            </a>
        </div>
    </div>

    <div class="centralize_form">
        <div class="form_container">
            <h1>Create a new exercise</h1>
            <input type="text" placeholder="Enter type of exercise">
            <div class="exercise_buttons">
                <a href="./Create_TF_Page.php">
                    <div class="btn">
                        <i class="fas fa-check-square"></i>
                        True/False
                    </div>
                </a>
                <a href="./Create_DG_Page.php">
                    <div class="btn">
                        <i class="fas fa-arrows-alt"></i>
                        Drag and Drop
                    </div>
                </a>
                <a href="./Create_mcqPage.php">
                    <div class="btn">
                        <i class="fas fa-list-ul"></i>
                        Multiple Choice
                    </div>
                </a>

            </div>
        </div>
    </div>
</body>
</html>
