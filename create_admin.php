<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Security check: Only logged-in admins can create new admins
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);

    if (empty($email) || empty($password) || empty($full_name)) {
        $error = "All fields are required.";
    } else {
        try {
            // Check if email already exists
            $checkStmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
            $checkStmt->execute([':email' => $email]);
            
            if ($checkStmt->rowCount() > 0) {
                $error = "An account with this email already exists.";
            } else {
                // Hash the password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new admin into your students table with role = 'admin'
                $insertStmt = $conn->prepare("INSERT INTO students (full_name, email, password, role) VALUES (:full_name, :email, :password, 'admin')");
                $insertStmt->execute([
                    ':full_name' => $full_name,
                    ':email' => $email,
                    ':password' => $hashed_password
                ]);

                $success = "New administrator account created successfully!";
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Admin - FUBK</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #222; margin: 0; padding: 40px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border: 1px solid #ccc; border-top: 4px solid #003366; }
        h2 { color: #003366; font-family: 'Times New Roman', Times, serif; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box; font-size: 0.9rem; }
        .btn { background-color: #003366; color: white; padding: 10px 20px; border: none; font-weight: bold; cursor: pointer; }
        .btn:hover { background-color: #002244; }
        .back-link { display: inline-block; margin-top: 15px; color: #003366; text-decoration: none; font-size: 0.9rem; font-weight: bold; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-size: 0.9rem; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Register New Administrator</h2>
        <p style="font-size: 0.9rem; color: #555;">Create a secure secondary administrative account for the system.</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="create_admin.php" method="POST">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" required>
            </div>

            <div class="form-group">
                <label>Email Address:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn">Create Administrator</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">Back</a>
    </div>

</body>
</html>