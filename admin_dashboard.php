<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Security check: Ensure user is logged in as admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// DEFINE HOST (SUPER) ADMIN EMAIL
$host_admin_email = 'ahmadlauwal0081@gmail.com';
$is_host_admin = (strtolower($_SESSION['user_email']) === strtolower($host_admin_email));

// Fetch all registered students
try {
    $stmt = $conn->prepare("SELECT * FROM students");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $students = [];
}

// Fetch student documents for verification review
try {
    $docStmt = $conn->prepare("SELECT d.*, s.full_name, s.registration_number 
                               FROM student_documents d 
                               JOIN students s ON d.student_email = s.email 
                               ORDER BY d.uploaded_at DESC");
    $docStmt->execute();
    $all_documents = $docStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $all_documents = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; color: #334155; margin: 0; padding: 24px; }
        .dashboard-container { max-width: 1200px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h2, h3 { color: #0f172a; }
        .top-links { margin-bottom: 20px; font-weight: 600; }
        .top-links a { color: #2563eb; text-decoration: none; margin-right: 15px; }
        .top-links a.logout { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 30px; }
        th, td { padding: 10px 12px; border: 1px solid #cbd5e1; text-align: left; font-size: 0.9rem; vertical-align: middle; }
        th { background: #e2e8f0; color: #0f172a; }
        .btn { background: #16a34a; color: #fff; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: inline-block; }
        .btn:hover { background: #15803d; }
        .badge-host { background: #003366; color: #ffffff; padding: 2px 7px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
        .badge-protected { color: #64748b; font-weight: 600; font-size: 0.85rem; }
        .dashboard-footer { text-align: center; margin-top: 40px; padding: 20px 0; color: #64748b; font-size: 0.85rem; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <!-- Top Navigation / Links -->
        <div class="top-links">
            <a href="index.php">Home</a>
            <?php if ($is_host_admin): ?>
                <a href="create_admin.php">Create New Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="logout">Logout</a>
        </div>

        <h2>Admin Control Panel</h2>
        <p>
            Welcome back, Administrator: <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong>
            <?php if ($is_host_admin): ?>
                <span class="badge-host">HOST ADMIN</span>
            <?php endif; ?>
        </p>

        <!-- Registered Students Management Table -->
        <h3 style="margin-top: 30px;">Registered Students Management</h3>
        <p style="color: #64748b; font-size: 0.9rem;">Here is a live list of all accounts registered in the system:</p>

        <table>
            <tr>
                <th>Registration Number</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Level</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars(isset($student['registration_number']) ? $student['registration_number'] : ''); ?></td>
                <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td><?php echo htmlspecialchars(isset($student['department']) ? $student['department'] : ''); ?></td>
                <td><strong><?php echo htmlspecialchars(isset($student['level']) ? $student['level'] : '0'); ?></strong></td>
                <td>
                    <em><?php echo htmlspecialchars($student['role']); ?></em>
                    <?php if (strtolower($student['email']) === strtolower($host_admin_email)): ?>
                        <span class="badge-host">Host</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                    $target_is_host = (strtolower($student['email']) === strtolower($host_admin_email));
                    $target_is_admin = ($student['role'] === 'admin');
                    $is_self = (strtolower($student['email']) === strtolower($_SESSION['user_email']));
                    ?>

                    <?php if ($target_is_host): ?>
                        <span class="badge-protected">Host Protected</span>

                    <?php elseif ($is_self): ?>
                        <span class="badge-protected">Current Admin</span>

                    <?php elseif ($target_is_admin): ?>
                        <?php if ($is_host_admin): ?>
                            <a href="edit_student.php?id=<?php echo $student['id']; ?>" style="color: #2563eb; text-decoration: none; font-weight: 600;">Edit</a> | 
                            <a href="delete_student.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure you want to remove this Admin account?');" style="color: #dc2626; text-decoration: none; font-weight: 600;">Delete</a>
                        <?php else: ?>
                            <span class="badge-protected">Admin Protected</span>
                        <?php endif; ?>

                    <?php else: ?>
                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" style="color: #2563eb; text-decoration: none; font-weight: 600;">Edit</a> | 
                        <a href="delete_student.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #dc2626; text-decoration: none; font-weight: 600;">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Select Student for Verification -->
        <hr style="margin: 40px 0; border: 0; border-top: 1px solid #cbd5e1;">

        <h3>Student Credential Verification</h3>
        <p style="color: #64748b; font-size: 0.9rem;">Select a student below to view, verify, and drop remarks on their multi-year documents.</p>

        <?php if (count($students) > 0): ?>
            <table>
                <tr>
                    <th>Registration Number</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($students as $stu): ?>
                    <?php if ($stu['role'] === 'student'): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(isset($stu['registration_number']) ? $stu['registration_number'] : 'N/A'); ?></td>
                        <td><strong><?php echo htmlspecialchars($stu['full_name']); ?></strong><br><span style="font-size:0.8rem; color:#64748b;"><?php echo htmlspecialchars($stu['email']); ?></span></td>
                        <td><?php echo htmlspecialchars(isset($stu['department']) ? $stu['department'] : 'N/A'); ?></td>
                        <td><strong><?php echo htmlspecialchars(isset($stu['level']) ? $stu['level'] : 'N/A'); ?></strong></td>
                        <td>
                            <a href="admin_view_student.php?email=<?php echo urlencode($stu['email']); ?>" style="background: #2563eb; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">View Credentials</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="color: #64748b; font-size: 0.9rem;">No students registered yet.</p>
        <?php endif; ?>

        <!-- Dashboard Footer -->
        <footer class="dashboard-footer">
            &copy; <?php echo date('Y'); ?> Federal University Birnin Kebbi. All Rights Reserved.
        </footer>

    </div>

</body>
</html>