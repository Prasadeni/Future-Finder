-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 08, 2026 at 03:56 PM
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
(73, 34, 1, 'Writing or reading code and fixing software bugs', 0),
(74, 34, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(75, 34, 3, 'Deploy a web application and make it available to users', 0),
(76, 34, 4, 'Create charts and visualizations to see patterns and trends at a glance', 0),
(77, 34, 5, 'Analyze the usage data and statistics to find where users drop off in the flow', 0),
(78, 34, 6, 'I enjoy organizing people, resources and plans to achieve a goal efficiently', 0),
(79, 34, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(80, 34, 8, 'SQL and Tableau — querying databases and building data dashboards', 0),
(81, 34, 9, 'Whether their SEO is good, how they attract visitors and what their conversion strategy is', 0),
(82, 34, 10, 'I focus on fixing the technical bottleneck causing the delay myself', 0),
(83, 34, 11, 'Analyzing applicant data to find patterns in who performs best after hiring', 0),
(84, 34, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0),
(85, 35, 1, 'Writing or reading code and fixing software bugs', 0),
(86, 35, 2, 'I feel bad for them and focus on helping them recover emotionally and find an alternative solution', 0),
(87, 35, 3, 'Extract and analyze the stored data to find business patterns', 0),
(88, 35, 4, 'Summarize the key findings in a clear written report for decision-makers', 0),
(89, 35, 5, 'Redesign the screens and improve the visual layout to make it more intuitive', 0),
(90, 35, 6, 'I enjoy organizing people, resources and plans to achieve a goal efficiently', 0),
(91, 35, 7, 'Planning the project timeline, assigning tasks and making sure it is delivered on time', 0),
(92, 35, 8, 'Google Analytics or Meta Ads — tracking campaigns and growing an online audience', 0),
(93, 35, 9, 'Whether it is easy to navigate and users would actually understand how to use it', 0),
(94, 35, 10, 'I analyze what went wrong and prepare a report with recommendations to prevent it next time', 0),
(95, 35, 11, 'Creating a social media campaign to attract the right candidates online', 0),
(96, 35, 12, 'I designed an experience or campaign that people genuinely loved and remember', 0);

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
(34, 1, 'Career Assessment', '2026-07-08', 0.00, '2026-07-08 14:57:15', 'completed'),
(35, 1, 'Career Assessment', '2026-07-08', 0.00, '2026-07-08 14:59:32', 'completed');

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
(15, 'Frontend Developer', 'Builds the visual, interactive parts of websites and apps that users see and interact with directly.', 'LKR 70,000 - 200,000/month', 'Very High', 'High', 'Bachelor\'s in Computer Science or IT', 'Technology');

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
(5, 9);

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
  `CareerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`CourseID`, `Title`, `Provider`, `URL`, `CareerID`) VALUES
(1, 'The Complete Web Developer Bootcamp', 'Udemy', 'https://www.udemy.com/course/the-complete-web-development-bootcamp/', 1),
(2, 'CS50 Introduction to Computer Science', 'Harvard / edX', 'https://cs50.harvard.edu/x/', 1),
(3, 'Google Data Analytics Certificate', 'Google / Coursera', 'https://www.coursera.org/professional-certificates/google-data-analytics', 2),
(4, 'Python for Data Science', 'IBM / Coursera', 'https://www.coursera.org/learn/python-for-applied-data-science-ai', 2),
(5, 'Google UX Design Certificate', 'Google / Coursera', 'https://www.coursera.org/professional-certificates/google-ux-design', 3),
(6, 'UI/UX Design Fundamentals', 'Udemy', 'https://www.udemy.com/course/ui-ux-web-design-using-adobe-xd/', 3),
(7, 'CompTIA Network+ Certification', 'CompTIA', 'https://www.comptia.org/certifications/network', 4),
(8, 'Google Project Management Certificate', 'Google / Coursera', 'https://www.coursera.org/professional-certificates/google-project-management', 5);

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
(1, 'When you sit down at a computer, which task feels most natural to you?', 'technical', 3, '[{\"label\": \"Writing or reading code and fixing software bugs\", \"scores\": {\"1\": 5, \"7\": 3, \"12\": 3, \"15\": 3}}, {\"label\": \"Building or styling web pages and seeing them come to life in a browser\", \"scores\": {\"15\": 5, \"1\": 3, \"3\": 2}}, {\"label\": \"Opening a spreadsheet or data tool and exploring rows of numbers\", \"scores\": {\"2\": 5, \"8\": 4, \"11\": 3, \"14\": 2}}, {\"label\": \"Checking emails, planning tasks and coordinating with people\", \"scores\": {\"5\": 5, \"9\": 3, \"13\": 3, \"10\": 2}}]'),
(2, 'A friend\'s laptop crashes and loses all data. What is your first reaction?', 'technical', 3, '[{\"label\": \"I immediately think about how the system failed and want to find the root cause in the software or hardware\", \"scores\": {\"1\": 4, \"4\": 5, \"7\": 3, \"14\": 3}}, {\"label\": \"I search online for recovery tools and try a step-by-step fix\", \"scores\": {\"12\": 4, \"6\": 3, \"4\": 3, \"1\": 2}}, {\"label\": \"I feel bad for them and focus on helping them recover emotionally and find an alternative solution\", \"scores\": {\"13\": 4, \"9\": 2, \"5\": 2}}, {\"label\": \"I think about backup systems and how this could have been prevented with a proper plan\", \"scores\": {\"6\": 5, \"14\": 4, \"7\": 3, \"5\": 2}}]'),
(3, 'You are given access to a company\'s server. What would you most want to do with it?', 'technical', 4, '[{\"label\": \"Test its security — find vulnerabilities before attackers do\", \"scores\": {\"6\": 5, \"12\": 2}}, {\"label\": \"Deploy a web application and make it available to users\", \"scores\": {\"7\": 5, \"1\": 3, \"15\": 2}}, {\"label\": \"Extract and analyze the stored data to find business patterns\", \"scores\": {\"2\": 5, \"8\": 4, \"11\": 3, \"14\": 3}}, {\"label\": \"Set up team workflows and access permissions for the organization\", \"scores\": {\"5\": 4, \"9\": 3, \"13\": 2, \"14\": 2}}]'),
(4, 'You are given a spreadsheet with 10,000 rows of student exam results. What do you do first?', 'analytical', 4, '[{\"label\": \"Write a script or formula to clean the data and remove duplicates automatically\", \"scores\": {\"2\": 5, \"8\": 4, \"7\": 2, \"14\": 2}}, {\"label\": \"Create charts and visualizations to see patterns and trends at a glance\", \"scores\": {\"2\": 4, \"8\": 3, \"11\": 3, \"10\": 2}}, {\"label\": \"Summarize the key findings in a clear written report for decision-makers\", \"scores\": {\"11\": 5, \"9\": 3, \"5\": 2}}, {\"label\": \"I would feel overwhelmed — I prefer working with people rather than raw data\", \"scores\": {\"13\": 4, \"5\": 2, \"10\": 2}}]'),
(5, 'A new mobile app has low user engagement. Your team asks you to investigate why. What is your approach?', 'analytical', 3, '[{\"label\": \"Review the app\'s code and performance logs to find technical bugs slowing users down\", \"scores\": {\"1\": 4, \"12\": 4, \"7\": 3, \"15\": 2}}, {\"label\": \"Analyze the usage data and statistics to find where users drop off in the flow\", \"scores\": {\"2\": 5, \"8\": 4, \"11\": 3, \"9\": 3}}, {\"label\": \"Redesign the screens and improve the visual layout to make it more intuitive\", \"scores\": {\"3\": 5, \"15\": 3}}, {\"label\": \"Run surveys and user interviews to understand what people actually want\", \"scores\": {\"9\": 5, \"3\": 3, \"10\": 3, \"13\": 2}}]'),
(6, 'Which of these statements feels most true about how you think?', 'analytical', 3, '[{\"label\": \"I enjoy breaking a complex problem into small logical steps and solving each one\", \"scores\": {\"1\": 5, \"12\": 3, \"7\": 3, \"4\": 2}}, {\"label\": \"I enjoy finding patterns in information and turning them into clear insights\", \"scores\": {\"2\": 5, \"8\": 5, \"11\": 3, \"14\": 2}}, {\"label\": \"I enjoy imagining how things could look better and sketching new ideas\", \"scores\": {\"3\": 5, \"15\": 3, \"10\": 2}}, {\"label\": \"I enjoy organizing people, resources and plans to achieve a goal efficiently\", \"scores\": {\"5\": 5, \"9\": 3, \"13\": 3}}]'),
(7, 'You are asked to improve a university website. Which part excites you most?', 'creative', 3, '[{\"label\": \"Redesigning the colors, fonts and layout to make it look modern and beautiful\", \"scores\": {\"3\": 5, \"15\": 3, \"10\": 2}}, {\"label\": \"Fixing the backend so pages load faster and the database queries are optimized\", \"scores\": {\"1\": 4, \"14\": 4, \"7\": 3, \"4\": 2}}, {\"label\": \"Building new interactive features students would actually find useful\", \"scores\": {\"15\": 5, \"1\": 3, \"9\": 3}}, {\"label\": \"Planning the project timeline, assigning tasks and making sure it is delivered on time\", \"scores\": {\"5\": 5, \"9\": 3, \"12\": 2}}]'),
(8, 'Which of these tools would you most enjoy learning deeply over the next year?', 'creative', 4, '[{\"label\": \"Python or Java — writing programs, automating tasks and building systems\", \"scores\": {\"1\": 5, \"8\": 3, \"7\": 3, \"6\": 2}}, {\"label\": \"Figma or Adobe XD — creating wireframes, prototypes and visual designs\", \"scores\": {\"3\": 5, \"15\": 3}}, {\"label\": \"SQL and Tableau — querying databases and building data dashboards\", \"scores\": {\"2\": 5, \"8\": 4, \"14\": 4, \"11\": 3}}, {\"label\": \"Google Analytics or Meta Ads — tracking campaigns and growing an online audience\", \"scores\": {\"10\": 5, \"9\": 2}}]'),
(9, 'You open a competitor\'s website for the first time. What do you immediately notice and think about?', 'creative', 2, '[{\"label\": \"How fast it loads and whether the code underneath is well structured\", \"scores\": {\"1\": 4, \"15\": 3, \"7\": 3, \"12\": 2}}, {\"label\": \"The color scheme, typography and whether the layout feels clean and modern\", \"scores\": {\"3\": 5, \"15\": 2}}, {\"label\": \"Whether their SEO is good, how they attract visitors and what their conversion strategy is\", \"scores\": {\"10\": 5, \"9\": 2, \"11\": 2}}, {\"label\": \"Whether it is easy to navigate and users would actually understand how to use it\", \"scores\": {\"3\": 4, \"9\": 3, \"12\": 2}}]'),
(10, 'Your group project is one week behind schedule. What role do you naturally take?', 'management', 4, '[{\"label\": \"I take charge — redraw the timeline, reassign tasks and push the team to deliver\", \"scores\": {\"5\": 5, \"9\": 3}}, {\"label\": \"I focus on fixing the technical bottleneck causing the delay myself\", \"scores\": {\"1\": 4, \"7\": 3, \"4\": 2, \"12\": 2}}, {\"label\": \"I talk to each team member individually to understand their blockers and help them\", \"scores\": {\"13\": 5, \"9\": 3, \"5\": 2}}, {\"label\": \"I analyze what went wrong and prepare a report with recommendations to prevent it next time\", \"scores\": {\"11\": 5, \"2\": 3, \"5\": 2}}]'),
(11, 'A company wants to hire 50 new employees in 3 months. What part of this challenge interests you?', 'management', 3, '[{\"label\": \"Building the recruitment tracking software to manage all applications automatically\", \"scores\": {\"1\": 4, \"7\": 3, \"15\": 2}}, {\"label\": \"Analyzing applicant data to find patterns in who performs best after hiring\", \"scores\": {\"2\": 5, \"8\": 3, \"11\": 3}}, {\"label\": \"Designing the interview process, onboarding plan and making candidates feel welcome\", \"scores\": {\"13\": 5, \"5\": 2}}, {\"label\": \"Creating a social media campaign to attract the right candidates online\", \"scores\": {\"10\": 5, \"13\": 2}}]'),
(12, 'Five years from now, which achievement would make you proudest?', 'management', 4, '[{\"label\": \"I built a software product or system that thousands of people use every day\", \"scores\": {\"1\": 5, \"7\": 3, \"15\": 3, \"6\": 2}}, {\"label\": \"I discovered a data insight that saved my company millions or changed a key strategy\", \"scores\": {\"8\": 5, \"2\": 4, \"11\": 3}}, {\"label\": \"I led a team that delivered a major project on time, on budget and beyond expectations\", \"scores\": {\"5\": 5, \"9\": 3, \"13\": 2}}, {\"label\": \"I designed an experience or campaign that people genuinely loved and remember\", \"scores\": {\"3\": 5, \"15\": 2, \"10\": 3}}]');

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

-- --------------------------------------------------------

--
-- Table structure for table `Roadmaps`
--

CREATE TABLE `Roadmaps` (
  `RoadmapID` int(11) NOT NULL,
  `CareerID` int(11) NOT NULL,
  `Description` text DEFAULT NULL,
  `EstimatedTime` varchar(100) DEFAULT NULL,
  `StageNumber` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Roadmaps`
--

INSERT INTO `Roadmaps` (`RoadmapID`, `CareerID`, `Description`, `EstimatedTime`, `StageNumber`) VALUES
(1, 1, 'Learn programming basics (HTML, CSS, JavaScript, Python)', '3 months', 1),
(2, 1, 'Learn a framework (React, Node.js) and databases (MySQL)', '3 months', 2),
(3, 1, 'Build portfolio projects and contribute to open source', '3 months', 3),
(4, 1, 'Apply for internships or junior developer positions', '3 months', 4),
(5, 2, 'Learn statistics, Excel and basic SQL', '2 months', 1),
(6, 2, 'Learn Python or R for data analysis and visualization', '3 months', 2),
(7, 2, 'Complete a data analysis project with real datasets', '2 months', 3),
(8, 2, 'Learn machine learning basics and get certified', '3 months', 4),
(9, 3, 'Learn design principles, typography and colour theory', '2 months', 1),
(10, 3, 'Master Figma or Adobe XD for UI design', '2 months', 2),
(11, 3, 'Build a design portfolio with 3-5 case studies', '3 months', 3),
(12, 3, 'Learn user research and usability testing methods', '2 months', 4);

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
  `role` varchar(10) NOT NULL DEFAULT 'registered',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Test', 'Student', 'test@test.com', '1234', 'registered', '2026-06-18 05:08:21');

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
-- Indexes for table `Roadmaps`
--
ALTER TABLE `Roadmaps`
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
  MODIFY `AssessmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `Comparisons`
--
ALTER TABLE `Comparisons`
  MODIFY `ComparisonID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Courses`
--
ALTER TABLE `Courses`
  MODIFY `CourseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `CV`
--
ALTER TABLE `CV`
  MODIFY `CVID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Recommendations`
--
ALTER TABLE `Recommendations`
  MODIFY `RecommendID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `Roadmaps`
--
ALTER TABLE `Roadmaps`
  MODIFY `RoadmapID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `Roadmaps`
--
ALTER TABLE `Roadmaps`
  ADD CONSTRAINT `roadmaps_ibfk_1` FOREIGN KEY (`CareerID`) REFERENCES `Careers` (`CareerID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
