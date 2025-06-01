<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in both username and password.";
        header("Location: index.php");
        exit();
    }

    try {
        // Prepare statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT * FROM login WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            // Check if password is hashed (starts with $2y$)
            if (strpos($user['password'], '$2y$') === 0) {
                // Verify hashed password
                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $username;
                    header("Location: unverify.php");
                    exit();
                }
            } else {
                // Fallback for plain text passwords (not recommended for production)
                if ($password === $user['password']) {
                    $_SESSION['username'] = $username;
                    header("Location: unverify.php");
                    exit();
                }
            }
        }

        // If we get here, authentication failed
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred. Please try again later.";
        header("Location: index.php");
        exit();
    }
} else {
    // If not a POST request, redirect to login page
    header("Location: index.ph");
    exit();
}
?>
