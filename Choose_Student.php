<?php
include('database/connection.php');

$class_id = $_GET['class_id'];

// Fetch class data with their associated color
$sql = "SELECT s.student_email, s.student_fname, s.student_lname, c.character_path,
            l.lesson_name, l.question_type, l.lesson_id
        FROM student s
        LEFT JOIN `character` c ON s.character_id = c.character_id
        LEFT JOIN class cl ON s.class_id = cl.class_id
        LEFT JOIN assigned a ON cl.class_id = a.class_id
        LEFT JOIN lesson l ON a.lesson_id = l.lesson_id
        WHERE cl.class_id = $class_id AND s.student_stat != 1;";

$student = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Student | E.M.S</title>
    <link rel="short icon" type= "x-icon" href="src/logo.png">
    <link rel="stylesheet" href="./Css_folder/Choose_student.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
    <header>
        <img src="./src/logo.png" width="60" class="logo-icon">
        <span class="logo-title">EasyMathStep</span>

        <div class="back_btn">
            <a href="./Choose_classes.php">
                <button class="backBtn_icon">
                    <i class="fas fa-chevron-left"></i>
                </button>
                back
            </a>
        </div>
    </header>
    
    <div class="title">
        <h1 class="title-text">Choose Your Name</h1>
    </div>
    <div class="std-name-card">
        <?php
        if ($student->num_rows > 0) {
            while($row = $student->fetch_assoc()) {
                $lesson_id = $row['lesson_id']; // Retrieve lesson_id
                $question_type = $row['question_type'];
                
                if($question_type == "MCQ"){
                    $quizpath = 'MCQ_quiz.php'; //MCQ page path
                } elseif($question_type == 'TF'){
                    $quizpath = 'TrueFalse_quiz.php'; //True/False page path
                } elseif($question_type == 'DragDrop'){
                    $quizpath = 'drag_dropQuiz.php'; //Drag and Drop path
                } else {
                    echo "<script type = 'text/javascript'> alert('No class assigned yet'); document.location = './Choose_classes.php' </script>";
                }
                
                // Pass lesson_id and quizpath in the URL
                echo '<a href="process_student.php?class_id=' . $class_id . '&student_id=' . urlencode($row["student_email"]) . '&lesson_id=' . $lesson_id . '&quizpath=' . urlencode($quizpath) . '" class="namecard-link">';
                echo    '<div class="namecard">';
                echo        '<img src="' . $row["character_path"] . '" alt="animal" width="75">';
                echo        '<div class="std-name">' . htmlspecialchars($row["student_fname"]) . ' ' . htmlspecialchars($row["student_lname"]) . '</div>';
                echo    '</div>';
                echo '</a>';
            }
        } else {
            echo "No student found.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
