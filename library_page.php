<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Css_folder/teacher_base.css">
    <link rel="stylesheet" href="./Css_folder/library_page.css">
    <link rel="short icon" type= "x-icon" href="src/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script defer src="JS_Folder/teacher_base.js"></script> 
    <title>Library | E.M.S</title>
    <style>
        /* Modal Styles */
        .edit_pop {
            display: none; 
            position: fixed; 
            left: 0; 
            top: 80px; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            z-index: 1000;
        }

        .edit_pop-content {
            background-color: #fff;
            margin: 13% auto; 
            padding: 40px; 
            border: 1px solid #ccc; 
            width: 500px; 
            text-align: center;
            border-radius: 8px;
        }

        .edit_pop-content p {
            margin-bottom: 20px;
            font-size: 25px;
            color: #333;
        }

        .edit_pop-content button {
            background-color: #0056b1;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }

        .edit_pop-content button:hover {
            background-color: #2079d7;
        }

        .edit_pop-content #edit_add-btn {
            background-color: #0056b1;
        }

        .edit_pop-content #edit_add-btn:hover {
            background-color: #2079d7;
        }

        .edit_pop-content #edit_delete-btn {
            background-color: #A00000;
        }

        .edit_pop-content #edit_delete-btn:hover {
            background-color: #cc1a1a;
        }

        .edit_pop-content #edit_update-btn {
            background-color: #004080;
        }

        .edit_pop-content #edit_update-btn:hover {
            background-color: #003366;
        }

        .edit_pop .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .edit_pop .close:hover,
        .edit_pop .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    include('teacher_base.php');

    $sql = "SELECT lesson_id, lesson_name, question_type, date_changes FROM lesson WHERE teacher_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $lesson_id = htmlspecialchars($row['lesson_id']);
        $lesson_name = htmlspecialchars($row['lesson_name']);
        $question_type = htmlspecialchars($row['question_type']);
        $date_changes = htmlspecialchars($row['date_changes']);

        $current_date = new DateTime();
        $date_change = new DateTime($date_changes);
        $interval = $current_date->diff($date_change);
        $days_ago = $interval->days . " days ago";

        $icons = [
            'MCQ' => "<i class='fas fa-question-circle'></i>",
            'DragDrop' => "<i class='far fa-hand-lizard'></i>",
            'TF' => "<i class='fas fa-check-circle'></i>"
        ];

        $edit_pages = [
            'MCQ' => [
                'add' => "./edit_lesson/add_mcq_page.php?lesson_id=$lesson_id",
                'update' => "./edit_lesson/update_mcq.php?lesson_id=$lesson_id",
            ],
            'DragDrop' => [
                'add' => "./edit_lesson/add_dragdrop_page.php?lesson_id=$lesson_id",
                'update' => "./edit_lesson/update_dragdrop.php?lesson_id=$lesson_id",
            ],
            'TF' => [
                'add' => "./edit_lesson/add_TF_page.php?lesson_id=$lesson_id",
                'update' => "./edit_lesson/update_TF.php?lesson_id=$lesson_id",
            ]
        ];

        $icon = $icons[$question_type] ?? '';
        $edit_pages_for_type = $edit_pages[$question_type] ?? [];

        echo "
        <div class='quizgame_wapper'> <!--item container-->
            <div class='quiz-container'>
                <div class='quiz-detail'>
                    <div class='img'>
                        $icon
                    </div>
                    <div class='text'>
                        <h2>$lesson_name</h2>
                        <span>$question_type</span>
                    </div>
                </div>
                <div class='edit_info'>
                    <span>$days_ago</span> <!--posted date-->
                    <div>
                        <button class='Btn' onclick='openModal(\"$lesson_id\", \"$question_type\")'>
                            Edit
                            <svg class='svg' viewBox='0 0 512 512'>
                                <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        ";
    }

    $stmt->close();
    $conn->close();
    ?>

    <!-- Modal Structure -->
    <div id="editPop" class="edit_pop">
        <div class="edit_pop-content">
            <span class="close">&times;</span>
            <p>Choose an action for the selected question:</p>
            <div>
                <button id="edit_add-btn" onclick="handleAction('add')">Add Question</button>
                <button id="edit_delete-btn" onclick="handleAction('delete')">Delete</button>
                <button id="edit_update-btn" onclick="handleAction('update')">Update Question</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("editPop");
        var currentLessonId = '';
        var currentQuestionType = '';

        function openModal(lessonId, questionType) {
            currentLessonId = lessonId;
            currentQuestionType = questionType;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        document.querySelector(".edit_pop .close").onclick = closeModal;

        function handleAction(action) {
            var url = '';
            switch (action) {
                case 'add':
                    url = `./edit_lesson/${currentQuestionType}/add_${currentQuestionType.toLowerCase()}_page.php?lesson_id=${currentLessonId}`;
                    break;
                case 'delete':
                    url = `./Delete_Lesson.php/?lesson_id=${currentLessonId}`;
                    break;
                case 'update':
                    url = `./edit_lesson/${currentQuestionType}/update_${currentQuestionType.toLowerCase()}.php?lesson_id=${currentLessonId}`;
                    break;
            }
            if (url) {
                window.location.href = url;
            }
        }
    </script>
</body>
</html>
