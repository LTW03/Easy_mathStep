<?php
include('../../database/connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$optionTarget_dir = "../../src/option_audio/";
$QuestionTarget_dir = "../../src/question_audio/";
$EncouragementTarget_dir = "../../src/encouragement_source/";

$lesson_id = $_POST['lesson_id'] ?? '';
$question_ids = $_POST['question_id'] ?? [];
$question_texts = $_POST['question_text'] ?? [];
$option_ids = $_POST['option_id'] ?? [];
$word_ids = $_POST['word_id'] ?? [];
$answer_texts = $_POST['answer_text'] ?? [];
$correct_answers = json_decode($_POST['correctAnswers'] ?? '{}', true);
$encouragement_texts = $_POST['encouragement_text'] ?? [];
$is_encouragements = $_POST['is_encouragement'] ?? [];
$question_images = $_FILES['question_image']['name'] ?? [];
$question_audios = $_FILES['question_audio']['name'] ?? [];
$option_audios = $_FILES['answer_audio']['name'] ?? [];

// Initialize the index for option
$option_index = 0;

foreach ($question_ids as $q_index => $question_id) {
    $question_text = $question_texts[$q_index] ?? '';
    $encouragement_text = $encouragement_texts[$q_index] ?? '';
    $is_encouragement = isset($is_encouragements[$q_index]) && $is_encouragements[$q_index] == 'on' ? '1' : '0';
    $word_id = $word_ids[$q_index] ?? '';

    // Update the question text
    $stmt = $conn->prepare("UPDATE question SET question_text = ? WHERE question_id = ?");
    $stmt->bind_param("si", $question_text, $question_id);
    $stmt->execute();

    // Update the question image if uploaded
    if (isset($question_images[$q_index]) && $question_images[$q_index] != '') {
        $target_file = $EncouragementTarget_dir . basename($_FILES['question_image']['name'][$q_index]);
        move_uploaded_file($_FILES['question_image']['tmp_name'][$q_index], $target_file);
        $image_path = str_replace('../../', '', $target_file); // Remove ../../

        $stmt = $conn->prepare("UPDATE words SET img_path = ? WHERE word_id = ?");
        $stmt->bind_param("si", $image_path, $word_id);
        $stmt->execute();
    }

    // Update the question audio if uploaded
    if (isset($question_audios[$q_index]) && $question_audios[$q_index] != '') {
        $target_file = $QuestionTarget_dir . basename($_FILES['question_audio']['name'][$q_index]);
        move_uploaded_file($_FILES['question_audio']['tmp_name'][$q_index], $target_file);
        $audio_path = str_replace('../../', '', $target_file); // Remove ../../

        $stmt = $conn->prepare("UPDATE question SET question_audio = ? WHERE question_id = ?");
        $stmt->bind_param("si", $audio_path, $question_id);
        $stmt->execute();
    }

    // Update options
    for ($i = 0; $i < 4; $i++) {
        if (isset($option_ids[$option_index])) {
            $option_id = $option_ids[$option_index];
            $answer_text = $answer_texts[$option_index] ?? 'No answer';
            $correct_answer = ($correct_answers["correctAnswer[$question_id]"] ?? '') == $option_id ? '1' : '0';

            // Update the answer text and correct answer
            $stmt = $conn->prepare("UPDATE mcq_answer SET answer_text = ?, is_correct = ? WHERE mcq_answer_id = ?");
            $stmt->bind_param("sii", $answer_text, $correct_answer, $option_id);
            $stmt->execute();

            // Update the option audio if uploaded
            if (isset($option_audios[$option_index]) && $option_audios[$option_index] != '') {
                $target_file = $optionTarget_dir . basename($_FILES['answer_audio']['name'][$option_index]);
                move_uploaded_file($_FILES['answer_audio']['tmp_name'][$option_index], $target_file);
                $audio_path = str_replace('../../', '', $target_file); // Remove ../../

                $stmt = $conn->prepare("UPDATE mcq_answer SET mcq_audio = ? WHERE mcq_answer_id = ?");
                $stmt->bind_param("si", $audio_path, $option_id);
                $stmt->execute();
            }

            $option_index++;
        }
    }

    // Update encouragement text if needed
    $stmt = $conn->prepare("UPDATE words SET word_text = ?, is_encouragement = ? WHERE word_id = ?");
    $stmt->bind_param("sii", $encouragement_text, $is_encouragement, $word_id);
    $stmt->execute();
}

echo "Form data processed successfully!";
header("Location: ../../library_page.php");
?>
