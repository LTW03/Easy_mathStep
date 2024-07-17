<?php
include('conn.php');
$user_email = $_COOKIE['user_email'];

if ($user_email == NULL ){
    header("Location: ./Login_page.php");
    exit();
}