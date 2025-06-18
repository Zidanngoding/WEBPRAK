<?php
session_start();
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/sidebar.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil bulan & tahun dari GET, default ke sekarang
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// Total pemasukan bulan ini
$q_pemasukan = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total FROM transaksi 
    WHERE tipe = 'pemasukan' AND user_id = $user_id 
    AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
");
$total_pemasukan = mysqli_fetch_assoc($q_pemasukan)['total'] ?? 0;

// Total pengeluaran bulan ini
$q_pengeluaran = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total FROM transaksi 
    WHERE tipe = 'pengeluaran' AND user_id = $user_id 
    AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
");
$total_pengeluaran = mysqli_fetch_assoc($q_pengeluaran)['total'] ?? 0;

// Total bulan ini (selisih)
$total_bersih = $total_pemasukan - $total_pengeluaran;

// Riwayat transaksi (semua tipe)
$riwayat_query = mysqli_query($conn, "
    SELECT * FROM transaksi 
    WHERE user_id = $user_id 
    AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun
    ORDER BY tanggal DESC
");

$nama_bulan = date("F", mktime(0, 0, 0, $bulan, 10));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Bulanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="p-6 md:ml-64">
    <!-- Judul -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Ringkasan Bulanan</h1>
        <p class="text-gray-500 text-sm"><?= $nama_bulan . ' ' . $tahun ?></p>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 max-w-5xl mx-auto">
        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500 mb-1">Pemasukan Bulan Ini</p>
            <p class="text-2xl font-bold text-green-600">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500 mb-1">Pengeluaran Bulan Ini</p>
            <p class="text-2xl font-bold text-red-600">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-sm text-gray-500 mb-1">Total Bulan Ini</p>
            <p class="text-2xl font-bold text-blue-600">Rp <?= number_format($total_bersih, 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- Filter Bulan & Tahun -->
    <form method="GET" class="flex justify-center gap-2 mb-6">
        <select name="bulan" class="p-2 border border-gray-300 rounded-md">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $bulan ? 'selected' : '' ?>>
                    <?= date("F", mktime(0, 0, 0, $i, 10)) ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="tahun" class="p-2 border border-gray-300 rounded-md">
            <?php $tahun_now = date('Y'); ?>
            <?php for ($y = $tahun_now; $y >= $tahun_now - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Tampilkan
        </button>
    </form>

    <!-- Riwayat Transaksi -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-5xl mx-auto">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Riwayat Transaksi Bulan Ini</h2>
        <?php if (mysqli_num_rows($riwayat_query) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left bg-gray-100">
                            <th class="py-2 px-3">Tanggal</th>
                            <th class="py-2 px-3">Keterangan</th>
                            <th class="py-2 px-3">Tipe</th>
                            <th class="py-2 px-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($riwayat_query)): ?>
                            <tr class="border-t">
                                <td class="py-2 px-3"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                <td class="py-2 px-3"><?= htmlspecialchars($row['keterangan']) ?></td>
                                <td class="py-2 px-3 capitalize"><?= $row['tipe'] ?></td>
                                <td class="py-2 px-3 text-right font-semibold 
                                    <?= $row['tipe'] === 'pengeluaran' ? 'text-red-600' : 'text-green-600' ?>">
                                    <?= $row['tipe'] === 'pengeluaran' ? '-' : '+' ?> 
                                    Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-sm text-center text-gray-500">Tidak ada transaksi di bulan ini.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>

</body>
</html>
