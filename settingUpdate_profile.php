<?php
include('database/connection.php');

$teacher_email = $_COOKIE['user_email'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $stmt = $conn->prepare("UPDATE teacher SET teacher_name = '$username', teacher_number = '$phone' WHERE teacher_email = '$teacher_email'");

    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully'); window.location.href = 'setting_page.php';</script>";
        exit();
        
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>


