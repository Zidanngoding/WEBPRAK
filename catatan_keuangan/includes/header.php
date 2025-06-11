<?php
include(__DIR__ . '/../config/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['user']);
$user = $is_logged_in ? $_SESSION['user'] : null;
$role = $user['role'] ?? 'user';
$base_url = '/WEBPRAK-main/catatan_keuangan'; // sesuaikan jika berubah
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?? 'MoneyTracker' ?> - Aplikasi Keuangan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-900">
  <header class="bg-[#4169e1] text-white shadow-md">
    <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
      <a href="<?= $base_url ?>/views/dashboard.php" class="text-2xl font-bold">MoneyTracker</a>
      <div class="flex items-center space-x-4">
        <?php if ($is_logged_in): ?>
          <a href="<?= $base_url ?>/views/dashboard.php" class="hover:underline">Dashboard</a>
          <a href="<?= $base_url ?>/logout.php" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md transition">Logout</a>
        <?php else: ?>
          <a href="<?= $base_url ?>/login.php" class="bg-white text-[#4169e1] border border-white hover:bg-blue-100 font-semibold py-2 px-4 rounded-md transition">Login</a>
          <a href="<?= $base_url ?>/register.php" class="bg-white text-[#4169e1] border border-white hover:bg-blue-100 font-semibold py-2 px-4 rounded-md transition">Register</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>
