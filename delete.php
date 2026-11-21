<?php
session_start();

// Security check: Ensure user is logged in AND is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once __DIR__ . '/config/db.php';

// Check if an ID was passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = $_GET['id'];

    try {
        // Prepare the delete statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $student_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Successfully deleted, redirect back to the admin dashboard
            header("Location: admin_dashboard.php?msg=deleted");
            exit();
        } else {
            echo "Failed to delete the record.";
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    // If no ID was provided, redirect back to dashboard
    header("Location: admin_dashboard.php");
    exit();
}
?>