<?php
session_start();

// Security check: Ensure user is logged in AND is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once __DIR__ . '/config/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture data safely using legacy isset() to support all PHP versions
    $registration_number = isset($_POST['registration_number']) ? trim($_POST['registration_number']) : '';
    $full_name           = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email               = isset($_POST['email']) ? trim($_POST['email']) : '';
    $department          = isset($_POST['department']) ? trim($_POST['department']) : '';
    $level               = isset($_POST['level']) ? trim($_POST['level']) : '';
    $password            = isset($_POST['password']) ? $_POST['password'] : '';
    $role                = 'student';

    // Validate inputs
    if (empty($registration_number) || empty($full_name) || empty($email) || empty($department) || empty($level) || empty($password)) {
        die("Error: All fields are required. Please go back and fill in every field.");
    }

    try {
        // Hash password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert query using your database column: registration_number
        $stmt = $conn->prepare("INSERT INTO students (registration_number, full_name, email, department, level, password, role) VALUES (:registration_number, :full_name, :email, :department, :level, :password, :role)");
        
        $stmt->execute([
            ':registration_number' => $registration_number,
            ':full_name'           => $full_name,
            ':email'               => $email,
            ':department'          => $department,
            ':level'               => $level,
            ':password'            => $hashed_password,
            ':role'                => $role
        ]);

        // Success! Redirect back to admin dashboard
        header("Location: admin_dashboard.php?success=1");
        exit();

    } catch (PDOException $e) {
        // If email or registration number already exists
        if ($e->getCode() == 23000) {
            echo "<script>alert('Error: This Email or Registration Number already exists in the system!'); window.location.href='register.php';</script>";
            exit();
        } else {
            die("Database Error: " . $e->getMessage());
        }
    }
} else {
    header("Location: register.php");
    exit();
}
?>