Future Finder – Smart Career Guidance System

A web-based career guidance platform that helps university students discover their ideal career paths through intelligent assessments, personalised roadmaps, and curated learning resources.

Overview

Future Finder is a Smart Career Guidance System developed as a 2nd-year group project for the course IIT271-2 Project I at Uva Wellassa University. The system helps students identify suitable career paths based on their skills, interests, and personality traits through a structured online assessment.

The Problem

Many students, particularly at Advanced Level and Undergraduate stages, lack proper guidance and awareness when making career decisions. They often rely on informal advice or external influences rather than structured and personalised support. Existing systems provide generic information without considering individual differences among students. Furthermore, they lack built-in capabilities like skill analysis, career paths, and CV generation. Consequently, students may choose career paths that are not related to their skills, resulting in decreased motivation, substandard educational outcomes, and career dissatisfaction.

The Solution

Future Finder provides a centralised platform where students can complete skill assessments, receive personalised career recommendations, explore career details with roadmaps, compare different careers, generate professional CVs, and track their career journey through a dashboard. The system addresses the key issues by offering a unified career guidance platform with questionnaire-based assessment, a career recommendation engine, career insights and learning guidance, and CV generation capabilities.

Key Features

User Authentication
The system provides secure registration and login with role-based access for guests, registered users, and administrators. Passwords are hashed using PHP's password_hash function for security.

Skill Assessment
Students can complete a 12-question assessment covering four categories: Technical, Analytical, Creative, and Management. Each question has a weight and the system automatically calculates category scores to determine the user's strengths.

Career Recommendations
Based on the assessment results, the system generates personalised career matches with match scores from 0 to 100 percent. Each recommendation includes a detailed career description, salary range, demand, growth rate, and required education.

Career Roadmap
For each career, the system provides a step-by-step progression roadmap with stages, descriptions, and estimated time for each stage. This helps students visualise their career journey and plan accordingly.

Career Comparison
Users can compare two careers side-by-side based on salary, demand, growth rate, and education requirements. Comparisons can be saved and viewed later, helping students make informed decisions.

CV Generator
Users can create professional CVs using a structured form with sections for personal details, education, work experience, skills, and additional information. The CV can be previewed live, saved to the database, and downloaded as a PDF using the browser's print-to-PDF functionality.

Dashboard
The dashboard provides a visual overview with animated donut charts showing the top three career matches. Users can see their assessment status, top career match, and access all features from the sidebar navigation.

Profile Management
Users can update their personal details including first name, last name, and email, educational information including institution, degree, field of study, graduation year, and GPA, skills, interests, and change their password with current password verification.

Responsive Design
The system is fully responsive and works seamlessly on desktop, tablet, and mobile devices, ensuring accessibility for all users.

Technology Stack

Frontend
The system uses HTML5, CSS3, and vanilla JavaScript for the frontend. Google Fonts using the Poppins font are used for typography, and Font Awesome 6.5.2 provides the icon set. The design follows a dark navy theme with teal accents for a professional and modern appearance.

Backend
PHP 8.2.4 handles all server-side logic including authentication, assessment processing, recommendation generation, and data management. The system uses procedural MySQLi for database operations.

Database
MySQL 10.4.28 MariaDB stores all data including user accounts, assessment questions and answers, career information, skill mappings, course recommendations, and roadmap data.

Server Environment
The system runs on XAMPP with Apache 2.4.56. Development tools include Visual Studio Code for coding and phpMyAdmin for database management.

Version Control
Git is used for version control with the code hosted on GitHub for collaboration and sharing.

Project Team

The project was developed by four students from the Department of Computer Science and Informatics at Uva Wellassa University.
I.C.P.P. De Silva, Team Leader, Index Number UWU/IIT/23/023. Contributed to database design, skill assessment, career recommendation and results modules. Worked as a Full Stack Developer handling both frontend and backend development.
S.A.T.S. Samaraweeraarachchi, Index Number UWU/IIT/23/046. Contributed to foundation setup, login and registration, and admin panel development. Worked as a Backend Developer.
R. Venujan, Index Number UWU/IIT/23/092. Contributed to career explorer, comparison and roadmap modules. Worked as a Full Stack Developer.
S. Thivishan, Index Number UWU/IIT/23/100. Contributed to CV generator and reports modules. Worked as a Full Stack Developer.

The project was supervised by Ms. J. Jenusiya as the Project Supervisor and Ms. R.W.V.N.D. Rajapaksha as the Co-Supervisor from the Department of Computer Science and Informatics.

