<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - Department of Computer Science - FUBK</title>
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
            margin-bottom: 12px;
            border-bottom: 2px solid #f4f4f4;
            padding-bottom: 5px;
        }
        p { 
            color: #444444; 
            font-size: 0.95rem; 
            line-height: 1.7; 
            margin-bottom: 15px;
        }
        ol, ul {
            color: #444444;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
            padding-left: 22px;
        }
        li {
            margin-bottom: 10px;
        }
        .faq-item {
            background-color: #f9fafb;
            border-left: 4px solid #003366;
            padding: 12px 16px;
            margin-bottom: 14px;
        }
        .faq-question {
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
            font-size: 0.98rem;
        }
        .faq-answer {
            color: #444444;
            font-size: 0.92rem;
            margin: 0;
            line-height: 1.5;
        }
        .contact-box {
            background-color: #f0f4f8;
            border: 1px solid #d0d7de;
            padding: 18px 20px;
            margin-top: 20px;
            border-radius: 3px;
        }
        .contact-box p {
            margin: 5px 0;
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

        /* Responsive 16:9 YouTube Video Container */
.video-container {
    position: relative;
    width: 100%;
    max-width: 1024px; /* Keeps the video sharp and prevents over-stretching */
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
    margin: 20px auto;
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
        <a href="about.php">About Us</a>
        <a href="login.php">Login</a>
        <a href="help.php" class="active">Help</a>
    </nav>

    <div class="video-container">
    <iframe
        src="https://www.youtube.com/embed/NoSyHyDXm9c"
        title="Federal University Birnin Kebbi Overview"
        allowfullscreen>
    </iframe>
</div>

    <!-- Content Box -->
    <div class="main-container">
        <h2>Portal Help &amp; User Guide</h2>
        
        <p>
            Need assistance using the Department of Computer Science Student Management System and Credential Verification Portal? Follow the step-by-step instructions and frequently asked questions below.
        </p>

        <h3>How to Use the Portal</h3>
        <ol>
            <li><strong>Account Registration:</strong> New students should click on the <em>Login</em> tab and select <em>Register here</em> to create an account using a valid email address and student details.</li>
            <li><strong>Logging In:</strong> Enter your registered email address and password on the Login Portal to access your personalized student dashboard.</li>
            <li><strong>Uploading Session Files:</strong> Navigate to your dashboard, select the required academic session documents, and upload clear, legible copies in the accepted file formats (PDF, JPG, or PNG).</li>
            <li><strong>Checking Verification Status:</strong> Monitor your dashboard regularly to see whether your uploaded credentials have been <em>Pending</em>, <em>Verified</em>, or require <em>Correction</em> by departmental administrators.</li>
        </ol>

        <h3>Frequently Asked Questions (FAQ)</h3>

        <div class="faq-item">
            <div class="faq-question">1. What should I do if I forget my login password?</div>
            <p class="faq-answer">Contact the Departmental Portal Administrator or visit the Computer Science Departmental Office with your student ID card to request a password reset.</p>
        </div>

        <div class="faq-item">
            <div class="faq-question">2. What file formats and sizes are supported for document uploads?</div>
            <p class="faq-answer">The system accepts standard <strong>PDF</strong>, <strong>JPG/JPEG</strong>, and <strong>PNG</strong> files. Ensure each document is clear and does not exceed the maximum upload size specified on your upload form.</p>
        </div>

        <div class="faq-item">
            <div class="faq-question">3. My document status shows "Rejected" or "Needs Correction." What next?</div>
            <p class="faq-answer">Review the administrator's remark on your dashboard, scan a clearer or updated copy of the required document, and re-upload it through your student dashboard.</p>
        </div>

        <div class="faq-item">
            <div class="faq-question">4. Who can access my academic records and uploaded files?</div>
            <p class="faq-answer">Your files are strictly confidential and can only be viewed by you and authorized departmental administrators responsible for student verification.</p>
        </div>

        <h3>Technical Support &amp; Contact</h3>
        <div class="contact-box">
            <p><strong>Office Location:</strong> Department of Computer Science, Faculty of Science, Federal University Birnin Kebbi (FUBK).</p>
            <p><strong>Support Hours:</strong> Monday – Friday, 8:00 AM – 4:00 PM</p>
            <p><strong>Support Email:</strong> support.cs@fubk.edu.ng</em></p>
        </div>
    </div>

    <!-- Official Footer Section -->
    <footer class="footer">
        <p>&copy; 2026–Date Computer Science Department, Federal University Birnin Kebbi. All rights reserved.</p>
        <p>Designed by Lauwal Ahmad.</p>
    </footer>

</body>
</html>