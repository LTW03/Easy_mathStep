<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | E.M.S</title>
    <link rel="stylesheet" href="./Css_folder/landing_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/> <!--icons-->
    <link rel="stylesheet" href="./Css_folder/general.css">
</head>
<body>
    <div class="page-container">
        <section class="home">
            <nav class="main-navbar">
                <a href="#" class="logo">
                    <img src="./src/logo.png" alt="">
                    <span class="logo-name">Easy Math Step</span>
                </a>

                <a href="./Login_page.php" class="get-started-btn-container">
                    <button class="get-started-btn btn">Teacher Login</button>
                </a>
            </nav>
        <div class="banner">
            <div class="banner_description">
                <h2>Start Learning Now</h2>
                <p>
                    Welcome to Easy Math Step, the perfect place for making math learning fun and engaging for children with Down syndrome.
                    Our goal is to support teachers in providing effective and enjoyable math education through interactive and inclusive activities.
                </p>
            
                <a href="./letsgo-page.html">
                    <button class="btn">
                        Let's Go
                    </button>
                </a>
            </div>

            <div class="banner_img">
                <div class="bannerImg_container">
                    <img src="./src/landing_page/ds_kid_img.png" alt=""> <!--img container-->
                </div>
            </div>
        </div>
        </section>

        <!--why us-->
        <section class="services">
            <header class="service-section_head">
                <h1>Why Us</h1>
                <p>
                    At Easy Math Step, we understand that every child learns differently, and we are committed to making math accessible and enjoyable for children with Down syndrome. Here’s why Easy Math Step is the ideal choice for educators and students alike:
                </p>
            </header>
            <div class="service-contents">
                <div class="service-box">
                    <div class="service-icon">
                        <i class="fa-solid fa-child-reaching"></i>
                    </div>
                    <div class="service-desc">
                        <h2>
                            Specialized Design for Down Syndrome
                        </h2>
                        <p>
                            Easy Math Step is crafted with a deep understanding of the cognitive and sensory needs of children with Down syndrome. Our activities are designed to be visually appealing, simple to understand, and easy to interact with, ensuring that every child can participate and succeed.
                        </p>
                    </div>
                </div>
                <div class="service-box">
                    <div class="service-icon">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div class="service-desc">
                        <h2> 
                            Interactive and Fun Learning
                        </h2>
                        <p>
                            We believe that learning should be fun! Our interactive games like drag and drop, true/false selections, and multiple-choice questions make math exciting and enjoyable. Each activity is designed to keep children motivated and interested in learning.
                        </p>
                    </div>
                </div>
                <div class="service-box">
                    <div class="service-icon">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    <div class="service-desc">
                        <h2>
                            Positive Reinforcement
                        </h2>
                        <p>
                            Positive reinforcement is key to building confidence and motivation. After completing each question, children receive encouraging words, playful memes, or joyful pictures, making learning a rewarding experience.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!--learning material img and desc-->
        <section>
            <div class="learningMaterial-img_container ">
                <div class="learningMaterial_img">
                    <div class="lm-img_column">
                        <img src="./src/landing_page/kid.jpg" alt="">
                    </div>
                    <div class="lm-img_column">
                        <img src="./src/landing_page/image 19.png" alt="">
                        <img src="./src/landing_page/image 22 (1).png" alt="">
                    </div> 
                </div>
                <div class="content">
                    <div>
                        <h2 class="content__title">
                            Engaging Learning Games
                        </h2>
                        <ul>
                            <li>
                                <p class="section__subtitle">
                                    Drag and Drop: Move objects to their correct places to solve problems.
                                </p>
                            </li>
                            <li>
                                <p class="section__subtitle">
                                    True/False Selection: Choose the right answer to validate understanding.
                                </p>
                            </li>
                            <li>
                                <p class="section__subtitle">
                                    Multiple Choice Questions (MCQs): Select the best answer from a set of options.
                                </p>
                            </li>

                        </ul>
                        <button class="btn" onclick="povpage()">View All</button>
                      </div>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <div class="footer_container">
            <div class="footer_column">
                <h3>
                    About Us
                </h3>
                <p>
                    Eazy Math Step is dedicated to providing engaging and inclusive math education for children with Down syndrome. Our goal is to make learning math a fun and rewarding experience for every child.
                </p>
            </div>
            <div class="footer_column">
                <h4>Support</h4>
                <p>Home</p>
                <p>FAQs</p>
                <p>Terms & Conditions</p>
                <p>Privacy Policy</p>
                <p>Contact Us</p>
            </div>
            <div class="footer_column">
                <h4>Address</h4>
                <p>
                    <span>Address</span>Jalan Teknologi 5, Taman Teknologi Malaysia, 57000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur
                </p>
                <p>
                    <span>Email:</span> chinyew@gmail.com
                </p>
                <p>
                    <span>Phone:</span> +60 111111111
                </p>
            </div>
        </div>
        <div class="footer__bar">
            Copyright © 2024 Eazy Math Step. All rights reserved.
        </div>
    </footer>
</body>
<script>
    function povpage(){
        window.location.href = "./userguide_pov.html";
    }
</script>
</html>