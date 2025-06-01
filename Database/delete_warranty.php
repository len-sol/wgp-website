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

try {
    $pdo->beginTransaction();
    
    // Delete warranty data
    $stmt = $pdo->prepare("DELETE FROM warranty WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    if ($stmt->rowCount() > 0) {
        // Log the delete activity
        $logStmt = $pdo->prepare("INSERT INTO log (username, activity) VALUES (:username, :activity)");
        $logStmt->execute([
            'username' => 'admin',
            'activity' => "menghapus data"
        ]);
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("Delete error: " . $ex->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error during deletion']);
}
