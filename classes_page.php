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
      <style>
        .lesson-selection{
            cursor: pointer; 
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .lesson-selection select {
            padding: 0.5rem;
            width: 90%;
            margin-top:9%;
            height: 30%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: var(--normal-font-size);
            color: var(--text-color);
            background-color: var(--container-color);
            font-family: var(--body-font);
        }

        .lesson-selection select:focus {
            border-color: var(--first-color);
            outline: none;
        }
            </style>
      <script defer src="./JS_Folder/teacher_base.js"></script> 
    <title>Class | E.M.S</title>
</head>
<body>
    <?php
    include('teacher_base.php');
    $sql = "SELECT 
            c.class_id, 
            c.class_name, 
            c.student_amount, 
            c.color_id, 
            color.hex_code,
            GROUP_CONCAT(l.lesson_name SEPARATOR ', ') AS assigned_lessons
        FROM 
            class c
        JOIN 
            color ON c.color_id = color.color_id
        LEFT JOIN 
            assigned a ON c.class_id = a.class_id
        LEFT JOIN 
            lesson l ON a.lesson_id = l.lesson_id
        WHERE 
            c.teacher_email = '$user_email'
        GROUP BY 
            c.class_id";

    $class = $conn->query($sql);
    $teacher_email = 'limtingwei2003@gmail.com';
    $sql_lessons = "SELECT lesson_id, lesson_name, question_type FROM lesson WHERE teacher_email = '$teacher_email'";
    $lessons = $conn->query($sql_lessons);
    ?>
          <!-- class card content -->
    <div class="cardcontent">
        <?php
        if ($class->num_rows > 0) {
            // Output data for each class
            while($row = $class->fetch_assoc()) {
                $class_id_fetch = $row["class_id"];
                $assigned_lessons = !empty($row["assigned_lessons"]) ? htmlspecialchars($row["assigned_lessons"]) : "No lessons assigned yet.";
                echo '<div onclick="classCookie(' . $class_id_fetch . ', 30); window.location.href=\'Class-Detail.php\';" class="class-card" style="border: solid ' . $row["hex_code"] . ';" data-class-id="' . $row["class_id"] . '">';
                echo    '<h3>' . htmlspecialchars($row["class_name"]) . '</h3>';
                echo    '<i class="bx bx-dots-vertical-rounded class-more"></i>';
                echo    '<div class="delete-edit-cls-popup">';
                echo        '<div class="edit-cls" data-class-id="' . $class_id_fetch . '" data-class-name="' . htmlspecialchars($row["class_name"]) . '"><i class="bx bxs-edit delete-edit-cls"><span class="icon-title"> Edit class name</span></i></div>';
                echo        '<div class="delete-cls" data-class-id-delete="' . $class_id_fetch . '"><i class="bx bxs-trash-alt delete-edit-cls"><span class="icon-title"> Delete your class</span></i></div>';
                echo        '<div class="assign-lesson" data-class-id-lesson="' . $class_id_fetch . '"><i class="bx bxs-book-add delete-edit-cls"><span class="icon-title"> Assign lesson</span></i></div>';
                echo    '</div>';
                echo    '<a>' . htmlspecialchars($row["student_amount"]) . ' Student(s)</a></br>';
                echo    '<a> Assigned Class :' . htmlspecialchars($assigned_lessons) . '</a>';
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
        <form id="editClassForm" method="post" action="./Classes-backend/edit_class.php">
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
        <form id="deleteClassForm" method="post" action="./Classes-backend/delete_class.php">
            <input type="hidden" id="delete_class_id" name="class_id" value="">
            <div class="delete-cls-btn-wrapper">
                <input class="edit-cancel-btn" type="button" id="delete-cancel-btn" value="Cancel">
                <button type="submit" class="edit-delete-btn">Delete</button>
            </div>
        </form>
    </div>


    <!-- add lesson to class-->
    <!-- add lesson to class -->
    <div class="add-lesson-popup" id="add-lesson-popup">
        <a class="popup-title">Assign a lesson to class</a>
        <a class="popup-subtitle">Please choose a lesson you want to assign to this class</a>
        <i class='bx bx-x delete-cls-close' id="add-lesson-close"></i>
        <form action="./Classes-backend/assign_lesson.php" method="post">
            <input type="hidden" id="class_id_hidden" name="class_id" value="">
            <div class="lesson-selection">
                <?php if ($lessons->num_rows > 0) : ?>
                    <select name="lesson_id" id="lesson_id" required>
                        <option value="">Select a Lesson</option>
                        <?php while($lesson = $lessons->fetch_assoc()) : ?>
                            <option value="<?= $lesson['lesson_id'] ?>">
                                <?= htmlspecialchars($lesson['lesson_name']) ?> - <?= htmlspecialchars($lesson['question_type']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                <?php else : ?>
                    <p>No lessons available to assign.</p>
                <?php endif; ?>
            </div>

            <div class="edit-cls-btn">
                <input class="edit-cancel-btn" type="button" name="" id="add-lesson-cancel-btn" value="Cancel">
                <button type="submit" class="save-btn">Confirm</button>
            </div>
        </form>
    </div>


    <div class="create_class-popup hide" id="create-class-popup">     
            <a class="popup-title">Create a new class</a>
            <i class='bx bx-x close-popup_page'></i>
            <form id="createClassForm" action="./Classes-backend/create_class.php" method="post">
                  <div class="input-list">
                        <div class="cls-name-input">
                              <li><input type="text" name="class_name" placeholder="Enter class name" class="class-name-input" id="classname" required></li>
                              <a class="input-classname-error" id="clsname-error">Please enter a class name</a>
                        </div>
                        <input type="hidden" name="selected_color" id="selectedColorInput">
                        <button type="submit" class="crt-class-btn">Create Class</button>
                        <div class="choose-color">
                              <li><div id="selected-color" class="color-preview"></div></li>
                              <li class="more-color"><i class='bx bxs-down-arrow more-color'></i></li>
                              <div class="more-color-menu">
                                    <div class="color-option" style="background-color: black;" data-color="black" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: orange;" data-color="orange" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: red;" data-color="red" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: lime;" data-color="lime" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: blue;" data-color="blue" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: yellow;" data-color="yellow" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: cyan;" data-color="cyan" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: magenta;" data-color="magenta" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: gold;" data-color="gold" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: MediumSlateBlue;" data-color="MediumSlateBlue" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: Aquamarine;" data-color="Aquamarine" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: olive;" data-color="olive" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: green;" data-color="green" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: purple;" data-color="purple" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: teal;" data-color="teal" onclick="selectColor(this)"></div>
                                    <div class="color-option" style="background-color: navy;" data-color="navy" onclick="selectColor(this)"></div>
                              </div>
                        </div>
                  </div>
            </form>
    </div>

    <script src="./JS_Folder/create_class/create-class-popup-cookie.js"></script>
    <script src="./JS_Folder/create_class/more-color.js"></script>
    <script src="./JS_Folder/create_class/delete-edit-cls-popup.js"></script>


</body>
</html>
