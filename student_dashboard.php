<?php
session_start();

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/config/db.php';
$student_email = $_SESSION['user_email'];

try {
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute([':email' => $student_email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Fetch upload history for this student across sessions
    $docStmt = $conn->prepare("SELECT * FROM student_documents WHERE student_email = :email ORDER BY uploaded_at DESC");
    $docStmt->execute([':email' => $student_email]);
    $documents = $docStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Detect current level to auto-select matching session on load
$currentLevel = isset($student['level']) ? trim($student['level']) : '';
$defaultSession = '';
if (in_array($currentLevel, ['100', '100L', '100 Level'])) {
    $defaultSession = '2024/2025 (Year 1)';
} elseif (in_array($currentLevel, ['200', '200L', '200 Level'])) {
    $defaultSession = '2025/2026 (Year 2)';
} elseif (in_array($currentLevel, ['300', '300L', '300 Level'])) {
    $defaultSession = '2026/2027 (Year 3)';
} elseif (in_array($currentLevel, ['400', '400L', '400 Level'])) {
    $defaultSession = '2027/2028 (Year 4)';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; color: #334155; margin: 0; padding: 24px; }
        .top-nav { max-width: 900px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; align-items: center; }
        .top-nav a.logout-link { color: #dc2626; text-decoration: none; font-weight: 600; }
        .dashboard-container { max-width: 900px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h2 { margin-top: 0; color: #0f172a; }
        .alert-success { background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #a7f3d0; font-weight: 500; }
        .alert-error { background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca; font-weight: 500; }
        .profile-header { display: flex; align-items: center; gap: 24px; margin-bottom: 24px; background: #f8fafc; padding: 20px; border-radius: 8px; }
        .profile-pic { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #2563eb; background: #e2e8f0; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
        .info-item { background: #f8fafc; padding: 12px 16px; border-radius: 6px; }
        .info-item label { font-size: 0.85rem; color: #64748b; display: block; margin-bottom: 4px; }
        .info-item span { font-weight: 600; color: #0f172a; }
        .level-badge { display: inline-block; background-color: #003366; color: #ffffff !important; padding: 3px 10px; border-radius: 4px; font-size: 0.9rem; }
        .upload-card { background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #e2e8f0; }
        .upload-card h3 { margin-top: 0; font-size: 1.1rem; color: #1e293b; }
        .btn-upload { background-color: #2563eb; color: #ffffff; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-upload:hover { background-color: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #cbd5e1; text-align: left; font-size: 0.9rem; }
        th { background: #e2e8f0; color: #0f172a; }
        .dashboard-footer { text-align: center; margin-top: 40px; padding: 20px 0; color: #64748b; font-size: 0.85rem; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <div class="top-nav">
        <h2>Student Portal</h2>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <div class="dashboard-container">
        
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert-success">File uploaded successfully!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="profile-header">
            <div>
                <?php if (!empty($student['profile_image'])): ?>
                    <img src="uploads/profiles/<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Picture" class="profile-pic">
                <?php else: ?>
                    <div class="profile-pic" style="display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.8rem;">No Image</div>
                <?php endif; ?>
            </div>
            <div>
                <h3 style="margin: 0 0 4px 0; color: #0f172a;"><?php echo htmlspecialchars($student['full_name']); ?></h3>
                <p style="margin: 0; color: #64748b; font-size: 0.95rem;"><?php echo htmlspecialchars($student['email']); ?></p>
            </div>
        </div>

        <!-- Student Info Grid -->
        <div class="info-grid">
            <div class="info-item"><label>Registration Number</label><span><?php echo htmlspecialchars(isset($student['registration_number']) ? $student['registration_number'] : 'Not Set'); ?></span></div>
            <div class="info-item"><label>Department</label><span><?php echo htmlspecialchars(isset($student['department']) ? $student['department'] : 'Not Set'); ?></span></div>
            <div class="info-item"><label>Current Level</label><span class="level-badge"><?php echo htmlspecialchars(!empty($currentLevel) ? $currentLevel : 'Not Set'); ?></span></div>
            <div class="info-item"><label>Account Role</label><span style="text-transform: uppercase;"><?php echo htmlspecialchars($student['role']); ?></span></div>
        </div>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

        <!-- 1. Profile Picture Update -->
        <div class="upload-card">
            <h3>Update Profile Picture</h3>
            <form action="upload_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload_type" value="profile_pic">
                <input type="file" name="file_to_upload" accept=".jpg, .jpeg, .png" required><br><br>
                <button type="submit" class="btn-upload">Upload Picture</button>
            </form>
        </div>

        <!-- 2. Session Credential Upload -->
        <div class="upload-card">
            <h3>Upload Session Credential / Document</h3>
            <form action="upload_handler.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                <input type="hidden" name="upload_type" value="session_doc">
                
                <label style="display: block; font-size: 0.85rem; color: #64748b; margin-bottom: 4px;">Select Academic Session & Level:</label>
                <select name="session_year" id="sessionSelect" required style="width: 100%; padding: 8px; margin-bottom: 12px; border-radius: 6px; border: 1px solid #cbd5e1;" onchange="filterCredentials()">
                    <option value="">-- Choose Session --</option>
                    <option value="2024/2025 (Year 1)" <?php echo ($defaultSession === '2024/2025 (Year 1)') ? 'selected' : ''; ?>>2024/2025 (Year 1)</option>
                    <option value="2025/2026 (Year 2)" <?php echo ($defaultSession === '2025/2026 (Year 2)') ? 'selected' : ''; ?>>2025/2026 (Year 2)</option>
                    <option value="2026/2027 (Year 3)" <?php echo ($defaultSession === '2026/2027 (Year 3)') ? 'selected' : ''; ?>>2026/2027 (Year 3)</option>
                    <option value="2027/2028 (Year 4)" <?php echo ($defaultSession === '2027/2028 (Year 4)') ? 'selected' : ''; ?>>2027/2028 (Year 4)</option>
                </select>

                <label style="display: block; font-size: 0.85rem; color: #64748b; margin-bottom: 4px;">Select Document Type:</label>
                <select name="document_type" id="docTypeSelect" required style="width: 100%; padding: 8px; margin-bottom: 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                    <option value="">-- Choose Document Type --</option>
                    
                    <!-- Year 1 Options -->
                    <option value="JAMB Original Result" class="year1-opt" style="display:none;">JAMB Original Result Copy</option>
                    <option value="Evidence of Payment & Admission Letter" class="year1-opt" style="display:none;">Evidence of Payment & Admission Letter</option>
                    <option value="Form 02 Copy" class="year1-opt" style="display:none;">Form 02 Copy</option>
                    <option value="Course Registration Form" class="year1-opt" style="display:none;">Course Registration Copy</option>
                    
                    <!-- Year 2 Options -->
                    <option value="200 Level Evidence of Payment" class="year2-opt" style="display:none;">200 Level Evidence of Payment Copy</option>
                    <option value="200 Level Form 02" class="year2-opt" style="display:none;">200 Level Form 02 Copy</option>
                    <option value="200 Level Course Registration" class="year2-opt" style="display:none;">200 Level Course Registration Form</option>

                    <!-- Year 3 Options -->
                    <option value="300 Level Evidence of Payment" class="year3-opt" style="display:none;">300 Level Evidence of Payment Copy</option>
                    <option value="300 Level Form 02" class="year3-opt" style="display:none;">300 Level Form 02 Copy</option>
                    <option value="300 Level Course Registration" class="year3-opt" style="display:none;">300 Level Course Registration Form</option>

                    <!-- Year 4 Options -->
                    <option value="400 Level Evidence of Payment" class="year4-opt" style="display:none;">400 Level Evidence of Payment Copy</option>
                    <option value="400 Level Form 02" class="year4-opt" style="display:none;">400 Level Form 02 Copy</option>
                    <option value="400 Level Course Registration" class="year4-opt" style="display:none;">400 Level Course Registration Form</option>
                </select>

                <label style="display: block; font-size: 0.85rem; color: #64748b; margin-bottom: 4px;">Upload Document (PDF/Image):</label>
                <input type="file" name="file_to_upload" accept=".pdf, .jpg, .jpeg, .png" required><br><br>
                
                <button type="submit" class="btn-upload">Upload Credential</button>
            </form>

            <script>
            function filterCredentials() {
                var sessionVal = document.getElementById('sessionSelect').value;
                var docSelect = document.getElementById('docTypeSelect');
                var year1Opts = document.getElementsByClassName('year1-opt');
                var year2Opts = document.getElementsByClassName('year2-opt');
                var year3Opts = document.getElementsByClassName('year3-opt');
                var year4Opts = document.getElementsByClassName('year4-opt');

                // Reset selection
                docSelect.value = "";

                // Hide all options first
                var allOpts = [year1Opts, year2Opts, year3Opts, year4Opts];
                for (var k = 0; k < allOpts.length; k++) {
                    for (var i = 0; i < allOpts[k].length; i++) {
                        allOpts[k][i].style.display = "none";
                    }
                }

                // Show target options based on selection
                var targetOpts = null;
                if (sessionVal.includes("Year 1")) targetOpts = year1Opts;
                else if (sessionVal.includes("Year 2")) targetOpts = year2Opts;
                else if (sessionVal.includes("Year 3")) targetOpts = year3Opts;
                else if (sessionVal.includes("Year 4")) targetOpts = year4Opts;

                if (targetOpts) {
                    for (var j = 0; j < targetOpts.length; j++) {
                        targetOpts[j].style.display = "block";
                    }
                }
            }

            // Automatically populate document options matching the student's current level on page load
            window.addEventListener('DOMContentLoaded', function() {
                filterCredentials();
            });
            </script>

            <h4 style="margin-top: 24px; color: #1e293b;">Your Uploaded Credentials & Documents</h4>
            <?php if (count($documents) > 0): ?>
                <table>
                    <tr>
                        <th>Session</th>
                        <th>Document Type</th>
                        <th>File Name</th>
                        <th>Status / Admin Feedback</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($doc['session_year']); ?></td>
                        <td><?php echo htmlspecialchars($doc['document_type']); ?></td>
                        <td><?php echo htmlspecialchars($doc['file_name']); ?></td>
                        <td>
                            <?php 
                                $status = isset($doc['status']) ? $doc['status'] : 'Pending';
                                $remark = isset($doc['admin_remark']) ? $doc['admin_remark'] : '';
                                
                                // Color code status badges
                                $badgeColor = '#d97706'; // Pending (Orange)
                                if ($status === 'Approved') $badgeColor = '#059669'; // Green
                                if ($status === 'Rejected') $badgeColor = '#dc2626'; // Red
                            ?>
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; background: <?php echo $badgeColor; ?>; color: #fff;">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                            <?php if (!empty($remark)): ?>
                                <div style="font-size: 0.85rem; color: #475569; margin-top: 4px; font-style: italic;">
                                    "<?php echo htmlspecialchars($remark); ?>"
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="uploads/documents/<?php echo htmlspecialchars($doc['file_name']); ?>" target="_blank">View</a> 
                            <?php if ($status !== 'Approved'): ?>
                                | <a href="delete_document.php?id=<?php echo $doc['id']; ?>" onclick="return confirm('Are you sure you want to delete this document?');" style="color: #dc2626; text-decoration: none; font-weight: 600;">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p style="color: #64748b; font-size: 0.9rem;">No documents uploaded yet.</p>
            <?php endif; ?>
        </div>

        <!-- Dashboard Footer -->
        <footer class="dashboard-footer">
            &copy; <?php echo date('Y'); ?> Federal University Birnin Kebbi. All Rights Reserved.
        </footer>

    </div>

</body>
</html>