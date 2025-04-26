-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 26, 2025 at 11:36 PM
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

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`degree_id`, `staff_id`, `degree_name`, `degree_classification`) VALUES
(1, 1, 'PhD in Software Engineering', 'PhD'),
(2, 2, 'PhD in Information Technology', 'PhD'),
(3, 3, 'Masters in Computer Science', 'Masters'),
(4, 4, 'Masters in Software Systems', 'Masters'),
(5, 5, 'Bachelor of Software Engineering', 'First Class'),
(6, 6, 'Bachelor of Information Technology', 'First Class'),
(7, 7, 'Bachelor of Computer Science', 'First Class'),
(8, 8, 'Bachelor of Information Systems', 'First Class'),
(9, 9, 'Bachelor of Software Applications', 'First Class'),
(10, 10, 'Bachelor of Data Science', 'First Class'),
(11, 11, 'Bachelor of Computing and Information Systems', 'Second Upper'),
(12, 12, 'Bachelor of Technology in Computer Engineering', 'Second Upper'),
(13, 13, 'PhD in Computer Science', 'PhD'),
(14, 14, 'PhD in Artificial Intelligence', 'PhD'),
(15, 15, 'Masters in Computer Science', 'Masters'),
(16, 16, 'Masters in Software Engineering', 'Masters'),
(17, 17, 'Bachelor of Computer Science', 'First Class'),
(18, 18, 'Bachelor of Software Engineering', 'First Class'),
(19, 19, 'Bachelor of Data Science', 'First Class'),
(20, 20, 'Bachelor of Information Systems', 'First Class'),
(21, 21, 'Bachelor of Computing', 'First Class'),
(22, 22, 'Bachelor of Information Technology', 'First Class'),
(23, 23, 'Bachelor of Software Applications', 'Second Upper'),
(24, 24, 'Bachelor of Computer Engineering', 'Second Upper'),
(25, 25, 'PhD in Information Technology', 'PhD'),
(26, 26, 'PhD in Cybersecurity', 'PhD'),
(27, 27, 'Masters in Information Technology', 'Masters'),
(28, 28, 'Masters in Cybersecurity', 'Masters'),
(29, 29, 'Bachelor of Information Technology', 'First Class'),
(30, 30, 'Bachelor of Networking and Security', 'First Class'),
(31, 31, 'Bachelor of Software Development', 'First Class'),
(32, 32, 'Bachelor of Web Development', 'First Class'),
(33, 33, 'Bachelor of Computer Networks', 'First Class'),
(34, 34, 'Bachelor of Information Systems', 'First Class'),
(35, 35, 'Bachelor of Cybersecurity', 'Second Upper'),
(36, 36, 'Bachelor of Information Management', 'Second Upper'),
(37, 37, 'PhD in Pharmacology', 'PhD'),
(38, 38, 'PhD in Clinical Pharmacology', 'PhD'),
(39, 39, 'Masters in Pharmacology', 'Masters'),
(40, 40, 'Masters in Clinical Pharmacy', 'Masters'),
(41, 41, 'Bachelor of Pharmacology', 'First Class'),
(42, 42, 'Bachelor of Clinical Pharmacology', 'First Class'),
(43, 43, 'Bachelor of Pharmaceutical Sciences', 'First Class'),
(44, 44, 'Bachelor of Pharmacotherapy', 'First Class'),
(45, 45, 'Bachelor of Medical Pharmacology', 'First Class'),
(46, 46, 'Bachelor of Pharmacy Practice', 'First Class'),
(47, 47, 'Bachelor of Pharmacy Technology', 'Second Upper'),
(48, 48, 'Bachelor of Clinical Medicine', 'Second Upper'),
(49, 49, 'PhD in Pharmaceutical Sciences', 'PhD'),
(50, 50, 'PhD in Pharmacy Practice', 'PhD'),
(51, 51, 'Masters in Pharmaceutical Chemistry', 'Masters'),
(52, 52, 'Masters in Pharmacognosy', 'Masters'),
(53, 53, 'Bachelor of Pharmacy', 'First Class'),
(54, 54, 'Bachelor of Pharmaceutical Sciences', 'First Class'),
(55, 55, 'Bachelor of Pharmacognosy', 'First Class'),
(56, 56, 'Bachelor of Clinical Pharmacy', 'First Class'),
(57, 57, 'Bachelor of Pharmaceutical Technology', 'First Class'),
(58, 58, 'Bachelor of Biopharmaceutical Sciences', 'First Class'),
(59, 59, 'Bachelor of Pharmacy Administration', 'Second Upper'),
(60, 60, 'Bachelor of Pharmaceutical Care', 'Second Upper'),
(61, 61, 'PhD in Human Anatomy', 'PhD'),
(62, 62, 'PhD in Clinical Anatomy', 'PhD'),
(63, 63, 'Masters in Anatomy', 'Masters'),
(64, 64, 'Masters in Human Physiology', 'Masters'),
(65, 65, 'Bachelor of Anatomy and Physiology', 'First Class'),
(66, 66, 'Bachelor of Human Anatomy', 'First Class'),
(67, 67, 'Bachelor of Medical Sciences', 'First Class'),
(68, 68, 'Bachelor of Biological Sciences', 'First Class'),
(69, 69, 'Bachelor of Biomedical Sciences', 'First Class'),
(70, 70, 'Bachelor of Clinical Anatomy', 'First Class'),
(71, 71, 'Bachelor of Medical Anatomy', 'Second Upper'),
(72, 72, 'Bachelor of Clinical Anatomy', 'Second Upper'),
(73, 73, 'PhD in Accounting', 'PhD'),
(74, 74, 'PhD in Financial Management', 'PhD'),
(75, 75, 'Masters in Accounting and Finance', 'Masters'),
(76, 76, 'Masters in Financial Analysis', 'Masters'),
(77, 77, 'Bachelor of Accounting', 'First Class'),
(78, 78, 'Bachelor of Finance and Banking', 'First Class'),
(79, 79, 'Bachelor of Commerce', 'First Class'),
(80, 80, 'Bachelor of Financial Management', 'First Class'),
(81, 81, 'Bachelor of Business Finance', 'First Class'),
(82, 82, 'Bachelor of Business Administration', 'First Class'),
(83, 83, 'Bachelor of Finance and Investment', 'Second Upper'),
(84, 84, 'Bachelor of Financial Engineering', 'Second Upper'),
(85, 85, 'PhD in Economics', 'PhD'),
(86, 86, 'PhD in Entrepreneurship', 'PhD'),
(87, 87, 'Masters in Economics', 'Masters'),
(88, 88, 'Masters in Business Innovation', 'Masters'),
(89, 89, 'Bachelor of Economics', 'First Class'),
(90, 90, 'Bachelor of Entrepreneurship and Innovation', 'First Class'),
(91, 91, 'Bachelor of Business Economics', 'First Class'),
(92, 92, 'Bachelor of Development Economics', 'First Class'),
(93, 93, 'Bachelor of Business Innovation', 'First Class'),
(94, 94, 'Bachelor of Financial Economics', 'First Class'),
(95, 95, 'Bachelor of Entrepreneurship and Finance', 'Second Upper'),
(96, 96, 'Bachelor of Development Studies', 'Second Upper'),
(97, 97, 'PhD in Human Resource Management', 'PhD'),
(98, 98, 'PhD in Organizational Leadership', 'PhD'),
(99, 99, 'Masters in Human Resource Management', 'Masters'),
(100, 100, 'Masters in Organizational Development', 'Masters'),
(101, 101, 'Bachelor of Human Resource Management', 'First Class'),
(102, 102, 'Bachelor of Organizational Psychology', 'First Class'),
(103, 103, 'Bachelor of Business Administration', 'First Class'),
(104, 104, 'Bachelor of Public Administration', 'First Class'),
(105, 105, 'Bachelor of Industrial Psychology', 'First Class'),
(106, 106, 'Bachelor of Labor and Industrial Relations', 'First Class'),
(107, 107, 'Bachelor of Business Leadership', 'Second Upper'),
(108, 108, 'Bachelor of Organizational Development', 'Second Upper'),
(109, 109, 'PhD in Chemistry', 'PhD'),
(110, 110, 'PhD in Organic Chemistry', 'PhD'),
(111, 111, 'Masters in Analytical Chemistry', 'Masters'),
(112, 112, 'Masters in Industrial Chemistry', 'Masters'),
(113, 113, 'Bachelor of Chemistry', 'First Class'),
(114, 114, 'Bachelor of Industrial Chemistry', 'First Class'),
(115, 115, 'Bachelor of Organic Chemistry', 'First Class'),
(116, 116, 'Bachelor of Biochemistry', 'First Class'),
(117, 117, 'Bachelor of Applied Chemistry', 'First Class'),
(118, 118, 'Bachelor of Analytical Chemistry', 'First Class'),
(119, 119, 'Bachelor of Industrial and Fine Chemistry', 'Second Upper'),
(120, 120, 'Bachelor of Environmental Chemistry', 'Second Upper'),
(121, 121, 'PhD in Biology', 'PhD'),
(122, 122, 'PhD in Molecular Biology', 'PhD'),
(123, 123, 'Masters in Botany', 'Masters'),
(124, 124, 'Masters in Zoology', 'Masters'),
(125, 125, 'Bachelor of Biology', 'First Class'),
(126, 126, 'Bachelor of Botany', 'First Class'),
(127, 127, 'Bachelor of Zoology', 'First Class'),
(128, 128, 'Bachelor of Environmental Biology', 'First Class'),
(129, 129, 'Bachelor of Microbiology', 'First Class'),
(130, 130, 'Bachelor of Biotechnology', 'First Class'),
(131, 131, 'Bachelor of Genetic Engineering', 'Second Upper'),
(132, 132, 'Bachelor of Agricultural Biology', 'Second Upper'),
(133, 133, 'PhD in Mathematics', 'PhD'),
(134, 134, 'PhD in Applied Mathematics', 'PhD'),
(135, 135, 'Masters in Pure Mathematics', 'Masters'),
(136, 136, 'Masters in Applied Statistics', 'Masters'),
(137, 137, 'Bachelor of Mathematics', 'First Class'),
(138, 138, 'Bachelor of Applied Mathematics', 'First Class'),
(139, 139, 'Bachelor of Statistics', 'First Class'),
(140, 140, 'Bachelor of Computational Mathematics', 'First Class'),
(141, 141, 'Bachelor of Data Science', 'First Class'),
(142, 142, 'Bachelor of Mathematical Sciences', 'First Class'),
(143, 143, 'Bachelor of Applied Statistics', 'Second Upper'),
(144, 144, 'Bachelor of Quantitative Economics', 'Second Upper');

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

