<?php
header('Content-Type: application/json');

include('../database/connection.php');

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $new_class_name = $_POST['class_name'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE class SET class_name = ? WHERE class_id = ?");
    $stmt->bind_param("si", $new_class_name, $class_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Class name updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating class name: ' . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>