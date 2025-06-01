<?php
require_once 'authenticate.php';
header('Content-Type: application/json');
require_once 'db_connection.php';

try {
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    if ($status === 'unverified') {
        // Get only unverified records (delete = 1)
        $stmt = $pdo->prepare("SELECT * FROM warranty WHERE `delete` = 1");
    } else {
        // Get only records with delete = 2
        $stmt = $pdo->prepare("SELECT * FROM warranty WHERE `delete` = 2");
    }
    
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates for each record
    foreach ($data as &$record) {
        // Convert datetime to date format for the frontend
        if ($record['warranty_start']) {
            $record['warranty_start'] = date('Y-m-d', strtotime($record['warranty_start']));
        }
        if ($record['warranty_end']) {
            $record['warranty_end'] = date('Y-m-d', strtotime($record['warranty_end']));
        }
    }
    
    // Log access to warranty data with actual username from session
    $logStmt = $pdo->prepare("INSERT INTO log (username, activity) VALUES (:username, :activity)");
    $logStmt->execute([
        'username' => $_SESSION['username'],
        'activity' => $status === 'unverified' ? 
            "Accessed warranty data (pending verification)" : 
            "Accessed warranty data"
    ]);
    
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
} catch (Exception $ex) {
    error_log("General error: " . $ex->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving warranty data'
    ]);
}
?>
