<?php
session_start();
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/sidebar.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$pemasukan_total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah) AS total FROM transaksi 
    WHERE tipe = 'pemasukan' AND user_id = $user_id
"))['total'] ?? 0;

$pengeluaran_total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah) AS total FROM transaksi 
    WHERE tipe = 'pengeluaran' AND user_id = $user_id
"))['total'] ?? 0;

$saldo = $pemasukan_total - $pengeluaran_total;

$transaksi_terakhir = mysqli_query($conn, "
    SELECT * FROM transaksi 
    WHERE user_id = $user_id 
    ORDER BY tanggal DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - MoneyTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- KONTEN -->
<div class="p-6 md:ml-64">
    <h1 class="text-3xl font-bold text-center mb-8">Dashboard Keuangan</h1>

    <div class="grid md:grid-cols-3 gap-4 text-center mb-8">
        <a href="pemasukan.php" class="bg-blue-100 p-6 rounded-xl shadow hover:bg-blue-200 transition">
            <div class="text-sm text-gray-600">Total Pemasukan</div>
            <div class="text-2xl font-bold text-blue-800">Rp <?= number_format($pemasukan_total, 0, ',', '.') ?></div>
        </a>

        <a href="pengeluaran.php" class="bg-red-100 p-6 rounded-xl shadow hover:bg-red-200 transition">
            <div class="text-sm text-gray-600">Total Pengeluaran</div>
            <div class="text-2xl font-bold text-red-800">Rp <?= number_format($pengeluaran_total, 0, ',', '.') ?></div>
        </a>

        <div class="bg-green-100 p-6 rounded-xl shadow">
            <div class="text-sm text-gray-600">Saldo</div>
            <div class="text-2xl font-bold text-green-800">Rp <?= number_format($saldo, 0, ',', '.') ?></div>
        </div>
    </div>

    <!-- Diagram Lingkaran -->
    <div class="text-center mb-8">
        <div class="w-48 h-48 mx-auto relative">
            <svg viewBox="0 0 36 36" class="w-full h-full">
                <path class="text-gray-300"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                      fill="none" stroke="currentColor" stroke-width="2"/>
                <path class="text-green-600"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                      fill="none" stroke="currentColor" stroke-width="2"
                      stroke-dasharray="100, 100"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-xl font-bold text-gray-700">100%</div>
            </div>
        </div>
        <p class="mt-2 text-gray-600 font-semibold">Rp <?= number_format($saldo, 0, ',', '.') ?> - Total Saldo</p>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Transaksi Terakhir</h2>
        <?php if (mysqli_num_rows($transaksi_terakhir) > 0): ?>
            <ul class="divide-y divide-gray-200">
                <?php while ($row = mysqli_fetch_assoc($transaksi_terakhir)): ?>
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <div class="font-semibold"><?= htmlspecialchars($row['keterangan']) ?></div>
                            <div class="text-sm text-gray-500"><?= date('d M Y', strtotime($row['tanggal'])) ?> | <?= ucfirst($row['tipe']) ?></div>
                        </div>
                        <div class="text-sm font-bold <?= $row['tipe'] == 'pemasukan' ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $row['tipe'] == 'pemasukan' ? '+' : '-' ?> Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-center text-sm text-gray-500">Belum ada transaksi.</p>
        <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-[#4169e1] text-white py-8 mt-12 ml-0 md:ml-64">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
    
    <div>
      <h4 class="font-semibold text-xl mb-3">About MoneyTracker</h4>
      <p class="text-sm leading-relaxed text-blue-100">
        Aplikasi pencatat keuangan pribadi.
      </p>
    </div>

    <div>
      <h4 class="font-semibold text-xl mb-3 uppercase">Contact</h4>
      <p class="text-sm text-blue-100">Universitas Lampung, Bandar Lampung</p>
      <p class="text-sm mt-1 text-blue-100">
        Email: <a href="mailto:info@onlibrary.com" class="underline hover:text-yellow-200 transition-colors">info@moneytracker.com</a>
      </p>
      <p class="text-sm mt-1 text-blue-100">
        Telp: <a href="tel:+62895344533797" class="underline hover:text-yellow-200 transition-colors">+62 895-344-533-797</a>
      </p>
    </div>

    <div>
      <h4 class="font-semibold text-xl mb-3 uppercase">Our Social Media</h4>
      <div class="flex justify-center md:justify-start space-x-5">
        <a href="#" class="hover:text-yellow-200 transition-colors" aria-label="Facebook">
          <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="#" class="hover:text-yellow-200 transition-colors" aria-label="Twitter">
          <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53A4.48 4.48 0 0 0 22.4 1a9 9 0 0 1-2.85 1.1 4.52 4.52 0 0 0-7.73 4.12A12.84 12.84 0 0 1 2.52 1.5a4.48 4.48 0 0 0 1.4 6 4.48 4.48 0 0 1-2.05-.57v.06a4.52 4.52 0 0 0 3.63 4.44 4.52 4.52 0 0 1-2.04.08 4.52 4.52 0 0 0 4.22 3.14A9 9 0 0 1 1 19.54a12.73 12.73 0 0 0 6.92 2.03c8.3 0 12.85-6.88 12.85-12.85 0-.2 0-.42-.01-.63A9.22 9.22 0 0 0 23 3z"/></svg>
        </a>
        <a href="#" class="hover:text-yellow-200 transition-colors" aria-label="Instagram">
          <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><circle cx="17.5" cy="6.5" r="1"/></svg>
        </a>
      </div>
    </div>
  </div>

  <hr class="my-6 border-blue-300">
  <div class="text-center text-sm italic text-blue-100">
    &copy; <?= date('Y') ?> <span class="font-semibold">MoneyTracker</span>. All rights reserved.
  </div>
</footer>

</body>
</html>
