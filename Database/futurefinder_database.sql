-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 22, 2026 at 06:30 AM
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
-- Database: `futurefinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `Answers`
--

CREATE TABLE `Answers` (
  `AnswerID` int(11) NOT NULL,
  `AssessmentID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `SelectedOption` varchar(255) DEFAULT NULL,
  `IsCorrect` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Answers`
--

INSERT INTO `Answers` (`AnswerID`, `AssessmentID`, `QuestionID`, `SelectedOption`, `IsCorrect`) VALUES
(1, 340, 1, 'Building or styling web pages and seeing them come to life in a browser', 0),
(2, 340, 2, 'I immediately think about how the system failed and want to find the root cause in the software or hardware', 0),
(3, 340, 3, 'Set up team workflows and access permissions for the organization', 0),
(4, 340, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(5, 340, 5, 'Redesign the screens and improve the visual layout to make it more intuitive', 0),
(6, 340, 6, 'I enjoy organizing people, resources and plans to achieve a goal efficiently', 0),
(7, 340, 7, 'Building new interactive features students would actually find useful', 0),
(8, 340, 8, 'Google Analytics or Meta Ads — tracking campaigns and growing an online audience', 0),
(9, 340, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(10, 340, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(11, 340, 11, 'Designing the interview process, onboarding plan and making candidates feel welcome', 0),
(12, 340, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(13, 341, 1, 'Building or styling web pages and seeing them come to life in a browser', 0),
(14, 341, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(15, 341, 3, 'Set up team workflows and access permissions for the organization', 0),
(16, 341, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(17, 341, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(18, 341, 6, 'I enjoy organizing people, resources and plans to achieve a goal efficiently', 0),
(19, 341, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(20, 341, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(21, 341, 9, 'Whether their SEO is good, how they attract visitors and what their conversion strategy is', 0),
(22, 341, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(23, 341, 11, 'Creating a social media campaign to attract the right candidates online', 0),
(24, 341, 12, 'I led a team that delivered a major project on time, on budget and beyond expectations', 0),
(25, 342, 1, 'Building or styling web pages and seeing them come to life in a browser', 0),
(26, 342, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(27, 342, 3, 'Set up team workflows and access permissions for the organization', 0),
(28, 342, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(29, 342, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(30, 342, 6, 'I enjoy imagining how things could look better and sketching new ideas', 0),
(31, 342, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(32, 342, 8, 'Google Analytics or Meta Ads — tracking campaigns and growing an online audience', 0),
(33, 342, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(34, 342, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(35, 342, 11, 'Creating a social media campaign to attract the right candidates online', 0),
(36, 342, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(37, 343, 1, 'Writing or reading code and fixing software bugs', 0),
(38, 343, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(39, 343, 3, 'Set up team workflows and access permissions for the organization', 0),
(40, 343, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(41, 343, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(42, 343, 6, 'I enjoy imagining how things could look better and sketching new ideas', 0),
(43, 343, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(44, 343, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(45, 343, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(46, 343, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(47, 343, 11, 'Analyzing applicant data to find patterns in who performs best after hiring', 0),
(48, 343, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(49, 343, 1, 'Writing or reading code and fixing software bugs', 0),
(50, 343, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(51, 343, 3, 'Set up team workflows and access permissions for the organization', 0),
(52, 343, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(53, 343, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(54, 343, 6, 'I enjoy imagining how things could look better and sketching new ideas', 0),
(55, 343, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(56, 343, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(57, 343, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(58, 343, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(59, 343, 11, 'Analyzing applicant data to find patterns in who performs best after hiring', 0),
(60, 343, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(61, 343, 1, 'Writing or reading code and fixing software bugs', 0),
(62, 343, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(63, 343, 3, 'Set up team workflows and access permissions for the organization', 0),
(64, 343, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(65, 343, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(66, 343, 6, 'I enjoy imagining how things could look better and sketching new ideas', 0),
(67, 343, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(68, 343, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(69, 343, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(70, 343, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(71, 343, 11, 'Analyzing applicant data to find patterns in who performs best after hiring', 0),
(72, 343, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(73, 343, 1, 'Writing or reading code and fixing software bugs', 0),
(74, 343, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(75, 343, 3, 'Set up team workflows and access permissions for the organization', 0),
(76, 343, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(77, 343, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(78, 343, 6, 'I enjoy imagining how things could look better and sketching new ideas', 0),
(79, 343, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(80, 343, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(81, 343, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(82, 343, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(83, 343, 11, 'Analyzing applicant data to find patterns in who performs best after hiring', 0),
(84, 343, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(85, 343, 1, 'Writing or reading code and fixing software bugs', 0),
(86, 343, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(87, 343, 3, 'Set up team workflows and access permissions for the organization', 0),
(88, 343, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(89, 343, 5, 'Run surveys and user interviews to understand what people actually want', 0),
(90, 343, 6, 'I enjoy imagining how things could look better and sketching new ideas', 0),
(91, 343, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(92, 343, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(93, 343, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(94, 343, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(95, 343, 11, 'Analyzing applicant data to find patterns in who performs best after hiring', 0),
(96, 343, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Assessments`
--

