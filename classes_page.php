<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css_folder/teacher_base.css">
    <!-- <link rel="stylesheet" href="Css_folder/library_page.css"> -->
    <link rel="stylesheet" href="Css_folder/class_page.css">
    <!-- <link rel="stylesheet" href="Css_folder/general.css"> -->

      <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

      <script defer src="./JS_Folder/teacher_base.js"></script> 
    <title>CLass | E.M.S</title>
</head>
<body>
    <?php
    include('teacher_base.php');
    $sql = "SELECT class.class_id, class.class_name, class.student_amount, class.teacher_email, class.color_id, color.* FROM class JOIN color ON class.color_id = color.color_id WHERE class.teacher_email = '$user_email';";

    $class = $conn->query($sql);
    ?>
          <!-- class card content -->
          <div class="cardcontent">
        <?php
        if ($class->num_rows > 0) {
            // Output data for each class
            while($row = $class->fetch_assoc()) {
                echo '<div onclick="classCookie(' . $row["class_id"] . ', 30); window.location.href=\'Class-Detail.php\';" class="class-card" style="border: solid ' . $row["hex_code"] . ';" data-class-id="' . $row["class_id"] . '">';
                echo    '<h3>' . htmlspecialchars($row["class_name"]) . '</h3>';
                echo    '<i class="bx bx-dots-vertical-rounded class-more"></i>';
                echo    '<div class="delete-edit-cls-popup">';
                echo        '<div class="edit-cls" data-class-id="' . $row["class_id"] . '" data-class-name="' . htmlspecialchars($row["class_name"]) . '"><i class="bx bxs-edit delete-edit-cls"><span class="icon-title"> Edit class name</span></i></div>';
                echo        '<div class="delete-cls" data-class-id-delete="' . $row["class_id"] . '"><i class="bx bxs-trash-alt delete-edit-cls"><span class="icon-title"> Delete your class</span></i></div>';
                echo        '<div class="assign-lesson" data-class-id-lesson="' . $row["class_id"] . '"><i class="bx bxs-book-add delete-edit-cls"><span class="icon-title"> Assign lesson</span></i></div>';
                echo    '</div>';
                echo    '<a>' . htmlspecialchars($row["student_amount"]) . ' Student(s)</a>';
                echo '</div>';
            }
        } else {
            echo "No classes found.";
        }
        $conn->close();
        ?>
    </div>

    <!-- edit class -->
    <div class="edit-cls-popup" id="edit-cls-popup">
        <a class="popup-title">Edit class name</a>
        <i class='bx bx-x edit-cls-close' ></i>
        <form id="editClassForm" method="post" action="PHP-backend/edit_class.php">
            <input type="hidden" id="edit_class_id" name="class_id" value="">
            <div class="input-list">
                <div class="cls-name-input">
                    <li><input type="text" name="class_name" placeholder="Enter class name" class="edit-class-name" id="edit_class_name" required></li>
                </div>            
            </div>
            <div class="edit-cls-btn">
                <input class="edit-cancel-btn" type="button" name="" id="edit-cancel-btn" value="Cancel">
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>
    </div>

    <!-- delete class -->
    <div class="delete-cls-popup popup" id="delete-cls-popup">
        <a class="popup-title">Delete this class?</a>
        <a class="popup-subtitle">Deleted class cannot be recovered</a>
        <i class='bx bx-x delete-cls-close'></i>
        <form id="deleteClassForm" method="post" action="PHP-backend/delete_class.php">
            <input type="hidden" id="delete_class_id" name="class_id" value="">
            <div class="delete-cls-btn-wrapper">
                <input class="edit-cancel-btn" type="button" id="delete-cancel-btn" value="Cancel">
                <button type="submit" class="edit-delete-btn">Delete</button>
            </div>
        </form>
    </div>


    <!-- add lesson to class-->
    <div class="add-lesson-popup" id="add-lesson-popup">
        <a class="popup-title">Assign a lesson to class</a>
        <a class="popup-subtitle">Please choose a lesson you want assign to this class</a>
        <i class='bx bx-x  delete-cls-close' id="add-lesson-close"></i>
        <form action="">
            <div class="edit-cls-btn">
                <input class="edit-cancel-btn" type="button" name="" id="add-lesson-cancel-btn" value="Cancel">
                <button type="submit" class="save-btn">Comfirm</button>
            </div>
        </form>
        
    </div>

    <script src="./JS_Folder/create_class/create-class-popup-cookie.js"></script>
    <script src="./JS_Folder/create_class/more-color.js"></script>
    <script src="./JS_Folder/create_class/delete-edit-cls-popup.js"></script>


</body>
</html>
