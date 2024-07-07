<?php

include("./database/connection.php");

if(isset($_POST['_login'])){
    $login_email = $conn->real_escape_string($_POST['user_email']);
    $login_password = $conn->real_escape_string($_POST['user_password']);

    $query = "SELECT * FROM teacher WHERE teacher_email = '$login_email' and password = '$login_password'";
    $result = $conn->query($query);

    if($result-> num_rows == 1){
        //valid login, start session
        $row = $result->fetch_assoc();
        $user_email = $row['teacher_email'];
        $user_name = $row['teacher_name'];
        //set cookie for 30 days
        setcookie("user_email", $login_email, time() + (86400 * 30), "/"); // 86400 = 1 day
        echo "<script type = 'text/javascript'> alert('Login Succesfull'); document.location = './library_page.php' </script>";
    }else{
        echo "<script type = 'text/javascript'> alert('Invalid Username or Password!'); document.location = './Login_page.php' </script>";
    }
}
$conn->close();


