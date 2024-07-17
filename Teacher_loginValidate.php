<?php
include('database/connection.php');
$user_email = $_COOKIE['user_email'];

if ($user_email == NULL ){
    header("Location: ./Login_page.php");
    exit();
}

$username_query = "SELECT * FROM teacher WHERE teacher_email = '$user_email'";
$result = $conn->query($username_query);
$row = $result->fetch_assoc();
$teacherName = $row['teacher_name']
?>