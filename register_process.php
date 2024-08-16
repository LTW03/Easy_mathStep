<?php

include('database/connection.php');

if (isset($_POST['submit'])) {
    $errors = array();

    // Check if keys exist in the $_POST array before accessing them
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $phone_number = isset($_POST['phone-number']) ? $_POST['phone-number'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $password_repeat = isset($_POST['repeat-password']) ? $_POST['repeat-password'] : null;

    if (empty($username) || empty($phone_number) || empty($email) || empty($password) || empty($password_repeat)) {
        array_push($errors, 'Please Fill in All Fields!');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, 'Please use a valid email.');
    }

    if (strlen($password) < 8) {
        array_push($errors, 'Password must be more than 8 characters.');
    }

    if ($password !== $password_repeat) {
        array_push($errors, 'Passwords are not matching.');
    }

    if(empty($errors)){
        $stmt = $conn->prepare("SELECT COUNT(*) FROM teacher WHERE teacher_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            array_push($errors, 'Email is already registered. Please use a different email.');
        }
    }

    if (count($errors) > 0) {
        // Redirect back to the register page with errors
        session_start();
        $_SESSION['errors'] = $errors;
        header("Location: ./Register_page.php");
        exit();
    } else {
        include('./database/connection.php');

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO teacher (teacher_name, teacher_number, teacher_email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $phone_number, $email, $password); // Assuming $password is not hashed

        // Execute the query
        if ($stmt->execute()) {

            echo "<script>alert('Data saved successfully'); document.location = 'Login_page.php'</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
