<?php
include('Teacher_loginValidate.php')
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create True/False | E.M.S</title>
    <link rel="stylesheet" href="./Css_folder/Create_TFPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
    <div class="header">
        <div class="back_btn">
            <a href="#" id = "back_btn">
                <button class="backBtn_icon">
                    <i class="fas fa-chevron-left"></i>
                </button>
                back
            </a>
        </div>
        <div class= "rightside">
            <div class="help_btn">
                <button id="helpBtn">
                    <i class="fas fa-question-circle"></i>
                </button>
            </div>

            <button class="Publish_Btn" onclick="saveQuiz(event)">
                Publish
                <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path d="M568.5 177.4L424.5 313.4C409.3 327.8 384 317.1 384 296v-72c-144.6 1-205.6 35.1-164.8 171.4 4.5 15-12.8 26.6-25 17.3C155.3 383.1 120 326.5 120 269.3c0-143.9 117.6-172.5 264-173.3V24c0-21.2 25.3-31.8 40.5-17.4l144 136c10 9.5 10 25.4 0 34.9zM384 379.1V448H64V128h50.9a12 12 0 0 0 8.6-3.7c15-15.6 32.2-27.9 51-37.7C185.7 80.8 181.6 64 169 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-88.8c0-8.3-8.2-14.1-16-11.3a71.8 71.8 0 0 1-34.2 3.4c-7.3-1-13.8 4.5-13.8 11.9z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="centralize_form">
        <div class="form_container">
            <h1>Create a Quiz</h1>
            <form id="quizForm" action="Create_TF_SavingProcess.php" method="POST" enctype="multipart/form-data">
                <label for="quizName">Quiz/Lesson Name:</label>
                <input type="text" id="quizName" name="quizName" required><br><br>

                <div id="questionsContainer">
                    <!-- dynamically question input -->
                </div>
                
                <div class="exercise_buttons">
                    <button type="button" onclick="addQuestion()">Add Question</button>
                </div>
            </form>
        </div>
    </div>

    <div id="helpModal" class="help_modal">
        <div class="help_modal_content">
            <span class="help_modal_close">&times;</span>
            <video controls>
                <source src="src/User_guideVideo/Ture and False .mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <script src="./JS_Folder/Create_TFpage.js"></script>
</body>
</html>
