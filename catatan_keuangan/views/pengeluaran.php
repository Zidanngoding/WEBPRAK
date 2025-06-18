<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/sidebar.php');

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM transaksi WHERE user_id = $user_id AND tipe = 'pengeluaran' ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengeluaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="p-6 md:ml-64">
    <h1 class="text-2xl font-bold mb-6 text-center text-red-700">Data Pengeluaran</h1>
    <div class="mb-4 flex gap-2">
        <a href="dashboard.php" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 text-sm">&larr; Kembali ke Transaksi</a>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-red-100 text-left">
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Keterangan</th>
                    <th class="p-2 border">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border"><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td class="p-2 border"><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td class="p-2 border text-red-700">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr><td colspan="3" class="p-4 text-center text-gray-500">Tidak ada data pengeluaran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>

</body>
</html>
