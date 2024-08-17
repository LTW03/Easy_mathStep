<?php
include('./database/connection.php');

// Get form data
$class_name = $_POST['class_name'];
$selected_color = $_POST['selected_color'];
$user_email = isset($_COOKIE['user_email'])? $_COOKIE['user_email'] : '';

if (empty($user_email)) {
    die("User email is missing.");
}

if (empty($class_name))  {
    die("Class name is missing.");
}

if (empty($selected_color)){
    $selected_color = "cyan";
}

// Check if color exists in the color table
$color_query = $conn->prepare("SELECT color_id FROM color WHERE color_name = ?");
$color_query->bind_param("s", $selected_color);
$color_query->execute();
$color_query->store_result();

if ($color_query->num_rows > 0) {
    $color_query->bind_result($color_id);
    $color_query->fetch();
} else {
    // Insert new color if it doesn't exist
    $insert_color = $conn->prepare("INSERT INTO color (color_name, hex_code) VALUES (?, ?)");
    $hex_code = getColorHexCode($selected_color); // Define this function to return the hex code for the color
    $insert_color->bind_param("ss", $selected_color, $hex_code);
    $insert_color->execute();
    $color_id = $insert_color->insert_id;
    $insert_color->close();
}
$color_query->close();

// Insert the new class into the class table
$insert_class = $conn->prepare("INSERT INTO class (class_name, color_id, teacher_email, student_amount) VALUES (?, ?, ?, 0)");
$insert_class->bind_param("sis", $class_name, $color_id, $user_email);

if ($insert_class->execute()) {
    header("Location: ../classes_page.php?");
} else {
    echo "Error: " . $insert_class->error;
}

$insert_class->close();
$conn->close();

// Function to return the hex code for a color
function getColorHexCode($color_name) {
    $colors = [
        "black" => "#000000",
        "orange" => "#FFA500",
        "red" => "#FF0000",
        "lime" => "#00FF00",
        "blue" => "#0000FF",
        "yellow" => "#FFFF00",
        "cyan" => "#00FFFF",
        "magenta" => "#FF00FF",
        "gold" => "#FFD700",
        "MediumSlateBlue" => "#7B68EE",
        "Aquamarine" => "#7FFFD4",
        "olive" => "#808000",
        "green" => "#008000",
        "purple" => "#800080",
        "teal" => "#008080",
        "navy" => "#000080"
    ];
    return $colors[$color_name];
}
?>