<?php
include('Teacher_loginValidate.php');
?>  
  <!-- Header -->
      <header class="header">
            <div class="header-container">
                  <a class="header-title">My Classes</a>           

                  <div class="header-search">
                        <input type="search" placeholder="Search" class="header-input">
                        <i class="bx bx-search header-icon"></i>
                  </div>

                  <div class="headertoggle">
                        <i class="bx bx-menu" id="header-toggle"></i>
                  </div>

                  <div class="header-create-class">
                        <button class="create-class-btn" id="create_btn"> Create </button>
                  </div>
            </div>
      </header>

      <!-- Sidebar -->
      <div class="nav" id="navbar">
            <nav class="nav-container">
                  <div>
                        <a href="#" class="nav-link nav-logo">
                              <img src="src/logo.png" width="75" class="nav-icon">
                              <span class="logo-title">EasyMathStep</span>
                        </a>

                        <div class="nav-list">
                              <div class="nav-item">
                                    <h3 class="nav-subtitle"><?php echo "$teacherName"?> </h3> 
      
                                    <a href="library_page.php" class="nav-link">
                                          <i class='bx bxs-book nav-icon'></i>
                                          <span class="nav-name">Library</span>
                                    </a>
      
                                    <a href="classes_page.php" class="nav-link">
                                          <i class='bx bx-group nav-icon' ></i>
                                          <span class="nav-name">Classes</span>
                                    </a>
      
                                    <a href="setting_page.php" class="nav-link">
                                          <i class='bx bxs-cog nav-icon' ></i>
                                          <span class="nav-name">Setting</span>
                                    </a>
                                    
                                    <a href="#" class="nav-link">
                                          <i class='bx bx-log-out nav-icon'></i>
                                          <span class="nav-name">Logout</span>
                                    </a>
                              </div>
                        </div>
                  </div>
            </nav>
      </div>

      <!-- create selection pop up  -->
      <div id="create_popup" class="create_popup">
            <div class="selector_container">
                <div class="selector_content">
                    <div>
                        <p>Create</p>
                        <div class="close" id="popup-close">
                              &times;
                        </div>
                    </div>
        
                    <a href="create_game_page.php">
                        <div class="selection_content_container">
                            <img src="./src/teacher_base/quiz.png" alt="Quiz Icon">
                            <div class="selection_content">
                                <h2>Quiz</h2>
                                <p>
                                    Empower your Down syndrome students with customized quizzes that cater to their unique learning needs. 
                                    Our 'Create Quiz' feature allows you to design engaging and interactive assessments that make learning fun and accessible. 
                                    Start creating quizzes today to help each child reach their full potential!
                                </p>
                            </div>
                        </div>
                    </a>
                    <a href="classes_page.php?showPopup=true" id = "create-class-link">
                        <div class="selection_content_container">
                            <img src="src/teacher_base/class.png" alt="Class Icon">
                            <div class="selection_content">
                                <h2>Classes</h2>
                                <p>
                                    Transform your teaching approach with our 'Create Classes' feature, designed specifically for Down syndrome students. 
                                    Organize and manage tailored classes that address their individual learning styles, fostering an inclusive and supportive educational environment. 
                                    Create your classes now and watch your students thrive!
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
      </div>


