<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Authentication required'
    ]);
    exit;
}

// For admin users, provide an option to see deleted records
$showDeleted = isset($_GET['showDeleted']) && $_SESSION['role'] === 'admin';

try {
    if ($showDeleted && $_SESSION['role'] === 'admin') {
        $sql = "SELECT w.*, l.username as deleted_by_user, w.deleted_at 
                FROM warranty w 
                LEFT JOIN login l ON w.delete_by = l.username";
    } else {
        $sql = "SELECT * FROM warranty WHERE `delete` = 0";
    }

    $result = $conn->query($sql);
    $warranty_main = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // If user is admin, include delete status and metadata
            if ($_SESSION['role'] === 'admin') {
                $row['can_delete'] = true;
                if ($row['delete'] == 1) {
                    $row['deleted_info'] = [
                        'deleted_by' => $row['deleted_by_user'] ?? 'Unknown',
                        'deleted_at' => $row['deleted_at']
                    ];
                }
            } else {
                // Remove sensitive fields for non-admin users
                unset($row['delete']);
                unset($row['delete_by']);
                unset($row['deleted_at']);
            }
            $warranty_main[] = $row;
        }
    }

    // Log the data access
    $username = $_SESSION['username'];
    $activity = "Accessed warranty data" . ($showDeleted ? " (including deleted records)" : "");
    $logStmt = $conn->prepare("INSERT INTO log (username, activity) VALUES (?, ?)");
    $logStmt->bind_param("ss", $username, $activity);
    $logStmt->execute();

    echo json_encode([
        'success' => true,
        'warranty_main' => $warranty_main,
        'warranty_submissions' => [], // Placeholder if needed for future use
        'isAdmin' => $_SESSION['role'] === 'admin'
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching data'
    ]);
}
?>
