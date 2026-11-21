<?php
session_start();

// Include database connection
require_once __DIR__ . '/config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            // Check if user exists in the database
            $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify user existence and password
            if ($user && password_verify($password, $user['password'])) {
                
                // Set session variables
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role']; // 'admin' or 'student'
                $_SESSION['user_name'] = $user['full_name'];

                // Smart Role-Based Redirection
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    header("Location: student_dashboard.php");
                    exit();
                }

            } else {
                $error = "Invalid email or password.";
            }

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            margin: 0 !important;
            padding: 0 !important;
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

        @media (max-width: 768px) {
            .banner-inner { flex-direction: column; text-align: center; }
            .banner-logo { width: 85px; height: 85px; }
            .banner-text h1 { font-size: 1.45rem; }
            .banner-text .dept-title { font-size: 1.1rem; }
        }

        /* Full-Width University Navigation Bar (Matches Home Page) */
        .navbar { 
            background-color: #003366; 
            display: flex; 
            justify-content: center; 
            border-bottom: 2px solid #002244; 
            width: 100% !important;
            max-width: none !important;
            margin: 0 0 30px 0 !important;
            padding: 0 !important;
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

        /* Original Styles Preserved */
        .top-nav {
            max-width: 1100px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
        }

        .top-nav a.home-link {
            font-weight: bold;
            color: #2563eb;
            text-decoration: none;
            font-size: 1rem;
        }

        .top-nav a.home-link:hover {
            text-decoration: underline;
        }

        .top-nav a.logout-link {
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .top-nav a.logout-link:hover {
            text-decoration: underline;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        h2 {
            margin-top: 0;
            color: #0f172a;
            font-size: 1.5rem;
        }

        .welcome-text {
            margin-bottom: 20px;
            font-size: 0.95rem;
            color: #475569;
        }

        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 16px;
        }

        .table-responsive {
            overflow-x: auto;
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 16px;
            font-weight: 600;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .action-btn-edit {
            color: #d97706;
            text-decoration: none;
            font-weight: 600;
            margin-right: 8px;
            background: #fef3c7;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .action-btn-edit:hover {
            background: #fde68a;
        }

        .action-btn-delete {
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
            background: #fee2e2;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .action-btn-delete:hover {
            background: #fecaca;
        }

        .current-admin {
            color: #94a3b8;
            font-style: italic;
        }

        .register-btn {
            display: inline-block;
            background-color: #16a34a;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }

        .register-btn:hover {
            background-color: #15803d;
        }

        /* Full-Width Official Institutional Footer */
        .footer {
            background-color: #003366;
            color: #ffffff;
            text-align: center;
            padding: 16px 20px;
            font-size: 0.85rem;
            border-top: 3px solid #ffcc00;
            margin-top: auto;
            width: 100% !important;
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
        <a href="about.php">About Us</a>
        <a href="login.php" class="active">Login</a>
        <a href="help.php">Help</a>
    </nav>
    
    <!-- Original Login Container -->
    <div class="container">
        <center> <img src="image/logo.PNG" width="150" height="100"/></center><br>
        <center> <h2> Login Portal</h2></center>

        <!-- Display error message if login failed -->
        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="authenticate.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <!-- Official Footer Section -->
    <footer class="footer">
        <p>&copy; 2026–Date Computer Science Department, Federal University Birnin Kebbi. All rights reserved.</p>
        <p>Designed by Lauwal Ahmad.</p>
    </footer>
    
</body>
</html>