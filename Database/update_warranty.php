<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !is_numeric($input['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid warranty id']);
    exit;
}

$id = (int)$input['id'];
$name = $input['name'] ?? '';
$cp = $input['cp'] ?? '';
$serial_num = $input['serial_num'] ?? '';
$files = $input['files'] ?? '';
$delete = isset($input['delete']) ? (int)$input['delete'] : 2; // Default to verified (2) if not set
// Validate and format dates
$warranty_start = null;
$warranty_end = null;

if (!empty($input['warranty_start'])) {
    $warranty_start = date('Y-m-d H:i:s', strtotime($input['warranty_start']));
}
if (!empty($input['warranty_end'])) {
    $warranty_end = date('Y-m-d H:i:s', strtotime($input['warranty_end']));
}

// Validate date range
if ($warranty_start && $warranty_end && strtotime($warranty_end) < strtotime($warranty_start)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Warranty end date cannot be earlier than start date']);
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Update warranty data
    $stmt = $pdo->prepare("UPDATE warranty SET 
        name = :name,
        cp = :cp,
        serial_num = :serial_num,
        files = :files,
        warranty_start = :warranty_start,
        warranty_end = :warranty_end,
        `delete` = :delete
        WHERE id = :id");
        
    $stmt->execute([
        'name' => $name,
        'cp' => $cp,
        'serial_num' => $serial_num,
        'files' => $files,
        'warranty_start' => $warranty_start,
        'warranty_end' => $warranty_end,
        'delete' => $delete,
        'id' => $id
    ]);
    
    if ($stmt->rowCount() > 0) {
        // Log the edit activity
        $logStmt = $pdo->prepare("INSERT INTO log (username, activity) VALUES (:username, :activity)");
        // Get the actual username from session if available
        session_start();
        $username = $_SESSION['username'] ?? 'admin';
        
        $logStmt->execute([
            'username' => $username,
            'activity' => "Edited warranty record #$id"
        ]);
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Data berhasil diupdate']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan atau tidak ada perubahan']);
    }
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("Update error: " . $ex->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error during update']);
}
