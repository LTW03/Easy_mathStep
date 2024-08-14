<?php
include('database/connection.php');

$class_id = $_GET['class_id'];
// Fetch class data with their associated color
$sql = "SELECT s.student_fname, s.student_lname, c.character_path 
        FROM student s 
        LEFT JOIN `character` c ON s.character_id = c.character_id 
        WHERE s.class_id = $class_id;";
        

$student = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>

      <link rel="stylesheet" href="./Css_folder/Choose_student.css">
</head>
<body>
      <a href="#" class="logo">
            <img src="./src/logo.png" width="60" class="logo-icon">
            <span class="logo-title">EasyMathStep</span>
      </a>
      <div class="title">
            <h1 class="title-text">Choose Your Name</h1>
      </div>
      <div class="std-name-card">
            <?php
            if ($student->num_rows > 0) {
                  // Output data for each class
                  while($row = $student->fetch_assoc()) {
                  echo '<div class="namecard">';
                  echo    '<img src="' . $row["character_path"] . '" alt="animal" width="75">';
                  echo '<a class="std-name">' . htmlspecialchars($row["student_fname"]) . ' ' . htmlspecialchars($row["student_lname"]) . '</a>';
                  echo '</div>';
                  }
            } else {
                  echo "No student found.";
            }
            $conn->close();
            ?>
            
      </div>
</body>
</html>