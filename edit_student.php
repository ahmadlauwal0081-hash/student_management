<?php
session_start();

// Security check: Only admins can access
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/config/db.php';

$student = null;

// 1. Fetch the student's current data based on the ID in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            die("Student not found.");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// 2. If the form is submitted, update the database record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $registration_number = trim($_POST['registration_number']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $level = trim($_POST['level']);
    $role = trim($_POST['role']);

    try {
        $updateStmt = $conn->prepare("UPDATE students SET registration_number = :reg_number, full_name = :full_name, email = :email, department = :department, level = :level, role = :role WHERE id = :id");
        
        $updateStmt->execute([
            ':reg_number' => $registration_number,
            ':full_name' => $full_name,
            ':email' => $email,
            ':department' => $department,
            ':level' => $level,
            ':role' => $role,
            ':id' => $id
        ]);

        header("Location: admin_dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("Error updating record: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .form-container { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #007BFF; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Student Profile</h2>
    <form action="edit_student.php?id=<?php echo htmlspecialchars($student['id']); ?>" method="POST">
        <!-- Hidden ID field -->
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">

        <label>Registration Number:</label>
        <input type="text" name="registration_number" value="<?php echo isset($student['registration_number']) ? htmlspecialchars($student['registration_number']) : ''; ?>" required>

        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>

        <label>Email Address:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>

        <label>Department:</label>
        <input type="text" name="department" value="<?php echo htmlspecialchars($student['department']); ?>" required>

        <label>Level:</label>
        <select name="level" required>
            <option value="100" <?php if($student['level'] == '100') echo 'selected'; ?>>100</option>
            <option value="200" <?php if($student['level'] == '200') echo 'selected'; ?>>200</option>
            <option value="300" <?php if($student['level'] == '300') echo 'selected'; ?>>300</option>
            <option value="400" <?php if($student['level'] == '400') echo 'selected'; ?>>400</option>
        </select>

        <label>Role Privilege:</label>
        <select name="role" required>
            <option value="student" <?php if($student['role'] == 'student') echo 'selected'; ?>>Student</option>
            <option value="admin" <?php if($student['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select>

        <button type="submit">Update Student</button>
    </form>

    <a href="admin_dashboard.php" class="back-link">&larr; Back to Admin Dashboard</a>
</div>

</body>
</html>