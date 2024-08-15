-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 13, 2024 at 04:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigned`
--

CREATE TABLE `assigned` (
  `assign_key` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned`
--

INSERT INTO `assigned` (`assign_key`, `lesson_id`, `class_id`) VALUES
(6, 2, 33),
(7, 3, 26),
(8, 3, 27),
(9, 2, 34);

-- --------------------------------------------------------

--
-- Table structure for table `character`
--

CREATE TABLE `character` (
  `character_id` int(11) NOT NULL,
  `character_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `character`
--

INSERT INTO `character` (`character_id`, `character_path`) VALUES
(1, 'delapouite/unicorn.png'),
(2, 'delapouite/charging-bull.png'),
(3, 'delapouite/armadillo.png'),
(4, 'delapouite/bastet.png'),
(5, 'delapouite/bear-head.png'),
(6, 'delapouite/beaver.png'),
(7, 'delapouite/bison.png'),
(8, 'delapouite/buffalo-head.png'),
(9, 'delapouite/camel-head.png'),
(10, 'delapouite/camel.png'),
(11, 'delapouite/cow.png'),
(12, 'delapouite/dolphin.png'),
(13, 'delapouite/elephant-head.png'),
(14, 'delapouite/elephant.png'),
(15, 'delapouite/feline.png'),
(16, 'delapouite/gorilla.png'),
(17, 'delapouite/horse-head.png'),
(18, 'delapouite/juggling-seal.png'),
(19, 'delapouite/jumping-dog.png'),
(20, 'delapouite/kangaroo.png'),
(21, 'delapouite/koala.png'),
(22, 'delapouite/labrador-head.png'),
(23, 'delapouite/lynx-head.png'),
(24, 'delapouite/mammoth.png'),
(25, 'delapouite/panda.png'),
(26, 'delapouite/rabbit-head.png'),
(27, 'delapouite/rabbit.png'),
(28, 'delapouite/raccoon-head.png'),
(29, 'delapouite/ram-profile.png'),
(30, 'delapouite/rhinoceros-horn.png'),
(31, 'delapouite/saber-toothed-cat-head.png'),
(32, 'delapouite/sheep.png'),
(33, 'delapouite/sitting-dog.png'),
(34, 'delapouite/sperm-whale.png'),
(35, 'delapouite/squirrel.png'),
(36, 'delapouite/tapir.png'),
(37, 'delapouite/tiger-head.png'),
(38, 'delapouite/tiger.png'),
(39, 'delapouite/walrus-head.png'),
(40, 'delapouite/anteater_2253681.png'),
(41, 'delapouite/antelope_4726758.png'),
(42, 'delapouite/chimpanzee_6158410.png'),
(43, 'delapouite/crocodile_697183.png'),
(44, 'delapouite/dolphin_202208.png'),
(45, 'delapouite/eagle_235399.png'),
(46, 'delapouite/giraffe_1888365.png'),
(47, 'delapouite/hamster_5389261.png'),
(48, 'delapouite/gorilla_3251167.png'),
(49, 'delapouite/hippopotamus_194959.png'),
(50, 'delapouite/koala_3069172.png'),
(51, 'delapouite/pig_1960025.png'),
(52, 'delapouite/raccoon_8034530.png'),
(53, 'delapouite/rhinoceros_3831184.png'),
(54, 'delapouite/sheep_9220496.png'),
(55, 'delapouite/turtle_3436818.png'),
(56, 'delapouite/wolf_235427.png');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `student_amount` int(11) DEFAULT NULL,
  `teacher_email` varchar(255) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `student_amount`, `teacher_email`, `color_id`) VALUES
(2, 'tingting1345', 30, 'jane.smith@example.com', 1),
(22, 'chinchin4', 10, 'ikhwannabil77@gmail.com', 7),
(23, 'tingting1313', 1, 'ikhwannabil77@gmail.com', 1),
(26, 'test5', 1, 'limtingwei2003@gmail.com', 6),
(27, 'class 2', 0, 'limtingwei2003@gmail.com', 7),
(31, 'class 2', 0, 'ikhwannabil77@gmail.com', 14),
(33, 'tingting1346', 0, 'limtingwei2003@gmail.com', 8),
(34, 'tingting1344', 0, 'limtingwei2003@gmail.com', 2);

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `color_id` int(11) NOT NULL,
  `color_name` text DEFAULT NULL,
  `hex_code` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`color_id`, `color_name`, `hex_code`) VALUES
(1, 'Black', '#000000'),
(2, 'Orange', '#FFA500'),
(3, 'Red', '#FF0000'),
(4, 'Lime', '#00FF00'),
(5, 'Blue', '#0000FF'),
(6, 'Yellow', '#FFFF00'),
(7, 'Cyan', '#00FFFF'),
(8, 'Magenta', '#FF00FF'),
(9, 'Gold', '#FFD700'),
(10, 'MediumSlateBlue', '#7B68EE'),
(11, 'Aquamarine', '#7FFFD4'),
(12, 'Olive', '#808000'),
(13, 'Green', '#008000'),
(14, 'Purple', '#800080'),
(15, 'Teal', '#008080'),
(16, 'Navy', '#000080');

-- --------------------------------------------------------

--
-- Table structure for table `completion`
--

CREATE TABLE `completion` (
  `lesson_id` int(11) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `completion_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `draggable_options`
--

CREATE TABLE `draggable_options` (
  `drag_option_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `drag_option_audio` varchar(255) DEFAULT NULL,
  `drag_option_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `blank_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `lesson_id` int(11) NOT NULL,
  `lesson_name` char(100) DEFAULT NULL,
  `teacher_email` varchar(255) DEFAULT NULL,
  `question_type` enum('MCQ','TF','DragDrop') DEFAULT NULL,
  `date_changes` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson`
--

INSERT INTO `lesson` (`lesson_id`, `lesson_name`, `teacher_email`, `question_type`, `date_changes`) VALUES
(1, 'TrueFalseQuiz_Class1', 'limtingwei2003@gmail.com', 'TF', '2024-08-05 11:43:42'),
(2, 'MCQquiz_Class2', 'limtingwei2003@gmail.com', 'MCQ', '2024-08-10 11:25:59'),
(3, 'testing', 'limtingwei2003@gmail.com', 'DragDrop', '2024-08-10 11:27:59');

-- --------------------------------------------------------

--
-- Table structure for table `mcq_answer`
--

CREATE TABLE `mcq_answer` (
  `mcq_answer_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `mcq_audio` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mcq_answer`
--

INSERT INTO `mcq_answer` (`mcq_answer_id`, `question_id`, `answer_text`, `mcq_audio`, `is_correct`) VALUES
(73, 3, '2', NULL, 0),
(74, 3, '33', NULL, 0),
(75, 3, '3', NULL, 0),
(76, 3, '4', NULL, 0),
(77, 5, 'xasxasx', '', 0),
(78, 5, 'axascsdc', '', 1),
(79, 5, 'vdscs', '', 0),
(80, 5, 'dsvd', '../../src/option_audio/two.m4a', 0);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `question_text` text DEFAULT NULL,
  `question_audio` varchar(255) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `question_audio`, `lesson_id`) VALUES
(1, 'one = 2', 'src/question_audio/2.m4a', 1),
(2, 'two = 2', 'src/question_audio/two.m4a', 1),
(3, '1 + 1 = ?', NULL, 2),
(4, '26 + 5 -  2 - 3 = 26', 'src/question_audio/26.m4a', 3),
(5, 'ascsaxa', '../../src/question_audio/26 copy.m4a', 2);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_email` varchar(255) NOT NULL,
  `student_stat` tinyint(1) DEFAULT NULL,
  `student_fname` char(100) DEFAULT NULL,
  `student_lname` char(100) DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `character_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_email`, `student_stat`, `student_fname`, `student_lname`, `gender`, `character_id`, `class_id`) VALUES
('alicedaddy@gmail.com', 1, 'lee', 'Johnson', 'female', NULL, 22),
('bobmommy@gmail.com', 0, 'Bob', 'Brown', 'Male', NULL, 22),
('limtingwei2003@gmail.com', NULL, 'Lim', 'Ting Wei', 'male', 6, 26),
('limtingwei200@gmail.com', NULL, 'Lim', 'Ting Wei', 'male', 5, 33);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `teacher_name` char(100) DEFAULT NULL,
  `teacher_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_email`, `password`, `teacher_name`, `teacher_number`) VALUES
('ikhwannabil77@gmail.com', '1234567890', 'nabil', '0129582395'),
('jane.smith@example.com', 'password456', 'Jane Smith', '+9876543210'),
('john.doe@example.com', 'password123', 'John Doe', '+1234567890'),
('limtingwei2003@gmail.com', '456123789', 'admin', '0182982586');

-- --------------------------------------------------------

--
-- Table structure for table `true_false_options`
--

CREATE TABLE `true_false_options` (
  `tfoption_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `is_true` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `true_false_options`
--

INSERT INTO `true_false_options` (`tfoption_id`, `question_id`, `is_true`) VALUES
(1, 1, 0),
(2, 2, 1),
(3, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE `words` (
  `word_id` int(11) NOT NULL,
  `word_text` text DEFAULT NULL,
  `is_encouragement` tinyint(4) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `words`
--

INSERT INTO `words` (`word_id`, `word_text`, `is_encouragement`, `img_path`, `question_id`) VALUES
(1, 'Keep up', 1, '', 1),
(2, '', 0, '', 2),
(3, 'Do not let what you cannot do interfere with what you can do.', 1, NULL, 3),
(4, '', 0, '', 4),
(5, 'axaxa', 1, '../../src/encouragement_source/main-qimg-6d93167b7062f53c6060361360ead4f8-pjlq.jpeg', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assigned`
--
ALTER TABLE `assigned`
  ADD PRIMARY KEY (`assign_key`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`character_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `teacher_email` (`teacher_email`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `completion`
--
ALTER TABLE `completion`
  ADD PRIMARY KEY (`lesson_id`,`student_email`),
  ADD KEY `student_email` (`student_email`);

--
-- Indexes for table `draggable_options`
--
ALTER TABLE `draggable_options`
  ADD PRIMARY KEY (`drag_option_id`),
  ADD KEY `question_id` (`question_id`);


--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `teacher_email` (`teacher_email`);

--
-- Indexes for table `mcq_answer`
--
ALTER TABLE `mcq_answer`
  ADD PRIMARY KEY (`mcq_answer_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_email`),
  ADD KEY `character_id` (`character_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_email`);

--
-- Indexes for table `true_false_options`
--
ALTER TABLE `true_false_options`
  ADD PRIMARY KEY (`tfoption_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`word_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assigned`
--
ALTER TABLE `assigned`
  MODIFY `assign_key` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `character`
--
ALTER TABLE `character`
  MODIFY `character_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `draggable_options`
--
ALTER TABLE `draggable_options`
  MODIFY `drag_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mcq_answer`
--
ALTER TABLE `mcq_answer`
  MODIFY `mcq_answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `true_false_options`
--
ALTER TABLE `true_false_options`
  MODIFY `tfoption_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `word_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned`
--
ALTER TABLE `assigned`
  ADD CONSTRAINT `assigned_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`lesson_id`),
  ADD CONSTRAINT `assigned_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_email`) REFERENCES `teacher` (`teacher_email`),
  ADD CONSTRAINT `class_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `color` (`color_id`);

--
-- Constraints for table `completion`
--
ALTER TABLE `completion`
  ADD CONSTRAINT `completion_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`lesson_id`),
  ADD CONSTRAINT `completion_ibfk_2` FOREIGN KEY (`student_email`) REFERENCES `student` (`student_email`);

--
-- Constraints for table `dragdropmapping`
--
ALTER TABLE `draggable_options`
  ADD CONSTRAINT `draggable_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

--
-- Constraints for table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `lesson_ibfk_1` FOREIGN KEY (`teacher_email`) REFERENCES `teacher` (`teacher_email`);

--
-- Constraints for table `mcq_answer`
--
ALTER TABLE `mcq_answer`
  ADD CONSTRAINT `mcq_answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`lesson_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`character_id`) REFERENCES `character` (`character_id`);

--
-- Constraints for table `true_false_options`
--
ALTER TABLE `true_false_options`
  ADD CONSTRAINT `true_false_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

--
-- Constraints for table `words`
--
ALTER TABLE `words`
  ADD CONSTRAINT `words_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