Installation Guide

Before installing the system, ensure you have XAMPP installed with PHP 7.4 or higher and MySQL. You will also need a modern web browser such as Google Chrome, Microsoft Edge, or Mozilla Firefox.

Step 1: Clone or Download the Repository
  Clone the repository using Git or download the ZIP file from the repository and extract it to your XAMPP htdocs directory. For Windows, this is typically C:/xampp/htdocs/future-finder. For Linux, it is /opt/lampp/htdocs/future-finder. For macOS, it is /Applications/XAMPP/htdocs/future-finder.

Step 2: Start XAMPP Services
  Open the XAMPP Control Panel and start both Apache and MySQL services. Ensure they are running without errors.

Step 3: Import the Database
  Open phpMyAdmin by navigating to http://localhost/phpmyadmin in your browser. Create a new database named futurefinder. Go to the Import tab, select the futurefinder.sql file from the project folder, and click Go to import the database structure and sample data.

Step 4: Configure Database Connection
  Open the Includes/db_connection.php file and verify the database credentials. The default configuration uses localhost as the host, root as the username, an empty password, and futurefinder as the database name. Update these if your setup differs.

Step 5: Access the Application
  Open your browser and navigate to http://localhost/future_finder/ to access the home page. You can register a new account or use the sample account with email test@test.com and password 1234.

Database Schema
  The database contains several tables that work together to power the system.
  The Users table stores account information including first name, last name, email, hashed password, and role.
  The Assessments table tracks each user's assessment attempts with status and total score.
  The Answers table stores individual question responses linked to assessments.
  The Questions table contains the assessment questions with their categories, weights, and JSON-formatted options.
  The Careers table holds detailed career information including title, description, salary range, demand, growth rate, required education, and industry.
  The Skill table lists skills with their categories, and the Career_Skills table maps careers to their required skills.
  The Courses table contains recommended courses for each career with title, provider, URL, and a boolean IsFree flag.
  The Roadmaps table defines the stages for each career with descriptions, estimated time, and stage numbers.
  The Recommendations table stores the match scores generated for each assessment.
  The Comparisons table allows users to save career comparisons.
  The CV table stores generated CV data as JSON in the PersonalDetails column.
  The user_profiles table extends user data with educational details, skills, and interests.

Project Structure

The project follows a clean and organised structure. The root directory contains the main pages including index.php for the home page, login.php for login and registration, about.php for the about us page, careers.php for the career explorer, career-details.php for individual career details, restricted.php for access control, and logout.php for session destruction.
The API directory contains backend endpoints for data operations including get_career_details.php, get_questions.php, start_assessment.php, and submit_assessment.php.
The CSS directory holds all stylesheets including home-page.css, home-responsive.css, and login.css.
The Images directory stores the logo and other visual assets.
The Includes directory contains db_connection.php for database connectivity.
The JS directory contains JavaScript files for frontend interactions including home-counter.js, home-script.js, and login.js.
The shared directory holds reusable components including footer.php, navbar.php, and navbaroptional.php for different user states.
The User directory contains all user-specific pages including dashboard.php, assessment.php, before_assessment.php, results.php, compare.php, cv.php, roadmap.php, and profile.php. This separation keeps the code organised and maintainable.

Testing and Evaluation

The system was tested using a comprehensive approach that included unit testing, integration testing, system testing, and user acceptance testing. The testing environment consisted of XAMPP v3.3.0 with Apache 2.4.56, PHP 8.2.4, and MySQL 10.4.28. Testing was performed on Google Chrome, Microsoft Edge, and Mozilla Firefox browsers.

Functional Testing
A total of 45 test cases were designed and executed across all modules. The User Authentication module had 5 test cases covering registration, login, and access control. The Career Assessment module had 6 test cases covering question loading, progress tracking, and submission. The Career Recommendations module had 4 test cases for generating and displaying matches. The Career Comparison module had 6 test cases for selecting, comparing, and saving careers. The CV Generator module had 7 test cases for form input, preview, saving, and PDF download. The Profile Management module had 7 test cases for updating personal details, educational information, and password changes. The Dashboard module had 5 test cases for displaying statistics and charts. The Security module had 5 test cases for preventing unauthorised access, SQL injection, and XSS attacks. All 45 test cases passed, achieving a 100 percent pass rate.

