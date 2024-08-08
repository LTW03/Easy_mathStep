<?php
include('../database/connection.php');

$original_email = isset($_POST['original_email']) ? $_POST['original_email'] : '';
$fname = isset($_POST['fname']) ? $_POST['fname'] : '';
$lname = isset($_POST['lname']) ? $_POST['lname'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$character = isset($_POST['character']) ? $_POST['character'] : null || '';

if ($character !== null) {
    $stmt = $conn->prepare("UPDATE student SET student_fname = ?, student_lname = ?, gender = ?, student_email = ?, character_id = ? WHERE student_email = ?");
    $stmt->bind_param("ssssss", $fname, $lname, $gender, $email, $character, $original_email);
}

if ($character == null ){
    $stmt = $conn->prepare("UPDATE student SET student_fname = ?, student_lname = ?, gender = ?, student_email = ? WHERE student_email = ?");
    $stmt->bind_param("sssss", $fname, $lname, $gender, $email, $original_email);
}

if ($stmt->execute()) {
    echo "Student details updated successfully";
} else {
    echo "Error updating student details: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>