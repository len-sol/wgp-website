<?php
require_once 'db_config.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM warranty";
$result = $conn->query($sql);

$warranty_main = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $warranty_main[] = $row;
    }
}

echo json_encode([
    'warranty_main' => $warranty_main,
    'warranty_submissions' => [] // Placeholder if needed for future use
]);
?>
