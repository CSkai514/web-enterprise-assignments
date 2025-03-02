-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 09:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `universitymagazine`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`) VALUES
(6);

-- --------------------------------------------------------

--
-- Table structure for table `closuredates`
--

CREATE TABLE `closuredates` (
  `academic_year` year(4) NOT NULL,
  `new_closure` date NOT NULL,
  `final_closure` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `closuredates`
--

INSERT INTO `closuredates` (`academic_year`, `new_closure`, `final_closure`) VALUES
('2025', '2025-03-01', '2025-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `contribution`
--

CREATE TABLE `contribution` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` enum('article','image') NOT NULL,
  `status` enum('draft','submitted','selected','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contribution`
--

INSERT INTO `contribution` (`id`, `user_id`, `title`, `file_path`, `submitted_at`, `updated_at`, `type`, `status`) VALUES
(1, 1, 'AI in Education', 'uploads/ai_education.docx', '2025-02-24 11:13:13', '2025-02-24 11:13:13', 'article', 'submitted'),
(2, 1, 'University Life', 'uploads/university_life.jpg', '2025-02-24 11:13:13', '2025-02-24 11:13:13', 'image', 'submitted'),
(3, 2, 'Financial Management Tips', 'uploads/finance_tips.docx', '2025-02-24 11:13:13', '2025-02-24 11:13:13', 'article', 'draft');

-- --------------------------------------------------------

--
-- Table structure for table `coordinatorcomment`
--

CREATE TABLE `coordinatorcomment` (
  `id` int(11) NOT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `coordinator_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `commented_at` datetime DEFAULT current_timestamp(),
  `comment_deadline` datetime NOT NULL,
  `decision` enum('approved','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `coordinatorcomment`
--

INSERT INTO `coordinatorcomment` (`id`, `contribution_id`, `coordinator_id`, `comment`, `commented_at`, `comment_deadline`, `decision`) VALUES
(1, 1, 3, 'Great topic! Needs minor revisions.', '2025-02-24 11:13:13', '2025-03-15 00:00:00', 'approved'),
(2, 2, 4, 'High quality image. Approved.', '2025-02-24 11:13:13', '2025-03-18 00:00:00', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `emailnotification`
--

CREATE TABLE `emailnotification` (
  `id` int(11) NOT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `emailnotification`
--

INSERT INTO `emailnotification` (`id`, `contribution_id`, `sent_at`) VALUES
(1, 1, '2025-02-24 11:13:13'),
(2, 2, '2025-02-24 11:13:13');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `name`) VALUES
(3, 'Arts'),
(2, 'Business'),
(1, 'Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `marketingcoordinator`
--

CREATE TABLE `marketingcoordinator` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `marketingcoordinator`
--

INSERT INTO `marketingcoordinator` (`user_id`) VALUES
(3),
(4);

-- --------------------------------------------------------

--
-- Table structure for table `marketingmanager`
--

CREATE TABLE `marketingmanager` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `marketingmanager`
--

INSERT INTO `marketingmanager` (`user_id`) VALUES
(5);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `user_id` int(11) NOT NULL,
  `submissions_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`user_id`, `submissions_count`) VALUES
(1, 2),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` enum('student','coordinator','manager','admin') NOT NULL,
  `tcs_agreed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password_hash`, `faculty_id`, `created_at`, `role`, `tcs_agreed`) VALUES
(1, 'student1@university.com', 'hashedpassword1', 1, '2025-02-24 11:13:13', 'student', 1),
(2, 'student2@university.com', 'hashedpassword2', 2, '2025-02-24 11:13:13', 'student', 1),
(3, 'coordinator1@university.com', 'hashedpassword3', 1, '2025-02-24 11:13:13', 'coordinator', 1),
(4, 'coordinator2@university.com', 'hashedpassword4', 2, '2025-02-24 11:13:13', 'coordinator', 1),
(5, 'manager@university.com', 'hashedpassword5', NULL, '2025-02-24 11:13:13', 'manager', 1),
(6, 'admin@university.com', 'hashedpassword6', NULL, '2025-02-24 11:13:13', 'admin', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `closuredates`
--
ALTER TABLE `closuredates`
  ADD PRIMARY KEY (`academic_year`);

--
-- Indexes for table `contribution`
--
ALTER TABLE `contribution`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_contribution_status` (`status`);

--
-- Indexes for table `coordinatorcomment`
--
ALTER TABLE `coordinatorcomment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contribution_id` (`contribution_id`),
  ADD KEY `coordinator_id` (`coordinator_id`);

--
-- Indexes for table `emailnotification`
--
ALTER TABLE `emailnotification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contribution_id` (`contribution_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `idx_faculty_name` (`name`);

--
-- Indexes for table `marketingcoordinator`
--
ALTER TABLE `marketingcoordinator`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `marketingmanager`
--
ALTER TABLE `marketingmanager`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `idx_user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contribution`
--
ALTER TABLE `contribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coordinatorcomment`
--
ALTER TABLE `coordinatorcomment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emailnotification`
--
ALTER TABLE `emailnotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contribution`
--
ALTER TABLE `contribution`
  ADD CONSTRAINT `contribution_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `student` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `coordinatorcomment`
--
ALTER TABLE `coordinatorcomment`
  ADD CONSTRAINT `coordinatorcomment_ibfk_1` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coordinatorcomment_ibfk_2` FOREIGN KEY (`coordinator_id`) REFERENCES `marketingcoordinator` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `emailnotification`
--
ALTER TABLE `emailnotification`
  ADD CONSTRAINT `emailnotification_ibfk_1` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketingcoordinator`
--
ALTER TABLE `marketingcoordinator`
  ADD CONSTRAINT `marketingcoordinator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketingmanager`
--
ALTER TABLE `marketingmanager`
  ADD CONSTRAINT `marketingmanager_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
