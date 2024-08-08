<?php
include('../database/connection.php');

if (isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];

    // Sanitize the class_id
    $class_id = $conn->real_escape_string($class_id);

    // Prepare the SQL delete query
    $sql = "DELETE FROM class WHERE class_id = '$class_id'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $message = "Class deleted successfully.";
    } else {
        $message = "Error deleting class: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    $message = "Class ID not provided.";
}

// Redirect back to the main page with a message
header("Location: ../classes_page.php?message=" . urlencode($message));
// header("Location: classes_page.php?");
exit();
?>