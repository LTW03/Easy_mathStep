<?php
include('./database/connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];

    // Sanitize the lesson_id
    $lesson_id = $conn->real_escape_string($lesson_id);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch the question_type from the lesson table
        $lesson_type_query = "SELECT question_type FROM lesson WHERE lesson_id = ?";
        $stmt = $conn->prepare($lesson_type_query);
        $stmt->bind_param('i', $lesson_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $question_type = $row['question_type'];

            // Fetch all question IDs related to this lesson_id
            $fetch_questions_query = "SELECT question_id FROM question WHERE lesson_id = ?";
            $stmt = $conn->prepare($fetch_questions_query);
            $stmt->bind_param('i', $lesson_id);
            $stmt->execute();
            $questions_result = $stmt->get_result();
            
            $question_ids = [];
            while ($question_row = $questions_result->fetch_assoc()) {
                $question_ids[] = $question_row['question_id'];
            }

            // Define the queries based on the question_type
            switch ($question_type) {
                case 'MCQ':
                    $queries = [
                        "DELETE FROM mcq_answer WHERE question_id IN (" . implode(',', $question_ids) . ")",
                        "DELETE FROM words WHERE question_id IN (" . implode(',', $question_ids) . ")"
                    ];
                    break;

                case 'TF':
                    $queries = [
                        "DELETE FROM true_false_options WHERE question_id IN (" . implode(',', $question_ids) . ")",
                        "DELETE FROM words WHERE question_id IN (" . implode(',', $question_ids) . ")"
                    ];
                    break;

                case 'DragDrop':
                    $queries = [
                        "DELETE FROM draggable_options WHERE question_id IN (" . implode(',', $question_ids) . ")",
                        "DELETE FROM words WHERE question_id IN (" . implode(',', $question_ids) . ")"
                    ];
                    break;

                default:
                    throw new Exception("Unknown question type: " . $question_type);
            }

            // Execute all delete queries for options and words
            foreach ($queries as $query) {
                if (!$conn->query($query)) {
                    throw new Exception("Error executing query: " . $conn->error);
                }
            }

            // Also delete questions
            $delete_questions_query = "DELETE FROM question WHERE lesson_id = ?";
            $stmt = $conn->prepare($delete_questions_query);
            $stmt->bind_param('i', $lesson_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting questions: " . $conn->error);
            }

            // Delete from the main Lesson table
            $delete_lesson_query = "DELETE FROM lesson WHERE lesson_id = ?";
            $stmt = $conn->prepare($delete_lesson_query);
            $stmt->bind_param('i', $lesson_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting lesson: " . $conn->error);
            }

            // Delete assigned records
            $delete_assigned_query = "DELETE FROM assigned WHERE lesson_id = ?";
            $stmt = $conn->prepare($delete_assigned_query);
            $stmt->bind_param('i', $lesson_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting assigned records: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();
            $message = "Lesson deleted successfully.";
        } else {
            throw new Exception("Lesson not found.");
        }

    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        $message = "Error deleting lesson: " . $e->getMessage();
    }

    // Close the connection
    $conn->close();
} else {
    $message = "Lesson ID not provided.";
}

// Redirect back to the main page with a message
header("Location: ../library_page.php?message=" . urlencode($message));
exit();
?>
