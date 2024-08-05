<?php

include("database/connection.php");

$response = array('status' => 'error', 'message' => 'Invalid Email');

if(isset($_POST['user_email'])){
    $login_email = $conn->real_escape_string($_POST['user_email']);

    $query = "SELECT * FROM teacher WHERE teacher_email = '$login_email'";
    $result = $conn->query($query);

    if($result->num_rows == 1){
        $response = array('status' => 'success', 'message' => 'Email Exists');
    }
}
$conn->close();

echo json_encode($response);
?>
