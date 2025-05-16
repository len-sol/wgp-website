<?php
require_once 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $serial = $conn->real_escape_string($_POST['serial']);
    $video_path = $conn->real_escape_string($_POST['video_path']);

    $sql = "INSERT INTO warranty (name, cp, serial_num, files) VALUES ('$name', '$contact', '$serial', '$video_path')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
