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
    
    // Update warranty status if unverified (delete = 1)
    $stmt = $pdo->prepare("UPDATE warranty SET `delete` = 2 WHERE id = :id AND `delete` = 1");
    $stmt->execute(['id' => $id]);
    
    if ($stmt->rowCount() > 0) {
        // Log the verification activity
        $logStmt = $pdo->prepare("INSERT INTO log (username, activity) VALUES (:username, :activity)");
        $logStmt->execute([
            'username' => 'admin',
            'activity' => "memverifikasi data"
        ]);
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Data berhasil diverifikasi']);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan atau sudah diverifikasi']);
    }
} catch (Exception $ex) {
    $pdo->rollBack();
    error_log("Verification error: " . $ex->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error during verification']);
}
