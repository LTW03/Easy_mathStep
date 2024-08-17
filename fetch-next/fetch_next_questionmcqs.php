<?php
header('Content-Type: application/json');

include('./database/connection.php');

// Get the current question ID from the request
$data = json_decode(file_get_contents("php://input"), true);
$currentQuestionId = $data['currentQuestionId'];
$lesson_id = $data['lessonId'];

$question_sql = "SELECT q.question_id, q.question_text, q.question_audio 
                 FROM question q 
                 WHERE q.lesson_id = ? 
                 AND q.question_id > ? 
                 ORDER BY q.question_id ";

$stmt = $conn->prepare($question_sql);
$stmt->bind_param("ii", $lesson_id, $currentQuestionId);
$stmt->execute();
$question_result = $stmt->get_result();

$response = [
    'question_text' => 'No more questions',
    'question_id' => null,
    'options' => [],
    'question_audio' => null
];

if ($question_result->num_rows > 0) {
    $question_row = $question_result->fetch_assoc();
    $nextQuestionId = $question_row['question_id'];
    $questionText = $question_row['question_text'];
    $questionAudio = $question_row['question_audio'];
    
    // Fetch options for the next question
    $answers_sql = "SELECT answer_text, is_correct 
                    FROM mcq_answer 
                    WHERE question_id = ?";
                    
    $stmt = $conn->prepare($answers_sql);
    $stmt->bind_param("i", $nextQuestionId);
    $stmt->execute();
    $answers_result = $stmt->get_result();

    while ($answer_row = $answers_result->fetch_assoc()) {
        $response['options'][] = [
            'answer_text' => $answer_row['answer_text'],
            'is_correct' => $answer_row['is_correct']
        ];
    }

    $response['question_text'] = $questionText;
    $response['question_id'] = $nextQuestionId;
    $response['question_audio'] = $questionAudio; 
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