--
-- Dumping data for table `grants`
--

INSERT INTO `grants` (`grant_id`, `staff_id`, `grant_amount`, `grant_year`) VALUES
(1, 1, 1250000000.00, '2023-03-15 10:45:21.000000'),
(2, 2, 750000000.00, '2022-06-18 14:32:55.000000'),
(3, 3, 300000000.00, '2021-08-10 09:21:45.000000'),
(4, 4, 85000000.00, '2024-02-11 17:50:31.000000'),
(5, 13, 1350000000.00, '2021-04-25 12:15:09.000000'),
(6, 14, 580000000.00, '2023-07-30 08:41:17.000000'),
(7, 15, 450000000.00, '2022-01-03 23:11:05.000000'),
(8, 16, 90000000.00, '2024-09-10 06:55:12.000000'),
(9, 25, 1400000000.00, '2022-12-05 16:33:22.000000'),
(10, 26, 800000000.00, '2023-11-19 11:22:33.000000'),
(11, 27, 200000000.00, '2021-03-14 21:01:12.000000'),
(12, 28, 95000000.00, '2024-05-27 15:45:01.000000'),
(13, 37, 1100000000.00, '2021-07-22 13:29:45.000000'),
(14, 38, 600000000.00, '2022-09-17 10:58:26.000000'),
(15, 39, 350000000.00, '2023-10-10 19:30:40.000000'),
(16, 40, 70000000.00, '2024-01-20 05:19:10.000000'),
(17, 49, 1200000000.00, '2022-02-06 07:48:19.000000'),
(18, 50, 670000000.00, '2021-11-25 22:14:37.000000'),
(19, 51, 280000000.00, '2023-08-16 14:37:59.000000'),
(20, 52, 95000000.00, '2024-06-14 09:28:45.000000'),
(21, 61, 1150000000.00, '2023-04-09 10:22:33.000000'),
(22, 62, 520000000.00, '2022-05-11 17:18:06.000000'),
(23, 63, 400000000.00, '2021-01-20 12:51:15.000000'),
(24, 64, 85000000.00, '2024-08-07 03:40:22.000000'),
(25, 73, 1320000000.00, '2021-06-24 15:29:18.000000'),
(26, 74, 700000000.00, '2023-03-27 07:12:10.000000'),
(27, 75, 250000000.00, '2022-10-12 21:49:03.000000'),
(28, 76, 99000000.00, '2024-11-15 06:18:57.000000'),
(29, 85, 1400000000.00, '2022-01-01 09:40:00.000000'),
(30, 86, 800000000.00, '2021-09-03 19:23:45.000000'),
(31, 87, 350000000.00, '2023-07-13 22:31:05.000000'),
(32, 88, 90000000.00, '2024-03-09 12:25:35.000000'),
(33, 97, 1250000000.00, '2023-05-21 18:45:32.000000'),
(34, 98, 750000000.00, '2022-04-06 11:36:28.000000'),
(35, 99, 320000000.00, '2021-07-30 08:59:13.000000'),
(36, 100, 87000000.00, '2024-02-22 16:32:41.000000'),
(37, 109, 1350000000.00, '2021-02-17 23:40:27.000000'),
(38, 110, 680000000.00, '2023-12-04 07:15:00.000000'),
(39, 111, 290000000.00, '2022-05-29 10:40:44.000000'),
(40, 112, 75000000.00, '2024-07-03 04:14:52.000000'),
(41, 121, 1180000000.00, '2022-06-20 21:45:11.000000'),
(42, 122, 500000000.00, '2021-10-11 15:50:08.000000'),
(43, 123, 450000000.00, '2023-01-09 13:29:22.000000'),
(44, 124, 99000000.00, '2024-10-01 11:05:55.000000'),
(45, 133, 1400000000.00, '2021-08-08 10:22:45.000000'),
(46, 134, 670000000.00, '2023-06-16 16:17:19.000000'),
(47, 135, 300000000.00, '2022-02-19 20:48:13.000000'),
(48, 136, 95000000.00, '2024-09-05 05:38:01.000000');

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

