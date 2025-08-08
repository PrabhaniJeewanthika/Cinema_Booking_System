<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    $conn = Database::getConnection();

    // Check email and password in one query
    $stmt = $conn->prepare("SELECT admin_id, admin_name FROM admin WHERE admin_email = ? AND admin_password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Login success
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['admin_name'] = $row['admin_name'];
        
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid email or password!";
    }

    $stmt->close();
    Database::closeConnection();
}
?>

