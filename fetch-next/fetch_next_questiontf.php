<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sdp_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_question_id = $_GET['question_id'];
$lesson_id = $_GET['lesson_id'];

$response = [];

$sql = "SELECT q.question_id, q.question_text, tfo.is_true, q.question_audio 
        FROM question q 
        JOIN true_false_options tfo ON q.question_id = tfo.question_id 
        WHERE q.lesson_id = ? AND q.question_id > ? 
        ORDER BY q.question_id ASC 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $lesson_id, $current_question_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['question_text'] = $row['question_text'];
    $response['next_question_id'] = $row['question_id'];
    $response['is_true'] = $row['is_true'];
    $response['question_audio'] = $row['question_audio'];
} else {
    $response['question_text'] = "No more questions";
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
