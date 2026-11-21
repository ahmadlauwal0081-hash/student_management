<?php
// Start the session to keep the user logged in
session_start();

// Include the database connection file from your config folder
require_once __DIR__ . '/config/db.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve and trim form data safely
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate that fields are not empty
    if (empty($email) || empty($password)) {
        die("Please fill in both email and password fields. <a href='login.php'>Go back</a>");
    }

    try {
        // Prepare the SQL statement to find the student/user by email
        $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->execute([':email' => $email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if the user exists and if the password matches the database hash
        if ($user && password_verify($password, $user['password'])) {
            
            // Store user details securely in the session
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = isset($user['role']) ? $user['role'] : 'student';

            // REDIRECT BASED ON ROLE (Admin vs Student)
            if ($_SESSION['user_role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: student_dashboard.php");
                exit();
            }

        } else {
            // Invalid credentials error message
            echo "Invalid email or password. <a href='login.php'>Try again</a>";
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    // If someone accesses authenticate.php directly in the browser without submitting the form
    header("Location: login.php");
    exit();
}
?>