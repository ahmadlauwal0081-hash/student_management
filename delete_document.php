<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Security check: Ensure student is logged in
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_email = $_SESSION['user_email'];
$doc_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($doc_id > 0) {
    try {
        // Fetch the file record to make sure it belongs to this student and find the filename
        $stmt = $conn->prepare("SELECT * FROM student_documents WHERE id = :id AND student_email = :email");
        $stmt->execute([':id' => $doc_id, ':email' => $student_email]);
        $doc = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doc) {
            $file_path = 'uploads/documents/' . $doc['file_name'];
            
            // Delete the physical file from the folder if it exists
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Delete the row from the database
            $delStmt = $conn->prepare("DELETE FROM student_documents WHERE id = :id");
            $delStmt->execute([':id' => $doc_id]);
        }
    } catch (PDOException $e) {
        // Handle database error if needed
    }
}

// Redirect back to dashboard
header("Location: student_dashboard.php");
exit();
?>