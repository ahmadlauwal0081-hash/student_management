<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Security check: Ensure user is logged in as admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id > 0) {
    try {
        // 1. First fetch the student's email so we can delete their associated documents first
        $stmt = $conn->prepare("SELECT email FROM students WHERE id = :id");
        $stmt->execute([':id' => $student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $student_email = $student['email'];

            // 2. Delete their uploaded documents from the database (and optionally their files from the folder)
            $docStmt = $conn->prepare("SELECT file_name FROM student_documents WHERE student_email = :email");
            $docStmt->execute([':email' => $student_email]);
            $docs = $docStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($docs as $doc) {
                $filePath = __DIR__ . '/uploads/documents/' . $doc['file_name'];
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete physical file from server
                }
            }

            // Remove document records from table
            $delDocs = $conn->prepare("DELETE FROM student_documents WHERE student_email = :email");
            $delDocs->execute([':email' => $student_email]);

            // 3. Now safely delete the student account row
            $delStudent = $conn->prepare("DELETE FROM students WHERE id = :id");
            $delStudent->execute([':id' => $student_id]);
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

// Redirect back to admin dashboard
header("Location: admin_dashboard.php");
exit();
?>