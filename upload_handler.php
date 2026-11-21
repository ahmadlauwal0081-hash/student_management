<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_type = isset($_POST['upload_type']) ? $_POST['upload_type'] : '';

    if (!isset($_FILES['file_to_upload']) || $_FILES['file_to_upload']['error'] !== UPLOAD_ERR_OK) {
        $errorCode = isset($_FILES['file_to_upload']['error']) ? $_FILES['file_to_upload']['error'] : 'Unknown';
        header("Location: student_dashboard.php?error=" . urlencode("Upload failed with error code: " . $errorCode));
        exit();
    }

    $file = $_FILES['file_to_upload'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_size = $file['size'];

    // 5MB Limit check
    if ($file_size > 5 * 1024 * 1024) {
        header("Location: student_dashboard.php?error=" . urlencode("File size exceeds the 5MB limit."));
        exit();
    }

    // 1. Handle Profile Picture
    if ($upload_type === 'profile_pic') {
        if (!in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
            header("Location: student_dashboard.php?error=" . urlencode("Invalid profile picture format. Use JPG or PNG."));
            exit();
        }

        $upload_dir = 'uploads/profiles/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        $new_filename = uniqid('profile_') . '.' . $file_ext;
        
        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
            $stmt = $conn->prepare("UPDATE students SET profile_image = :filename WHERE email = :email");
            $stmt->execute([':filename' => $new_filename, ':email' => $student_email]);
            header("Location: student_dashboard.php?success=1");
            exit();
        }
    } 
    // 2. Handle Session-Based Documents
    elseif ($upload_type === 'session_doc') {
        $session_year = isset($_POST['session_year']) ? trim($_POST['session_year']) : '';
        $document_type = isset($_POST['document_type']) ? trim($_POST['document_type']) : 'General Document';

        if (empty($session_year) || empty($document_type)) {
            header("Location: student_dashboard.php?error=" . urlencode("Please select both session and document type."));
            exit();
        }

        if (!in_array($file_ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
            header("Location: student_dashboard.php?error=" . urlencode("Invalid document format. Use PDF, JPG, or PNG."));
            exit();
        }

        $upload_dir = 'uploads/documents/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        $new_filename = uniqid('doc_') . '.' . $file_ext;

        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
            // Save uploaded document record
            $stmt = $conn->prepare("INSERT INTO student_documents (student_email, session_year, document_type, file_name) VALUES (:email, :session, :type, :filename)");
            $stmt->execute([
                ':email' => $student_email,
                ':session' => $session_year,
                ':type' => $document_type,
                ':filename' => $new_filename
            ]);

            // Determine target level from the uploaded session year
            $uploaded_level = 0;
            if (stripos($session_year, 'Year 1') !== false) {
                $uploaded_level = 100;
            } elseif (stripos($session_year, 'Year 2') !== false) {
                $uploaded_level = 200;
            } elseif (stripos($session_year, 'Year 3') !== false) {
                $uploaded_level = 300;
            } elseif (stripos($session_year, 'Year 4') !== false) {
                $uploaded_level = 400;
            }

            // Automatically promote student level if the uploaded session is higher than their current level
            if ($uploaded_level > 0) {
                $curStmt = $conn->prepare("SELECT level FROM students WHERE email = :email");
                $curStmt->execute([':email' => $student_email]);
                $current_row = $curStmt->fetch(PDO::FETCH_ASSOC);

                if ($current_row) {
                    // Extract numeric level (handles '100', '100L', '100 Level', etc.)
                    $current_numeric = (int) preg_replace('/[^0-9]/', '', $current_row['level']);

                    if ($uploaded_level > $current_numeric) {
                        $lvlStmt = $conn->prepare("UPDATE students SET level = :new_level WHERE email = :email");
                        $lvlStmt->execute([
                            ':new_level' => (string) $uploaded_level,
                            ':email' => $student_email
                        ]);
                    }
                }
            }

            header("Location: student_dashboard.php?success=1");
            exit();
        }
    }
}

header("Location: student_dashboard.php");
exit();
?>