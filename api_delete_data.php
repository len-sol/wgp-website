<?php
header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Authentication required'
    ]);
    exit;
}

// Get the warranty ID from POST request
$warrantyId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 'delete'; // 'delete' or 'restore'

if ($warrantyId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid warranty ID'
    ]);
    exit;
}

try {
    // Check if the record exists
    $checkStmt = $conn->prepare("SELECT `delete`, delete_by FROM warranty WHERE id = ?");
    $checkStmt->bind_param("i", $warrantyId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Warranty record not found'
        ]);
        exit;
    }

    $record = $result->fetch_assoc();

    // For restore action, only admin can perform it
    if ($action === 'restore' && $_SESSION['role'] !== 'admin') {
        echo json_encode([
            'success' => false,
            'message' => 'Only administrators can restore records'
        ]);
        exit;
    }

    // For delete action, check if already deleted
    if ($action === 'delete' && $record['delete'] == 1) {
        echo json_encode([
            'success' => false,
            'message' => 'Record is already deleted'
        ]);
        exit;
    }

    // For restore action, check if not deleted
    if ($action === 'restore' && $record['delete'] == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Record is not deleted'
        ]);
        exit;
    }

    // Prepare the update statement based on action
    if ($action === 'delete') {
        $stmt = $conn->prepare("UPDATE warranty SET `delete` = 1, delete_by = ?, deleted_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("si", $_SESSION['username'], $warrantyId);
    } else {
        $stmt = $conn->prepare("UPDATE warranty SET `delete` = 0, delete_by = NULL, deleted_at = NULL WHERE id = ?");
        $stmt->bind_param("i", $warrantyId);
    }

    if ($stmt->execute()) {
        // Log the activity
        $activity = $action === 'delete' ? 
            "Deleted warranty record ID: $warrantyId" : 
            "Restored warranty record ID: $warrantyId";
        
        $logStmt = $conn->prepare("INSERT INTO log (username, activity) VALUES (?, ?)");
        $logStmt->bind_param("ss", $_SESSION['username'], $activity);
        $logStmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Record ' . ($action === 'delete' ? 'deleted' : 'restored') . ' successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to ' . $action . ' record'
        ]);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request'
    ]);
}
?>
