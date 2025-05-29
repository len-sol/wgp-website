<?php
header('Content-Type: application/json');
session_start();

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

echo json_encode([
    'loggedIn' => $loggedIn,
    'username' => $loggedIn ? $_SESSION['username'] : null
]);
?>
