<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori - MoneyTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Halaman Kategori</h1>
    <p class="text-gray-600">Di sini kamu bisa mengelola kategori pemasukan/pengeluaran.</p>
</body>
</html>

