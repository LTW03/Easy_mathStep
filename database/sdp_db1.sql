-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2024 at 04:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `student_amount` int(11) DEFAULT NULL,
  `grade_level` char(10) DEFAULT NULL,
  `teacher_email` varchar(255) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `student_amount`, `grade_level`, `teacher_email`, `color_id`) VALUES
(1, 25, 'Grade 1', 'john.doe@example.com', 1),
(2, 30, 'Grade 2', 'jane.smith@example.com', 2);

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
-- Table structure for table `dragdropmapping`
--

CREATE TABLE `dragdropmapping` (
  `mapping_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `draggable_item` int(11) DEFAULT NULL,
  `droppable_target` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `draggable_options`
--

CREATE TABLE `draggable_options` (
  `drag_option_id` int(11) NOT NULL,
  `drag_option_audio` varchar(255) DEFAULT NULL,
  `drag_option_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `droppable`
--

CREATE TABLE `droppable` (
  `droppable_id` int(11) NOT NULL,
  `droppable_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `lesson_id` int(11) NOT NULL,
  `subjects` char(100) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `date_changes` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mcq_answer`
--

CREATE TABLE `mcq_answer` (
  `mcq_answer_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `mcq_audio` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `question_text` text DEFAULT NULL,
  `question_audio` varchar(255) DEFAULT NULL,
  `question_type` enum('MCQ','TF','DragDrop') DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `student_stat` tinyint(1) DEFAULT NULL,
  `student_name` char(100) DEFAULT NULL,
  `character_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_stat`, `student_name`) VALUES
(1, 1, 'Alice Johnson'),
(2, 0, 'Bob Brown');

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
('jane.smith@example.com', 'password456', 'Jane Smith', '+9876543210'),
('john.doe@example.com', 'password123', 'John Doe', '+1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `true_false_options`
--

CREATE TABLE `true_false_options` (
  `tfoption_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `tfoption_audio` varchar(255) DEFAULT NULL,
  `is_true` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE `words` (
  `word_id` int(11) NOT NULL,
  `word_text` text DEFAULT NULL,
  `is_encouragement` tinyint(4) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `character`
--
CREATE TABLE `character`(
  `character_id` int(11) NOT NULL,
  `character_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `dragdropmapping`
--
ALTER TABLE `dragdropmapping`
  ADD PRIMARY KEY (`mapping_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `draggable_item` (`draggable_item`),
  ADD KEY `droppable_target` (`droppable_target`);

--
-- Indexes for table `draggable_options`
--
ALTER TABLE `draggable_options`
  ADD PRIMARY KEY (`drag_option_id`);

--
-- Indexes for table `droppable`
--
ALTER TABLE `droppable`
  ADD PRIMARY KEY (`droppable_id`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `class_id` (`class_id`);

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
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `class_id`(`class_id`),
  ADD KEY `character_id`(`character_id`);

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
-- Indexes for table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`character_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dragdropmapping`
--
ALTER TABLE `dragdropmapping`
  MODIFY `mapping_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `draggable_options`
--
ALTER TABLE `draggable_options`
  MODIFY `drag_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `droppable`
--
ALTER TABLE `droppable`
  MODIFY `droppable_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mcq_answer`
--
ALTER TABLE `mcq_answer`
  MODIFY `mcq_answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `true_false_options`
--
ALTER TABLE `true_false_options`
  MODIFY `tfoption_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `word_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `character`
--
ALTER TABLE `character`
  MODIFY `character_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_email`) REFERENCES `teacher` (`teacher_email`),
  ADD CONSTRAINT `class_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `color` (`color_id`);

--
-- Constraints for table `dragdropmapping`
--
ALTER TABLE `dragdropmapping`
  ADD CONSTRAINT `dragdropmapping_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `dragdropmapping_ibfk_2` FOREIGN KEY (`draggable_item`) REFERENCES `draggable_options` (`drag_option_id`),
  ADD CONSTRAINT `dragdropmapping_ibfk_3` FOREIGN KEY (`droppable_target`) REFERENCES `droppable` (`droppable_id`);

--
-- Constraints for table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `lesson_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

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

--
-- Constraint for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1`FOREIGN KEY(`class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `student_ibfk_2`FOREIGN KEY(`character_id`) REFERENCES `character` (`character_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
