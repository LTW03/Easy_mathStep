<?php
include('./database/connection.php');

$student_email = isset($_COOKIE['student_email']) ? filter_var($_COOKIE['student_email'], FILTER_SANITIZE_EMAIL) : '';

if ($student_email) {
    $update_stmt = $conn->prepare("UPDATE student SET student_stat = 0 WHERE student_email = ?");
    $update_stmt->bind_param("s", $student_email);

    if ($update_stmt->execute()) {
        // Clear the cookie
        setcookie('student_email', '', time() - (86400 * 30), '/'); 
        setcookie('lesson_id', '', time() - (86400 * 30), '/'); 
        // Redirect
        header('Location: Choose_classes.php');
        exit();
    } else {
        http_response_code(500); 
        echo 'Error updating student status.';
    }

    $update_stmt->close();
} else {
    http_response_code(400); 
    echo 'No student email found.';
}

$conn->close();
?>
