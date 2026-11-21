<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Security check: Ensure user is logged in AND is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc_id = isset($_POST['doc_id']) ? intval($_POST['doc_id']) : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'Pending';
    $remark = isset($_POST['admin_remark']) ? trim($_POST['admin_remark']) : '';

    if ($doc_id > 0) {
        try {
            $stmt = $conn->prepare("UPDATE student_documents SET status = :status, admin_remark = :remark WHERE id = :id");
            $stmt->execute([
                ':status' => $status,
                ':remark' => $remark,
                ':id' => $doc_id
            ]);

            // Redirect back to admin review portal with success flag
            header("Location: admin_dashboard.php?success=1");
            exit();
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
}

header("Location: admin_dashboard.php");
exit();
?>