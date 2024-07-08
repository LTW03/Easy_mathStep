<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sdp_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quizType = $_POST['quizType'];
$questionText = $_POST['questionText'];

$sql = "INSERT INTO question (question_text, question_type) VALUES ('$questionText', '$quizType')";
if ($conn->query($sql) === TRUE) {
    $question_id = $conn->insert_id;
    
    if ($quizType == 'MCQ') {
        $options = $_POST['mcqOption'];
        $correctOption = $_POST['mcqCorrect'];
        
        foreach ($options as $index => $option) {
            $is_correct = ($index + 1 == $correctOption) ? 1 : 0;
            $sql = "INSERT INTO mcq_answer (question_id, mcq_audio, is_correct) VALUES ('$question_id', '$option', '$is_correct')";
            $conn->query($sql);
        }
    } elseif ($quizType == 'TF') {
        $is_true = ($_POST['tfOption'] == 'true') ? 1 : 0;
        $sql = "INSERT INTO true_false_options (question_id, is_true) VALUES ('$question_id', '$is_true')";
        $conn->query($sql);
    } elseif ($quizType == 'DragDrop') {
        $dragItem = $_POST['dragItem'];
        $dropTarget = $_POST['dropTarget'];
        
        $sql1 = "INSERT INTO draggable_options (drag_option_text) VALUES ('$dragItem')";
        $conn->query($sql1);
        $drag_option_id = $conn->insert_id;

        $sql2 = "INSERT INTO droppable (droppable_text) VALUES ('$dropTarget')";
        $conn->query($sql2);
        $droppable_id = $conn->insert_id;
        
        $sql3 = "INSERT INTO dragdropmapping (question_id, draggable_item, droppable_target) VALUES ('$question_id', '$drag_option_id', '$droppable_id')";
        $conn->query($sql3);
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

echo "Question saved successfully!";
$conn->close();
?>
