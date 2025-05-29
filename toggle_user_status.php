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

// Get JSON data from request body
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'] ?? null;
$newStatus = $data['status'] ?? null;

if ($userId === null || $newStatus === null) {
    echo json_encode([
        'success' => false,
        'message' => 'User ID and status are required'
    ]);
    exit;
}

try {
    // First check if user exists and is not an admin
    $checkStmt = $conn->prepare("SELECT username, role FROM login WHERE id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }

    $user = $result->fetch_assoc();
    
    // Prevent modifying admin users
    if ($user['role'] === 'admin') {
        echo json_encode([
            'success' => false,
            'message' => 'Cannot modify admin user status'
        ]);
        exit;
    }

    // Update user status
    $stmt = $conn->prepare("UPDATE login SET active = ? WHERE id = ?");
    $active = $newStatus ? 1 : 0;
    $stmt->bind_param("ii", $active, $userId);
    
    if ($stmt->execute()) {
        // Log the activity
        $adminUsername = $_SESSION['username'];
        $targetUsername = $user['username'];
        $action = $newStatus ? 'activated' : 'deactivated';
        $activity = "Admin $adminUsername $action user: $targetUsername";
        
        $logStmt = $conn->prepare("INSERT INTO log (username, activity) VALUES (?, ?)");
        $logStmt->bind_param("ss", $adminUsername, $activity);
        $logStmt->execute();

        echo json_encode([
            'success' => true,
            'message' => "User successfully " . ($newStatus ? 'activated' : 'deactivated')
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update user status'
        ]);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while updating user status'
    ]);
}
?>
