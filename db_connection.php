<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "warranty"; 

// Inisialisasi koneksi dan simpan di $dsn
$dsn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($dsn->connect_error) {
    die("Connection failed: " . $dsn->connect_error);
}
?>
