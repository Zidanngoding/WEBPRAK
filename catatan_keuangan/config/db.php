<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'moneytracker';

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Optional: atur charset agar mendukung UTF-8
$conn->set_charset("utf8");
?>