User Acceptance Testing
Ten participants from the target user group, which were undergraduate students, tested the system and provided feedback through a structured questionnaire. The questionnaire used a 5-point Likert scale where 1 represented Strongly Disagree or Very Difficult and 5 represented Strongly Agree or Very Easy. Participants rated system navigation at 4.6 out of 5, assessment clarity at 4.4, career recommendations at 4.2, career comparison at 4.5, CV generator at 4.7, and profile management at 4.6. The overall satisfaction rating was 4.5 out of 5.

Interview Feedback
Selected participants were interviewed for qualitative feedback. They appreciated the ease of use, the relevance of career recommendations, and the usefulness of the CV generator. Suggestions for improvement included adding AI-powered recommendations, mobile application development, LinkedIn integration, job market integration, video content, and completing the admin panel. The positive feedback confirms that Future Finder successfully meets its objectives.

Testing Results

The following table summarises the test results by module:

User Authentication had 5 test cases, all 5 passed, with a 100 percent pass rate.
Career Assessment had 6 test cases, all 6 passed, with a 100 percent pass rate.
Career Recommendations had 4 test cases, all 4 passed, with a 100 percent pass rate.
Career Comparison had 6 test cases, all 6 passed, with a 100 percent pass rate.
CV Generator had 7 test cases, all 7 passed, with a 100 percent pass rate.
Profile Management had 7 test cases, all 7 passed, with a 100 percent pass rate.
Dashboard had 5 test cases, all 5 passed, with a 100 percent pass rate.
Security had 5 test cases, all 5 passed, with a 100 percent pass rate.
The total was 45 test cases, all 45 passed, with a 100 percent pass rate.
User Acceptance Test results showed average scores above 4.0 for all criteria, with the CV Generator receiving the highest rating at 4.7 out of 5. The overall satisfaction score of 4.5 out of 5 indicates that users found the system effective and user-friendly.
The task completion times were also measured. Users completed registration in an average of 1.2 minutes, the assessment in 3.5 minutes, viewed recommendations in 0.8 minutes, compared careers in 1.5 minutes, generated a CV in 4.0 minutes, and updated their profile in 2.0 minutes. All tasks were completed within the expected time limits.

References

The project was informed by several key references. The Organisation for Economic Co-operation and Development in 2004 provided insights on Career Guidance and Public Policy: Bridging the Gap. UNESCO in 2020 contributed research on ICT in Career Guidance. Holland's Theory of Vocational Personalities and Work Environments in 1997 provided the theoretical foundation for career matching. Sommerville's Software Engineering 9th edition in 2011 guided the software development methodology. The Full Stack Developer Roadmap from GeeksforGeeks in 2023 and W3Schools Online Web Tutorials in 2024 provided technical references for implementation.

Contributors

The project was developed by Team IIT 10 under the supervision of the Department of Computer Science and Informatics at Uva Wellassa University. The contributors are:

I.C.P.P. De Silva, Team Leader, Index Number UWU/IIT/23/023, Full Stack Developer.
S.A.T.S. Samaraweeraarachchi, Index Number UWU/IIT/23/046, Backend Developer.
R. Venujan, Index Number UWU/IIT/23/092, Full Stack Developer.
S. Thivishan, Index Number UWU/IIT/23/100, Full Stack Developer.

The project was supervised by Ms. J. Jenusiya as Project Supervisor and Ms. R.W.V.N.D. Rajapaksha as Co-Supervisor.

Institution

This project was developed at the Department of Computer Science and Informatics, Faculty of Applied Sciences, Uva Wellassa University of Sri Lanka, as part of the course IIT271-2 Project I.

License

This project is developed for academic purposes as part of the IIT271-2 Project I course at Uva Wellassa University. All rights reserved. The code is provided for reference and educational purposes only.

Acknowledgements

The team would like to express sincere gratitude to Ms. J. Jenusiya for her valuable guidance and supervision throughout the project. We also thank Ms. R.W.V.N.D. Rajapaksha for her support and feedback as the co-supervisor. Our appreciation extends to the Department of Computer Science and Informatics for providing the resources and environment for this project. Finally, we thank all the participants who tested the system and provided constructive feedback.

Contact

For any inquiries regarding this project, please contact the team leader I.C.P.P. De Silva at iit23023@std.uwu.ac.lk. 
Alternatively, you can reach the project supervisor Ms. J. Jenusiya at jenusiya.je@uwu.ac.lk.

Made with love by IIT Group 10 (2nd Year)
Uva Wellassa University of Sri Lanka
2026


