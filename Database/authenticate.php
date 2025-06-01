<?php
// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (!isset($_SESSION['username'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        // For AJAX requests, return JSON error
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Authentication required'
        ]);
        exit();
    } else {
        // For regular requests, redirect to login page
        header("Location: login.php");
        exit();
    }
}
?>
