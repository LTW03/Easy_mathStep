<?php
// Start output buffering
ob_start();

include('../database/connection.php');

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
    echo "<script>alert('" . implode('\\n', $errors) . "'); window.location.href = '../Class-Detail.php';</script>";
    ob_end_flush();
    exit;
}

// Check for duplicate student within the same class
$duplicate_name_query = "SELECT COUNT(*) FROM student WHERE student_fname = ? AND student_lname = ? AND class_id = ?";
$name_stmt = $conn->prepare($duplicate_name_query);
$name_stmt->bind_param("ssi", $first_name, $last_name, $class_id);
$name_stmt->execute();
$name_stmt->bind_result($name_count);
$name_stmt->fetch();
$name_stmt->close();

if ($name_count > 0) {
    echo "<script>alert('A student with the same first and last name already exists in this class.'); window.location.href = '../Class-Detail.php';</script>";
    ob_end_flush();
    exit;
}

// Check for duplicate email within the same class
$duplicate_email_in_class_query = "SELECT COUNT(*) FROM student WHERE student_email = ? AND class_id = ?";
$email_in_class_stmt = $conn->prepare($duplicate_email_in_class_query);
$email_in_class_stmt->bind_param("si", $email, $class_id);
$email_in_class_stmt->execute();
$email_in_class_stmt->bind_result($email_in_class_count);
$email_in_class_stmt->fetch();
$email_in_class_stmt->close();

if ($email_in_class_count > 0) {
    echo "<script>alert('This email address is already associated with a student in this class.'); window.location.href = '../Class-Detail.php';</script>";
    ob_end_flush();
    exit;
}

// Check for duplicate email across all classes
$duplicate_email_query = "SELECT COUNT(*) FROM student WHERE student_email = ?";
$email_stmt = $conn->prepare($duplicate_email_query);
$email_stmt->bind_param("s", $email);
$email_stmt->execute();
$email_stmt->bind_result($email_count);
$email_stmt->fetch();
$email_stmt->close();

if ($email_count > 0) {
    echo "<script>alert('This email address is already associated with another student.'); window.location.href = '../Class-Detail.php';</script>";
    ob_end_flush();
    exit;
}

$stmt = $conn->prepare("INSERT INTO student (student_fname, student_lname, gender, student_email, character_id, class_id, student_stat) VALUES (?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("ssssii", $first_name, $last_name, $gender, $email, $character_id, $class_id); 

// Update the student amount in the class table
$update_stmt = $conn->prepare("UPDATE class SET student_amount = student_amount + 1 WHERE class_id = ?");
$update_stmt->bind_param("i", $class_id);

// Execute the statements
$stmt->execute();
$update_stmt->execute();

// Redirect to a confirmation page or the next step
echo "<script>window.location.href = '../Class-Detail.php';</script>";
exit;

// End output buffering and flush the output
ob_end_flush();
?>
