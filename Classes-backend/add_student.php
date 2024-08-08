<?php
// Start output buffering
ob_start();

include('./database/connection.php');

// Initialize an array to store any errors
$errors = [];

// Validate and sanitize input
$first_name = !empty($_POST['fname']) ? trim($_POST['fname']) : $errors[] = 'First name is required';
$last_name = !empty($_POST['lname']) ? trim($_POST['lname']) : $errors[] = 'Last name is required';
$gender = !empty($_POST['gender']) ? $_POST['gender'] : 'male';
$email = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : $errors[] = 'Email is required';
$character_id = !empty($_POST['character']) ? intval($_POST['character']) : $errors[] = 'Character selection is required';
$class_id = !empty($_POST['class_id']) ? intval($_POST['class_id']) : $errors[] = 'Class not found';

// If there are any errors, display them and stop execution
if (!empty($errors)) {
    echo "Errors:<br>" . implode("<br>", $errors);
    // End output buffering and flush the output
    ob_end_flush();
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO student (student_fname, student_lname, gender, student_email, character_id, class_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssii", $first_name, $last_name, $gender, $email, $character_id, $class_id);

$update_stmt = $conn->prepare("UPDATE class SET student_amount = student_amount + 1 WHERE class_id = ?");
$update_stmt->bind_param("i", $class_id);


// Execute the statement
$stmt->execute();
$update_stmt->execute();

// Redirect to a confirmation page or the next step
header("Location: ../Class-Detail.php");
exit();

// End output buffering and flush the output
ob_end_flush();
?>
