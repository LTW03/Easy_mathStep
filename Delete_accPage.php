<?php
include('./database/connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$teacher_email = $_GET['email'];

// Start transaction
$conn->begin_transaction();

try {
    //Delete all classes related with the teacher
    $stmt = $conn->prepare("SELECT class_id FROM class WHERE teacher_email = ?");
    $stmt->bind_param('s', $teacher_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $class_ids = [];
    while ($row = $result->fetch_assoc()) {
        $class_ids[] = $row['class_id'];
    }
    $stmt->close();

    // If no classes found, skip to delete teacher
    if (empty($class_ids)) {
        $class_ids = [0]; // To avoid error on next step
    }

    // Delete all students related with the classes
    $class_ids_placeholder = implode(',', array_fill(0, count($class_ids), '?'));
    $stmt = $conn->prepare("DELETE FROM student WHERE class_id IN ($class_ids_placeholder)");
    $stmt->bind_param(str_repeat('i', count($class_ids)), ...$class_ids);
    $stmt->execute();
    $stmt->close();

    // Delete all classes related with the teacher
    $stmt = $conn->prepare("DELETE FROM class WHERE teacher_email = ?");
    $stmt->bind_param('s', $teacher_email);
    $stmt->execute();
    $stmt->close();

    //Delete the teacher's account
    $stmt = $conn->prepare("DELETE FROM teacher WHERE teacher_email = ?");
    $stmt->bind_param('s', $teacher_email);
    $stmt->execute();
    $stmt->close();
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Teacher account and associated data successfully deleted.']);
    header('Location: ./index.php');

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    header('Location: ./setting_page.php');
}
?>