--
-- Dumping data for table `innovations`
--

INSERT INTO `innovations` (`innovation_id`, `staff_id`, `innovation_type`, `innovation_date`) VALUES
(1, 1, 'Product', '2022-11-21 03:47:27.000000'),
(2, 40, 'Patent', '2022-12-01 12:12:58.000000'),
(3, 40, 'Copyright', '2023-03-08 01:06:22.000000'),
(4, 23, 'Trademark', '2021-09-12 05:17:14.000000'),
(5, 63, 'Trademark', '2022-06-10 03:10:28.000000'),
(6, 64, 'Utility Model', '2024-05-14 10:17:20.000000'),
(7, 56, 'Trademark', '2022-12-02 15:45:41.000000'),
(8, 58, 'Product', '2024-02-09 12:16:50.000000'),
(9, 44, 'Trademark', '2021-04-10 04:24:46.000000'),
(10, 103, 'Product', '2023-03-25 15:34:55.000000'),
(11, 44, 'Product', '2024-09-01 21:39:30.000000'),
(12, 138, 'Copyright', '2023-06-24 05:44:35.000000'),
(13, 28, 'Patent', '2022-11-11 00:52:51.000000'),
(14, 88, 'Product', '2022-07-13 09:22:38.000000'),
(15, 143, 'Patent', '2021-09-07 08:57:37.000000'),
(16, 24, 'Trademark', '2021-08-16 18:20:37.000000'),
(17, 35, 'Utility Model', '2022-02-19 23:58:54.000000'),
(18, 21, 'Trademark', '2021-08-10 09:06:00.000000'),
(19, 97, 'Utility Model', '2022-06-27 19:26:31.000000'),
(20, 126, 'Utility Model', '2021-05-12 10:30:35.000000'),
(21, 111, 'Trademark', '2024-01-05 04:48:18.000000'),
(22, 86, 'Trademark', '2022-11-28 17:45:10.000000'),
(23, 76, 'Trademark', '2021-02-24 07:47:00.000000'),
(24, 65, 'Utility Model', '2024-03-28 06:25:41.000000'),
(25, 132, 'Product', '2021-05-12 02:04:29.000000'),
(26, 118, 'Utility Model', '2022-03-02 01:28:40.000000'),
(27, 27, 'Utility Model', '2021-04-13 16:26:46.000000'),
(28, 43, 'Copyright', '2024-08-27 08:30:38.000000'),
(29, 106, 'Product', '2021-09-05 21:58:19.000000'),
(30, 117, 'Trademark', '2024-04-05 08:53:41.000000'),
(31, 86, 'Copyright', '2021-02-04 10:49:10.000000'),
(32, 33, 'Patent', '2021-04-01 22:59:41.000000'),
(33, 136, 'Patent', '2021-04-05 17:06:04.000000'),
(34, 19, 'Copyright', '2022-05-09 14:03:27.000000'),
(35, 132, 'Trademark', '2022-01-01 00:02:38.000000'),
(36, 126, 'Patent', '2023-01-16 17:15:13.000000'),
(37, 33, 'Product', '2023-01-05 00:22:13.000000'),
(38, 24, 'Product', '2022-01-16 14:18:27.000000'),
(39, 60, 'Copyright', '2022-07-16 18:09:12.000000'),
(40, 3, 'Patent', '2023-03-20 22:03:54.000000'),
(41, 54, 'Patent', '2021-03-17 09:53:19.000000'),
(42, 6, 'Product', '2023-04-15 00:14:08.000000'),
(43, 92, 'Trademark', '2024-12-15 08:35:00.000000'),
(44, 128, 'Patent', '2021-03-18 12:25:18.000000'),
(45, 39, 'Patent', '2023-05-28 20:47:06.000000'),
(46, 67, 'Patent', '2021-06-03 01:28:48.000000'),
(47, 113, 'Product', '2022-01-04 01:45:52.000000'),
(48, 41, 'Utility Model', '2021-09-16 22:06:08.000000'),
(49, 39, 'Copyright', '2022-07-24 15:06:43.000000'),
(50, 132, 'Trademark', '2024-10-01 07:29:02.000000'),
(51, 116, 'Patent', '2023-05-24 06:18:02.000000'),
(52, 48, 'Copyright', '2022-03-13 16:50:09.000000'),
(53, 64, 'Product', '2022-08-24 01:47:46.000000'),
(54, 126, 'Utility Model', '2022-06-01 14:44:49.000000'),
(55, 94, 'Patent', '2023-06-08 05:45:10.000000'),
(56, 57, 'Utility Model', '2021-01-16 05:59:14.000000'),
(57, 65, 'Patent', '2023-03-07 22:42:58.000000'),
(58, 109, 'Trademark', '2022-09-13 03:07:01.000000'),
(59, 87, 'Product', '2022-04-06 18:42:58.000000'),
(60, 136, 'Utility Model', '2022-02-05 12:00:29.000000'),
(61, 50, 'Utility Model', '2024-05-15 16:30:10.000000'),
(62, 19, 'Utility Model', '2021-12-03 22:24:56.000000'),
(63, 68, 'Trademark', '2024-10-05 01:59:39.000000'),
(64, 57, 'Product', '2023-08-06 15:40:04.000000'),
(65, 142, 'Utility Model', '2022-09-19 18:23:14.000000'),
(66, 3, 'Trademark', '2024-02-04 23:26:05.000000'),
(67, 50, 'Trademark', '2023-11-27 14:44:29.000000'),
(68, 95, 'Utility Model', '2022-09-04 19:49:31.000000'),
(69, 122, 'Patent', '2021-04-17 00:52:24.000000'),
(70, 69, 'Copyright', '2024-06-02 22:12:44.000000'),
(71, 80, 'Product', '2022-07-18 23:03:26.000000'),
(72, 13, 'Utility Model', '2024-04-13 14:39:26.000000'),
(73, 11, 'Patent', '2024-10-15 17:08:58.000000'),
(74, 61, 'Utility Model', '2024-02-11 06:41:28.000000'),
(75, 129, 'Product', '2024-06-09 07:57:50.000000'),
(76, 27, 'Patent', '2021-01-01 16:38:04.000000'),
(77, 53, 'Trademark', '2024-05-12 21:07:35.000000'),
(78, 48, 'Patent', '2023-02-19 20:07:05.000000'),
(79, 141, 'Trademark', '2022-10-04 22:26:57.000000'),
(80, 79, 'Product', '2022-04-20 06:12:00.000000'),
(81, 38, 'Trademark', '2024-05-02 01:40:21.000000'),
(82, 82, 'Trademark', '2023-02-22 22:07:15.000000'),
(83, 41, 'Product', '2023-10-12 00:55:03.000000'),
(84, 55, 'Utility Model', '2023-01-09 05:07:25.000000'),
(85, 26, 'Copyright', '2023-11-07 20:58:38.000000'),
(86, 44, 'Patent', '2022-02-15 20:33:15.000000'),
(87, 138, 'Patent', '2023-02-15 14:35:36.000000'),
(88, 59, 'Trademark', '2022-04-01 04:26:35.000000'),
(89, 93, 'Utility Model', '2021-09-17 22:40:45.000000'),
(90, 27, 'Utility Model', '2023-09-13 21:22:50.000000'),
(91, 29, 'Patent', '2022-08-09 17:39:30.000000'),
(92, 130, 'Product', '2023-12-20 01:11:33.000000'),
(93, 81, 'Product', '2023-10-17 00:20:24.000000'),
(94, 34, 'Patent', '2024-08-12 01:32:03.000000'),
(95, 110, 'Copyright', '2022-09-20 22:28:01.000000'),
(96, 73, 'Patent', '2021-09-01 10:05:27.000000'),
(97, 101, 'Patent', '2024-03-05 10:03:53.000000'),
(98, 11, 'Trademark', '2023-03-05 04:16:02.000000'),
(99, 88, 'Copyright', '2023-07-28 00:43:29.000000'),
(100, 142, 'Product', '2022-03-09 20:37:19.000000'),
(101, 72, 'Copyright', '2023-11-04 23:30:56.000000'),
(102, 86, 'Patent', '2021-01-15 20:22:00.000000'),
(103, 7, 'Utility Model', '2022-10-07 00:28:10.000000'),
(104, 125, 'Trademark', '2023-01-17 08:53:23.000000'),
(105, 123, 'Utility Model', '2024-10-18 12:22:33.000000'),
(106, 135, 'Copyright', '2022-07-06 18:49:57.000000'),
(107, 110, 'Trademark', '2022-10-04 12:34:51.000000'),
(108, 123, 'Utility Model', '2024-10-12 15:32:56.000000'),
(109, 88, 'Trademark', '2024-08-28 02:46:15.000000'),
(110, 37, 'Trademark', '2023-04-11 03:51:31.000000'),
(111, 22, 'Utility Model', '2023-07-01 11:20:09.000000'),
(112, 11, 'Utility Model', '2022-06-16 14:38:09.000000'),
(113, 122, 'Utility Model', '2023-05-11 09:57:34.000000'),
(114, 83, 'Utility Model', '2021-07-11 13:20:36.000000'),
(115, 94, 'Patent', '2023-08-19 15:09:47.000000'),
(116, 93, 'Product', '2024-01-14 03:55:46.000000'),
(117, 33, 'Trademark', '2024-08-18 23:15:25.000000'),
(118, 15, 'Product', '2024-07-24 23:24:27.000000'),
(119, 116, 'Copyright', '2023-02-02 02:45:59.000000'),
(120, 140, 'Copyright', '2024-07-03 18:41:58.000000');

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

