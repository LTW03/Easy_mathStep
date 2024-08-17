<?php
include('database/connection.php');

if(isset($_GET['student_id']) && isset($_GET['lesson_id'])) {
    $student_email = $_GET['student_id'];
    $lesson_id = $_GET['lesson_id'];

    // Update the student_stat to 1
    $sql = "UPDATE student SET student_stat = 1 WHERE student_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_email);
    $stmt->execute();

    // Set a cookie for the student
    setcookie("student_email", $student_email, time() + (86400 * 30), "/"); 
    setcookie("lesson_id", $lesson_id, time() + (86400 * 30), "/"); 

    // Redirect to the appropriate quiz page
    if(isset($_GET['quizpath'])) {
        $quizpath = $_GET['quizpath'];
        header("Location: $quizpath?class_id=" . $_GET['class_id'] . "&lesson_id=" . $lesson_id);
    } else {
        header("Location: Choose_Student.php");
    }
    exit();
} else {
    echo "Invalid student or lesson.";
}
?>
