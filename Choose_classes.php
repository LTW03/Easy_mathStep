<?php
$studentEmail = $_COOKIE['student_email'] ?? '';
$lessonId = $_COOKIE['lesson_id'] ?? '';

// If cookies are set, determine the quiz type and redirect
if ($studentEmail && $lessonId) {
    include('database/connection.php');

    // Fetch the lesson details based on lesson_id
    $sql = "SELECT question_type FROM lesson WHERE lesson_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lessonId);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();

    $questionType = $lesson['question_type'] ?? '';

    if ($questionType === 'MCQ') {
        header("Location: MCQ_quiz.php?class_id=" . $_GET['class_id'] . "&lesson_id=" . $lessonId);
    } elseif ($questionType === 'TF') {
        header("Location: TrueFalse_quiz.php?class_id=" . $_GET['class_id'] . "&lesson_id=" . $lessonId);
    } elseif ($questionType === 'DragDrop') {
        header("Location: drag_dropQuiz.php?class_id=" . $_GET['class_id'] . "&lesson_id=" . $lessonId);
    } else {
        echo "<script type='text/javascript'>alert('No valid quiz type found.');window.location='Choose_Student.php';</script>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Choose Class</title>
    <link rel="stylesheet" href="./Css_folder/Choose_classes.css">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
</head>
<body>
    <div class="ripple-background"></div>
    <div class="circle xxlarge shade1"></div>
    <div class="circle xlarge shade2"></div>
    <div class="circle large shade3"></div>
    <div class="circle mediun shade4"></div>
    <div class="circle small shade5"></div>



    <div class='logoContainer'>
        <img class='logo' src='./src/logo.png'>
        <a class='logoLink' href=''>EasyMathStep</a>
    </div>
    
    <span class="carouselText">Choose Your Class</span>
    <div class="carousel-container swiper">
        <div class="swiper-wrapper">
            <?php
                include('database/connection.php');

                $query = "
                    SELECT class.class_id, class.class_name, color.hex_code 
                    FROM class 
                    INNER JOIN color ON class.color_id = color.color_id
                ";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    // Convert HEX to RGB
                    list($r, $g, $b) = sscanf($row['hex_code'], "#%02x%02x%02x");
                    
                    // Set the alpha value (e.g., 0.5 for 50% transparency)
                    $alpha = 0.4;
                    
                    echo '<div class="swiper-slide" style="background-color: rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $alpha . ');">';
                    echo '<div class="carousel-item" style="border: 3px solid ' . $row['hex_code'] . ';">';
                    echo '<a href="./Choose_Student.php?class_id=' . $row['class_id'] . '">';
                    echo '<button type="button" class="carouselItemBtn">Join!</button>';
                    echo '</a> ' . $row['class_name'];
                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
    

    <script src="./JS_Folder/Choose_class.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper(".swiper", {
            direction: "horizontal",
            loop: false,
            speed: 1500,
            slidesPerView: 4,
            spaceBetween: 60,
            mousewheel: true,
            parallax: true,
            centeredSlides: true,
            effect: "coverflow",
            coverflowEffect: {
            rotate: 40,
            slideShadows: true
            },
            autoplay: {
            delay: 1000,
            pauseOnMouseEnter: true
            },
            scrollbar: {
            el: ".swiper-scrollbar"
            },
            breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 60
            },
            600: {
                slidesPerView: 2,
                spaceBetween: 60
            },
            1000: {
                slidesPerView: 3,
                spaceBetween: 60
            },
            1400: {
                slidesPerView: 4,
                spaceBetween: 60
            },
            2300: {
                slidesPerView: 5,
                spaceBetween: 60
            },
            2900: {
                slidesPerView: 6,
                spaceBetween: 60
            }
            }
        });
    </script>



</body>
</html>
