<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('config/db.php');

$user_id = $_SESSION['user_id'];

$pemasukan_result = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE id_user = $user_id");
$total_pemasukan = mysqli_fetch_assoc($pemasukan_result)['total'] ?? 0;

$pengeluaran_result = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE id_user = $user_id");
$total_pengeluaran = mysqli_fetch_assoc($pengeluaran_result)['total'] ?? 0;

$riwayat = [];

$query1 = mysqli_query($conn, "SELECT id, tanggal, keterangan, jumlah, 'Pemasukan' AS tipe FROM pemasukan WHERE id_user = $user_id");
while ($row = mysqli_fetch_assoc($query1)) {
    $riwayat[] = $row;
}

$query2 = mysqli_query($conn, "SELECT id, tanggal, keterangan, jumlah, 'Pengeluaran' AS tipe FROM pengeluaran WHERE id_user = $user_id");
while ($row = mysqli_fetch_assoc($query2)) {
    $riwayat[] = $row;
}

usort($riwayat, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - MoneyTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<nav class="flex items-center justify-between bg-[#4169E1] text-white px-6 py-4">
    <button class="text-white text-xl" id="toggleSidebar">☰</button>
    <h1 class="text-lg font-semibold">MoneyTracker</h1>
    <a href="logout.php" class="hover:underline">Logout</a>
</nav>

<?php include('sidebar.php'); ?>

<div class="p-6 md:ml-64">
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white shadow rounded border-l-4 border-green-500 p-4">
            <h5 class="text-green-600 font-semibold mb-1">Total Pemasukan</h5>
            <p class="text-lg">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></p>
        </div>
        <div class="bg-white shadow rounded border-l-4 border-red-500 p-4">
            <h5 class="text-red-600 font-semibold mb-1">Total Pengeluaran</h5>
            <p class="text-lg">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
        </div>
    </div>

    <h4 class="text-xl font-semibold mb-3">Riwayat Transaksi</h4>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Keterangan</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Tipe</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($riwayat as $r) : ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= htmlspecialchars($r['tanggal']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($r['keterangan']) ?></td>
                        <td class="px-4 py-2">Rp <?= number_format($r['jumlah'], 0, ',', '.') ?></td>
                        <td class="px-4 py-2"><?= $r['tipe'] ?></td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="transaksi.php?edit=<?= $r['tipe'] ?>&id=<?= $r['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white text-sm px-2 py-1 rounded">Edit</a>
                            <a href="transaksi.php?delete=<?= $r['tipe'] ?>&id=<?= $r['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white text-sm px-2 py-1 rounded" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const btn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    btn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
    });
</script>

</body>
</html>
