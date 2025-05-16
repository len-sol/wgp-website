<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $serial = $conn->real_escape_string($_POST['serial']);

    // Handle file upload
$filePath = '';
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileTmpPath = $_FILES['video']['tmp_name'];
        $fileName = basename($_FILES['video']['name']);
        $fileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", $fileName);
        $destPath = $uploadDir . time() . '_' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $filePath = $conn->real_escape_string($destPath);
        } else {
            die("Error uploading file.");
        }
    }

    // Insert into database
    $sql = "INSERT INTO warranty (name, cp, serial_num, files) VALUES ('$name', '$contact', '$serial', '$filePath')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.html?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}
?>