CREATE TABLE `Assessments` (
  `AssessmentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `AssessmentType` varchar(100) DEFAULT 'Career Assessment',
  `Date` date NOT NULL,
  `TotalScore` decimal(5,2) DEFAULT 0.00,
  `CompletedDate` datetime DEFAULT NULL,
  `Status` enum('in_progress','completed') DEFAULT 'in_progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Assessments`
--

INSERT INTO `Assessments` (`AssessmentID`, `UserID`, `AssessmentType`, `Date`, `TotalScore`, `CompletedDate`, `Status`) VALUES
(339, 19, 'Career Assessment', '2026-09-22', 0.00, NULL, 'in_progress'),
(340, 19, 'Career Assessment', '2026-09-22', 0.00, NULL, 'in_progress'),
(341, 19, 'Career Assessment', '2026-09-22', 0.00, NULL, 'in_progress'),
(342, 19, 'Career Assessment', '2026-09-22', 0.00, NULL, 'in_progress'),
(343, 19, 'Career Assessment', '2026-09-22', 0.00, '2026-09-22 06:29:45', 'completed'),
(344, 19, 'Career Assessment', '2026-09-22', 0.00, NULL, 'in_progress');

-- --------------------------------------------------------

--
-- Table structure for table `Careers`
--

CREATE TABLE `Careers` (
  `CareerID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `SalaryRange` varchar(100) NOT NULL,
  `Demand` varchar(50) NOT NULL,
  `Growth` varchar(50) NOT NULL,
  `RequiredEducation` varchar(150) NOT NULL,
  `Industry` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Careers`
--

INSERT INTO `Careers` (`CareerID`, `Title`, `Description`, `SalaryRange`, `Demand`, `Growth`, `RequiredEducation`, `Industry`) VALUES
(1, 'Software Engineer', 'Designs, develops and maintains software systems and applications.', 'LKR 80,000 - 250,000/month', 'Very High', 'High', 'Bachelor\'s in Computer Science or IT', 'Technology'),
(2, 'Data Analyst', 'Collects, processes and analyses data to help businesses make decisions.', 'LKR 60,000 - 180,000/month', 'High', 'High', 'Bachelor\'s in Statistics, IT or Mathematics', 'Technology / Finance'),
(3, 'UI/UX Designer', 'Designs user interfaces and creates excellent user experiences for digital products.', 'LKR 50,000 - 150,000/month', 'High', 'Medium', 'Bachelor\'s in Design, IT or related field', 'Technology / Media'),
(4, 'Network Engineer', 'Plans, implements and manages computer networks for organizations.', 'LKR 60,000 - 180,000/month', 'Medium', 'Medium', 'Bachelor\'s in IT, Computer Networking', 'Technology / Telecom'),
(5, 'Project Manager', 'Plans, leads and oversees projects to ensure they are completed on time and within budget.', 'LKR 80,000 - 220,000/month', 'High', 'Medium', 'Bachelor\'s in Management or IT', 'Management / Technology'),
(6, 'Cybersecurity Analyst', 'Protects systems and networks from cyber threats by monitoring, detecting and responding to security incidents.', 'LKR 70,000 - 200,000/month', 'High', 'High', 'Bachelor\'s in Cybersecurity, IT or Computer Science', 'Technology'),
(7, 'DevOps Engineer', 'Builds and maintains the infrastructure and pipelines that let software be built, tested and released quickly and reliably.', 'LKR 90,000 - 230,000/month', 'High', 'High', 'Bachelor\'s in Computer Science or IT', 'Technology'),
(8, 'Data Scientist', 'Builds statistical models and machine learning systems to uncover insights and predictions from complex data.', 'LKR 100,000 - 260,000/month', 'High', 'High', 'Bachelor\'s in Data Science, Statistics or Computer Science', 'Technology'),
(9, 'Product Manager', 'Defines product vision and priorities, working across engineering, design and business to ship features users want.', 'LKR 100,000 - 250,000/month', 'High', 'High', 'Bachelor\'s in Business, IT or related field', 'Technology / Management'),
(10, 'Digital Marketing Specialist', 'Plans and runs online marketing campaigns, tracking analytics and audience data to grow reach and conversions.', 'LKR 50,000 - 150,000/month', 'High', 'Medium', 'Bachelor\'s in Marketing, Business or Communications', 'Marketing / Media'),
(11, 'Business Analyst', 'Analyses business processes and data to recommend improvements and support decision-making.', 'LKR 70,000 - 190,000/month', 'High', 'Medium', 'Bachelor\'s in Business, IT or related field', 'Technology / Finance'),
(12, 'QA Engineer', 'Tests software systematically to find bugs and ensure quality before release.', 'LKR 60,000 - 170,000/month', 'Medium', 'Medium', 'Bachelor\'s in Computer Science or IT', 'Technology'),
(13, 'HR / Talent Acquisition Specialist', 'Manages recruitment, hiring processes and candidate experience to help organisations build strong teams.', 'LKR 55,000 - 160,000/month', 'Medium', 'Medium', 'Bachelor\'s in Human Resources, Business or related field', 'Human Resources'),
(14, 'Database Administrator', 'Designs, secures and maintains an organisation\'s databases, ensuring performance, integrity and availability.', 'LKR 70,000 - 190,000/month', 'Medium', 'Medium', 'Bachelor\'s in Computer Science or IT', 'Technology'),
(15, 'Frontend Developer', 'Builds the visual, interactive parts of websites and apps that users see and interact with directly.', 'LKR 70,000 - 200,000/month', 'Very High', 'High', 'Bachelor\'s in Computer Science or IT', 'Technology'),
(16, 'AI/ML Engineer', 'Builds machine learning models and AI systems for automation and predictive analytics.', 'LKR 120,000 – 300,000/month', 'Very High', 'High', 'Master\'s/PhD in CS/AI', 'Technology'),
(17, 'Cloud Architect', 'Designs and oversees cloud infrastructure strategy on AWS/Azure/GCP.', 'LKR 110,000 – 280,000/month', 'High', 'High', 'Bachelor\'s in CS/IT + cloud certifications', 'Technology'),
(18, 'Full-Stack Developer', 'Develops both frontend and backend of web applications.', 'LKR 80,000 – 240,000/month', 'Very High', 'High', 'Bachelor\'s in CS or IT', 'Technology'),
(19, 'Mobile App Developer', 'Builds iOS/Android apps using native or cross-platform frameworks.', 'LKR 75,000 – 220,000/month', 'High', 'High', 'Bachelor\'s in CS or IT', 'Technology'),
(20, 'IT Consultant', 'Advises organisations on technology strategies and digital transformation.', 'LKR 90,000 – 250,000/month', 'High', 'Medium', 'Bachelor\'s in IT, Business, or related', 'Technology / Business'),
(21, 'Technical Writer', 'Writes documentation, user guides, and technical content for software/hardware.', 'LKR 50,000 – 150,000/month', 'Medium', 'Low', 'Bachelor\'s in English, Tech Comm, or IT', 'Technology / Media'),
(22, 'Product Manager', 'Defines product vision, prioritises features, and coordinates cross-functional teams.', 'LKR 100,000 – 280,000/month', 'Very High', 'High', 'Bachelor\'s in Business, IT, or Marketing', 'Technology / Management');

-- --------------------------------------------------------

--
-- Table structure for table `Career_Skills`
--

CREATE TABLE `Career_Skills` (
  `CareerID` int(11) NOT NULL,
  `SkillID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Career_Skills`
--

INSERT INTO `Career_Skills` (`CareerID`, `SkillID`) VALUES
(1, 1),
(1, 2),
(1, 7),
(2, 3),
(2, 7),
(2, 10),
(3, 2),
(3, 4),
(3, 6),
(4, 1),
(4, 2),
(4, 8),
(5, 4),
(5, 5),
(5, 9),
(16, 1),
(16, 7),
(16, 10),
(17, 1),
(17, 2),
(17, 8),
(18, 1),
(18, 2),
(18, 4),
(19, 1),
(19, 2),
(20, 4),
(20, 9),
(20, 10),
(21, 2),
(21, 4),
(22, 4),
(22, 5),
(22, 9);

-- --------------------------------------------------------

--
-- Table structure for table `Comparisons`
--

CREATE TABLE `Comparisons` (
  `ComparisonID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Career1ID` int(11) NOT NULL,
  `Career2ID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE `Courses` (
  `CourseID` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Provider` varchar(150) DEFAULT NULL,
  `URL` varchar(300) DEFAULT NULL,
  `CareerID` int(11) DEFAULT NULL,
  `IsFree` tinyint(1) DEFAULT 0 COMMENT '1=Free, 0=Paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`CourseID`, `Title`, `Provider`, `URL`, `CareerID`, `IsFree`) VALUES
(1, 'CS50 Introduction to Computer Science', 'Harvard / edX', 'https://cs50.harvard.edu', 1, 1),
(2, 'The Complete Java Masterclass', 'Udemy', 'https://www.udemy.com', 1, 0),
(3, 'Python for Everybody', 'Coursera / University of Michigan', 'https://www.coursera.org', 1, 1),
(4, 'Data Structures and Algorithms', 'freeCodeCamp', 'https://www.freecodecamp.org', 1, 1),
(5, 'Clean Code with Java', 'Pluralsight', 'https://www.pluralsight.com', 1, 0),
(6, 'The Web Developer Bootcamp', 'Udemy', 'https://www.udemy.com', 2, 0),
(7, 'Full Stack Open', 'University of Helsinki', 'https://fullstackopen.com', 2, 1),
(8, 'Responsive Web Design', 'freeCodeCamp', 'https://www.freecodecamp.org', 2, 1),
(9, 'React - The Complete Guide', 'Udemy', 'https://www.udemy.com', 2, 0),
(10, 'JavaScript Algorithms and Data Structures', 'freeCodeCamp', 'https://www.freecodecamp.org', 2, 1),
(11, 'Google UX Design Certificate', 'Google / Coursera', 'https://www.coursera.org', 3, 0),
(12, 'UI Design Fundamentals', 'Scrimba', 'https://scrimba.com', 3, 1),
(13, 'Advanced Figma Masterclass', 'Udemy', 'https://www.udemy.com', 3, 0),
(14, 'Intro to UI and UX Design', 'Codecademy', 'https://www.codecademy.com', 3, 1),
(15, 'Human-Centred Design', 'IDEO / Acumen', 'https://www.plusacumen.org', 3, 1),
(16, 'Cisco CCNA - Complete Course', 'Udemy', 'https://www.udemy.com', 4, 0),
(17, 'Introduction to Networking', 'Cisco Networking Academy', 'https://www.netacad.com', 4, 1),
(18, 'CompTIA Network+ Prep', 'Professor Messer', 'https://www.professormesser.com', 4, 1),
(19, 'Google IT Support Professional', 'Google / Coursera', 'https://www.coursera.org', 4, 0),
(20, 'Networking Basics', 'freeCodeCamp', 'https://www.freecodecamp.org', 4, 1),
(21, 'Google Project Management Certificate', 'Google / Coursera', 'https://www.coursera.org', 5, 0),
(22, 'PMP Exam Prep - Complete Course', 'Udemy', 'https://www.udemy.com', 5, 0),
(23, 'Agile Fundamentals - Scrum', 'Coursera', 'https://www.coursera.org', 5, 1),
(24, 'Project Management Principles', 'OpenLearn / Open University', 'https://www.open.edu/openlearn', 5, 1),
(25, 'Jira Fundamentals', 'Atlassian University', 'https://university.atlassian.com', 5, 1),
(26, 'Google Cybersecurity Certificate', 'Google / Coursera', 'https://www.coursera.org', 6, 0),
(27, 'CompTIA Security+ Prep', 'Udemy', 'https://www.udemy.com', 6, 0),
(28, 'Ethical Hacking Bootcamp', 'TCM Security', 'https://tcm-sec.com', 6, 0),
(29, 'Introduction to Cybersecurity', 'Cisco Networking Academy', 'https://www.netacad.com', 6, 1),
(30, 'Cybersecurity Fundamentals', 'IBM / edX', 'https://www.edx.org', 6, 1),
(31, 'Docker and Kubernetes - Complete Guide', 'Udemy', 'https://www.udemy.com', 7, 0),
(32, 'DevOps Beginners to Advanced', 'Udemy', 'https://www.udemy.com', 7, 0),
(33, 'Introduction to DevOps', 'IBM / Coursera', 'https://www.coursera.org', 7, 1),
(34, 'Git and GitHub for Beginners', 'freeCodeCamp', 'https://www.freecodecamp.org', 7, 1),
(35, 'Linux Command Line Basics', 'Udacity', 'https://www.udacity.com', 7, 1),
(36, 'IBM Data Science Professional Certificate', 'IBM / Coursera', 'https://www.coursera.org', 8, 0),
(37, 'Machine Learning Specialisation', 'Stanford / Coursera', 'https://www.coursera.org', 8, 0),
(38, 'Python for Data Analysis', 'DataCamp', 'https://www.datacamp.com', 8, 0),
(39, 'Data Analysis with Python', 'freeCodeCamp', 'https://www.freecodecamp.org', 8, 1),
(40, 'Statistics for Data Science', 'Khan Academy', 'https://www.khanacademy.org', 8, 1),
(41, 'Become a Product Manager', 'Udemy', 'https://www.udemy.com', NULL, 0),
(42, 'Product Management Fundamentals', 'LinkedIn Learning', 'https://www.linkedin.com/learning', NULL, 0),
(43, 'Digital Product Management', 'University of Virginia / Coursera', 'https://www.coursera.org', NULL, 0),
(44, 'Introduction to Product Management', 'ProductSchool', 'https://productschool.com', NULL, 1),
(45, 'Agile Product Owner Role', 'Scrum.org', 'https://www.scrum.org', NULL, 1),
(46, 'Google Digital Marketing Certificate', 'Google Digital Garage', 'https://learndigital.withgoogle.com', 10, 1),
(47, 'Social Media Marketing Course', 'HubSpot Academy', 'https://academy.hubspot.com', 10, 1),
(48, 'SEO Training Course', 'Semrush Academy', 'https://www.semrush.com/academy', 10, 1),
(49, 'Meta Social Media Marketing', 'Meta / Coursera', 'https://www.coursera.org', 10, 0),
(50, 'Email Marketing Certification', 'HubSpot Academy', 'https://academy.hubspot.com', 10, 1),
(51, 'Business Analysis Fundamentals', 'Udemy', 'https://www.udemy.com', 11, 0),
(52, 'SQL for Data Analysis', 'Mode Analytics', 'https://mode.com', 11, 1),
(53, 'Business Analysis Certification CBAP', 'Udemy', 'https://www.udemy.com', 11, 0),
(54, 'Excel for Business', 'Macquarie University / Coursera', 'https://www.coursera.org', 11, 0),
(55, 'Requirements Gathering and Analysis', 'LinkedIn Learning', 'https://www.linkedin.com/learning', 11, 0),
(56, 'Software Testing - Complete Course', 'Udemy', 'https://www.udemy.com', 12, 0),
(57, 'Selenium WebDriver with Java', 'Udemy', 'https://www.udemy.com', 12, 0),
(58, 'Introduction to Software Testing', 'University of Minnesota / Coursera', 'https://www.coursera.org', 12, 0),
(59, 'ISTQB Foundation Level Prep', 'Udemy', 'https://www.udemy.com', 12, 0),
(60, 'Postman API Testing', 'Postman / YouTube', 'https://www.youtube.com', 12, 1),
(61, 'Human Resource Management', 'University of Minnesota / Coursera', 'https://www.coursera.org', 13, 0),
(62, 'People Analytics', 'Wharton / Coursera', 'https://www.coursera.org', 13, 0),
(63, 'Recruiting and Talent Acquisition', 'LinkedIn Learning', 'https://www.linkedin.com/learning', 13, 0),
(64, 'SHRM Essentials of HR', 'SHRM', 'https://www.shrm.org', 13, 0),
(65, 'HR Fundamentals', 'CIPD / FutureLearn', 'https://www.futurelearn.com', 13, 1),
(66, 'MySQL - The Complete Developer Guide', 'Udemy', 'https://www.udemy.com', 14, 0),
(67, 'Oracle DBA - Become a Certified DBA', 'Udemy', 'https://www.udemy.com', 14, 0),
(68, 'Introduction to Databases', 'Stanford / Coursera', 'https://www.coursera.org', 14, 0),
(69, 'SQL and Database Design', 'freeCodeCamp', 'https://www.freecodecamp.org', 14, 1),
(70, 'MongoDB Basics', 'MongoDB University', 'https://university.mongodb.com', 14, 1),
(71, 'Frontend Web Developer Bootcamp', 'Udemy', 'https://www.udemy.com', 15, 0),
(72, 'JavaScript - The Complete Guide', 'Udemy', 'https://www.udemy.com', 15, 0),
(73, 'CSS - Complete Guide', 'Udemy', 'https://www.udemy.com', 15, 0),
(74, 'Frontend Development Libraries', 'freeCodeCamp', 'https://www.freecodecamp.org', 15, 1),
(75, 'TypeScript for Beginners', 'Scrimba', 'https://scrimba.com', 15, 1),
(76, 'AI for Everyone', 'Coursera', 'https://www.coursera.org/learn/ai-for-everyone', 16, 1),
(77, 'AWS Certified Solutions Architect', 'AWS Training', 'https://aws.amazon.com/training/', 17, 0),
(78, 'The Complete Full-Stack Web Developer', 'Udemy', 'https://www.udemy.com/course/the-complete-web-development-bootcamp/', 18, 0),
(79, 'iOS & Android App Development', 'Udacity', 'https://www.udacity.com/course/developer', 19, 0),
(80, 'IT Strategy and Digital Transformation', 'edX', 'https://www.edx.org/learn/it', 20, 1),
(81, 'Technical Writing Certification', 'Google Coursera', 'https://www.coursera.org/learn/technical-writing', 21, 1),
(82, 'Product Management 101', 'Product School', 'https://www.productschool.com/', 22, 0),
(83, 'AI for Everyone', 'Coursera', 'https://www.coursera.org/learn/ai-for-everyone', 16, 1),
(84, 'AWS Certified Solutions Architect', 'AWS Training', 'https://aws.amazon.com/training/', 17, 0),
(85, 'The Complete Full-Stack Web Developer', 'Udemy', 'https://www.udemy.com/course/the-complete-web-development-bootcamp/', 18, 0),
(86, 'iOS & Android App Development', 'Udacity', 'https://www.udacity.com/course/developer', 19, 0),
(87, 'IT Strategy and Digital Transformation', 'edX', 'https://www.edx.org/learn/it', 20, 1),
(88, 'Technical Writing Certification', 'Google Coursera', 'https://www.coursera.org/learn/technical-writing', 21, 1),
(89, 'Product Management 101', 'Product School', 'https://www.productschool.com/', 22, 0);

-- --------------------------------------------------------

--
-- Table structure for table `CV`
--

CREATE TABLE `CV` (
  `CVID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PersonalDetails` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `workExperience` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `generatePath` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE `Questions` (
  `QuestionID` int(11) NOT NULL,
  `Text` text NOT NULL,
  `Category` varchar(50) NOT NULL,
  `Weight` decimal(10,0) NOT NULL,
  `Options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`Options`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Questions`
--

INSERT INTO `Questions` (`QuestionID`, `Text`, `Category`, `Weight`, `Options`) VALUES
(1, 'When you sit down at a computer, which task feels most natural to you?', 'technical', 3, '[{\"label\":\"Writing or reading code and fixing software bugs\",\"scores\":{\"1\":5,\"7\":3,\"12\":3,\"15\":3,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Building or styling web pages and seeing them come to life in a browser\",\"scores\":{\"15\":5,\"1\":3,\"3\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Opening a spreadsheet or data tool and exploring rows of numbers\",\"scores\":{\"2\":5,\"8\":4,\"11\":3,\"14\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Checking emails, planning tasks and coordinating with people\",\"scores\":{\"5\":5,\"9\":3,\"13\":3,\"10\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}}]'),
(2, 'A friend\'s laptop crashes and loses all data. What is your first reaction?', 'technical', 3, '[{\"label\":\"I immediately think about how the system failed and want to find the root cause in the software or hardware\",\"scores\":{\"1\":4,\"4\":5,\"7\":3,\"14\":3,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I search online for recovery tools and try a step-by-step fix\",\"scores\":{\"12\":4,\"6\":3,\"4\":3,\"1\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I feel bad for them and focus on helping them recover emotionally and find an alternative solution\",\"scores\":{\"13\":4,\"9\":2,\"5\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I think about backup systems and how this could have been prevented with a proper plan\",\"scores\":{\"6\":5,\"14\":4,\"7\":3,\"5\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}}]'),
(3, 'You are given access to a company\'s server. What would you most want to do with it?', 'technical', 4, '[{\"label\":\"Test its security \\u2014 find vulnerabilities before attackers do\",\"scores\":{\"6\":5,\"12\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Deploy a web application and make it available to users\",\"scores\":{\"7\":5,\"1\":3,\"15\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Extract and analyze the stored data to find business patterns\",\"scores\":{\"2\":5,\"8\":4,\"11\":3,\"14\":3,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Set up team workflows and access permissions for the organization\",\"scores\":{\"5\":4,\"9\":3,\"13\":2,\"14\":2,\"16\":5.160000000000000142108547152020037174224853515625,\"17\":5.1699999999999999289457264239899814128875732421875,\"18\":5.17999999999999971578290569595992565155029296875,\"19\":5.19000000000000039079850466805510222911834716796875,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}}]'),
(4, 'You are given a spreadsheet with 10,000 rows of student exam results. What do you do first?', 'analytical', 4, '[{\"label\":\"Write a script or formula to clean the data and remove duplicates automatically\",\"scores\":{\"2\":5,\"8\":4,\"7\":2,\"14\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Create charts and visualizations to see patterns and trends at a glance\",\"scores\":{\"2\":4,\"8\":3,\"11\":3,\"10\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Summarize the key findings in a clear written report for decision-makers\",\"scores\":{\"11\":5,\"9\":3,\"5\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I would feel overwhelmed \\u2014 I prefer working with people rather than raw data\",\"scores\":{\"13\":4,\"5\":2,\"10\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}}]'),
(5, 'A new mobile app has low user engagement. Your team asks you to investigate why. What is your approach?', 'analytical', 3, '[{\"label\":\"Review the app\'s code and performance logs to find technical bugs slowing users down\",\"scores\":{\"1\":4,\"12\":4,\"7\":3,\"15\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Analyze the usage data and statistics to find where users drop off in the flow\",\"scores\":{\"2\":5,\"8\":4,\"11\":3,\"9\":3,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Redesign the screens and improve the visual layout to make it more intuitive\",\"scores\":{\"3\":5,\"15\":3,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"Run surveys and user interviews to understand what people actually want\",\"scores\":{\"9\":5,\"3\":3,\"10\":3,\"13\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}}]'),
(6, 'Which of these statements feels most true about how you think?', 'analytical', 3, '[{\"label\":\"I enjoy breaking a complex problem into small logical steps and solving each one\",\"scores\":{\"1\":5,\"12\":3,\"7\":3,\"4\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I enjoy finding patterns in information and turning them into clear insights\",\"scores\":{\"2\":5,\"8\":5,\"11\":3,\"14\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I enjoy imagining how things could look better and sketching new ideas\",\"scores\":{\"3\":5,\"15\":3,\"10\":2,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}},{\"label\":\"I enjoy organizing people, resources and plans to achieve a goal efficiently\",\"scores\":{\"5\":5,\"9\":3,\"13\":3,\"16\":3.160000000000000142108547152020037174224853515625,\"17\":3.1699999999999999289457264239899814128875732421875,\"18\":3.180000000000000159872115546022541821002960205078125,\"19\":3.189999999999999946709294817992486059665679931640625,\"20\":1.1999999999999999555910790149937383830547332763671875,\"21\":1.20999999999999996447286321199499070644378662109375,\"22\":1.2199999999999999733546474089962430298328399658203125}}]'),
(7, 'You are asked to improve a university website. Which part excites you most?', 'creative', 3, '[{\"label\":\"Redesigning the colors, fonts and layout to make it look modern and beautiful\",\"scores\":{\"3\":5,\"15\":3,\"10\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Fixing the backend so pages load faster and the database queries are optimized\",\"scores\":{\"1\":4,\"14\":4,\"7\":3,\"4\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Building new interactive features students would actually find useful\",\"scores\":{\"15\":5,\"1\":3,\"9\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Planning the project timeline, assigning tasks and making sure it is delivered on time\",\"scores\":{\"5\":5,\"9\":3,\"12\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}}]'),
(8, 'Which of these tools would you most enjoy learning deeply over the next year?', 'creative', 4, '[{\"label\":\"Python or Java \\u2014 writing programs, automating tasks and building systems\",\"scores\":{\"1\":5,\"8\":3,\"7\":3,\"6\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Figma or Adobe XD \\u2014 creating wireframes, prototypes and visual designs\",\"scores\":{\"3\":5,\"15\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"SQL and Tableau \\u2014 querying databases and building data dashboards\",\"scores\":{\"2\":5,\"8\":4,\"14\":4,\"11\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Google Analytics or Meta Ads \\u2014 tracking campaigns and growing an online audience\",\"scores\":{\"10\":5,\"9\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}}]'),
(9, 'You open a competitor\'s website for the first time. What do you immediately notice and think about?', 'creative', 2, '[{\"label\":\"How fast it loads and whether the code underneath is well structured\",\"scores\":{\"1\":4,\"15\":3,\"7\":3,\"12\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"The color scheme, typography and whether the layout feels clean and modern\",\"scores\":{\"3\":5,\"15\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Whether their SEO is good, how they attract visitors and what their conversion strategy is\",\"scores\":{\"10\":5,\"9\":2,\"11\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}},{\"label\":\"Whether it is easy to navigate and users would actually understand how to use it\",\"scores\":{\"3\":4,\"9\":3,\"12\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":2.20000000000000017763568394002504646778106689453125,\"21\":5.20999999999999996447286321199499070644378662109375,\"22\":2.220000000000000195399252334027551114559173583984375}}]'),
(10, 'Your group project is one week behind schedule. What role do you naturally take?', 'management', 4, '[{\"label\":\"I take charge \\u2014 redraw the timeline, reassign tasks and push the team to deliver\",\"scores\":{\"5\":5,\"9\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"I focus on fixing the technical bottleneck causing the delay myself\",\"scores\":{\"1\":4,\"7\":3,\"4\":2,\"12\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"I talk to each team member individually to understand their blockers and help them\",\"scores\":{\"13\":5,\"9\":3,\"5\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"I analyze what went wrong and prepare a report with recommendations to prevent it next time\",\"scores\":{\"11\":5,\"2\":3,\"5\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}}]'),
(11, 'A company wants to hire 50 new employees in 3 months. What part of this challenge interests you?', 'management', 3, '[{\"label\":\"Building the recruitment tracking software to manage all applications automatically\",\"scores\":{\"1\":4,\"7\":3,\"15\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"Analyzing applicant data to find patterns in who performs best after hiring\",\"scores\":{\"2\":5,\"8\":3,\"11\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"Designing the interview process, onboarding plan and making candidates feel welcome\",\"scores\":{\"13\":5,\"5\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"Creating a social media campaign to attract the right candidates online\",\"scores\":{\"10\":5,\"13\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}}]'),
(12, 'Five years from now, which achievement would make you proudest?', 'management', 4, '[{\"label\":\"I built a software product or system that thousands of people use every day\",\"scores\":{\"1\":5,\"7\":3,\"15\":3,\"6\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"I discovered a data insight that saved my company millions or changed a key strategy\",\"scores\":{\"8\":5,\"2\":4,\"11\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"I led a team that delivered a major project on time, on budget and beyond expectations\",\"scores\":{\"5\":5,\"9\":3,\"13\":2,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}},{\"label\":\"I designed an experience or campaign that people genuinely loved and remember\",\"scores\":{\"3\":5,\"15\":2,\"10\":3,\"16\":1.1599999999999999200639422269887290894985198974609375,\"17\":1.1699999999999999289457264239899814128875732421875,\"18\":1.1799999999999999378275106209912337362766265869140625,\"19\":1.189999999999999946709294817992486059665679931640625,\"20\":5.20000000000000017763568394002504646778106689453125,\"21\":2.20999999999999996447286321199499070644378662109375,\"22\":5.21999999999999975131004248396493494510650634765625}}]');

-- --------------------------------------------------------

--
-- Table structure for table `Recommendations`
--

CREATE TABLE `Recommendations` (
  `RecommendID` int(11) NOT NULL,
  `AssessmentID` int(11) NOT NULL,
  `CareerID` int(11) NOT NULL,
  `MatchScore` decimal(5,2) DEFAULT NULL,
  `Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Recommendations`
--

INSERT INTO `Recommendations` (`RecommendID`, `AssessmentID`, `CareerID`, `MatchScore`, `Date`) VALUES
(2, 341, 5, 100.00, '2026-09-22'),
(5, 343, 11, 100.00, '2026-09-22'),
(7, 343, 11, 100.00, '2026-09-22'),
(9, 343, 11, 100.00, '2026-09-22'),
(11, 343, 11, 100.00, '2026-09-22'),
(13, 343, 11, 100.00, '2026-09-22'),
(14, 343, 9, 98.36, '2026-09-22'),
(15, 343, 5, 86.89, '2026-09-22');

-- --------------------------------------------------------

--
-- Table structure for table `Roadmap`
--

CREATE TABLE `Roadmap` (
  `RoadmapID` int(11) NOT NULL,
  `CareerID` int(11) NOT NULL,
  `StageNumber` int(11) NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Description` text DEFAULT NULL,
  `EstimatedTime` varchar(50) DEFAULT NULL,
  `Icon` varchar(10) DEFAULT '?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Roadmap`
--

INSERT INTO `Roadmap` (`RoadmapID`, `CareerID`, `StageNumber`, `Title`, `Description`, `EstimatedTime`, `Icon`) VALUES
(1, 1, 1, 'Programming Foundations', 'Learn C/Java basics, variables, loops, functions and data structures', '3 months', '💻'),
(2, 1, 2, 'OOP & Design Patterns', 'Object-oriented programming, SOLID principles and common design patterns', '2 months', '🧩'),
(3, 1, 3, 'Backend Development', 'REST APIs, databases, PHP/Node.js, SQL queries and server-side logic', '3 months', '⚙️'),
(4, 1, 4, 'Version Control & CI/CD', 'Git, GitHub, CI/CD pipelines, Docker basics and deployment workflows', '1 month', '🔧'),
(5, 1, 5, 'Projects & Portfolio', 'Build 3 real-world projects and publish them on GitHub', '2 months', '🚀'),
(6, 1, 6, 'Job Readiness', 'Interview prep, LeetCode problem solving and system design basics', '1 month', '🎯'),
(7, 2, 1, 'HTML & CSS Mastery', 'Semantic HTML, responsive design, Flexbox and CSS Grid', '2 months', '🌐'),
(8, 2, 2, 'JavaScript Fundamentals', 'ES6+, DOM manipulation, async/await and the fetch API', '2 months', '⚡'),
(9, 2, 3, 'React Framework', 'Components, hooks, state management and client-side routing', '3 months', '⚛️'),
(10, 2, 4, 'Backend Integration', 'PHP or Node.js, REST APIs, MySQL database connectivity', '2 months', '🔗'),
(11, 2, 5, 'Deploy & Optimise', 'XAMPP hosting, SEO basics, performance auditing and analytics', '1 month', '📊'),
(12, 3, 1, 'Design Principles', 'Colour theory, typography, visual hierarchy and layout fundamentals', '2 months', '🎨'),
(13, 3, 2, 'Figma Proficiency', 'Wireframing, prototyping, auto-layout and component libraries in Figma', '2 months', '✏️'),
(14, 3, 3, 'User Research', 'User interviews, surveys, personas and usability testing methods', '2 months', '🔍'),
(15, 3, 4, 'Interaction Design', 'Micro-interactions, animation principles and design systems', '2 months', '✨'),
(16, 3, 5, 'Portfolio & Case Studies', 'Design 3 full case studies covering problem, process and outcome', '2 months', '📁'),
(17, 4, 1, 'Networking Basics', 'OSI model, TCP/IP, subnetting, IP addressing and network topologies', '2 months', '🌍'),
(18, 4, 2, 'Cisco & Routing', 'Routers, switches, VLANs, OSPF and BGP routing protocols', '3 months', '📡'),
(19, 4, 3, 'Network Security', 'Firewalls, VPNs, IDS/IPS systems and network hardening techniques', '2 months', '🛡️'),
(20, 4, 4, 'Cloud Networking', 'AWS/Azure networking, virtual private clouds and load balancers', '2 months', '☁️'),
(21, 4, 5, 'CCNA Certification', 'Prepare and sit for the Cisco CCNA certification exam', '3 months', '🏆'),
(22, 5, 1, 'PM Fundamentals', 'Project lifecycle, scope, schedule, budget and stakeholder management', '2 months', '📋'),
(23, 5, 2, 'Agile & Scrum', 'Sprints, backlog grooming, daily standups, retrospectives and velocity', '2 months', '🔄'),
(24, 5, 3, 'Risk & Quality Management', 'Risk identification, mitigation plans, quality assurance and control', '2 months', '⚠️'),
(25, 5, 4, 'PM Tools', 'Jira, MS Project, Trello, Asana and stakeholder reporting dashboards', '1 month', '🛠️'),
(26, 5, 5, 'PMP Certification', 'Prepare for and complete the PMP or PRINCE2 certification exam', '3 months', '🎓'),
(27, 6, 1, 'Security Fundamentals', 'CIA triad, common threats, attack vectors and defence strategies', '2 months', '🔐'),
(28, 6, 2, 'Networking for Security', 'TCP/IP deep dive, packet analysis, Wireshark and network forensics', '2 months', '📡'),
(29, 6, 3, 'Ethical Hacking', 'Penetration testing methodology, Kali Linux, Metasploit and OWASP Top 10', '3 months', '🕵️'),
(30, 6, 4, 'SIEM & Incident Response', 'Log analysis, SIEM tools, incident handling playbooks and forensics', '2 months', '🚨'),
(31, 6, 5, 'Security Certifications', 'CompTIA Security+ or CEH — prepare and sit for the exam', '3 months', '🏅'),
(32, 7, 1, 'Linux & Shell Scripting', 'Linux command line, bash scripting, file systems and permissions', '2 months', '🐧'),
(33, 7, 2, 'Version Control & CI', 'Git workflows, GitHub Actions, Jenkins pipelines and automated testing', '2 months', '🔁'),
(34, 7, 3, 'Containers & Docker', 'Docker images, containers, volumes, networking and Docker Compose', '2 months', '🐳'),
(35, 7, 4, 'Kubernetes & Orchestration', 'K8s deployments, services, ingress, scaling and Helm charts', '3 months', '☸️'),
(36, 7, 5, 'Cloud & Infrastructure', 'AWS/GCP/Azure, Terraform, infrastructure as code and monitoring', '3 months', '☁️'),
(37, 8, 1, 'Statistics & Mathematics', 'Probability, linear algebra, descriptive and inferential statistics', '2 months', '📐'),
(38, 8, 2, 'Python for Data', 'Pandas, NumPy, Matplotlib and Seaborn for data wrangling and visualisation', '2 months', '🐍'),
(39, 8, 3, 'SQL & Databases', 'MySQL queries, joins, aggregation, window functions and data modelling', '1 month', '🗃️'),
(40, 8, 4, 'Machine Learning', 'Scikit-learn, regression, classification, clustering and model evaluation', '3 months', '🤖'),
(41, 8, 5, 'Data Storytelling', 'Tableau, Power BI, presenting insights and stakeholder communication', '1 month', '📈'),
(42, 8, 6, 'Capstone Project', 'End-to-end data project: collect, clean, model, visualise and present', '2 months', '🏆'),
(48, 10, 1, 'Marketing Fundamentals', 'Consumer behaviour, marketing mix, branding and campaign planning', '2 months', '📣'),
(49, 10, 2, 'SEO & Content Marketing', 'Keyword research, on-page SEO, content strategy and blog writing', '2 months', '🔍'),
(50, 10, 3, 'Social Media & Ads', 'Meta Ads, Google Ads, targeting, budgeting and performance tracking', '2 months', '📱'),
(51, 10, 4, 'Analytics & Reporting', 'Google Analytics 4, data interpretation, conversion optimisation and reports', '2 months', '📊'),
(52, 10, 5, 'Email & Automation', 'Email campaigns, lead nurturing, HubSpot and marketing automation tools', '1 month', '📧'),
(53, 11, 1, 'BA Fundamentals', 'Business analysis framework, stakeholder roles and project lifecycle', '2 months', '📋'),
(54, 11, 2, 'Requirements Engineering', 'Elicitation techniques, use cases, user stories and requirement docs', '2 months', '📝'),
(55, 11, 3, 'Process Modelling', 'BPMN, UML diagrams, swimlane charts and as-is vs to-be analysis', '2 months', '🔄'),
(56, 11, 4, 'SQL & Data Analysis', 'SQL queries, Excel pivot tables, data interpretation and reporting', '2 months', '🗃️'),
(57, 11, 5, 'CBAP Certification Prep', 'Business Analysis Body of Knowledge (BABOK) and CBAP exam preparation', '3 months', '🏅'),
(58, 12, 1, 'Testing Fundamentals', 'SDLC, test planning, test cases, defect lifecycle and testing types', '2 months', '🧪'),
(59, 12, 2, 'Manual Testing', 'Functional, regression, integration, exploratory and UAT testing', '2 months', '✅'),
(60, 12, 3, 'Test Automation', 'Selenium WebDriver, Java/Python scripting and page object model', '3 months', '🤖'),
(61, 12, 4, 'API & Performance Testing', 'Postman, REST API testing, JMeter and load performance analysis', '2 months', '⚡'),
(62, 12, 5, 'ISTQB Certification', 'Prepare for and pass the ISTQB Foundation Level certification exam', '2 months', '🎓'),
(63, 13, 1, 'HR Fundamentals', 'HR functions, employment law, organisational behaviour and HR policies', '2 months', '👥'),
(64, 13, 2, 'Talent Acquisition', 'Job profiling, sourcing strategies, LinkedIn recruiting and ATS systems', '2 months', '🎯'),
(65, 13, 3, 'Interviewing & Selection', 'Interview design, competency frameworks, assessment centres and offers', '2 months', '🤝'),
(66, 13, 4, 'People Analytics', 'HR metrics, turnover analysis, workforce planning and HR dashboards', '2 months', '📊'),
(67, 13, 5, 'SHRM / CIPD Certification', 'Prepare for SHRM-CP or CIPD Level 3 HR practice certification', '3 months', '🏅'),
(68, 14, 1, 'Database Fundamentals', 'Relational concepts, ER diagrams, normalisation and SQL basics', '2 months', '🗄️'),
(69, 14, 2, 'MySQL Administration', 'Installation, user management, indexing, stored procedures and backups', '2 months', '🔧'),
(70, 14, 3, 'Performance Tuning', 'Query optimisation, execution plans, indexing strategies and caching', '2 months', '⚡'),
(71, 14, 4, 'High Availability & Security', 'Replication, clustering, encryption, access control and audit logging', '2 months', '🛡️'),
(72, 14, 5, 'Cloud Databases', 'AWS RDS, Azure SQL, managed databases and migration strategies', '2 months', '☁️'),
(73, 15, 1, 'HTML & CSS Deep Dive', 'Semantic HTML5, advanced CSS, animations and responsive design patterns', '2 months', '🌐'),
(74, 15, 2, 'JavaScript Mastery', 'ES6+, closures, promises, async/await, modules and browser APIs', '3 months', '⚡'),
(75, 15, 3, 'React & State Management', 'React hooks, Context API, Redux, routing and performance optimisation', '3 months', '⚛️'),
(76, 15, 4, 'Testing & Tooling', 'Jest, React Testing Library, Webpack, Vite and TypeScript basics', '2 months', '🧪'),
(77, 15, 5, 'Portfolio Projects', 'Build and deploy 3 polished frontend projects with live URLs', '2 months', '🚀'),
(78, 1, 1, 'Programming Foundations', 'Learn C/Java basics, variables, loops, functions and data structures', '3 months', '💻'),
(79, 1, 2, 'OOP & Design Patterns', 'Object-oriented programming, SOLID principles and common design patterns', '2 months', '🧩'),
(80, 1, 3, 'Backend Development', 'REST APIs, databases, PHP/Node.js, SQL queries and server-side logic', '3 months', '⚙️'),
(81, 1, 4, 'Version Control & CI/CD', 'Git, GitHub, CI/CD pipelines, Docker basics and deployment workflows', '1 month', '🔧'),
(82, 1, 5, 'Projects & Portfolio', 'Build 3 real-world projects and publish them on GitHub', '2 months', '🚀'),
(83, 1, 6, 'Job Readiness', 'Interview prep, LeetCode problem solving and system design basics', '1 month', '🎯'),
(84, 2, 1, 'HTML & CSS Mastery', 'Semantic HTML, responsive design, Flexbox and CSS Grid', '2 months', '🌐'),
(85, 2, 2, 'JavaScript Fundamentals', 'ES6+, DOM manipulation, async/await and the fetch API', '2 months', '⚡'),
(86, 2, 3, 'React Framework', 'Components, hooks, state management and client-side routing', '3 months', '⚛️'),
(87, 2, 4, 'Backend Integration', 'PHP or Node.js, REST APIs, MySQL database connectivity', '2 months', '🔗'),
(88, 2, 5, 'Deploy & Optimise', 'XAMPP hosting, SEO basics, performance auditing and analytics', '1 month', '📊'),
(89, 3, 1, 'Design Principles', 'Colour theory, typography, visual hierarchy and layout fundamentals', '2 months', '🎨'),
(90, 3, 2, 'Figma Proficiency', 'Wireframing, prototyping, auto-layout and component libraries in Figma', '2 months', '✏️'),
(91, 3, 3, 'User Research', 'User interviews, surveys, personas and usability testing methods', '2 months', '🔍'),
(92, 3, 4, 'Interaction Design', 'Micro-interactions, animation principles and design systems', '2 months', '✨'),
(93, 3, 5, 'Portfolio & Case Studies', 'Design 3 full case studies covering problem, process and outcome', '2 months', '📁'),
(94, 4, 1, 'Networking Basics', 'OSI model, TCP/IP, subnetting, IP addressing and network topologies', '2 months', '🌍'),
(95, 4, 2, 'Cisco & Routing', 'Routers, switches, VLANs, OSPF and BGP routing protocols', '3 months', '📡'),
(96, 4, 3, 'Network Security', 'Firewalls, VPNs, IDS/IPS systems and network hardening techniques', '2 months', '🛡️'),
(97, 4, 4, 'Cloud Networking', 'AWS/Azure networking, virtual private clouds and load balancers', '2 months', '☁️'),
(98, 4, 5, 'CCNA Certification', 'Prepare and sit for the Cisco CCNA certification exam', '3 months', '🏆'),
(99, 5, 1, 'PM Fundamentals', 'Project lifecycle, scope, schedule, budget and stakeholder management', '2 months', '📋'),
(100, 5, 2, 'Agile & Scrum', 'Sprints, backlog grooming, daily standups, retrospectives and velocity', '2 months', '🔄'),
(101, 5, 3, 'Risk & Quality Management', 'Risk identification, mitigation plans, quality assurance and control', '2 months', '⚠️'),
(102, 5, 4, 'PM Tools', 'Jira, MS Project, Trello, Asana and stakeholder reporting dashboards', '1 month', '🛠️'),
(103, 5, 5, 'PMP Certification', 'Prepare for and complete the PMP or PRINCE2 certification exam', '3 months', '🎓'),
(104, 6, 1, 'Security Fundamentals', 'CIA triad, common threats, attack vectors and defence strategies', '2 months', '🔐'),
(105, 6, 2, 'Networking for Security', 'TCP/IP deep dive, packet analysis, Wireshark and network forensics', '2 months', '📡'),
(106, 6, 3, 'Ethical Hacking', 'Penetration testing methodology, Kali Linux, Metasploit and OWASP Top 10', '3 months', '🕵️'),
(107, 6, 4, 'SIEM & Incident Response', 'Log analysis, SIEM tools, incident handling playbooks and forensics', '2 months', '🚨'),
(108, 6, 5, 'Security Certifications', 'CompTIA Security+ or CEH — prepare and sit for the exam', '3 months', '🏅'),
(109, 7, 1, 'Linux & Shell Scripting', 'Linux command line, bash scripting, file systems and permissions', '2 months', '🐧'),
(110, 7, 2, 'Version Control & CI', 'Git workflows, GitHub Actions, Jenkins pipelines and automated testing', '2 months', '🔁'),
(111, 7, 3, 'Containers & Docker', 'Docker images, containers, volumes, networking and Docker Compose', '2 months', '🐳'),
(112, 7, 4, 'Kubernetes & Orchestration', 'K8s deployments, services, ingress, scaling and Helm charts', '3 months', '☸️'),
(113, 7, 5, 'Cloud & Infrastructure', 'AWS/GCP/Azure, Terraform, infrastructure as code and monitoring', '3 months', '☁️'),
(114, 8, 1, 'Statistics & Mathematics', 'Probability, linear algebra, descriptive and inferential statistics', '2 months', '📐'),
(115, 8, 2, 'Python for Data', 'Pandas, NumPy, Matplotlib and Seaborn for data wrangling and visualisation', '2 months', '🐍'),
(116, 8, 3, 'SQL & Databases', 'MySQL queries, joins, aggregation, window functions and data modelling', '1 month', '🗃️'),
(117, 8, 4, 'Machine Learning', 'Scikit-learn, regression, classification, clustering and model evaluation', '3 months', '🤖'),
(118, 8, 5, 'Data Storytelling', 'Tableau, Power BI, presenting insights and stakeholder communication', '1 month', '📈'),
(119, 8, 6, 'Capstone Project', 'End-to-end data project: collect, clean, model, visualise and present', '2 months', '🏆'),
(125, 10, 1, 'Marketing Fundamentals', 'Consumer behaviour, marketing mix, branding and campaign planning', '2 months', '📣'),
(126, 10, 2, 'SEO & Content Marketing', 'Keyword research, on-page SEO, content strategy and blog writing', '2 months', '🔍'),
(127, 10, 3, 'Social Media & Ads', 'Meta Ads, Google Ads, targeting, budgeting and performance tracking', '2 months', '📱'),
(128, 10, 4, 'Analytics & Reporting', 'Google Analytics 4, data interpretation, conversion optimisation and reports', '2 months', '📊'),
(129, 10, 5, 'Email & Automation', 'Email campaigns, lead nurturing, HubSpot and marketing automation tools', '1 month', '📧'),
(130, 11, 1, 'BA Fundamentals', 'Business analysis framework, stakeholder roles and project lifecycle', '2 months', '📋'),
(131, 11, 2, 'Requirements Engineering', 'Elicitation techniques, use cases, user stories and requirement docs', '2 months', '📝'),
(132, 11, 3, 'Process Modelling', 'BPMN, UML diagrams, swimlane charts and as-is vs to-be analysis', '2 months', '🔄'),
(133, 11, 4, 'SQL & Data Analysis', 'SQL queries, Excel pivot tables, data interpretation and reporting', '2 months', '🗃️'),
(134, 11, 5, 'CBAP Certification Prep', 'Business Analysis Body of Knowledge (BABOK) and CBAP exam preparation', '3 months', '🏅'),
(135, 12, 1, 'Testing Fundamentals', 'SDLC, test planning, test cases, defect lifecycle and testing types', '2 months', '🧪'),
(136, 12, 2, 'Manual Testing', 'Functional, regression, integration, exploratory and UAT testing', '2 months', '✅'),
(137, 12, 3, 'Test Automation', 'Selenium WebDriver, Java/Python scripting and page object model', '3 months', '🤖'),
(138, 12, 4, 'API & Performance Testing', 'Postman, REST API testing, JMeter and load performance analysis', '2 months', '⚡'),
(139, 12, 5, 'ISTQB Certification', 'Prepare for and pass the ISTQB Foundation Level certification exam', '2 months', '🎓'),
(140, 13, 1, 'HR Fundamentals', 'HR functions, employment law, organisational behaviour and HR policies', '2 months', '👥'),
(141, 13, 2, 'Talent Acquisition', 'Job profiling, sourcing strategies, LinkedIn recruiting and ATS systems', '2 months', '🎯'),
(142, 13, 3, 'Interviewing & Selection', 'Interview design, competency frameworks, assessment centres and offers', '2 months', '🤝'),
(143, 13, 4, 'People Analytics', 'HR metrics, turnover analysis, workforce planning and HR dashboards', '2 months', '📊'),
(144, 13, 5, 'SHRM / CIPD Certification', 'Prepare for SHRM-CP or CIPD Level 3 HR practice certification', '3 months', '🏅'),
(145, 14, 1, 'Database Fundamentals', 'Relational concepts, ER diagrams, normalisation and SQL basics', '2 months', '🗄️'),
(146, 14, 2, 'MySQL Administration', 'Installation, user management, indexing, stored procedures and backups', '2 months', '🔧'),
(147, 14, 3, 'Performance Tuning', 'Query optimisation, execution plans, indexing strategies and caching', '2 months', '⚡'),
(148, 14, 4, 'High Availability & Security', 'Replication, clustering, encryption, access control and audit logging', '2 months', '🛡️'),
(149, 14, 5, 'Cloud Databases', 'AWS RDS, Azure SQL, managed databases and migration strategies', '2 months', '☁️'),
(150, 15, 1, 'HTML & CSS Deep Dive', 'Semantic HTML5, advanced CSS, animations and responsive design patterns', '2 months', '🌐'),
(151, 15, 2, 'JavaScript Mastery', 'ES6+, closures, promises, async/await, modules and browser APIs', '3 months', '⚡'),
(152, 15, 3, 'React & State Management', 'React hooks, Context API, Redux, routing and performance optimisation', '3 months', '⚛️'),
(153, 15, 4, 'Testing & Tooling', 'Jest, React Testing Library, Webpack, Vite and TypeScript basics', '2 months', '🧪'),
(154, 15, 5, 'Portfolio Projects', 'Build and deploy 3 polished frontend projects with live URLs', '2 months', '🚀'),
(155, 16, 1, 'Python & Data Structures', 'Master Python and data structures fundamentals.', '3 months', '🐍'),
(156, 16, 2, 'ML Algorithms & Libraries', 'Learn machine learning algorithms and libraries (scikit-learn, TensorFlow).', '4 months', '🤖'),
(157, 16, 3, 'ML Portfolio Projects', 'Build a portfolio of machine learning projects.', '3 months', '📁'),
(158, 16, 4, 'Apply for ML Roles', 'Apply for AI/ML internships or junior positions.', '2 months', '💼'),
(159, 17, 1, 'Cloud Certification', 'Get AWS/Azure fundamentals certification.', '2 months', '☁️'),
(160, 17, 2, 'Cloud Networking & Security', 'Learn cloud networking, security, and cost management.', '3 months', '🔒'),
(161, 17, 3, 'Deploy Cloud Solution', 'Design and deploy a real cloud solution.', '3 months', '🚀'),
(162, 17, 4, 'Professional Certification', 'Achieve professional cloud architect certification.', '2 months', '🎓'),
(163, 18, 1, 'Frontend Fundamentals', 'Learn HTML, CSS, JavaScript and a frontend framework (React).', '3 months', '🎨'),
(164, 18, 2, 'Backend & Database', 'Master backend with Node.js/PHP and MySQL.', '3 months', '🗄️'),
(165, 18, 3, 'Full-Stack Projects', 'Build full-stack projects and deploy on cloud.', '3 months', '🌐'),
(166, 18, 4, 'Apply for Developer Roles', 'Apply for full-stack developer roles.', '2 months', '💻'),
(167, 19, 1, 'Mobile Language Basics', 'Learn Kotlin (Android) or Swift (iOS) basics.', '3 months', '📱'),
(168, 19, 2, 'UI & State Management', 'Build UI components and manage state.', '3 months', '🖌️'),
(169, 19, 3, 'API & Database Integration', 'Integrate APIs and databases into apps.', '3 months', '🔗'),
(170, 19, 4, 'Publish & Apply', 'Publish an app to the store and apply for roles.', '2 months', '📲'),
(171, 20, 1, 'Business & IT Foundations', 'Gain foundational knowledge in business and IT.', '3 months', '📊'),
(172, 20, 2, 'Communication & Analytics', 'Develop communication and analytical skills.', '3 months', '🗣️'),
(173, 20, 3, 'Consulting Case Studies', 'Work on real consulting case studies.', '3 months', '📋'),
(174, 20, 4, 'Apply for Junior Consultant', 'Apply for junior consultant positions.', '2 months', '💼'),
(175, 21, 1, 'Writing & Tools', 'Improve writing skills and learn documentation tools.', '2 months', '✍️'),
(176, 21, 2, 'API Documentation', 'Study API documentation and user guide formats.', '3 months', '📄'),
(177, 21, 3, 'Open-Source Contributions', 'Contribute to open-source documentation projects.', '3 months', '📚'),
(178, 21, 4, 'Apply for Tech Writer Roles', 'Apply for technical writing roles.', '2 months', '📝'),
(179, 22, 1, 'Product Lifecycle', 'Learn product lifecycle, user research, and agile.', '3 months', '🔄'),
(180, 22, 2, 'Prioritisation & Analytics', 'Master prioritisation frameworks and analytics.', '3 months', '📈'),
(181, 22, 3, 'Mock Product Project', 'Work on a mock product or internship project.', '3 months', '💡'),
(182, 22, 4, 'Apply for APM Roles', 'Apply for associate product manager positions.', '2 months', '🚀');

-- --------------------------------------------------------

--
-- Table structure for table `Skill`
--

CREATE TABLE `Skill` (
  `SkillID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Skill`
--

INSERT INTO `Skill` (`SkillID`, `Name`, `Category`) VALUES
(1, 'Programming', 'Technical'),
(2, 'Problem Solving', 'Analytical'),
(3, 'Data Analysis', 'Analytical'),
(4, 'Communication', 'Soft Skill'),
(5, 'Leadership', 'Soft Skill'),
(6, 'Design Thinking', 'Creative'),
(7, 'Mathematics', 'Analytical'),
(8, 'Networking', 'Technical'),
(9, 'Project Management', 'Management'),
(10, 'Research', 'Analytical');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Chamodi', 'Prasadeni', 'chamodiprasadeni@gmail.com', '$2y$10$Gg2CtPNGXUCO6NtKm2WvhOdx1Eja/AmQX2UrVup8mM1sHNoN/UJDe', 'user', '2026-07-09 01:02:58'),
(11, 'Tharusha', 'Bandara', 'tharushabandara@hotmail.com', '$2y$10$S2./cURUih4pntBZqMkWFeb.33elDyJV/JWqFA1X3i6FrA./MlIkS', 'user', '2026-08-30 14:22:43'),
(13, 'Dulmi', 'Subhashwaree', 'dulmi@gmail.com', '$2y$10$ST1/ZTyJy4YPrGzerpVmae1Aw0zcnCmqZRjwsKJqSkw2Rq6rIy.pC', 'user', '2026-08-30 16:50:12'),
(14, 'Prabodha', 'Kumarasinghe', 'prabodhakumarasinghe23@gmail.com', '$2y$10$c1GT/uOIWFvViSxso62LX.rQGtyKaZ.FmsayQEkWD/bmuD3dv93Lm', 'user', '2026-08-30 18:08:42'),
(19, 'Chamodi', 'Prasadeni', 'chamodiprasadenidesilva@gmail.com', '$2y$10$03ZgsS.f7IBo6cQ/V2a7mOKM/.gb9eNGgN49zSvvKN5iP5LCeJA76', 'user', '2026-09-22 02:54:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `field_of_study` varchar(255) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `gpa` varchar(20) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answers`
--
ALTER TABLE `Answers`
  ADD PRIMARY KEY (`AnswerID`),
  ADD KEY `AssessmentID` (`AssessmentID`),
  ADD KEY `QuestionID` (`QuestionID`);

--
-- Indexes for table `Assessments`
--
ALTER TABLE `Assessments`
  ADD PRIMARY KEY (`AssessmentID`),
  ADD KEY `assessments_ibfk_1` (`UserID`);

--
-- Indexes for table `Careers`
--
ALTER TABLE `Careers`
  ADD PRIMARY KEY (`CareerID`);

--
-- Indexes for table `Career_Skills`
--
ALTER TABLE `Career_Skills`
  ADD PRIMARY KEY (`CareerID`,`SkillID`),
  ADD KEY `SkillID` (`SkillID`);

--
-- Indexes for table `Comparisons`
--
ALTER TABLE `Comparisons`
  ADD PRIMARY KEY (`ComparisonID`),
  ADD KEY `Career1ID` (`Career1ID`),
  ADD KEY `Career2ID` (`Career2ID`),
  ADD KEY `comparisons_ibfk_1` (`UserID`);

--
-- Indexes for table `Courses`
--
ALTER TABLE `Courses`
  ADD PRIMARY KEY (`CourseID`),
  ADD KEY `CareerID` (`CareerID`);

--
-- Indexes for table `CV`
--
ALTER TABLE `CV`
  ADD PRIMARY KEY (`CVID`),
  ADD KEY `cv_ibfk_1` (`UserID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `Questions`
--
ALTER TABLE `Questions`
  ADD PRIMARY KEY (`QuestionID`);

--
-- Indexes for table `Recommendations`
--
ALTER TABLE `Recommendations`
  ADD PRIMARY KEY (`RecommendID`),
  ADD KEY `AssessmentID` (`AssessmentID`),
  ADD KEY `CareerID` (`CareerID`);

--
-- Indexes for table `Roadmap`
--
ALTER TABLE `Roadmap`
  ADD PRIMARY KEY (`RoadmapID`),
  ADD KEY `CareerID` (`CareerID`);

--
-- Indexes for table `Skill`
--
ALTER TABLE `Skill`
  ADD PRIMARY KEY (`SkillID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Answers`
--
ALTER TABLE `Answers`
  MODIFY `AnswerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `Assessments`
--
ALTER TABLE `Assessments`
  MODIFY `AssessmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `Comparisons`
--
ALTER TABLE `Comparisons`
  MODIFY `ComparisonID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Courses`
--
ALTER TABLE `Courses`
  MODIFY `CourseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `CV`
--
ALTER TABLE `CV`
  MODIFY `CVID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Recommendations`
--
ALTER TABLE `Recommendations`
  MODIFY `RecommendID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Roadmap`
--
ALTER TABLE `Roadmap`
  MODIFY `RoadmapID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answers`
--
ALTER TABLE `Answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`AssessmentID`) REFERENCES `Assessments` (`AssessmentID`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`QuestionID`) REFERENCES `Questions` (`QuestionID`);

--
-- Constraints for table `Assessments`
--
ALTER TABLE `Assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Career_Skills`
--
ALTER TABLE `Career_Skills`
  ADD CONSTRAINT `career_skills_ibfk_1` FOREIGN KEY (`CareerID`) REFERENCES `Careers` (`CareerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `career_skills_ibfk_2` FOREIGN KEY (`SkillID`) REFERENCES `Skill` (`SkillID`) ON DELETE CASCADE;

--
-- Constraints for table `Comparisons`
--
ALTER TABLE `Comparisons`
  ADD CONSTRAINT `comparisons_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comparisons_ibfk_2` FOREIGN KEY (`Career1ID`) REFERENCES `Careers` (`CareerID`),
  ADD CONSTRAINT `comparisons_ibfk_3` FOREIGN KEY (`Career2ID`) REFERENCES `Careers` (`CareerID`);

--
-- Constraints for table `Courses`
--
ALTER TABLE `Courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`CareerID`) REFERENCES `Careers` (`CareerID`) ON DELETE SET NULL;

--
-- Constraints for table `CV`
--
ALTER TABLE `CV`
  ADD CONSTRAINT `cv_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Recommendations`
--
ALTER TABLE `Recommendations`
  ADD CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`AssessmentID`) REFERENCES `Assessments` (`AssessmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `recommendations_ibfk_2` FOREIGN KEY (`CareerID`) REFERENCES `Careers` (`CareerID`) ON DELETE CASCADE;

--
-- Constraints for table `Roadmap`
--
ALTER TABLE `Roadmap`
  ADD CONSTRAINT `roadmap_ibfk_1` FOREIGN KEY (`CareerID`) REFERENCES `Careers` (`CareerID`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
