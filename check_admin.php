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
        $stmt = $conn->prepare("SELECT id, username, role FROM login WHERE username = ? AND password = ? AND role = 'admin'");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];
            
            // Log admin login activity
            $activity = "Admin login successful";
            $logStmt = $conn->prepare("INSERT INTO log (username, activity) VALUES (?, ?)");
            $logStmt->bind_param("ss", $username, $activity);
            $logStmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Admin authentication successful'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid admin credentials'
            ]);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred during authentication'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
