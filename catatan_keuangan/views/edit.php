<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include_once(__DIR__ . '/../config/db.php');

$user_id = $_SESSION['user_id'];
$id = intval($_GET['id'] ?? 0);

// Ambil data transaksi
$query = "SELECT * FROM transaksi WHERE id = $id AND user_id = $user_id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Transaksi tidak ditemukan atau bukan milik Anda.";
    exit();
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);

    $update = "UPDATE transaksi SET tanggal='$tanggal', keterangan='$keterangan', tipe='$tipe', jumlah='$jumlah' 
               WHERE id=$id AND user_id=$user_id";
    mysqli_query($conn, $update);
    header("Location: transaksi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">Edit Transaksi</h2>
    <form method="POST">
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Keterangan</label>
            <input type="text" name="keterangan" value="<?= htmlspecialchars($data['keterangan']) ?>" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Jenis</label>
            <select name="tipe" class="w-full border rounded px-3 py-2" required>
                <option value="pemasukan" <?= $data['tipe'] == 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                <option value="pengeluaran" <?= $data['tipe'] == 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Jumlah</label>
            <input type="number" name="jumlah" value="<?= htmlspecialchars($data['jumlah']) ?>" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
<!-- INCLUDE FOOTER -->

</div>
</body>
</html>
