<?php
session_start();
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/sidebar.php');

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil total pemasukan dan pengeluaran user
$pemasukan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM transaksi WHERE tipe='pemasukan' AND user_id=$user_id"))['total'] ?? 0;
$pengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM transaksi WHERE tipe='pengeluaran' AND user_id=$user_id"))['total'] ?? 0;
$saldo = $pemasukan - $pengeluaran;

$total_transaksi = $pemasukan + $pengeluaran;
$persen_masuk = $total_transaksi > 0 ? ($pemasukan / $total_transaksi * 100) : 0;
$persen_keluar = $total_transaksi > 0 ? ($pengeluaran / $total_transaksi * 100) : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - MoneyTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="p-6 md:ml-64">
    <h1 class="text-4xl font-bold mb-6 text-center">📊 Laporan Keuangan</h1>

    <!-- Kartu Ringkasan -->
    <div class="grid md:grid-cols-3 gap-6 text-center mb-10">
        <!-- Pemasukan -->
        <div class="bg-green-50 p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="text-sm text-gray-500 font-semibold">Total Pemasukan</div>
            <div class="text-3xl font-bold text-green-700 mt-2 mb-1">Rp <?= number_format($pemasukan, 0, ',', '.') ?></div>
            <div class="text-sm text-green-700"><?= round($persen_masuk, 1) ?>% dari total transaksi</div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-red-50 p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="text-sm text-gray-500 font-semibold">Total Pengeluaran</div>
            <div class="text-3xl font-bold text-red-700 mt-2 mb-1">Rp <?= number_format($pengeluaran, 0, ',', '.') ?></div>
            <div class="text-sm text-red-700"><?= round($persen_keluar, 1) ?>% dari total transaksi</div>
        </div>

        <!-- Saldo -->
        <div class="bg-blue-50 p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="text-sm text-gray-500 font-semibold">Saldo Saat Ini</div>
            <div class="text-3xl font-bold text-blue-700 mt-2 mb-1">Rp <?= number_format($saldo, 0, ',', '.') ?></div>
            <div class="text-sm text-blue-700">
                <?= $saldo >= 0 ? 'Keuangan Sehat 👍' : 'Saldo Negatif ⚠️' ?>
            </div>
        </div>
    </div>
</div>
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>

</body>
</html>
