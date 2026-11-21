<?php
$host = "localhost";
$dbname = "student_management_db";
$username = "root";
$password = "";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Uncomment the line below temporarily if you want to test the connection message
    // echo "Database connected succesfully!";

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>