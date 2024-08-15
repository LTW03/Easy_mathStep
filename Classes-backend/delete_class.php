<?php
include('../database/connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];

    // Sanitize the class_id
    $class_id = $conn->real_escape_string($class_id);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare and execute the SQL delete queries
        $sql1 = "DELETE FROM class WHERE class_id = '$class_id'";
        $sql2 = "DELETE FROM assigned WHERE class_id = '$class_id'";
        $sql3 = "DELETE FROM student WHERE class_id = '$class_id'";

        // Execute the queries
        $conn->query($sql1);
        $conn->query($sql2);
        $conn->query($sql3);

        // Commit the transaction
        $conn->commit();
        $message = "Class deleted successfully.";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        $message = "Error deleting class: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    $message = "Class ID not provided.";
}

// Redirect back to the main page with a message
header("Location: ../classes_page.php?message=" . urlencode($message));
exit();
?>
