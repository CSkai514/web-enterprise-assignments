<?php
session_start();
include 'db_connection.php'; // Ensure you have a separate file for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields'); window.location='login.php';</script>";
        exit();
    }
    
    $conn = openConnection(); // Function from db_connection.php
    $stmt = $conn->prepare("SELECT id, email, password_hash, role FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_email, $db_password, $role);
        $stmt->fetch();
        
        if (password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $db_email;
            $_SESSION['role'] = $role;
            echo "<script>alert('Login Successful'); window.location='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid Password'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('No user found'); window.location='login.php';</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>
