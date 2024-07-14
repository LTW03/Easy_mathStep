<?php
header('Content-Type: application/json');

include('database/connection.php');
// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$newPassword = $data['new_password']; // Ensure this is passed in the correct request

// Check if email exists
$sql = "SELECT * FROM teacher WHERE teacher_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email exists, update the password
    $updateSql = "UPDATE teacher SET password = ? WHERE teacher_email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $newPassword, $email);

    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Password updated successfully"]);
    } else {
        echo json_encode(['success' => false, 'error' => "Error updating password: " . $conn->error]);
    }
} else {
    // Email does not exist
    echo json_encode(['success' => false, 'error' => "Email does not exist"]);
}

// Close connections
$stmt->close();
$conn->close();
?>
