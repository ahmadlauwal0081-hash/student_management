<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - FUBK</title>
    <style>
        /* Formal Academic Theme */
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; color: #222222; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        
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

        /* University Navigation Bar (Navy Blue) */
        .navbar { background-color: #003366; display: flex; justify-content: center; border-bottom: 2px solid #002244; }
        .navbar a { color: #ffffff; text-decoration: none; padding: 12px 24px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .navbar a:hover, .navbar a.active { background-color: #002244; border-bottom: 3px solid #ffcc00; }

        /* Clean, Standard Box Layout */
        .main-container { max-width: 850px; margin: 30px auto; background: #ffffff; padding: 30px; border: 1px solid #cccccc; border-top: 4px solid #003366; text-align: center; width: 92%; box-sizing: border-box; }
        h2 { color: #003366; font-family: 'Times New Roman', Times, serif; font-size: 1.6rem; margin-top: 0; }
        p { color: #444444; font-size: 0.95rem; line-height: 1.6; }

        /* Standard Institutional Button */
        .btn-login { display: inline-block; background-color: #003366; color: white; padding: 10px 24px; text-decoration: none; font-weight: bold; font-size: 0.9rem; margin-top: 10px; margin-bottom: 25px; border-radius: 2px; }
        .btn-login:hover { background-color: #002244; }

        /* Responsive 16:9 Video Container */
        .video-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .video-section h3 {
            color: #003366;
            font-family: 'Times New Roman', Times, serif;
            font-size: 1.25rem;
            margin: 0 0 15px 0;
        }
        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border: 2px solid #003366;
            border-radius: 4px;
            background-color: #000000;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
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
        <a href="index.php" class="active">Home</a>
        <a href="about.php">About Us</a>
        <a href="login.php">Login</a>
        <a href="help.php">Help</a>
    </nav>

    <!-- Content Box -->
    <div class="main-container">
        <h2>Department of Computer Science Portal</h2>
        <p>Welcome to the Federal University Birnin Kebbi Student Management System and Credential Verification Portal. Please log in to manage academic records, upload required session files, and review verification statuses.</p>
        
        <a href="login.php" class="btn-login">Proceed to Login</a>

        <!-- University Overview Video -->
        <div class="video-section">
            <h3>University Overview</h3>
            <div class="video-container">
                <iframe
                    src="https://www.youtube.com/embed/NoSyHyDXm9c"
                    title="Federal University Birnin Kebbi Overview"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

    <!-- Official Footer Section -->
    <footer class="footer">
        <p>&copy; 2026–Date Computer Science Department, Federal University Birnin Kebbi. All rights reserved.</p>
        <p>Designed by Lauwal Ahmad.</p>
    </footer>

</body>
</html>