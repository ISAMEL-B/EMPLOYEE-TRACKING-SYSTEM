-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 07:53 AM
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
(2, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Masters', 'Masters', 12, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(3, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Bachelor\'s (First Class)', 'Bachelor&#039;s (First Class)', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(4, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Bachelor\'s (Second Upper)', 'Bachelor&#039;s (Second Upper)', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(5, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Other Qualifications', 'Other Qualifications', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(6, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (First author)', 'Peer-reviewed Journal (First author)', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(7, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (Corresponding author)', 'Peer-reviewed Journal (Corresponding author)', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(8, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (Co-author)', 'Peer-reviewed Journal (Co-author)', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(9, 'Academic and Professional Qualifications (Non-clinical Scholars)', '1 point per year', '1 point per year', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(10, 'Academic and Professional Qualifications (Clinical Scholars)', 'PhD or being on PhD track', 'PhD or being on PhD track', 8, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(11, 'Academic and Professional Qualifications (Clinical Scholars)', 'Bachelor\'s degree (First class)', 'Bachelor&#039;s degree (First class)', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(12, 'Academic and Professional Qualifications (Clinical Scholars)', 'Bachelor\'s degree (Second upper)', 'Bachelor&#039;s degree (Second upper)', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(13, 'Academic and Professional Qualifications (Clinical Scholars)', 'Other academic and professional qualifications', 'Other academic and professional qualifications', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(14, 'Research Grants and Collaborations', 'More than UGX 1,000,000,000', 'More than UGX 1,000,000,000', 12, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(15, 'Research Grants and Collaborations', 'UGX 500,000,000 - 1,000,000,000', 'UGX 500,000,000 - 1,000,000,000', 8, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(16, 'Research Grants and Collaborations', 'UGX 100,000,000 - 500,000,000', 'UGX 100,000,000 - 500,000,000', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(17, 'Research Grants and Collaborations', 'Less than UGX 100,000,000', 'Less than UGX 100,000,000', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(18, 'Supervision of Students', 'PhD Candidates (max 10)', 'PhD Candidates (max 10)', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(19, 'Supervision of Students', 'Masters Candidates (max 5)', 'Masters Candidates (max 5)', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(20, 'Teaching Courses', 'More than 6 Courses', 'More than 6 Courses', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(21, 'Teaching Courses', '4 - 6 Courses', '4 - 6 Courses', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(22, 'Teaching Courses', 'Less than 4 Courses', 'Less than 4 Courses', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(23, 'Intellectual Property', 'Patent', 'Patent', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(24, 'Intellectual Property', 'Utility Model', 'Utility Model', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(25, 'Intellectual Property', 'Copyright', 'Copyright', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(26, 'Intellectual Property', 'Product', 'Product', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(27, 'Intellectual Property', 'Trademark', 'Trademark', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(28, 'Administrative Roles', 'Dean / Director', 'Dean / Director', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(29, 'Administrative Roles', 'Deputy Dean/Director', 'Deputy Dean/Director', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(30, 'Administrative Roles', 'Head of Department', 'Head of Department', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(31, 'Administrative Roles', 'Other', 'Other', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(32, 'International Collaboration', 'Collaborations with international organizations', 'Collaborations with international organizations', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(33, 'International Collaboration', 'Exchange programs with international institutions', 'Exchange programs with international institutions', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(34, 'International Collaboration', 'Joint research initiatives', 'Joint research initiatives', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(35, 'Teaching Assistants', 'Teaching experience (max 3 years)', 'Teaching experience (max 3 years)', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(36, 'Teaching Assistants', 'Research contributions', 'Research contributions', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(37, 'Teaching Assistants', 'Participation in workshops or seminars', 'Participation in workshops or seminars', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(38, 'Overall', 'Overall', 'Overall', 120, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(39, 'General', 'crit_6512bd43d9caa6e02c990b0a82652dca', 'crit_6512bd43d9caa6e02c990b0a82652dca', 12, '2025-04-06 20:06:32', '2025-04-10 02:45:29'),
(40, 'General', 'crit_4295ed0c9cbd0dc7e7476c91e7be83c0', 'PhD', 9, '2025-04-10 02:45:29', '2025-04-10 02:45:29');

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
(33, 'staff', '/uploads/staff_20230501.csv', 1, '2023-05-01 09:15:22', 12, 'approved', 2, '2025-04-10 04:07:31', NULL),
(34, 'publications', '/uploads/publications_20230502.csv', 2, '2023-05-02 14:30:45', 8, 'approved', 2, '2025-04-09 23:00:02', NULL),
(35, 'grants', '/uploads/grants_20230503.csv', 3, '2023-05-03 11:20:33', 5, 'pending', NULL, NULL, NULL),
(36, 'departments', '/uploads/departments_20230504.csv', 1, '2023-05-04 16:45:12', 3, 'pending', NULL, NULL, NULL),
(37, 'staff', '/uploads/staff_20230505.csv', 2, '2023-05-05 10:05:18', 7, 'rejected', 2, '2025-04-10 04:48:06', 'dddf'),
(38, 'publications', '/uploads/publications_20230506.csv', 3, '2023-05-06 13:25:09', 6, 'approved', 2, '2025-04-10 04:10:41', NULL),
(39, 'grants', '/uploads/grants_20230507.csv', 1, '2023-05-07 15:40:27', 4, 'approved', 2, '2025-04-10 04:09:12', NULL),
(40, 'degrees', '/uploads/degrees_20230508.csv', 2, '2023-05-08 08:50:14', 9, 'approved', 2, '2025-04-10 04:07:17', NULL),
(41, 'staff', '/uploads/staff_20230509.csv', 3, '2023-05-09 12:35:41', 11, 'approved', 2, '2025-04-10 04:07:01', NULL),
(42, 'innovations', '/uploads/innovations_20230510.csv', 1, '2023-05-10 17:10:53', 5, 'approved', 2, '2025-04-10 04:06:06', NULL);

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
(2, 2, 700000000.00),
(3, 3, 400000000.00),
(4, 4, 80000000.00);

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
-- Table structure for table `performance_metrics`
--

CREATE TABLE `performance_metrics` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `metric_name` varchar(100) NOT NULL,
  `metric_value` decimal(10,2) NOT NULL,
  `target_value` decimal(10,2) DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_metrics`
--

INSERT INTO `performance_metrics` (`id`, `staff_id`, `department_id`, `metric_name`, `metric_value`, `target_value`, `period_start`, `period_end`, `recorded_at`, `remarks`) VALUES
(21, 2, 2, 'Research Publications', 5.00, 10.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'Q1 research output'),
(22, 4, 4, 'Student Supervisions', 3.00, 5.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'Fewer due to leave'),
(23, 5, 5, 'Community Engagements', 2.00, 3.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'Attended rural health workshop'),
(24, 6, 6, 'Projects Completed', 4.00, 4.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'All deliverables met'),
(25, 7, 1, 'Training Sessions', 6.00, 5.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'Overachieved goal'),
(26, 8, 2, 'Grants Acquired', 1.00, 2.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', '1 grant approved'),
(27, 9, 3, 'Conference Presentations', 2.00, 3.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'Presented at MUST Symposium'),
(28, 10, 4, 'Workshops Conducted', 1.00, 2.00, '2024-01-01', '2024-03-31', '2025-04-10 04:08:02', 'Limited by funding');

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
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profile_id` int(10) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `register_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `role` varchar(30) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `personal_email` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `employee_id`, `email`, `password`, `role`, `first_name`, `last_name`, `phone_number`, `personal_email`, `date_created`, `photo_path`) VALUES
(1, 'ar', 'ar@must.ac.ug', '$2y$10$GQF8pAt4Oc4EJyP/k4xEVu2npOoPUiSLLv50tQXv/OkdpZ.A3PZIe', 'ar', 'Mugabi', 'Praise', '0757003628', 'praise@gmail.com', '2025-04-09 20:15:47', NULL),
(2, 'hrm', 'hrm@must.ac.ug', '$2y$10$yGUKLVHLji2ZybkxjpGXD.XMvYyV6f42s46iktELm4nawd1znaSJS', 'hrm', 'Byaruhanga', 'Isamel', '0757003628', 'byaruhangangaisamelk@gmail.com', '2025-04-09 20:15:47', 'uploads/profile_photos/user_2_1744229873.png'),
(3, 'hod', 'hod@must.ac.ug', '$2y$10$MD7A6e554ok4jURO/q.R7OeF8.4AfUQ8/m4IBl8RjGhFMotqjD5L.', 'hod', 'ww', 'rr', '0757003628', 'byaruhangangaisamelk@gmail.com', '2025-04-09 20:15:47', 'uploads/profile_photos/user_3_1744223535.jpeg');

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
-- Indexes for table `performance_metrics`
--
ALTER TABLE `performance_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `professionalbodies`
--
ALTER TABLE `professionalbodies`
  ADD PRIMARY KEY (`professional_body_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
-- AUTO_INCREMENT for table `performance_metrics`
--
ALTER TABLE `performance_metrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `professionalbodies`
--
ALTER TABLE `professionalbodies`
  MODIFY `professional_body_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(10) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `performance_metrics`
--
ALTER TABLE `performance_metrics`
  ADD CONSTRAINT `performance_metrics_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `performance_metrics_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

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
