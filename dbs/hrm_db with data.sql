-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 10:21 PM
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
-- Database: `hrm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academicactivities`
--

CREATE TABLE `academicactivities` (
  `activity_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academicactivities`
--

INSERT INTO `academicactivities` (`activity_id`, `staff_id`, `activity_type`) VALUES
(1, 1, 'External Examination'),
(2, 2, 'Internal Examination'),
(3, 3, 'Conference Presentation'),
(4, 4, 'Journal Editor');

-- --------------------------------------------------------

--
-- Table structure for table `communityservice`
--

CREATE TABLE `communityservice` (
  `community_service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communityservice`
--

INSERT INTO `communityservice` (`community_service_id`, `staff_id`, `description`) VALUES
(1, 1, 'Volunteer Teaching'),
(2, 2, 'Community Cleanup'),
(3, 3, 'Technical Workshop'),
(4, 4, 'Health Awareness Campaign');

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `points` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `category`, `name`, `display_name`, `points`, `created_at`, `updated_at`) VALUES
(2, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Masters', 'Masters', 12, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(3, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Bachelor\'s (First Class)', 'Bachelor&#039;s (First Class)', 6, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(4, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Bachelor\'s (Second Upper)', 'Bachelor&#039;s (Second Upper)', 4, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(5, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Other Qualifications', 'Other Qualifications', 2, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(6, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (First author)', 'Peer-reviewed Journal (First author)', 4, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(7, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (Corresponding author)', 'Peer-reviewed Journal (Corresponding author)', 2, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(8, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (Co-author)', 'Peer-reviewed Journal (Co-author)', 1, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(9, 'Academic and Professional Qualifications (Non-clinical Scholars)', '1 point per year', '1 point per year', 1, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(10, 'Academic and Professional Qualifications (Clinical Scholars)', 'PhD or being on PhD track', 'PhD or being on PhD track (added advantage)', 8, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(11, 'Academic and Professional Qualifications (Clinical Scholars)', 'Bachelor\'s degree (First class)', 'Bachelor&#039;s degree: First class', 0, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(12, 'Academic and Professional Qualifications (Clinical Scholars)', 'Bachelor\'s degree (Second upper)', 'Bachelor&#039;s degree: Second upper', 0, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(13, 'Academic and Professional Qualifications (Clinical Scholars)', 'Other academic and professional qualifications', 'Other academic and professional qualifications', 1, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(14, 'Research Grants and Collaborations', 'More than UGX 1,000,000,000', 'More than UGX 1,000,000,000', 12, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(15, 'Research Grants and Collaborations', 'UGX 500,000,000 - 1,000,000,000', 'UGX 500,000,000 - 1,000,000,000', 8, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(16, 'Research Grants and Collaborations', 'UGX 100,000,000 - 500,000,000', 'UGX 100,000,000 - 500,000,000', 6, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(17, 'Research Grants and Collaborations', 'Less than UGX 100,000,000', 'Less than UGX 100,000,000', 4, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(18, 'Supervision of Students', 'PhD Candidates (max 10)', 'PhD Candidates (max 10)', 5, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(19, 'Supervision of Students', 'Masters Candidates (max 5)', 'Masters Candidates (max 5)', 2, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(20, 'Teaching Courses', 'More than 6 Courses', 'More than 6 Courses', 5, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(21, 'Teaching Courses', '4 - 6 Courses', '4 - 6 Courses', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(22, 'Teaching Courses', 'Less than 4 Courses', 'Less than 4 Courses', 2, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(23, 'Intellectual Property', 'Patent', 'Patent', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(24, 'Intellectual Property', 'Utility Model', 'Utility Model', 4, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(25, 'Intellectual Property', 'Copyright', 'Copyright', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(26, 'Intellectual Property', 'Product', 'Product', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(27, 'Intellectual Property', 'Trademark', 'Trademark', 1, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(28, 'Administrative Roles', 'Dean / Director', 'Dean / Director', 5, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(29, 'Administrative Roles', 'Deputy Dean/Director', 'Deputy Dean/Director', 4, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(30, 'Administrative Roles', 'Head of Department', 'Head of Department', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(31, 'Administrative Roles', 'Other', 'Other', 1, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(32, 'International Collaboration', 'Collaborations with international organizations', 'Collaborations with international organizations', 5, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(33, 'International Collaboration', 'Exchange programs with international institutions', 'Exchange programs with international institutions', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(34, 'International Collaboration', 'Joint research initiatives', 'Joint research initiatives', 2, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(35, 'Teaching Assistants', 'Teaching experience (max 3 years)', 'Teaching experience (max 3 years)', 4, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(36, 'Teaching Assistants', 'Research contributions', 'Research contributions', 3, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(37, 'Teaching Assistants', 'Participation in workshops or seminars', 'Participation in workshops or seminars', 1, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(38, 'Overall', 'Overall', 'Overall Points', 120, '2025-04-06 20:04:55', '2025-04-06 20:06:58'),
(39, 'General', 'crit_6512bd43d9caa6e02c990b0a82652dca', '11', 12, '2025-04-06 20:06:32', '2025-04-06 20:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `csv_approvals`
--

CREATE TABLE `csv_approvals` (
  `id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `record_count` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `csv_approvals`
--

INSERT INTO `csv_approvals` (`id`, `table_name`, `file_path`, `submitted_by`, `submitted_at`, `record_count`, `status`, `reviewed_by`, `reviewed_at`, `rejection_reason`) VALUES
(33, 'staff', '/uploads/staff_20230501.csv', 1, '2023-05-01 09:15:22', 12, 'pending', NULL, NULL, NULL),
(34, 'publications', '/uploads/publications_20230502.csv', 2, '2023-05-02 14:30:45', 8, 'pending', NULL, NULL, NULL),
(35, 'grants', '/uploads/grants_20230503.csv', 3, '2023-05-03 11:20:33', 5, 'pending', NULL, NULL, NULL),
(36, 'departments', '/uploads/departments_20230504.csv', 1, '2023-05-04 16:45:12', 3, 'pending', NULL, NULL, NULL),
(37, 'staff', '/uploads/staff_20230505.csv', 2, '2023-05-05 10:05:18', 7, 'pending', NULL, NULL, NULL),
(38, 'publications', '/uploads/publications_20230506.csv', 3, '2023-05-06 13:25:09', 6, 'pending', NULL, NULL, NULL),
(39, 'grants', '/uploads/grants_20230507.csv', 1, '2023-05-07 15:40:27', 4, 'pending', NULL, NULL, NULL),
(40, 'degrees', '/uploads/degrees_20230508.csv', 2, '2023-05-08 08:50:14', 9, 'pending', NULL, NULL, NULL),
(41, 'staff', '/uploads/staff_20230509.csv', 3, '2023-05-09 12:35:41', 11, 'pending', NULL, NULL, NULL),
(42, 'innovations', '/uploads/innovations_20230510.csv', 1, '2023-05-10 17:10:53', 5, 'pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `degrees`
--

CREATE TABLE `degrees` (
  `degree_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `degree_name` varchar(50) DEFAULT NULL,
  `degree_classification` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`degree_id`, `staff_id`, `degree_name`, `degree_classification`) VALUES
(1, 1, 'Bachelor of Science in Software Engineering', 'First Class'),
(2, 2, 'Bachelor of Science in Information Technology', 'Second Class Upper'),
(3, 3, 'Bachelor of Science in Computer Science', 'Second Class Lower'),
(4, 4, 'Bachelor of Engineering in Telecommunication', 'First Class');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'Computer Science'),
(2, 'Information Technology'),
(3, 'Electrical Engineering'),
(4, 'Mechanical Engineerings'),
(5, 'Civil Engineering'),
(6, 'Human Resource Management');

-- --------------------------------------------------------

--
-- Table structure for table `grants`
--

CREATE TABLE `grants` (
  `grant_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `grant_amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grants`
--

INSERT INTO `grants` (`grant_id`, `staff_id`, `grant_amount`) VALUES
(1, 1, 1500000000.00),
(2, 2, 800000000.00),
(3, 3, 450000000.00),
(4, 4, 50000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `innovations`
--

CREATE TABLE `innovations` (
  `innovation_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `innovation_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `innovations`
--

INSERT INTO `innovations` (`innovation_id`, `staff_id`, `innovation_type`) VALUES
(1, 1, 'Patent'),
(2, 2, 'Utility Model'),
(3, 3, 'Copyrigh');

-- --------------------------------------------------------

--
-- Table structure for table `professionalbodies`
--

CREATE TABLE `professionalbodies` (
  `professional_body_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `body_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professionalbodies`
--

INSERT INTO `professionalbodies` (`professional_body_id`, `staff_id`, `body_name`) VALUES
(1, 1, 'IEEE'),
(2, 2, 'ACM'),
(3, 3, 'ISTE'),
(4, 4, 'National Engineers Society');

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `publication_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `publication_type` varchar(50) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publications`
--

INSERT INTO `publications` (`publication_id`, `staff_id`, `publication_type`, `role`) VALUES
(1, 1, 'Journal Article', 'Author'),
(2, 2, 'Conference Paper', 'Co-Author'),
(3, 2, 'Journal Article', 'Co-Author');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Professor'),
(2, 'Associate Professor'),
(3, 'Senior Lecturer'),
(4, 'Lecturer'),
(5, 'Assistant Lecturer'),
(6, 'Teaching Assistant');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `staff_id`, `service_type`) VALUES
(1, 1, 'Dean'),
(2, 2, 'Head of Department'),
(3, 3, 'Committee Member'),
(4, 4, 'Deputy Director');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `scholar_type` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `performance_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `last_name`, `scholar_type`, `role_id`, `department_id`, `years_of_experience`, `performance_score`) VALUES
(1, 'John', 'Aine', 'Full_Time', 1, 1, 10, 85),
(2, 'Akanyoreka', 'Smith', 'Part_Time', 2, 2, 5, 90),
(4, 'Emily', 'Davidson', 'Adjunct', 4, 4, 15, 88),
(5, 'David', 'Wilson', 'Full_Time', 5, 5, 8, 75),
(6, 'Sarah', 'Connor', 'Part_Time', 6, 6, 2, 70),
(7, 'Robert', 'Brown', 'Visiting', 4, 1, 6, 82),
(8, 'Alice', 'Williams', 'Adjunct', 2, 2, 12, 89),
(9, 'James', 'Anderson', 'Full_Time', 1, 3, 11, 86),
(10, 'Linda', 'Taylor', 'Part_Time', 3, 4, 7, 78);

-- --------------------------------------------------------

--
-- Table structure for table `supervision`
--

CREATE TABLE `supervision` (
  `supervision_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervision`
--

INSERT INTO `supervision` (`supervision_id`, `staff_id`, `student_level`) VALUES
(1, 1, 'PhD'),
(2, 2, 'Masters'),
(3, 3, 'PhD'),
(4, 4, 'Masters');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `employee_id` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `employee_id`, `email`, `password`, `role`) VALUES
(1, 'ar', 'ar@must.ac.ug', '$2y$10$GQF8pAt4Oc4EJyP/k4xEVu2npOoPUiSLLv50tQXv/OkdpZ.A3PZIe', 'ar'),
(2, 'hrm', 'hrm@must.ac.ug', '$2y$10$yGUKLVHLji2ZybkxjpGXD.XMvYyV6f42s46iktELm4nawd1znaSJS', 'hrm'),
(3, 'hod', 'hod@must.ac.ug', '$2y$10$MD7A6e554ok4jURO/q.R7OeF8.4AfUQ8/m4IBl8RjGhFMotqjD5L.', 'hod');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academicactivities`
--
ALTER TABLE `academicactivities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `communityservice`
--
ALTER TABLE `communityservice`
  ADD PRIMARY KEY (`community_service_id`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `csv_approvals`
--
ALTER TABLE `csv_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `fk_submitted_by` (`submitted_by`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`degree_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `grants`
--
ALTER TABLE `grants`
  ADD PRIMARY KEY (`grant_id`);

--
-- Indexes for table `innovations`
--
ALTER TABLE `innovations`
  ADD PRIMARY KEY (`innovation_id`);

--
-- Indexes for table `professionalbodies`
--
ALTER TABLE `professionalbodies`
  ADD PRIMARY KEY (`professional_body_id`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`publication_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `fk_role_id` (`role_id`),
  ADD KEY `fk_department_id` (`department_id`);

--
-- Indexes for table `supervision`
--
ALTER TABLE `supervision`
  ADD PRIMARY KEY (`supervision_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academicactivities`
--
ALTER TABLE `academicactivities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `communityservice`
--
ALTER TABLE `communityservice`
  MODIFY `community_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `csv_approvals`
--
ALTER TABLE `csv_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `degrees`
--
ALTER TABLE `degrees`
  MODIFY `degree_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `grants`
--
ALTER TABLE `grants`
  MODIFY `grant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `innovations`
--
ALTER TABLE `innovations`
  MODIFY `innovation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `professionalbodies`
--
ALTER TABLE `professionalbodies`
  MODIFY `professional_body_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `publication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `supervision`
--
ALTER TABLE `supervision`
  MODIFY `supervision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `csv_approvals`
--
ALTER TABLE `csv_approvals`
  ADD CONSTRAINT `csv_approvals_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_submitted_by` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_department_id` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
