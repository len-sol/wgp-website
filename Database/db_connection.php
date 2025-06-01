<?php
$host = 'localhost:3307';
$db = 'warranty';
$user = 'root';
$password = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    error_log("Attempting to connect to database at $host");
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Test the connection
    $test = $pdo->query("SELECT 1");
    error_log("Database connection successful");
    
} catch (PDOException $e) {
    error_log("Connection Error: " . $e->getMessage());
    
    // Return JSON error for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed'
        ]);
        exit();
    }
    
    die("Database connection failed. Please try again later.");
}
?>
