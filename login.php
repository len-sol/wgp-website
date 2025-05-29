<?php
header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Username and password are required'
        ]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id, username, password, role, active FROM login WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Check if user is active
            if (!$user['active']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Account is deactivated. Please contact administrator.'
                ]);
                exit;
            }
            
            // In a real application, you should use password_verify() with hashed passwords
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Log the login activity
                $activity = "User login successful";
                $logStmt = $conn->prepare("INSERT INTO log (username, activity) VALUES (?, ?)");
                $logStmt->bind_param("ss", $username, $activity);
                $logStmt->execute();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful'
                ]);
                exit;
            }
        }

        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred during login'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
