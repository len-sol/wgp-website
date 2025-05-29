<?php
header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Admin access required'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'user'); // Default to 'user' if not specified

    // Validate inputs
    if (empty($username) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Username and password are required'
        ]);
        exit;
    }

    // Validate role
    if (!in_array($role, ['user', 'admin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid role specified'
        ]);
        exit;
    }

    try {
        // Check if username already exists
        $checkStmt = $conn->prepare("SELECT id FROM login WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Username already exists'
            ]);
            exit;
        }

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO login (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        
        if ($stmt->execute()) {
            // Log the activity
            $adminUsername = $_SESSION['username'];
            $activity = "Added new user: $username with role: $role";
            $logStmt = $conn->prepare("INSERT INTO log (username, activity) VALUES (?, ?)");
            $logStmt->bind_param("ss", $adminUsername, $activity);
            $logStmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'User added successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to add user'
            ]);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while adding user'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
