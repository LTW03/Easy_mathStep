<?php
include('database/connection.php');

$teacher_email = $_COOKIE['user_email'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = !empty($_POST['username']) ? $_POST['username'] : null;
    $phone = !empty($_POST['phone']) ? $_POST['phone'] : null;

    if ($username || $phone) {
        $query = "UPDATE teacher SET ";
        $params = [];
        $types = '';

        if ($username) {
            $query .= "teacher_name = ?, ";
            $params[] = $username;
            $types .= 's';
        }
        
        if ($phone) {
            $query .= "teacher_number = ?, ";
            $params[] = $phone;
            $types .= 's';
        }

        $query = rtrim($query, ", ") . " WHERE teacher_email = ?";
        $params[] = $teacher_email;
        $types .= 's';

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully'); window.location.href = 'setting_page.php';</script>";
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
