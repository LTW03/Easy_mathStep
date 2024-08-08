<?php
include('../database/connection.php');

if(isset($_POST['email'])) {
    $email = $_POST['email'];
    $class_id = $_COOKIE['class_cookie'];
    
    $stmt = $conn->prepare("DELETE FROM student WHERE student_email = ?");
    $stmt->bind_param("s", $email);

    $update_stmt = $conn->prepare("UPDATE class SET student_amount = student_amount - 1 WHERE class_id = ?");
    $update_stmt->bind_param("i", $class_id);
    
    if($stmt->execute() && $update_stmt->execute()) {
        echo '<script> alert("Student kicked out successfully") </script>';
    } else {
        echo "Error deleting student: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>