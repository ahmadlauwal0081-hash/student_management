<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Security check: Ensure user is logged in as admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$student_email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($student_email)) {
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch student details
try {
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([':email' => $student_email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Student not found.");
    }

    // Fetch documents uploaded by this specific student
    $docStmt = $conn->prepare("SELECT * FROM student_documents WHERE student_email = :email ORDER BY session_year DESC");
    $docStmt->execute([':email' => $student_email]);
    $documents = $docStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Student: <?php echo htmlspecialchars($student['full_name']); ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; color: #334155; margin: 0; padding: 24px; }
        .dashboard-container { max-width: 1000px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h2, h3 { color: #0f172a; margin-top: 0; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; font-weight: 600; }
        .student-info { background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; }
        .profile-img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 2px solid #cbd5e1; background: #e2e8f0; }
        .student-details p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #cbd5e1; text-align: left; font-size: 0.9rem; vertical-align: middle; }
        th { background: #e2e8f0; color: #0f172a; }
        .btn-action { background-color: #2563eb; color: #ffffff; padding: 6px 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.85rem; }
        .btn-action:hover { background-color: #1d4ed8; }
        select, input[type="text"] { padding: 6px; border-radius: 4px; border: 1px solid #cbd5e1; font-size: 0.85rem; margin-bottom: 4px; width: 100%; box-sizing: border-box; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <a href="admin_dashboard.php" class="back-link"> Back </a>

        <h2>Student Credential Audit</h2>
        
        <div class="student-info">
            <!-- Display profile picture if available, otherwise show a default avatar placeholder -->
            <div>
                <?php if (!empty($student['profile_image']) && file_exists('uploads/profiles/' . $student['profile_image'])): ?>
                    <img src="uploads/profiles/<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Picture" class="profile-img">
                <?php else: ?>
                    <div class="profile-img" style="display: flex; align-items: center; justify-content: center; color: #64748b; font-weight: bold; font-size: 1.2rem;">
                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="student-details">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['full_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Registration Number:</strong> <?php echo htmlspecialchars(isset($student['registration_number']) ? $student['registration_number'] : 'N/A'); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars(isset($student['department']) ? $student['department'] : 'N/A'); ?> | <strong>Level:</strong> <?php echo htmlspecialchars(isset($student['level']) ? $student['level'] : 'N/A'); ?></p>
            </div>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-weight: 500;">Document status updated successfully!</div>
        <?php endif; ?>

        <h3>Uploaded Credentials Across Sessions</h3>

        <?php if (count($documents) > 0): ?>
            <table>
                <tr>
                    <th>Session / Level</th>
                    <th>Document Type</th>
                    <th>File</th>
                    <th>Verification Status & Admin Remark</th>
                </tr>
                <?php foreach ($documents as $doc): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($doc['session_year']); ?></strong></td>
                    <td><?php echo htmlspecialchars($doc['document_type']); ?></td>
                    <td><a href="uploads/documents/<?php echo htmlspecialchars($doc['file_name']); ?>" target="_blank" style="color: #2563eb; font-weight: 600;">View Document</a></td>
                    <td>
                        <form action="admin_verify_document.php" method="POST">
                            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
                            <input type="hidden" name="redirect_email" value="<?php echo htmlspecialchars($student['email']); ?>">
                            
                            <select name="status" style="padding: 4px; margin-bottom: 4px;">
                                <option value="Pending" <?php echo (isset($doc['status']) && $doc['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Approved" <?php echo (isset($doc['status']) && $doc['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?php echo (isset($doc['status']) && $doc['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                            </select>

                            <input type="text" name="admin_remark" placeholder="Add remark..." value="<?php echo htmlspecialchars(isset($doc['admin_remark']) ? $doc['admin_remark'] : ''); ?>">
                            
                            <button type="submit" class="btn-action">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="color: #64748b; font-size: 0.9rem;">This student has not uploaded any credentials yet.</p>
        <?php endif; ?>

    </div>

</body>
</html>