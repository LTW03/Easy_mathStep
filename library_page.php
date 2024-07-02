<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css_folder/teacher_base.css">
    <link rel="stylesheet" href="Css_folder/library_page.css">
    <!-- <link rel="stylesheet" href="Css_folder/general.css"> -->

      <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

      <script defer src="JS_Folder/teacher_base.js"></script> 
    <title>Library | E.M.S</title>
</head>
<body>
    <?php
    include('teacher_base.html')
    ?>

    <div class= "quizgame_wapper"> <!--item container-->
      <div class="quiz-container">
            <div class="quiz-detail">
              <div class="img">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="text">
                <h2> Addition </h2>
                <span>True and False</span>
              </div>
            </div>
            <div class="edit_info">
              <span>1 days ago</span> <!--posted date-->
              <div>
                <a href="">
                    <button class="Btn">
                        Edit
                        <svg class="svg" viewBox="0 0 512 512">
                            <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                        </svg>
                    </button>
                </a>
            </div>
            </div>
        </div>

    </div>
    
</body>
</html>
