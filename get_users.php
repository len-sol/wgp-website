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

try {
    // Get all users except the current admin
    $stmt = $conn->prepare("SELECT id, username, role, active FROM login WHERE id != ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        // Convert active status to boolean for frontend
        $row['active'] = (bool)$row['active'];
        $users[] = $row;
    }

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching users list',
        'error' => $e->getMessage()
    ]);
}
?>
