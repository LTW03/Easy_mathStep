<?php
include('./database/connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$score = $data['score'];
$incorrectQuestions = $data['incorrectQuestions'];

session_start();
$_SESSION['score'] = $score;
$_SESSION['incorrectQuestions'] = $incorrectQuestions;

$conn->close();
?>