--
-- Dumping data for table `publications`
--

INSERT INTO `publications` (`publication_id`, `staff_id`, `publication_type`, `role`, `publication_date`) VALUES
(1, 1, 'Book with ISBN', NULL, '2023-08-13 01:38:56.000000'),
(2, 2, 'Book with ISBN', NULL, '2021-10-10 02:43:11.000000'),
(3, 3, 'Book Chapter', NULL, '2021-07-11 20:39:21.000000'),
(4, 4, 'Book Chapter', NULL, '2021-01-23 18:15:31.000000'),
(5, 5, 'Journal Article', 'Corresponding Author', '2022-02-19 18:52:09.000000'),
(6, 6, 'Journal Article', 'Co-author', '2023-06-03 15:07:26.000000'),
(7, 7, 'Journal Article', 'Corresponding Author', '2024-11-10 17:00:31.000000'),
(8, 8, 'Journal Article', 'Co-author', '2021-02-11 16:51:23.000000'),
(9, 9, 'Journal Article', 'Corresponding Author', '2022-06-17 22:26:51.000000'),
(10, 10, 'Journal Article', 'Corresponding Author', '2022-09-04 19:48:20.000000'),
(11, 11, 'Journal Article', 'First Author', '2021-02-11 02:37:08.000000'),
(12, 12, 'Journal Article', 'Corresponding Author', '2024-11-27 05:48:34.000000'),
(13, 3, 'Journal Article', 'Corresponding Author', '2022-02-04 20:00:21.000000'),
(14, 12, 'Journal Article', 'Co-author', '2022-10-14 12:40:13.000000'),
(15, 13, 'Book with ISBN', NULL, '2022-10-21 02:11:34.000000'),
(16, 14, 'Book with ISBN', NULL, '2023-03-25 17:32:25.000000'),
(17, 15, 'Book Chapter', NULL, '2021-04-12 00:35:27.000000'),
(18, 16, 'Book Chapter', NULL, '2024-05-14 06:55:57.000000'),
(19, 17, 'Journal Article', 'First Author', '2024-02-18 14:39:35.000000'),
(20, 18, 'Journal Article', 'Co-author', '2023-03-06 03:15:18.000000'),
(21, 19, 'Journal Article', 'Corresponding Author', '2024-08-22 18:51:49.000000'),
(22, 20, 'Journal Article', 'Corresponding Author', '2024-01-19 17:52:13.000000'),
(23, 21, 'Journal Article', 'Corresponding Author', '2024-11-02 11:58:31.000000'),
(24, 22, 'Journal Article', 'Corresponding Author', '2022-10-01 20:14:24.000000'),
(25, 23, 'Journal Article', 'Co-author', '2022-04-25 06:20:15.000000'),
(26, 24, 'Journal Article', 'Corresponding Author', '2023-11-13 13:43:06.000000'),
(27, 14, 'Journal Article', 'First Author', '2024-03-02 05:00:50.000000'),
(28, 19, 'Journal Article', 'First Author', '2022-07-18 19:31:00.000000'),
(29, 25, 'Book with ISBN', NULL, '2024-07-06 05:17:23.000000'),
(30, 26, 'Book with ISBN', NULL, '2023-05-17 02:13:09.000000'),
(31, 27, 'Book Chapter', NULL, '2024-12-12 20:50:55.000000'),
(32, 28, 'Book Chapter', NULL, '2021-08-03 03:19:02.000000'),
(33, 29, 'Journal Article', 'First Author', '2023-06-02 18:33:24.000000'),
(34, 30, 'Journal Article', 'Co-author', '2022-11-27 01:36:26.000000'),
(35, 31, 'Journal Article', 'First Author', '2021-03-26 07:29:44.000000'),
(36, 32, 'Journal Article', 'Corresponding Author', '2022-09-03 23:07:02.000000'),
(37, 33, 'Journal Article', 'First Author', '2021-04-15 13:57:45.000000'),
(38, 34, 'Journal Article', 'Co-author', '2024-11-04 11:49:54.000000'),
(39, 35, 'Journal Article', 'Corresponding Author', '2023-07-07 02:23:28.000000'),
(40, 36, 'Journal Article', 'Co-author', '2024-10-06 21:59:00.000000'),
(41, 36, 'Journal Article', 'Co-author', '2023-07-25 12:03:44.000000'),
(42, 34, 'Journal Article', 'Co-author', '2023-12-01 18:47:38.000000'),
(43, 27, 'Journal Article', 'First Author', '2021-07-01 03:32:02.000000'),
(44, 35, 'Journal Article', 'Co-author', '2023-04-25 18:55:18.000000'),
(45, 37, 'Book with ISBN', NULL, '2021-03-28 02:12:39.000000'),
(46, 38, 'Book with ISBN', NULL, '2021-02-26 07:26:03.000000'),
(47, 39, 'Book Chapter', NULL, '2024-08-17 20:09:52.000000'),
(48, 40, 'Book Chapter', NULL, '2024-01-28 10:23:50.000000'),
(49, 41, 'Journal Article', 'Co-author', '2023-11-08 02:04:25.000000'),
(50, 42, 'Journal Article', 'Co-author', '2022-08-15 00:49:37.000000'),
(51, 43, 'Journal Article', 'First Author', '2024-01-15 22:19:15.000000'),
(52, 44, 'Journal Article', 'Corresponding Author', '2023-04-06 04:21:39.000000'),
(53, 45, 'Journal Article', 'Corresponding Author', '2024-08-02 06:02:34.000000'),
(54, 46, 'Journal Article', 'Co-author', '2024-08-02 15:52:19.000000'),
(55, 47, 'Journal Article', 'First Author', '2022-11-01 12:52:44.000000'),
(56, 48, 'Journal Article', 'First Author', '2021-08-07 03:12:34.000000'),
(57, 42, 'Journal Article', 'Co-author', '2024-07-10 12:58:08.000000'),
(58, 45, 'Journal Article', 'Co-author', '2023-06-24 10:07:23.000000'),
(59, 42, 'Journal Article', 'Co-author', '2021-04-01 23:40:18.000000'),
(60, 49, 'Book with ISBN', NULL, '2024-08-12 04:24:20.000000'),
(61, 50, 'Book with ISBN', NULL, '2024-09-13 22:59:08.000000'),
(62, 51, 'Book Chapter', NULL, '2022-12-24 14:34:41.000000'),
(63, 52, 'Book Chapter', NULL, '2023-04-28 21:49:49.000000'),
(64, 53, 'Journal Article', 'First Author', '2021-11-10 09:25:39.000000'),
(65, 54, 'Journal Article', 'First Author', '2023-04-04 08:25:57.000000'),
(66, 55, 'Journal Article', 'First Author', '2024-05-18 12:59:34.000000'),
(67, 56, 'Journal Article', 'First Author', '2023-02-18 18:14:10.000000'),
(68, 57, 'Journal Article', 'Corresponding Author', '2023-12-14 17:18:58.000000'),
(69, 58, 'Journal Article', 'First Author', '2022-06-17 11:13:50.000000'),
(70, 59, 'Journal Article', 'Co-author', '2024-01-13 04:30:21.000000'),
(71, 60, 'Journal Article', 'Corresponding Author', '2024-11-25 00:13:38.000000'),
(72, 53, 'Journal Article', 'Co-author', '2021-06-27 21:17:43.000000'),
(73, 52, 'Journal Article', 'Corresponding Author', '2021-12-12 14:41:39.000000'),
(74, 59, 'Journal Article', 'Co-author', '2022-03-16 06:22:12.000000'),
(75, 61, 'Book with ISBN', NULL, '2022-05-10 07:30:05.000000'),
(76, 62, 'Book with ISBN', NULL, '2021-04-09 05:40:25.000000'),
(77, 63, 'Book Chapter', NULL, '2024-07-10 19:51:39.000000'),
(78, 64, 'Book Chapter', NULL, '2022-03-21 20:37:05.000000'),
(79, 65, 'Journal Article', 'Co-author', '2022-09-07 08:19:15.000000'),
(80, 66, 'Journal Article', 'Corresponding Author', '2023-08-10 14:08:58.000000'),
(81, 67, 'Journal Article', 'Corresponding Author', '2023-03-04 06:40:50.000000'),
(82, 68, 'Journal Article', 'Co-author', '2023-06-22 17:21:03.000000'),
(83, 69, 'Journal Article', 'Corresponding Author', '2023-02-11 22:01:05.000000'),
(84, 70, 'Journal Article', 'First Author', '2022-07-05 19:16:06.000000'),
(85, 71, 'Journal Article', 'Corresponding Author', '2021-12-18 00:29:47.000000'),
(86, 72, 'Journal Article', 'Co-author', '2023-01-06 23:30:14.000000'),
(87, 66, 'Journal Article', 'First Author', '2023-06-20 06:28:13.000000'),
(88, 69, 'Journal Article', 'Corresponding Author', '2022-07-18 08:20:52.000000'),
(89, 67, 'Journal Article', 'First Author', '2022-06-08 10:29:38.000000'),
(90, 73, 'Book with ISBN', NULL, '2021-01-21 14:29:14.000000'),
(91, 74, 'Book with ISBN', NULL, '2024-08-24 01:30:12.000000'),
(92, 75, 'Book Chapter', NULL, '2022-06-19 02:21:17.000000'),
(93, 76, 'Book Chapter', NULL, '2023-07-04 18:02:51.000000'),
(94, 77, 'Journal Article', 'Corresponding Author', '2022-03-07 06:13:24.000000'),
(95, 78, 'Journal Article', 'Co-author', '2021-10-07 02:03:50.000000'),
(96, 79, 'Journal Article', 'Corresponding Author', '2021-06-05 18:02:19.000000'),
(97, 80, 'Journal Article', 'Co-author', '2021-09-04 19:23:18.000000'),
(98, 81, 'Journal Article', 'Corresponding Author', '2024-08-10 20:46:03.000000'),
(99, 82, 'Journal Article', 'Co-author', '2022-11-25 14:31:15.000000'),
(100, 83, 'Journal Article', 'Corresponding Author', '2021-08-02 02:05:35.000000'),
(101, 84, 'Journal Article', 'Co-author', '2023-05-14 06:53:09.000000'),
(102, 83, 'Journal Article', 'Corresponding Author', '2021-01-21 16:31:06.000000'),
(103, 80, 'Journal Article', 'Co-author', '2024-02-05 21:28:25.000000'),
(104, 82, 'Journal Article', 'Corresponding Author', '2024-04-14 07:47:12.000000'),
(105, 85, 'Book with ISBN', NULL, '2023-03-18 07:04:51.000000'),
(106, 86, 'Book with ISBN', NULL, '2024-06-25 07:57:46.000000'),
(107, 87, 'Book Chapter', NULL, '2022-01-19 23:48:35.000000'),
(108, 88, 'Book Chapter', NULL, '2021-04-19 20:44:33.000000'),
(109, 89, 'Journal Article', 'First Author', '2023-06-13 03:17:36.000000'),
(110, 90, 'Journal Article', 'Co-author', '2022-12-21 08:51:33.000000'),
(111, 91, 'Journal Article', 'Corresponding Author', '2024-05-06 12:08:11.000000'),
(112, 92, 'Journal Article', 'Co-author', '2024-06-27 18:33:05.000000'),
(113, 93, 'Journal Article', 'First Author', '2024-05-26 13:39:01.000000'),
(114, 94, 'Journal Article', 'First Author', '2023-04-01 22:14:47.000000'),
(115, 95, 'Journal Article', 'First Author', '2023-02-04 06:43:51.000000'),
(116, 96, 'Journal Article', 'First Author', '2021-09-07 02:42:56.000000'),
(117, 95, 'Journal Article', 'Corresponding Author', '2024-09-22 16:35:08.000000'),
(118, 86, 'Journal Article', 'Corresponding Author', '2022-11-09 16:11:27.000000'),
(119, 85, 'Journal Article', 'Co-author', '2021-12-27 22:48:41.000000'),
(120, 97, 'Book with ISBN', NULL, '2023-05-24 04:31:39.000000'),
(121, 98, 'Book with ISBN', NULL, '2023-05-04 06:39:32.000000'),
(122, 99, 'Book Chapter', NULL, '2022-07-16 16:35:55.000000'),
(123, 100, 'Book Chapter', NULL, '2021-08-07 17:28:24.000000'),
(124, 101, 'Journal Article', 'Co-author', '2022-09-16 18:42:48.000000'),
(125, 102, 'Journal Article', 'Corresponding Author', '2021-12-16 07:47:12.000000'),
(126, 103, 'Journal Article', 'Corresponding Author', '2024-07-12 05:41:23.000000'),
(127, 104, 'Journal Article', 'Co-author', '2023-08-20 05:18:40.000000'),
(128, 105, 'Journal Article', 'First Author', '2023-01-18 23:55:37.000000'),
(129, 106, 'Journal Article', 'Co-author', '2022-02-18 22:57:43.000000'),
(130, 107, 'Journal Article', 'Co-author', '2022-03-20 03:15:12.000000'),
(131, 108, 'Journal Article', 'Co-author', '2022-10-05 14:12:46.000000'),
(132, 100, 'Journal Article', 'Co-author', '2021-06-06 15:04:09.000000'),
(133, 100, 'Journal Article', 'Co-author', '2024-05-19 08:33:20.000000'),
(134, 103, 'Journal Article', 'Corresponding Author', '2021-12-14 08:49:46.000000'),
(135, 105, 'Journal Article', 'First Author', '2024-11-21 06:53:52.000000'),
(136, 109, 'Book with ISBN', NULL, '2022-08-15 04:43:21.000000'),
(137, 110, 'Book with ISBN', NULL, '2024-12-04 16:30:52.000000'),
(138, 111, 'Book Chapter', NULL, '2024-02-23 23:52:12.000000'),
(139, 112, 'Book Chapter', NULL, '2021-12-26 13:49:01.000000'),
(140, 113, 'Journal Article', 'Co-author', '2021-10-19 23:05:56.000000'),
(141, 114, 'Journal Article', 'First Author', '2022-02-22 19:21:35.000000'),
(142, 115, 'Journal Article', 'First Author', '2021-02-10 14:48:23.000000'),
(143, 116, 'Journal Article', 'Corresponding Author', '2024-01-22 08:13:04.000000'),
(144, 117, 'Journal Article', 'First Author', '2024-08-13 19:25:18.000000'),
(145, 118, 'Journal Article', 'Co-author', '2023-02-02 07:01:26.000000'),
(146, 119, 'Journal Article', 'First Author', '2021-02-03 21:11:22.000000'),
(147, 120, 'Journal Article', 'Corresponding Author', '2024-03-09 18:55:18.000000'),
(148, 111, 'Journal Article', 'Corresponding Author', '2022-08-25 22:12:52.000000'),
(149, 116, 'Journal Article', 'First Author', '2022-06-26 03:21:00.000000'),
(150, 121, 'Book with ISBN', NULL, '2023-09-01 05:12:24.000000'),
(151, 122, 'Book with ISBN', NULL, '2024-02-08 02:16:11.000000'),
(152, 123, 'Book Chapter', NULL, '2021-06-11 14:26:34.000000'),
(153, 124, 'Book Chapter', NULL, '2024-10-15 23:08:02.000000'),
(154, 125, 'Journal Article', 'Co-author', '2023-06-14 08:50:12.000000'),
(155, 126, 'Journal Article', 'Co-author', '2023-02-24 14:43:05.000000'),
(156, 127, 'Journal Article', 'Co-author', '2022-01-27 22:28:00.000000'),
(157, 128, 'Journal Article', 'Corresponding Author', '2024-11-27 14:05:37.000000'),
(158, 129, 'Journal Article', 'First Author', '2022-06-07 11:35:49.000000'),
(159, 130, 'Journal Article', 'Corresponding Author', '2022-08-09 23:45:45.000000'),
(160, 131, 'Journal Article', 'Co-author', '2021-06-25 13:33:06.000000'),
(161, 132, 'Journal Article', 'Co-author', '2024-01-26 19:21:59.000000'),
(162, 128, 'Journal Article', 'Co-author', '2021-04-26 17:20:06.000000'),
(163, 124, 'Journal Article', 'Corresponding Author', '2023-11-27 06:21:00.000000'),
(164, 123, 'Journal Article', 'First Author', '2021-10-21 22:43:52.000000'),
(165, 133, 'Book with ISBN', NULL, '2021-03-03 01:52:52.000000'),
(166, 134, 'Book with ISBN', NULL, '2024-11-16 08:02:59.000000'),
(167, 135, 'Book Chapter', NULL, '2022-09-02 23:57:42.000000'),
(168, 136, 'Book Chapter', NULL, '2024-01-08 15:20:22.000000'),
(169, 137, 'Journal Article', 'First Author', '2023-02-06 12:01:06.000000'),
(170, 138, 'Journal Article', 'First Author', '2021-04-25 13:59:29.000000'),
(171, 139, 'Journal Article', 'First Author', '2022-06-03 16:17:15.000000'),
(172, 140, 'Journal Article', 'Co-author', '2023-08-14 05:06:42.000000'),
(173, 141, 'Journal Article', 'Corresponding Author', '2021-02-19 18:33:41.000000'),
(174, 142, 'Journal Article', 'Co-author', '2024-02-24 12:15:15.000000'),
(175, 143, 'Journal Article', 'Co-author', '2024-07-02 15:14:56.000000'),
(176, 144, 'Journal Article', 'First Author', '2022-08-22 19:59:52.000000'),
(177, 142, 'Journal Article', 'Corresponding Author', '2022-02-13 13:49:06.000000'),
(178, 140, 'Journal Article', 'First Author', '2024-10-24 15:58:01.000000'),
(179, 102, 'Journal Article', 'Corresponding Author', '2024-01-09 00:53:28.000000'),
(180, 101, 'Book Chapter', NULL, '2022-08-27 22:21:13.000000'),
(181, 30, 'Book with ISBN', NULL, '2021-06-19 14:19:31.000000'),
(182, 1, 'Journal Article', 'Corresponding Author', '2022-12-17 20:31:07.000000'),
(183, 49, 'Journal Article', 'First Author', '2022-10-14 00:01:02.000000'),
(184, 56, 'Journal Article', 'Co-author', '2022-06-22 05:37:18.000000'),
(185, 48, 'Journal Article', 'Corresponding Author', '2024-01-19 19:23:57.000000'),
(186, 36, 'Journal Article', 'First Author', '2023-06-24 01:20:45.000000'),
(187, 111, 'Journal Article', 'First Author', '2024-06-08 10:01:21.000000'),
(188, 10, 'Book Chapter', NULL, '2022-07-13 14:51:24.000000'),
(189, 140, 'Journal Article', 'Co-author', '2021-02-14 01:03:24.000000'),
(190, 20, 'Journal Article', 'First Author', '2023-08-27 05:33:36.000000'),
(191, 13, 'Journal Article', 'Corresponding Author', '2021-05-11 23:14:03.000000'),
(192, 96, 'Journal Article', 'Corresponding Author', '2024-12-08 01:19:07.000000'),
(193, 43, 'Book with ISBN', NULL, '2022-09-17 23:44:43.000000'),
(194, 71, 'Journal Article', 'Corresponding Author', '2022-06-19 05:09:46.000000'),
(195, 124, 'Journal Article', 'Co-author', '2023-02-10 09:38:18.000000'),
(196, 26, 'Journal Article', 'First Author', '2021-03-05 16:50:15.000000'),
(197, 101, 'Journal Article', 'Corresponding Author', '2022-09-04 23:20:10.000000'),
(198, 66, 'Journal Article', 'Corresponding Author', '2024-09-21 13:28:54.000000'),
(199, 23, 'Journal Article', 'Co-author', '2021-09-14 10:23:34.000000'),
(200, 65, 'Journal Article', 'Corresponding Author', '2023-07-06 22:01:27.000000'),
(201, 15, 'Book with ISBN', NULL, '2023-05-27 10:30:53.000000'),
(202, 36, 'Journal Article', 'Co-author', '2023-12-23 10:01:04.000000'),
(203, 22, 'Book with ISBN', NULL, '2022-07-21 15:20:09.000000'),
(204, 139, 'Book with ISBN', NULL, '2022-06-27 21:41:45.000000'),
(205, 104, 'Book Chapter', NULL, '2022-06-05 14:53:51.000000'),
(206, 116, 'Book Chapter', NULL, '2021-09-06 22:29:43.000000'),
(207, 106, 'Journal Article', 'Corresponding Author', '2024-08-17 11:29:49.000000'),
(208, 20, 'Book with ISBN', NULL, '2021-10-25 06:47:56.000000'),
(209, 104, 'Journal Article', 'Corresponding Author', '2024-12-07 22:36:52.000000'),
(210, 1, 'Journal Article', 'First-Author', '2022-07-23 15:18:57.000000');

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
(144, 'Kabengele', 'Anderson', 'non-clinical', 6, 12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-26 17:32:01', NULL, NULL, NULL),
(145, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hrm001', 'hrm@must.ac.ug', '$2y$10$JUqu3adyHjukqD8RE2sSd.E/LqJFKvFxcO7oJuvy8MwcRTUmxg3jS', 'hrm', NULL, NULL, '2025-04-26 15:53:27', NULL, NULL, NULL);

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
(1, 2, 'Masters'),
(2, 43, 'Masters'),
(3, 1, 'PhD'),
(4, 2, 'Masters'),
(5, 2, 'Masters'),
(6, 2, 'Masters'),
(7, 3, 'PhD'),
(8, 3, 'Masters'),
(9, 4, 'Masters'),
(10, 4, 'Masters'),
(11, 4, 'PhD'),
(12, 4, 'Masters'),
(13, 13, 'Masters'),
(14, 13, 'Masters'),
(15, 13, 'PhD'),
(16, 14, 'Masters'),
(17, 14, 'Masters'),
(18, 14, 'Masters'),
(19, 15, 'PhD'),
(20, 15, 'Masters'),
(21, 16, 'Masters'),
(22, 16, 'Masters'),
(23, 16, 'PhD'),
(24, 16, 'Masters'),
(25, 25, 'Masters'),
(26, 25, 'Masters'),
(27, 25, 'PhD'),
(28, 26, 'Masters'),
(29, 26, 'Masters'),
(30, 26, 'Masters'),
(31, 27, 'PhD'),
(32, 27, 'Masters'),
(33, 28, 'Masters'),
(34, 28, 'Masters'),
(35, 28, 'PhD'),
(36, 28, 'Masters'),
(37, 37, 'Masters'),
(38, 37, 'Masters'),
(39, 37, 'PhD'),
(40, 38, 'Masters'),
(41, 38, 'Masters'),
(42, 38, 'Masters'),
(43, 39, 'PhD'),
(44, 39, 'Masters'),
(45, 40, 'Masters'),
(46, 40, 'Masters'),
(47, 40, 'PhD'),
(48, 40, 'Masters'),
(49, 49, 'Masters'),
(50, 49, 'Masters'),
(51, 49, 'PhD'),
(52, 50, 'Masters'),
(53, 50, 'Masters'),
(54, 50, 'Masters'),
(55, 51, 'PhD'),
(56, 51, 'Masters'),
(57, 52, 'Masters'),
(58, 52, 'Masters'),
(59, 52, 'PhD'),
(60, 52, 'Masters'),
(61, 61, 'Masters'),
(62, 61, 'Masters'),
(63, 61, 'PhD'),
(64, 62, 'Masters'),
(65, 62, 'Masters'),
(66, 62, 'Masters'),
(67, 63, 'PhD'),
(68, 63, 'Masters'),
(69, 64, 'Masters'),
(70, 64, 'Masters'),
(71, 64, 'PhD'),
(72, 64, 'Masters'),
(73, 73, 'Masters'),
(74, 73, 'Masters'),
(75, 73, 'PhD'),
(76, 74, 'Masters'),
(77, 74, 'Masters'),
(78, 74, 'Masters'),
(79, 75, 'PhD'),
(80, 75, 'Masters'),
(81, 76, 'Masters'),
(82, 76, 'Masters'),
(83, 76, 'PhD'),
(84, 76, 'Masters'),
(85, 85, 'Masters'),
(86, 85, 'Masters'),
(87, 85, 'PhD'),
(88, 86, 'Masters'),
(89, 86, 'Masters'),
(90, 86, 'Masters'),
(91, 87, 'PhD'),
(92, 87, 'Masters'),
(93, 88, 'Masters'),
(94, 88, 'Masters'),
(95, 88, 'PhD'),
(96, 88, 'Masters'),
(97, 97, 'Masters'),
(98, 97, 'Masters'),
(99, 97, 'PhD'),
(100, 98, 'Masters'),
(101, 98, 'Masters'),
(102, 98, 'Masters'),
(103, 99, 'PhD'),
(104, 99, 'Masters'),
(105, 100, 'Masters'),
(106, 100, 'Masters'),
(107, 100, 'PhD'),
(108, 100, 'Masters'),
(109, 109, 'Masters'),
(110, 109, 'Masters'),
(111, 109, 'PhD'),
(112, 110, 'Masters'),
(113, 110, 'Masters'),
(114, 110, 'Masters'),
(115, 111, 'PhD'),
(116, 111, 'Masters'),
(117, 112, 'Masters'),
(118, 112, 'Masters'),
(119, 112, 'PhD'),
(120, 112, 'Masters'),
(121, 121, 'Masters'),
(122, 121, 'Masters'),
(123, 121, 'PhD'),
(124, 122, 'Masters'),
(125, 122, 'Masters'),
(126, 122, 'Masters'),
(127, 123, 'PhD'),
(128, 123, 'Masters'),
(129, 124, 'Masters'),
(130, 124, 'Masters'),
(131, 124, 'PhD'),
(132, 124, 'Masters'),
(133, 133, 'Masters'),
(134, 133, 'Masters'),
(135, 133, 'PhD'),
(136, 134, 'Masters'),
(137, 134, 'Masters'),
(138, 134, 'Masters'),
(139, 135, 'PhD'),
(140, 135, 'Masters'),
(141, 136, 'Masters'),
(142, 136, 'Masters'),
(143, 136, 'PhD'),
(144, 136, 'Masters'),
(145, 1, 'Masters'),
(146, 1, 'PhD'),
(147, 2, 'Masters'),
(148, 2, 'Masters'),
(149, 3, 'PhD'),
(150, 3, 'Masters'),
(151, 4, 'Masters'),
(152, 4, 'PhD'),
(153, 13, 'Masters'),
(154, 13, 'Masters'),
(155, 14, 'PhD'),
(156, 14, 'Masters'),
(157, 15, 'Masters'),
(158, 15, 'Masters'),
(159, 16, 'PhD'),
(160, 16, 'Masters'),
(161, 25, 'Masters'),
(162, 25, 'PhD'),
(163, 26, 'Masters'),
(164, 26, 'Masters'),
(165, 27, 'PhD'),
(166, 27, 'Masters'),
(167, 28, 'Masters'),
(168, 28, 'PhD'),
(169, 37, 'Masters'),
(170, 37, 'Masters'),
(171, 38, 'PhD'),
(172, 38, 'Masters'),
(173, 39, 'Masters'),
(174, 39, 'Masters'),
(175, 40, 'PhD'),
(176, 40, 'Masters'),
(177, 49, 'Masters'),
(178, 49, 'PhD'),
(179, 50, 'Masters'),
(180, 50, 'Masters'),
(181, 51, 'PhD'),
(182, 51, 'Masters'),
(183, 52, 'Masters'),
(184, 52, 'PhD'),
(185, 61, 'Masters'),
(186, 61, 'Masters'),
(187, 62, 'PhD'),
(188, 62, 'Masters'),
(189, 63, 'Masters'),
(190, 63, 'Masters'),
(191, 64, 'PhD'),
(192, 64, 'Masters'),
(193, 73, 'Masters'),
(194, 73, 'PhD'),
(195, 74, 'Masters'),
(196, 74, 'Masters'),
(197, 75, 'PhD'),
(198, 75, 'Masters'),
(199, 76, 'Masters'),
(200, 76, 'PhD'),
(201, 85, 'Masters'),
(202, 85, 'Masters'),
(203, 86, 'PhD'),
(204, 86, 'Masters'),
(205, 87, 'Masters'),
(206, 87, 'Masters'),
(207, 88, 'PhD'),
(208, 88, 'Masters'),
(209, 97, 'Masters'),
(210, 97, 'PhD'),
(211, 98, 'Masters'),
(212, 98, 'Masters'),
(213, 99, 'PhD'),
(214, 99, 'Masters'),
(215, 100, 'Masters'),
(216, 100, 'PhD'),
(217, 109, 'Masters'),
(218, 109, 'Masters'),
(219, 110, 'PhD'),
(220, 110, 'Masters'),
(221, 111, 'Masters'),
(222, 111, 'Masters'),
(223, 112, 'PhD'),
(224, 112, 'Masters'),
(225, 121, 'Masters'),
(226, 121, 'PhD'),
(227, 122, 'Masters'),
(228, 122, 'Masters'),
(229, 123, 'PhD'),
(230, 123, 'Masters'),
(231, 124, 'Masters'),
(232, 124, 'PhD'),
(233, 133, 'Masters'),
(234, 133, 'Masters'),
(235, 134, 'PhD'),
(236, 134, 'Masters'),
(237, 135, 'Masters'),
(238, 135, 'Masters'),
(239, 136, 'PhD'),
(240, 136, 'Masters');

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
  MODIFY `degree_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grants`
--
ALTER TABLE `grants`
  MODIFY `grant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `innovations`
--
ALTER TABLE `innovations`
  MODIFY `innovation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

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
  MODIFY `publication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

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
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `supervision`
--
ALTER TABLE `supervision`
  MODIFY `supervision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

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
