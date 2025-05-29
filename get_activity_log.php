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
    // Get activity logs with most recent first
    $query = "SELECT username, activity, created_at as timestamp 
              FROM log 
              ORDER BY created_at DESC 
              LIMIT 100"; // Limit to last 100 activities for performance
    
    $result = $conn->query($query);
    
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        // Format timestamp for frontend display
        $row['timestamp'] = date('Y-m-d H:i:s', strtotime($row['timestamp']));
        $activities[] = $row;
    }

    echo json_encode([
        'success' => true,
        'activities' => $activities
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching activity log',
        'error' => $e->getMessage()
    ]);
}
?>
