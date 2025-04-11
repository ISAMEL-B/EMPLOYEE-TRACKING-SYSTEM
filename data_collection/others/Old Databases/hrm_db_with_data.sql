-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 12, 2024 at 08:33 PM
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
-- Database: `hrm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `AcademicActivities`
--

CREATE TABLE `AcademicActivities` (
  `activity_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `AcademicActivities`
--

INSERT INTO `AcademicActivities` (`activity_id`, `staff_id`, `activity_type`, `points`) VALUES
(1, 1, 'External Examination', 1),
(2, 2, 'Internal Examination', 1),
(3, 3, 'Conference Presentation', 0),
(4, 4, 'Journal Editor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `CommunityService`
--

CREATE TABLE `CommunityService` (
  `community_service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CommunityService`
--

INSERT INTO `CommunityService` (`community_service_id`, `staff_id`, `description`, `points`) VALUES
(1, 1, 'Volunteer Teaching', 5),
(2, 2, 'Community Cleanup', 5),
(3, 3, 'Technical Workshop', 5),
(4, 4, 'Health Awareness Campaign', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Degrees`
--

CREATE TABLE `Degrees` (
  `degree_id` int(11) NOT NULL,
  `degree_name` varchar(50) DEFAULT NULL,
  `degree_classification` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Degrees`
--

INSERT INTO `Degrees` (`degree_id`, `degree_name`, `degree_classification`, `points`) VALUES
(9, 'Bachelor of Science in Software Engineering', 'First Class', 4),
(10, 'Bachelor of Science in Information Technology', 'Second Class Upper', 4),
(11, 'Bachelor of Science in Computer Science', 'Second Class Lower', 3),
(12, 'Bachelor of Engineering in Telecommunication', 'First Class', 4),
(13, '1', 'External Examination', 1),
(14, '2', 'Internal Examination', 1),
(15, '3', 'Conference Presentation', 0),
(16, '4', 'Journal Editor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Departments`
--

CREATE TABLE `Departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Departments`
--

INSERT INTO `Departments` (`department_id`, `department_name`) VALUES
(1, 'Computer Science'),
(2, 'Information Technology'),
(3, 'Electrical Engineering'),
(4, 'Mechanical Engineering'),
(5, 'Civil Engineering'),
(6, 'Human Resource Management'),
(7, 'Mechanical Engineerings');

-- --------------------------------------------------------

--
-- Table structure for table `Grants`
--

CREATE TABLE `Grants` (
  `grant_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `grant_amount` decimal(15,2) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Grants`
--

INSERT INTO `Grants` (`grant_id`, `staff_id`, `grant_amount`, `points`) VALUES
(5, 1, 1500000000.00, 12),
(6, 2, 800000000.00, 8),
(7, 3, 450000000.00, 6),
(8, 4, 50000000.00, 4);

-- --------------------------------------------------------

--
-- Table structure for table `Innovations`
--

CREATE TABLE `Innovations` (
  `innovation_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `innovation_type` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Innovations`
--

INSERT INTO `Innovations` (`innovation_id`, `staff_id`, `innovation_type`, `points`) VALUES
(1, 1, 'Patent', 5),
(2, 2, 'Utility Model', 4),
(3, 3, 'Copyright', 3),
(4, 4, 'Product', 3);

-- --------------------------------------------------------

--
-- Table structure for table `ProfessionalBodies`
--

CREATE TABLE `ProfessionalBodies` (
  `professional_body_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `body_name` varchar(100) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ProfessionalBodies`
--

INSERT INTO `ProfessionalBodies` (`professional_body_id`, `staff_id`, `body_name`, `points`) VALUES
(5, 1, 'IEEE', 1),
(6, 2, 'ACM', 1),
(7, 3, 'ISTE', 1),
(8, 4, 'National Engineers Society', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Publications`
--

CREATE TABLE `Publications` (
  `publication_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `publication_type` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Publications`
--

INSERT INTO `Publications` (`publication_id`, `staff_id`, `publication_type`, `points`) VALUES
(5, 1, 'Journal', 4),
(6, 2, 'Book', 12),
(7, 3, 'Chapter', 4),
(8, 4, 'Journal', 2),
(9, 5, 'Co-author', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL,
  `max_points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`role_id`, `role_name`, `max_points`) VALUES
(37, 'Professor', 100),
(38, 'Associate Professor', 80),
(39, 'Senior Lecturer', 60),
(40, 'Lecturer', 50),
(41, 'Assistant Lecturer', 40),
(42, 'Teaching Assistant', 30);

-- --------------------------------------------------------

--
-- Table structure for table `Service`
--

CREATE TABLE `Service` (
  `service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Service`
--

INSERT INTO `Service` (`service_id`, `staff_id`, `service_type`, `points`) VALUES
(21, 1, 'Dean', 5),
(22, 2, 'Head of Department', 2),
(23, 3, 'Committee Member', 1),
(24, 4, 'Deputy Director', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `degree_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `performance_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`staff_id`, `first_name`, `last_name`, `role_id`, `degree_id`, `department_id`, `years_of_experience`, `performance_score`) VALUES
(1, 'John', 'Doe', 37, 9, 1, 15, 85),
(2, 'Jane', 'Smith', 38, 10, 2, 10, 75),
(3, 'Michael', 'Johnson', 39, 11, 3, 8, 65),
(4, 'Sarah', 'Brown', 41, 12, 4, 6, 55),
(5, 'Emily', 'Davis', 42, 9, 5, 4, 45);

-- --------------------------------------------------------

--
-- Table structure for table `Supervision`
--

CREATE TABLE `Supervision` (
  `supervision_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_level` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Supervision`
--

INSERT INTO `Supervision` (`supervision_id`, `staff_id`, `student_level`, `points`) VALUES
(1, 1, 'PhD', 6),
(2, 2, 'Masters', 2),
(3, 3, 'PhD', 6),
(4, 4, 'Masters', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AcademicActivities`
--
ALTER TABLE `AcademicActivities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `CommunityService`
--
ALTER TABLE `CommunityService`
  ADD PRIMARY KEY (`community_service_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `Degrees`
--
ALTER TABLE `Degrees`
  ADD PRIMARY KEY (`degree_id`);

--
-- Indexes for table `Departments`
--
ALTER TABLE `Departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `Grants`
--
ALTER TABLE `Grants`
  ADD PRIMARY KEY (`grant_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `Innovations`
--
ALTER TABLE `Innovations`
  ADD PRIMARY KEY (`innovation_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `ProfessionalBodies`
--
ALTER TABLE `ProfessionalBodies`
  ADD PRIMARY KEY (`professional_body_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `Publications`
--
ALTER TABLE `Publications`
  ADD PRIMARY KEY (`publication_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `Service`
--
ALTER TABLE `Service`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `degree_id` (`degree_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `Supervision`
--
ALTER TABLE `Supervision`
  ADD PRIMARY KEY (`supervision_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AcademicActivities`
--
ALTER TABLE `AcademicActivities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `CommunityService`
--
ALTER TABLE `CommunityService`
  MODIFY `community_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Degrees`
--
ALTER TABLE `Degrees`
  MODIFY `degree_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Departments`
--
ALTER TABLE `Departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Grants`
--
ALTER TABLE `Grants`
  MODIFY `grant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Innovations`
--
ALTER TABLE `Innovations`
  MODIFY `innovation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ProfessionalBodies`
--
ALTER TABLE `ProfessionalBodies`
  MODIFY `professional_body_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Publications`
--
ALTER TABLE `Publications`
  MODIFY `publication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `Service`
--
ALTER TABLE `Service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Supervision`
--
ALTER TABLE `Supervision`
  MODIFY `supervision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AcademicActivities`
--
ALTER TABLE `AcademicActivities`
  ADD CONSTRAINT `AcademicActivities_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `CommunityService`
--
ALTER TABLE `CommunityService`
  ADD CONSTRAINT `CommunityService_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `Grants`
--
ALTER TABLE `Grants`
  ADD CONSTRAINT `Grants_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `Innovations`
--
ALTER TABLE `Innovations`
  ADD CONSTRAINT `Innovations_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `ProfessionalBodies`
--
ALTER TABLE `ProfessionalBodies`
  ADD CONSTRAINT `ProfessionalBodies_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `Publications`
--
ALTER TABLE `Publications`
  ADD CONSTRAINT `Publications_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `Service`
--
ALTER TABLE `Service`
  ADD CONSTRAINT `Service_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);

--
-- Constraints for table `Staff`
--
ALTER TABLE `Staff`
  ADD CONSTRAINT `Staff_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `Roles` (`role_id`),
  ADD CONSTRAINT `Staff_ibfk_2` FOREIGN KEY (`degree_id`) REFERENCES `Degrees` (`degree_id`),
  ADD CONSTRAINT `Staff_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `Departments` (`department_id`);

--
-- Constraints for table `Supervision`
--
ALTER TABLE `Supervision`
  ADD CONSTRAINT `Supervision_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `Staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
