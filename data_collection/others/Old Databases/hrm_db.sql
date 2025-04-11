-- Create the database
-- CREATE DATABASE hrm_db;

-- Use the database
USE hrm_db;

-- Table 2: Roles
CREATE TABLE Roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50),
    max_points INT
);

-- Table 3: Degrees
CREATE TABLE Degrees (
    degree_id INT PRIMARY KEY AUTO_INCREMENT,
    degree_name VARCHAR(50),
    degree_classification VARCHAR(50),
    points INT
);

-- Table 12: Departments
CREATE TABLE Departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100)
);

-- Table 1: Staff
CREATE TABLE Staff (
    staff_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role_id INT,
    degree_id INT,
    department_id INT,
    years_of_experience INT,
    performance_score INT,
    FOREIGN KEY (role_id) REFERENCES Roles(role_id),
    FOREIGN KEY (degree_id) REFERENCES Degrees(degree_id),
    FOREIGN KEY (department_id) REFERENCES Departments(department_id)
);

-- Table 4: Publications
CREATE TABLE Publications (
    publication_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    publication_type VARCHAR(50), -- e.g., Journal, Book, Chapter
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 5: Grants
CREATE TABLE Grants (
    grant_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    grant_amount DECIMAL(15, 2), -- Grant amount in UGX
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 6: Supervision
CREATE TABLE Supervision (
    supervision_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    student_level VARCHAR(50), -- e.g., Masters, PhD
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 7: Innovations
CREATE TABLE Innovations (
    innovation_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    innovation_type VARCHAR(50), -- e.g., Patent, Product
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 8: Academic Activities
CREATE TABLE AcademicActivities (
    activity_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    activity_type VARCHAR(100), -- e.g., External Examination, Conference Presentation
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 9: Service
CREATE TABLE Service (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    service_type VARCHAR(50), -- e.g., Dean, Head of Department
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 10: Community Service
CREATE TABLE CommunityService (
    community_service_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    description TEXT,
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 11: Professional Bodies
CREATE TABLE ProfessionalBodies (
    professional_body_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT,
    body_name VARCHAR(100),
    points INT,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id)
);

-- Table 13: Users
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id VARCHAR(100),
    email VARCHAR(100),
    passkey VARCHAR(100)

);

