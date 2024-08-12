<?php
include('../database/connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize response array
$response = array('success' => false, 'message' => '');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : 0;
    $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;

    // Validate data
    if (empty($class_id) || empty($lesson_id)) {
        $response['message'] = "Class ID or Lesson ID is missing.";
    } else {
        // Check if an assignment already exists
        $sql_check = "SELECT * FROM assigned WHERE class_id = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing assignment
            $sql_update = "UPDATE assigned SET lesson_id = ? WHERE class_id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ii", $lesson_id, $class_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Lesson assignment updated successfully.";
            } else {
                $response['message'] = "Error updating lesson assignment: " . $conn->error;
            }
        } else {
            // Insert new assignment
            $sql_insert = "INSERT INTO assigned (class_id, lesson_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("ii", $class_id, $lesson_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Lesson assigned successfully.";
            } else {
                $response['message'] = "Error assigning lesson: " . $conn->error;
            }
        }

        $stmt->close();
    }

    $conn->close();
} else {
    $response['message'] = "Invalid request method.";
}
echo "<script type='text/javascript'> alert(" . json_encode($response['message']) . ");
    window.location.href = '../classes_page.php';
</script>";
exit();
?>
