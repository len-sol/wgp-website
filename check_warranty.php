<?php
header('Content-Type: application/json');
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serial = trim($_POST['serial'] ?? '');

    if (empty($serial)) {
        echo json_encode([
            'success' => false,
            'message' => 'Serial number is required'
        ]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT w.*, p.product_name 
                               FROM warranty_records w 
                               LEFT JOIN products p ON w.product_id = p.id 
                               WHERE w.serial_number = ?");
        $stmt->bind_param("s", $serial);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $warranty = $result->fetch_assoc();
            $registration_date = new DateTime($warranty['registration_date']);
            $warranty_end = clone $registration_date;
            $warranty_end->modify('+1 year'); // Assuming 1-year warranty
            $current_date = new DateTime();

            $status = $warranty_end > $current_date ? 'Active' : 'Expired';
            $days_remaining = $current_date > $warranty_end ? 0 : $current_date->diff($warranty_end)->days;

            $message = "Product: {$warranty['product_name']}\n";
            $message .= "Status: {$status}\n";
            
            if ($status === 'Active') {
                $message .= "Days Remaining: {$days_remaining} days";
            } else {
                $message .= "Warranty expired on " . $warranty_end->format('Y-m-d');
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'data' => [
                    'status' => $status,
                    'days_remaining' => $days_remaining,
                    'product_name' => $warranty['product_name'],
                    'registration_date' => $warranty['registration_date'],
                    'expiry_date' => $warranty_end->format('Y-m-d')
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No warranty record found for this serial number'
            ]);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while checking the warranty'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
