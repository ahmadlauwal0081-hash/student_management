<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Department of Computer Science - FUBK</title>
    <style>
        /* Formal Academic Theme */
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            background-color: #f4f4f4; 
            color: #222222; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            width: 100%;
        }
        
        /* Banner Container (Logos + Text, No Background Image) */
        .banner-container { 
            background: transparent; 
            border-bottom: 3px solid #003366; 
            width: 100%; 
            padding: 20px 30px; 
            margin: 0; 
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .banner-inner {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .banner-logo {
            width: 105px;
            height: 105px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .banner-text {
            text-align: center;
            flex: 1;
        }
        .banner-text h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 2rem;
            color: #111111;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }
        .banner-text .dept-title {
            font-size: 1.35rem;
            color: #003366;
            font-weight: bold;
            margin: 0 0 6px 0;
            letter-spacing: 0.5px;
        }
        .banner-text .sys-title {
            font-size: 1.1rem;
            color: #2e7d32;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
        }

        /* Responsive adjustment for smaller screens */
        @media (max-width: 768px) {
            .banner-inner { flex-direction: column; text-align: center; }
            .banner-logo { width: 85px; height: 85px; }
            .banner-text h1 { font-size: 1.45rem; }
            .banner-text .dept-title { font-size: 1.1rem; }
        }

        /* Full-Width University Navigation Bar (Navy Blue) */
        .navbar { 
            background-color: #003366; 
            display: flex; 
            justify-content: center; 
            border-bottom: 2px solid #002244; 
            width: 100%;
            box-sizing: border-box;
        }
        .navbar a { 
            color: #ffffff; 
            text-decoration: none; 
            padding: 12px 24px; 
            font-weight: bold; 
            font-size: 0.9rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .navbar a:hover, .navbar a.active { 
            background-color: #002244; 
            border-bottom: 3px solid #ffcc00; 
        }

        /* Content Box layout matching theme */
        .main-container { 
            max-width: 900px; 
            margin: 30px auto; 
            background: #ffffff; 
            padding: 40px; 
            border: 1px solid #cccccc; 
            border-top: 4px solid #003366; 
            text-align: left; 
            box-sizing: border-box;
        }
        h2 { 
            color: #003366; 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 1.8rem; 
            margin-top: 0; 
            text-align: center;
            margin-bottom: 20px;
        }
        h3 {
            color: #003366;
            font-size: 1.2rem;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 2px solid #f4f4f4;
            padding-bottom: 5px;
        }
        p { 
            color: #444444; 
            font-size: 0.95rem; 
            line-height: 1.7; 
            margin-bottom: 15px;
        }
        ul {
            color: #444444;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
            padding-left: 20px;
        }
        li {
            margin-bottom: 8px;
        }

        /* Official Institutional Footer */
        .footer {
            background-color: #003366;
            color: #ffffff;
            text-align: center;
            padding: 16px 20px;
            font-size: 0.85rem;
            border-top: 3px solid #ffcc00;
            margin-top: auto;
            width: 100%;
            box-sizing: border-box;
            line-height: 1.6;
        }
        .footer p {
            color: #ffffff;
            margin: 0;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Official Banner Section -->
    <div class="banner-container">
        <div class="banner-inner">
            <img src="image/logo.png" alt="Federal University Birnin Kebbi Logo" class="banner-logo">
            
            <div class="banner-text">
                <h1>FEDERAL UNIVERSITY BIRNIN KEBBI STATE</h1>
                <div class="dept-title">DEPARTMENT OF COMPUTER SCIENCE</div>
                <div class="sys-title">STUDENT MANAGEMENT SYSTEM</div>
            </div>

            <img src="image/nacoss.png" alt="NACOS Logo" class="banner-logo">
        </div>
    </div>

    <!-- Official Navigation Menu -->
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="about.php" class="active">About Us</a>
        <a href="login.php">Login</a>
        <a href="help.php">Help</a>
    </nav>

    <!-- Content Box -->
    <div class="main-container">
        <h2>About the Department</h2>
        
        <p>
            Welcome to the Department of Computer Science at Federal University Birnin Kebbi (FUBK). Established with a vision to be a center of academic excellence in computing education, research, and innovation, the department is committed to producing highly skilled, competent, and ethically sound professionals ready to meet global technological challenges.
        </p>

        <h3>Our Mission</h3>
        <p>
            To provide robust theoretical and practical training in computer science, foster cutting-edge research, and equip students with problem-solving skills necessary to drive digital transformation and technological advancement in Nigeria and beyond.
        </p>

        <h3>Our Vision</h3>
        <p>
            To be a leading department recognized globally for excellence in computer science education, innovative research development, and impactful community service.
        </p>

        <h3>Academic Programs</h3>
        <p>
            The department offers comprehensive undergraduate degree programs designed in alignment with national educational standards and international curriculum benchmarks. Our courses cover critical areas of technology, including:
        </p>
        <ul>
            <li>Software Engineering and Development</li>
            <li>Database Management Systems</li>
            <li>Computer Networks and Cybersecurity</li>
            <li>Artificial Intelligence and Data Science</li>
            <li>Web and Mobile Application Development</li>
        </ul>

        <h3>About the Portal</h3>
        <p>
            This Student Management System and Credential Verification Portal was developed to streamline administrative workflows, secure student records, simplify session file uploads, and ensure transparent verification processes for both students and university administrators.
        </p>
    </div>

    <!-- Official Footer Section -->
    <footer class="footer">
        <p>&copy; 2026–Date Computer Science Department, Federal University Birnin Kebbi. All rights reserved.</p>
        <p>Designed by Lauwal Ahmad.</p>
    </footer>

</body>
</html>