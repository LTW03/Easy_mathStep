<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sdp_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$score = $data['score'];
$incorrectQuestions = $data['incorrectQuestions'];

session_start();
$_SESSION['score'] = $score;
$_SESSION['incorrectQuestions'] = $incorrectQuestions;

$conn->close();
?>
