<?php
session_start();
include_once(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu.";
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
?>

<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<main class="bg-[#f5f7fa] min-h-screen p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500">Hi, <?= $nama; ?> 👋 Selamat datang kembali!</p>
      </div>
      <img src="../assets/1.jpg" alt="User Avatar" class="w-10 h-10 rounded-full">
    </div>

    <!-- Overview Box -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white rounded-2xl p-6 shadow mb-10">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-xl font-semibold mb-1">Halo, <?= $nama; ?> ☕</h2>
          <p class="text-sm opacity-90">Apa yang ingin kamu lakukan hari ini?</p>
        </div>
        <img src="https://i.pinimg.com/736x/ba/83/5d/ba835dbbc7d85bb2442d31dd250b76e2.jpg" alt="Bear" class="w-24 h-24">
      </div>
    </div>

    <!-- Cards Kegiatan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <a href="../pemasukan.php" class="bg-white rounded-xl p-5 shadow hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-2">
          <div class="bg-blue-100 text-blue-600 p-2 rounded-full">
            💰
          </div>
          <h3 class="text-lg font-semibold">Tambah Pemasukan</h3>
        </div>
        <p class="text-sm text-gray-500">Catat pemasukan harianmu dengan cepat.</p>
      </a>

      <a href="../pengeluaran.php" class="bg-white rounded-xl p-5 shadow hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-2">
          <div class="bg-red-100 text-red-600 p-2 rounded-full">
            🧾
          </div>
          <h3 class="text-lg font-semibold">Tambah Pengeluaran</h3>
        </div>
        <p class="text-sm text-gray-500">Lacak pengeluaran kamu dengan mudah.</p>
      </a>

      <a href="../riwayat.php" class="bg-white rounded-xl p-5 shadow hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-2">
          <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full">
            📊
          </div>
          <h3 class="text-lg font-semibold">Lihat Riwayat</h3>
        </div>
        <p class="text-sm text-gray-500">Lihat semua histori transaksi kamu.</p>
      </a>
    </div>

    <!-- Kartu Akses Cepat dan Hapus -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
      <a href="../kategori.php" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl p-5 shadow hover:shadow-lg transition">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold">Akses Cepat</h3>
            <p class="text-sm opacity-90 mt-1">Kelola kategori pemasukan & pengeluaran.</p>
          </div>
          <div class="text-2xl">⚡</div>
        </div>
      </a>

      <a href="../hapus_semua.php" onclick="return confirm('Yakin ingin menghapus semua data?')" class="bg-gradient-to-r from-pink-400 to-red-500 text-white rounded-xl p-5 shadow hover:shadow-lg transition">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold">Hapus Semua Data</h3>
            <p class="text-sm opacity-90 mt-1">Reset seluruh transaksi di database.</p>
          </div>
          <div class="text-2xl">🗑️</div>
        </div>
      </a>
    </div>
  </div>
</main>

<?php include_once(__DIR__ . '/../includes/footer.php'); ?>
