-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 12:04 AM
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
  `points` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `category`, `name`, `points`, `created_at`, `updated_at`) VALUES
(2, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Masters', 12, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(3, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Bachelor\'s (First Class)', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(4, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Bachelor\'s (Second Upper)', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(5, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Other Qualifications', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(6, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (First author)', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(7, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (Corresponding author)', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(8, 'Academic and Professional Qualifications (Non-clinical Scholars)', 'Peer-reviewed Journal (Co-author)', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(9, 'Academic and Professional Qualifications (Non-clinical Scholars)', '1 point per year', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(10, 'Academic and Professional Qualifications (Clinical Scholars)', 'PhD or being on PhD track', 12, '2025-04-06 20:04:55', '2025-04-14 12:48:13'),
(11, 'Academic and Professional Qualifications (Clinical Scholars)', 'Bachelor\'s degree (First class)', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(12, 'Academic and Professional Qualifications (Clinical Scholars)', 'Bachelor\'s degree (Second upper)', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(13, 'Academic and Professional Qualifications (Clinical Scholars)', 'Other academic and professional qualifications', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(14, 'Research Grants and Collaborations', 'More than UGX 1,000,000,000', 12, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(15, 'Research Grants and Collaborations', 'UGX 500,000,000 - 1,000,000,000', 8, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(16, 'Research Grants and Collaborations', 'UGX 100,000,000 - 500,000,000', 6, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(17, 'Research Grants and Collaborations', 'Less than UGX 100,000,000', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(18, 'Supervision of Students', 'PhD Candidates (max 10)', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(19, 'Supervision of Students', 'Masters Candidates (max 5)', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(20, 'Teaching Courses', 'More than 6 Courses', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(21, 'Teaching Courses', '4 - 6 Courses', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(22, 'Teaching Courses', 'Less than 4 Courses', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(23, 'Intellectual Property', 'Patent', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(24, 'Intellectual Property', 'Utility Model', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(25, 'Intellectual Property', 'Copyright', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(26, 'Intellectual Property', 'Product', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(27, 'Intellectual Property', 'Trademark', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(28, 'Administrative Roles', 'Dean / Director', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(29, 'Administrative Roles', 'Deputy Dean/Director', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(30, 'Administrative Roles', 'Head of Department', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(31, 'Administrative Roles', 'Other', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(32, 'International Collaboration', 'Collaborations with international organizations', 5, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(33, 'International Collaboration', 'Exchange programs with international institutions', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(34, 'International Collaboration', 'Joint research initiatives', 2, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(35, 'Teaching Assistants', 'Teaching experience (max 3 years)', 4, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(36, 'Teaching Assistants', 'Research contributions', 3, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(37, 'Teaching Assistants', 'Participation in workshops or seminars', 1, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(38, 'Overall', 'Overall', 120, '2025-04-06 20:04:55', '2025-04-10 02:45:29'),
(41, 'Academic and Professional Qualifications (Clinical Scholars)', 'masters', 6, '2025-04-14 12:58:08', '2025-04-14 12:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `csv_approvals`
--

CREATE TABLE `csv_approvals` (
  `id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_by` int(11) DEFAULT NULL,
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
(33, 'staff', '/uploads/staff_20230501.csv', 1, '2023-05-01 09:15:22', 12, 'pending', 2, '2025-04-10 04:07:31', NULL),
(34, 'publications', '/uploads/publications_20230502.csv', 2, '2023-05-02 14:30:45', 8, 'pending', 2, '2025-04-09 23:00:02', NULL),
(35, 'grants', '/uploads/grants_20230503.csv', 3, '2023-05-03 11:20:33', 5, 'pending', NULL, NULL, NULL),
(36, 'departments', '/uploads/departments_20230504.csv', 1, '2023-05-04 16:45:12', 3, 'approved', 2, '2025-04-19 18:34:54', NULL),
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
  `faculty_id` int(10) DEFAULT NULL,
  `department_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `faculty_id`, `department_name`) VALUES
(1, 1, 'Computer Science'),
(2, 1, 'Information Technology'),
(3, 5, 'Electrical Engineering'),
(4, 5, 'Mechanical Engineerings'),
(5, 5, 'Civil Engineering'),
(6, 6, 'Human Resource Management'),
(7, 2, 'Mathematics'),
(8, 2, 'Physics'),
(9, 3, 'Human Anatomy'),
(10, 3, 'Nursing'),
(11, 4, 'Accounting'),
(12, 4, 'Marketing'),
(13, 5, 'Food Science'),
(14, 5, 'Agricultural Engineering'),
(15, 6, 'Development Studies'),
(16, 6, 'Gender and Women Studies'),
(17, 1, 'Software Engineering'),
(18, 2, 'Biology'),
(19, 2, 'Physics'),
(20, 2, 'Chemistry');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `faculty_id` int(10) NOT NULL,
  `faculty_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`faculty_id`, `faculty_name`) VALUES
(1, 'faculty of computing and informatics'),
(2, 'faculty of science'),
(3, 'faculty of medicine'),
(4, 'faculty of business'),
(5, 'faculty of applied sciences'),
(6, 'faculty of interdisciplinary studies');

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
(21, 2, 2, 'Research Publications', 5.00, 10.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'Q1 research output'),
(22, 4, 4, 'Student Supervisions', 3.00, 5.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'Fewer due to leave'),
(23, 5, 5, 'Community Engagements', 2.00, 3.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'Attended rural health workshop'),
(24, 6, 6, 'Projects Completed', 4.00, 4.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'All deliverables met'),
(25, 7, 1, 'Training Sessions', 6.00, 5.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'Overachieved goal'),
(26, 8, 2, 'Grants Acquired', 1.00, 2.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', '1 grant approved'),
(27, 9, 3, 'Conference Presentations', 2.00, 3.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'Presented at MUST Symposium'),
(28, 10, 4, 'Workshops Conducted', 1.00, 2.00, '2025-01-01', '2025-06-30', '2025-04-10 04:08:02', 'Limited by funding');

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
(3, 2, 'Journal Article', 'Co-Author'),
(4, 0, 'Conference Paper', 'Co-Author'),
(5, 0, 'Journal Article', 'Author'),
(6, 0, 'Conference Paper', 'Co-Author'),
(7, 0, 'Journal Article', 'Author'),
(8, 0, 'Conference Paper', 'Co-Author'),
(9, 0, 'Journal Article', 'Author'),
(10, 0, 'Conference Paper', 'Co-Author');

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
  `performance_score` int(11) DEFAULT NULL,
  `employee_id` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `system_role` varchar(30) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `personal_email` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `photo_path` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `last_name`, `scholar_type`, `role_id`, `department_id`, `years_of_experience`, `performance_score`, `employee_id`, `email`, `password`, `system_role`, `phone_number`, `personal_email`, `date_created`, `photo_path`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'Byaruhanga', 'Isamel', NULL, NULL, NULL, NULL, NULL, NULL, 'bi@must.ac.ug', NULL, 'is', NULL, NULL, '2025-04-19 18:10:59', NULL, NULL, NULL),
(2, 'Akanyoreka', 'Smith', 'Non Clinical', 1, 17, 15, 150, 'hrm001', 'hrm@must.ac.ug', '$2y$10$LzQlLL9qmzyi3v5Hv1FLfO3Dbt38cNmRw4dtgoTrBZA3xzilZHsUy', 'hrm', '0757003628', 'akanyoreka@gmail.com', '2025-04-19 17:36:42', 'uploads/profile_pictures/user_2_1744703588.png', NULL, NULL),
(3, 'Michael', 'Johnson', 'Clinical', 4, 8, 3, 80, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(4, 'Emily', 'Davidson', 'Non Clinical', 4, 4, 15, 88, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(5, 'David', 'Wilson', 'Clinical', 5, 5, 8, 75, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(6, 'Sarah', 'Connor', 'Non Clinical', 6, 6, 2, 70, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(7, 'Robert', 'Brown', 'Clinical', 4, 1, 6, 82, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(8, 'Alice', 'Williams', 'Non Clinical', 2, 2, 10, 89, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(9, 'James', 'Anderson', 'Clinical', 1, 3, 11, 86, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(10, 'Linda', 'Taylor', 'Non Clinical', 3, 4, 7, 78, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(12, 'Jane', 'isa', 'Researcher', 3, 2, 3, 78, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(13, 'Ali', 'Khan', 'Assistant Lecturer', 4, 1, 2, 67, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(14, 'Maria', 'Nabwire', 'Lecturer', 2, 3, 7, 92, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(15, 'Peter', 'Okello', 'Senior Lecturer', 1, 2, 10, 95, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(16, 'Sarah', 'Kimani', 'Assistant Lecturer', 4, 4, 1, 60, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(17, 'David', 'Tumusiime', 'Lecturer', 2, 3, 6, 88, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(18, 'Grace', 'Achan', 'Research Fellow', 3, 1, 4, 73, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(19, 'Michael', 'Mwangi', 'Senior Researcher', 5, 2, 9, 90, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(20, 'Linda', 'Namatovu', 'Lecturer', 2, 4, 5, 82, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 17:36:42', NULL, NULL, NULL),
(22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hod', 'hod@must.ac.ug', '$2y$10$LvQjNY/K5.tVzRjJGPgLRO5dg9sflPAgg.wmucPr1101NsdE66S5W', 'hod', NULL, NULL, '2025-04-19 20:50:26', NULL, NULL, NULL),
(23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dean001', 'dean@must.ac.ug', '$2y$10$7ewntoj01aZVfDps4QND9uZrxSlTMQRP0hkIY4gCm5/65XXUYI/Km', 'dean', NULL, NULL, '2025-04-19 20:50:57', NULL, NULL, NULL),
(24, 'Mugabi', 'Praise', NULL, NULL, NULL, NULL, NULL, 'ar001', 'ar@must.ac.ug', '$2y$10$00k6I66SihJLeJwtTR5or.e3oQJDbOp6x4PjXa4ujQ6c5YH4/yCyu', 'ar', '0764920075', 'praise@gmail.com', '2025-04-19 20:51:22', NULL, NULL, NULL),
(25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'std001', 'staff@must.ac.ug', '$2y$10$n4yOdyNYulunzvrg2GP1ie8PW4bhalLGhd9nQdHWLMweJdb5XBRYa', 'staff', NULL, NULL, '2025-04-19 20:56:52', NULL, NULL, NULL);

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
-- Table structure for table `users1`
--

CREATE TABLE `users1` (
  `user_id` int(11) NOT NULL,
  `staff_id` int(10) DEFAULT NULL,
  `employee_id` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(30) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `personal_email` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `photo_path` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`user_id`, `staff_id`, `employee_id`, `email`, `password`, `role`, `first_name`, `last_name`, `phone_number`, `personal_email`, `date_created`, `photo_path`, `reset_token`, `reset_token_expiry`) VALUES
(1, NULL, 'ar', 'ar@must.ac.ug', '$2y$10$GQF8pAt4Oc4EJyP/k4xEVu2npOoPUiSLLv50tQXv/OkdpZ.A3PZIe', 'ar', 'Mugabi', 'Praise', '0757003628', 'praise@gmail.com', '2025-04-09 20:15:47', 'uploads/profile_photos/user_1_1744734471.png', 'fa3af6f64503d47f073c0076363c2bd6950f2172c9f15cf0714e08b4969b8c37', '2025-04-14 01:39:04'),
(2, 3, 'hrm', 'hrm@must.ac.ug', '$2y$10$vJFVJ3399r5XEPjjeTmMU.UR3iKYRytHVzyQ21qYYMPdw/JHvNLHW', 'hrm', 'Isamel.k', 'Byaruhanga', '0757094854', 'byaruhangaisamelk@gmail.com', '2025-04-09 20:15:47', 'uploads/profile_pictures/user_2_1744703588.png', '5b6d1e69f13ea506741dc3ad5c96a9eec8c7dc69dc205850f992e01018bed984', '2025-04-14 03:08:03'),
(3, 8, 'hod', 'hod@must.ac.ug', '$2y$10$MD7A6e554ok4jURO/q.R7OeF8.4AfUQ8/m4IBl8RjGhFMotqjD5L.', 'hod', 'Mutungi3', 'Felix', '0757003621', 'byaruhangangaisamelk@gmail.com', '2025-04-09 20:15:47', 'uploads/profile_photos/user_3_1744716530.png', NULL, NULL),
(4, 2, 'dean', 'dean@must.ac.ug', '$2y$10$nkXlTcl.7.c8HmVRfHoNW.RkYpaDJqBwRERlHHvxOWxzk4TNeE./K', 'dean', 'Akanyoreka', 'Smith', '0757001010', 'akanyoreka@gmail.com', '2025-04-11 13:36:47', 'uploads/profile_photos/user_4_1744367945.png', NULL, NULL),
(5, NULL, 'grants', 'grants@gmail.com', '$2y$10$/1SjpyseSz.D0Sq5yiuBmewAbQdZ30oH0s4ClGuz2EGfX6G2IIQDm', 'grants', 'Odongo', 'Samuel', '0757003621', 'sam@gmail.com', '2025-04-13 13:29:48', 'uploads/profile_photos/user_5_1744734448.png', NULL, NULL),
(6, 8, 'staff-001', 'staff@gmail.com', '$2y$10$kbk6Kynao.1TQE/.3pZcwurW6brsmKWCwbB/MF4jNytTaqfvf/paK', 'staff', 'Mukama', 'Martin', '0757000011', 'martn@gmail.com', '2025-04-13 19:24:56', 'uploads/profile_photos/user_6_1744636243.png', NULL, NULL);

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
  ADD KEY `submitted_by_fk` (`submitted_by`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`degree_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `faculty_ibfk_1` (`faculty_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`faculty_id`);

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
-- Indexes for table `users1`
--
ALTER TABLE `users1`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `profile_ibfk_1` (`staff_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `publication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `supervision`
--
ALTER TABLE `supervision`
  MODIFY `supervision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users1`
--
ALTER TABLE `users1`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `csv_approvals`
--
ALTER TABLE `csv_approvals`
  ADD CONSTRAINT `submitted_by_fk` FOREIGN KEY (`submitted_by`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `users1`
--
ALTER TABLE `users1`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
