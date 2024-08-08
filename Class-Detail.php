
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link rel="stylesheet" href="./Css_folder/teacher_base.css">
      <!-- <link rel="stylesheet" href="CSS-folder/popup.css"> -->
      <link rel="stylesheet" href="Css_folder/class_page.css">
      <link rel="stylesheet" href="./Css_folder/Class-Detail.css">
      
      <!--this link is link to the icon website, then can use the icon without download the icon-->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"> 

      <script defer src="./JS_Folder/teacher_base.js"></script> 

      <title>Class Details | E.M.S</title>
</head>
<?php
$class_id = isset($_COOKIE['class_cookie']) ? $_COOKIE['class_cookie'] : '';
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('teacher_base.php');

?>
<body>
    <!-- delete student popup -->
    <div class="delete-std-popup" id="delete-std-popup">
        <a class="popup-title">Kick this student?</a>
        <a class="popup-subtitle">Deleted student cannot be recovered</a>
        <i class='bx bx-x delete-std-close'></i>
        <form action="./Classes-backend/delete_student.php" method="post">
            <div class="delete-cls-btn-wrapper">
                <input class="edit-cancel-btn" type="button" id="delete-std-cancel-btn" value="Cancel">
                <button type="button" class="edit-delete-btn">Yes</button>
            </div>
        </form>
    </div>  
    <!-- edit student popup -->
    <div class="edit-student-popup" id="edit-student-popup">
        <div class="edit-student-popup-content">
            <a class="new-student-popup-title">Edit your student info</a>
            <i class='bx bx-x edit-student-close' id="edit-student-close"></i>
            <form class="new-student-info" id="form-1" method="post" action="./Classes-backend/edit_student.php">
                <div class="student-info">
                    <label for="fname_edit">Student first name</label><br>
                    <input type="text" id="fname_edit" name="fname" class="info-input" required><br>
                </div>
                <div class="student-info">
                    <label for="lname_edit">Student last name</label><br>
                    <input type="text" id="lname_edit" name="lname" class="info-input" required><br>
                </div>
                <div class="student-info">
                    <label for="gender_edit">Gender</label><br>
                    <select id="gender_edit" name="gender" class="info-input" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select><br>
                </div>
                <div class="student-info">
                    <label for="email_edit">Student email</label><br>
                    <input type="email" id="email_edit" name="email" class="info-input" required><br>
                </div>
                <a class="new-student-popup-title">Choose profile for your student</a>
                <div class="profile-list" id="profile-list">
                    <?php
                    $sql = "SELECT `character_id`, `character_path` FROM `character`";
                    $edit_student = $conn->query($sql);
                    if ($edit_student->num_rows > 0) {
                        while($row = $edit_student->fetch_assoc()) {
                            echo '<div class="profile" onclick="selectCharacterEdit(' . $row["character_id"] . ')">';
                            echo '<img src="' . $row["character_path"] . '" alt="animal">';
                            echo '</div>';
                        }
                    } else {
                        echo "No images found.";
                    }
                    ?>
                </div>
                <input type="hidden" id="character_edit" name="character_edit">
                <button type="submit" class="next-btn" id="next">Next</button>
            </form>
        </div>
    </div>
      <!-- content --->
      <div class="back-to-all-class">
            <i class='bx bx-arrow-back back-btn'></i> <a href="classes_page.php">Back to all class</a>
      </div> 
      <br>
      
       <!-- class title card -->
      <div class="cardcontetn">
            <?php
            if ($class_id) {
                  // Prepare and execute the query to fetch class details
                  $query = "SELECT class.class_id, class.class_name, class.color_id, class.student_amount, color.hex_code FROM class JOIN color ON color.color_id = class.color_id WHERE class_id = ?";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param("i", $class_id);
                  $stmt->execute();
                  $class_card = $stmt->get_result();
            
                  if ($class_card->num_rows > 0) {
                  while ($class_details = $class_card->fetch_assoc()) {
                        // Display class details
                        echo '<div class="class-title-card" style="border: solid ' . $class_details["hex_code"] . ';" data-class-id="' . $class_details["class_id"] . '">';
                        echo    '<h2>' . htmlspecialchars($class_details["class_name"]) . '</h2>';
                        echo    '<a class="student-amount">' . htmlspecialchars($class_details["student_amount"]) . ' Student(s)</a>';
                        echo    '<div class="add-student">';
                        echo        '<i class="bx bxs-user-plus add-student" id="add-student"></i>';
                        echo    '</div>';
                        echo '</div>';
                                          
                  }
                  } else {
                  echo "No class details found.";
                  }
            } else {
                  echo "No class selected.";
            }
            
            // Close the statement and connection
            $stmt->close();
            ?>
      </div>
      <div class="class-activity">
            <h3>Your Class Activity</h3>        
      </div>
      <div class="search-student">
            <input type="search" id="searchInput" placeholder="Search students" class="search-student-input">
            <i class="bx bx-search search-student-icon" id="searchIcon"></i>
      </div>
      
      <div class="student-table">
            <table class="student-list">
                  <thead>
                        <tr>
                              <th>First Name</th>
                              <th>Last Name</th>
                              <th>Gender</th>
                              <th>Student Email</th>
                              <th></th>
                              
                        </tr>
                  </thead>
                  <tbody id="studentTableBody">
                  <?php
                  $stmt = $conn->prepare("SELECT student.student_email, student.student_fname, student.student_lname, student.gender FROM student INNER JOIN class ON student.class_id = class.class_id WHERE class.class_id = ?");
                  $stmt->bind_param("s", $class_id); // Bind the class_id parameter
                  $stmt->execute();
                  $students = $stmt->get_result();
                  if ($students->num_rows > 0) {
                        while($row = $students->fetch_assoc()) {
                              echo '<tr>';
                              echo '<td>'. htmlspecialchars($row["student_fname"]). '</td>';
                              echo '<td>'. htmlspecialchars($row["student_lname"]). '</td>';
                              echo '<td>'. htmlspecialchars($row["gender"]). '</td>';
                              echo '<td>'. htmlspecialchars($row["student_email"]). '</td>'; ?>
                                    <td><i class="bx bxs-trash-alt new-std-icon" id="delete-student"></i><i class="bx bxs-edit new-std-icon" id="edit-student"></i></td>
                        <?php echo '</tr>';
                        }
                        } else {
                              echo "No student found.";
                        }

                  ?>
                  </tbody>
            </table>
      </div>

       <!-- create new student popup -->
      <div class="new-student-popup" id="new-student-popup">
            <div class="popup-content">          
                  <a class="new-student-popup-title">Create a new student account</a>
                  <i class='bx bx-x student-close' id="student-close"></i>    
                  <!-- fill info -->
                  <form class="new-student-info" id="form-1" method="post" action="./Classes-backend/add_student.php">
                        <div class="student-info">
                        <label for="fname" >Student first name</label><br>
                        <input type="text" id="fname" name="fname" class="info-input" placeholder="Put your student first name" required><br>
                        </div>
                        <div class="student-info">
                        <label for="lname" >Student last name</label><br>
                        <input type="text" id="lname" name="lname" class="info-input" placeholder="Put your student last name" required><br>
                        </div>
                        <div class="student-info">
                        <label for="gender" >Gender</label><br>
                        <select id="gender" name="gender" class="info-input" required>
                              <option value="male">Male</option>
                              <option value="female">Female</option>
                              <option value="other">Other</option>
                        </select><br>
                        </div>
                        <div class="student-info">
                        <label for="email" >Student email</label><br>
                        <input type="email" id="email" name="email" class="info-input" placeholder="Put your student email" required><br>
                        </div>  
                        <!-- choose profile -->
                        <a class="new-student-popup-title">Choose profile for your student</a>
                        <div class="profile-list" id="profile-list">
                        <?php
                        // Fetch character data
                        $sql = "SELECT `character_id`, `character_path` FROM `character`";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                        echo '<div class="profile" onclick="selectCharacter(' . $row["character_id"] . ')">';
                        echo '<img src="' . $row["character_path"] . '" alt="animal">';
                        echo '</div>';
                        }
                        } else {
                              echo "No images found.";
                        }
                        $conn->close();
                        ?>
                        </div>
                        <input type="hidden" id="character" name="character">     
                        <button type="submit" class="next-btn" id="next">Next</button>  
                  </form> 
            </div>     
      </div> 
      
      <script src="./JS_Folder/create_class/create-class-popup-cookie.js"></script>
      <script src="./JS_Folder/create_class/more-color.js"></script>
      <script src="./JS_Folder/create_class/student-edit-delete.js"></script>
</body>
</html>
