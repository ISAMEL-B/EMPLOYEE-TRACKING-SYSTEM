-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 04:34 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `activity_types`
--

CREATE TABLE `activity_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communityservice`
--

CREATE TABLE `communityservice` (
  `community_service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `beneficiaries` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_service_activities`
--

CREATE TABLE `community_service_activities` (
  `activity_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `activity_date` date NOT NULL,
  `beneficiaries` int(11) DEFAULT NULL COMMENT 'Number of people served (optional)',
  `points_earned` int(11) DEFAULT 5 COMMENT 'Fixed 5 points per Form 009',
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `proof_document_path` varchar(255) DEFAULT NULL COMMENT 'Path/URL to uploaded proof document',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` varchar(20) DEFAULT NULL COMMENT 'staff_id of verifier (AR/DHR)',
  `activity_type_id` int(11) DEFAULT NULL COMMENT 'Optional categorization',
  `notes` text DEFAULT NULL COMMENT 'Additional details'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 'Department of Software Engineering'),
(2, 1, 'Department of Computer Science'),
(3, 1, 'Department of Information Technology'),
(4, 2, 'Department of Pharmacology'),
(5, 2, 'Department of Pharmacy'),
(6, 2, 'Department of Anatomy'),
(7, 3, 'Department of Accounting and Finance'),
(8, 3, 'Department of Economics and Entrepreneurship'),
(9, 3, 'Department of Human Resource Management'),
(10, 4, 'Department of Chemistry'),
(11, 4, 'Department of Biology'),
(12, 4, 'Department of Mathematics');

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
(1, 'Faculty of Computing and Informatics'),
(2, 'Faculty of Medicine'),
(3, 'Faculty of Business'),
(4, 'Faculty of Science\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `grants`
--

CREATE TABLE `grants` (
  `grant_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `grant_amount` decimal(15,2) DEFAULT NULL,
  `grant_year` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `innovations`
--

CREATE TABLE `innovations` (
  `innovation_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `innovation_type` varchar(50) DEFAULT NULL,
  `innovation_date` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_change_log`
--

CREATE TABLE `password_change_log` (
  `password_change_lid` int(10) NOT NULL,
  `staff_email` varchar(45) DEFAULT NULL,
  `change_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_change_log`
--

INSERT INTO `password_change_log` (`password_change_lid`, `staff_email`, `change_date`) VALUES
(1, 'byaruhangaisamelk@gmail.com', '2025-04-24 20:40:32');

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

-- --------------------------------------------------------

--
-- Table structure for table `professionalbodies`
--

CREATE TABLE `professionalbodies` (
  `professional_body_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `body_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `publication_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `publication_type` varchar(50) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `publication_date` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_code_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `last_name`, `scholar_type`, `role_id`, `department_id`, `years_of_experience`, `performance_score`, `employee_id`, `email`, `password`, `system_role`, `phone_number`, `personal_email`, `date_created`, `photo_path`, `reset_code`, `reset_code_expiry`) VALUES
(1, 'Kahindo', 'Smith', 'clinical', 1, 1, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(2, 'Nakato', 'Johnson', 'non-clinical', 1, 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(3, 'Kato', 'Williams', 'clinical', 2, 1, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(4, 'Turyahikayo', 'Brown', 'non-clinical', 2, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(5, 'Byarugaba', 'Davis', 'clinical', 3, 1, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(6, 'Okwera', 'Wilson', 'non-clinical', 3, 1, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(7, 'Ndiho', 'Miller', 'clinical', 4, 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(8, 'Kirwa', 'Moore', 'non-clinical', 4, 1, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(9, 'Musinguzi', 'Taylor', 'clinical', 5, 1, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(10, 'Mukisa', 'Anderson', 'non-clinical', 5, 1, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(11, 'Bashaija', 'Jackson', 'clinical', 6, 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(12, 'Kanyonyi', 'Thomas', 'non-clinical', 6, 1, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(13, 'Ntabwoba', 'Miller', 'clinical', 1, 2, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(14, 'Kisembo', 'Roberts', 'non-clinical', 1, 2, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(15, 'Kanyamunyu', 'Johnson', 'clinical', 2, 2, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(16, 'Kirungi', 'Walker', 'non-clinical', 2, 2, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(17, 'Bashasha', 'Harris', 'clinical', 3, 2, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(18, 'Tendo', 'Clark', 'non-clinical', 3, 2, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(19, 'Kanyemera', 'Lopez', 'clinical', 4, 2, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(20, 'Kawooya', 'Young', 'non-clinical', 4, 2, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(21, 'Mubiru', 'King', 'clinical', 5, 2, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(22, 'Bajjo', 'Scott', 'non-clinical', 5, 2, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(23, 'Tiberwa', 'Green', 'clinical', 6, 2, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(24, 'Mubarak', 'Adams', 'non-clinical', 6, 2, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:10', NULL, NULL, NULL),
(25, 'Tushabe', 'Taylor', 'clinical', 1, 3, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(26, 'Amooti', 'Evans', 'non-clinical', 1, 3, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(27, 'Nabasa', 'Wilson', 'clinical', 2, 3, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(28, 'Kasirye', 'Morris', 'non-clinical', 2, 3, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(29, 'Kahwa', 'Riley', 'clinical', 3, 3, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(30, 'Kyamwanga', 'Green', 'non-clinical', 3, 3, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(31, 'Rugaba', 'White', 'clinical', 4, 3, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(32, 'Zuberi', 'Adams', 'non-clinical', 4, 3, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(33, 'Rukundo', 'Evans', 'clinical', 5, 3, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(34, 'Nsubuga', 'Martin', 'non-clinical', 5, 3, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(35, 'Baharana', 'King', 'clinical', 6, 3, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(36, 'Kimbowa', 'Harrison', 'non-clinical', 6, 3, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(37, 'Bashir', 'Wright', 'clinical', 1, 4, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(38, 'Rukundo', 'Morris', 'non-clinical', 1, 4, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(39, 'Kaddu', 'Miller', 'clinical', 2, 4, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(40, 'Nyamwange', 'Smith', 'non-clinical', 2, 4, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(41, 'Mugisha', 'Johnson', 'clinical', 3, 4, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(42, 'Turyahikayo', 'Davis', 'non-clinical', 3, 4, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(43, 'Kasumba', 'Anderson', 'clinical', 4, 4, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(44, 'Kireka', 'Clark', 'non-clinical', 4, 4, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(45, 'Musinguzi', 'Walker', 'clinical', 5, 4, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(46, 'Naboth', 'Evans', 'non-clinical', 5, 4, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(47, 'Tumwine', 'Young', 'clinical', 6, 4, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(48, 'Kabengele', 'Adams', 'non-clinical', 6, 4, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(49, 'Okello', 'Brown', 'clinical', 1, 5, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(50, 'Bishar', 'Williams', 'non-clinical', 1, 5, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(51, 'Tushabe', 'Roberts', 'clinical', 2, 5, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(52, 'Kisakye', 'Morris', 'non-clinical', 2, 5, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(53, 'Tumwebaze', 'Adams', 'clinical', 3, 5, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(54, 'Nakiyingi', 'Taylor', 'non-clinical', 3, 5, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(55, 'Mugisha', 'Walker', 'clinical', 4, 5, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(56, 'Bashir', 'Green', 'non-clinical', 4, 5, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(57, 'Tumwesigye', 'Smith', 'clinical', 5, 5, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(58, 'Rugumayo', 'Wilson', 'non-clinical', 5, 5, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(59, 'Tushabe', 'Bashasha', 'clinical', 6, 5, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(60, 'Kasirye', 'Davis', 'non-clinical', 6, 5, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:29:11', NULL, NULL, NULL),
(61, 'Tushabe', 'Johnson', 'clinical', 1, 6, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(62, 'Akankwasa', 'Morris', 'non-clinical', 1, 6, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(63, 'Kato', 'Walker', 'clinical', 2, 6, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(64, 'Kiberu', 'Taylor', 'non-clinical', 2, 6, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(65, 'Okwera', 'Brown', 'clinical', 3, 6, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(66, 'Amooti', 'King', 'non-clinical', 3, 6, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(67, 'Rukundo', 'Smith', 'clinical', 4, 6, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(68, 'Byarugaba', 'Evans', 'non-clinical', 4, 6, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(69, 'Kiryowa', 'Adams', 'clinical', 5, 6, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(70, 'Bashir', 'Harris', 'non-clinical', 5, 6, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(71, 'Mutebi', 'Green', 'clinical', 6, 6, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(72, 'Kahindo', 'Roberts', 'non-clinical', 6, 6, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:05', NULL, NULL, NULL),
(73, 'Bashasha', 'Green', 'clinical', 1, 7, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(74, 'Karekezi', 'Davis', 'non-clinical', 1, 7, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(75, 'Kanyonyi', 'Walker', 'clinical', 2, 7, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(76, 'Kasirye', 'Morris', 'non-clinical', 2, 7, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(77, 'Byarugaba', 'Adams', 'clinical', 3, 7, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(78, 'Rukundo', 'Johnson', 'non-clinical', 3, 7, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(79, 'Tushabe', 'Harris', 'clinical', 4, 7, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(80, 'Akankwasa', 'Miller', 'non-clinical', 4, 7, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(81, 'Mugisha', 'Smith', 'clinical', 5, 7, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(82, 'Kabengele', 'Brown', 'non-clinical', 5, 7, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(83, 'Kisembo', 'Johnson', 'clinical', 6, 7, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(84, 'Naboth', 'Roberts', 'non-clinical', 6, 7, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:26', NULL, NULL, NULL),
(85, 'Tushabe', 'Smith', 'clinical', 1, 8, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(86, 'Musinguzi', 'Wilson', 'non-clinical', 1, 8, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(87, 'Kato', 'Anderson', 'clinical', 2, 8, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(88, 'Kigongo', 'Clark', 'non-clinical', 2, 8, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(89, 'Kasumba', 'Morris', 'clinical', 3, 8, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(90, 'Byarugaba', 'Taylor', 'non-clinical', 3, 8, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(91, 'Kabengele', 'Davis', 'clinical', 4, 8, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(92, 'Kato', 'Roberts', 'non-clinical', 4, 8, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(93, 'Mugisha', 'Adams', 'clinical', 5, 8, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(94, 'Amooti', 'King', 'non-clinical', 5, 8, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(95, 'Turyahikayo', 'Wilson', 'clinical', 6, 8, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(96, 'Kabengele', 'Harris', 'non-clinical', 6, 8, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:42', NULL, NULL, NULL),
(97, 'Kahindo', 'Smith', 'clinical', 1, 9, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(98, 'Byarugaba', 'Davis', 'non-clinical', 1, 9, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(99, 'Rukundo', 'Miller', 'clinical', 2, 9, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(100, 'Tushabe', 'Brown', 'non-clinical', 2, 9, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(101, 'Kato', 'Wilson', 'clinical', 3, 9, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(102, 'Mugisha', 'Taylor', 'non-clinical', 3, 9, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(103, 'Rugumayo', 'Harrison', 'clinical', 4, 9, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(104, 'Kasirye', 'Moore', 'non-clinical', 4, 9, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(105, 'Kiryowa', 'Harris', 'clinical', 5, 9, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(106, 'Musinguzi', 'Evans', 'non-clinical', 5, 9, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(107, 'Amooti', 'Adams', 'clinical', 6, 9, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(108, 'Kasumba', 'Wilson', 'non-clinical', 6, 9, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:30:58', NULL, NULL, NULL),
(109, 'Kasumba', 'Johnson', 'clinical', 1, 10, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(110, 'Turyahikayo', 'Adams', 'non-clinical', 1, 10, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(111, 'Kanyonyi', 'Morris', 'clinical', 2, 10, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(112, 'Musinguzi', 'Smith', 'non-clinical', 2, 10, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(113, 'Kato', 'Brown', 'clinical', 3, 10, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(114, 'Kabengele', 'Taylor', 'non-clinical', 3, 10, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(115, 'Amooti', 'King', 'clinical', 4, 10, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(116, 'Tushabe', 'Adams', 'non-clinical', 4, 10, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(117, 'Kisembo', 'Johnson', 'clinical', 5, 10, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(118, 'Byarugaba', 'Brown', 'non-clinical', 5, 10, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(119, 'Kiryowa', 'Davis', 'clinical', 6, 10, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(120, 'Turyahikayo', 'Harris', 'non-clinical', 6, 10, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:17', NULL, NULL, NULL),
(121, 'Bashir', 'Johnson', 'clinical', 1, 11, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(122, 'Musinguzi', 'Smith', 'non-clinical', 1, 11, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(123, 'Kanyonyi', 'Brown', 'clinical', 2, 11, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(124, 'Kasumba', 'Miller', 'non-clinical', 2, 11, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(125, 'Okwera', 'Williams', 'clinical', 3, 11, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(126, 'Kabengele', 'Evans', 'non-clinical', 3, 11, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(127, 'Kasirye', 'Taylor', 'clinical', 4, 11, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(128, 'Turyahikayo', 'King', 'non-clinical', 4, 11, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(129, 'Tushabe', 'Davis', 'clinical', 5, 11, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(130, 'Mutebi', 'Adams', 'non-clinical', 5, 11, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(131, 'Kiryowa', 'Brown', 'clinical', 6, 11, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(132, 'Tendo', 'Morris', 'non-clinical', 6, 11, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:31:42', NULL, NULL, NULL),
(133, 'Kasumba', 'Johnson', 'clinical', 1, 12, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(134, 'Amooti', 'Taylor', 'non-clinical', 1, 12, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(135, 'Turyahikayo', 'Smith', 'clinical', 2, 12, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(136, 'Bashasha', 'King', 'non-clinical', 2, 12, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(137, 'Kabengele', 'Davis', 'clinical', 3, 12, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(138, 'Kato', 'Morris', 'non-clinical', 3, 12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(139, 'Bashir', 'Brown', 'clinical', 4, 12, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(140, 'Kasirye', 'Evans', 'non-clinical', 4, 12, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(141, 'Kiryowa', 'Miller', 'clinical', 5, 12, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(142, 'Turyahikayo', 'Johnson', 'non-clinical', 5, 12, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(143, 'Musinguzi', 'Wilson', 'clinical', 6, 12, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(144, 'Kabengele', 'Anderson', 'non-clinical', 6, 12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supervision`
--

CREATE TABLE `supervision` (
  `supervision_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `verification_documents`
--

CREATE TABLE `verification_documents` (
  `document_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `uploaded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academicactivities`
--
ALTER TABLE `academicactivities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `activity_types`
--
ALTER TABLE `activity_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `communityservice`
--
ALTER TABLE `communityservice`
  ADD PRIMARY KEY (`community_service_id`);

--
-- Indexes for table `community_service_activities`
--
ALTER TABLE `community_service_activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `verification_status` (`verification_status`),
  ADD KEY `activity_date` (`activity_date`);

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
-- Indexes for table `password_change_log`
--
ALTER TABLE `password_change_log`
  ADD PRIMARY KEY (`password_change_lid`);

--
-- Indexes for table `performance_metrics`
--
ALTER TABLE `performance_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `performance_metrics_ibfk_1` (`staff_id`);

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
-- Indexes for table `verification_documents`
--
ALTER TABLE `verification_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academicactivities`
--
ALTER TABLE `academicactivities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_types`
--
ALTER TABLE `activity_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communityservice`
--
ALTER TABLE `communityservice`
  MODIFY `community_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `community_service_activities`
--
ALTER TABLE `community_service_activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grants`
--
ALTER TABLE `grants`
  MODIFY `grant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `innovations`
--
ALTER TABLE `innovations`
  MODIFY `innovation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `password_change_log`
--
ALTER TABLE `password_change_log`
  MODIFY `password_change_lid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

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
-- AUTO_INCREMENT for table `verification_documents`
--
ALTER TABLE `verification_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_service_activities`
--
ALTER TABLE `community_service_activities`
  ADD CONSTRAINT `community_service_activities_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

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
  ADD CONSTRAINT `performance_metrics_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE,
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

--
-- Constraints for table `verification_documents`
--
ALTER TABLE `verification_documents`
  ADD CONSTRAINT `verification_documents_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `community_service_activities` (`activity_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verification_documents_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
