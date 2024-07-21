<?php
include('database/connection.php');

$teacher_email = $_COOKIE['user_email'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $re_password = $_POST['re_password'];

    if ($new_password !== $re_password) {
        echo "<script>alert('New passwords do not match.'); window.location.href = 'test.php';</script>";
        exit();
    }

    // Check if the old password matches
    $stmt = $conn->prepare("SELECT password FROM teacher WHERE teacher_email = ?");
    $stmt->bind_param("s", $teacher_email);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if ($old_password !== $db_password) {
        echo "<script>alert('Old password does not match.'); window.location.href = 'test.php';</script>";
        exit();
    }

    // Update the password
    $stmt = $conn->prepare("UPDATE teacher SET password = ? WHERE teacher_email = ?");
    $stmt->bind_param("ss", $new_password, $teacher_email);

    if ($stmt->execute()) {
        echo "<script>alert('Password updated successfully.'); window.location.href = 'test.php';</script>";
    } else {
        echo "Error updating password: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
