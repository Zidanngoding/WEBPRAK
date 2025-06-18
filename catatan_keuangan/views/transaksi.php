<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/sidebar.php');

$user_id = $_SESSION['user_id'];

// Hapus transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $hapus_id = intval($_POST['hapus_id']);
    $query = "DELETE FROM transaksi WHERE id = $hapus_id AND user_id = $user_id";
    mysqli_query($conn, $query);
    header("Location: transaksi.php");
    exit();
}

// Tambah transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tanggal']) && isset($_POST['tipe'])) {
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);

    $query = "INSERT INTO transaksi (user_id, tanggal, keterangan, tipe, jumlah)
              VALUES ('$user_id', '$tanggal', '$keterangan', '$tipe', '$jumlah')";
    mysqli_query($conn, $query);
    header("Location: transaksi.php");
    exit();
}

$query = "SELECT * FROM transaksi WHERE user_id = $user_id ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi - MoneyTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="p-6 md:ml-64">
    <h1 class="text-4xl font-bold mb-4 text-center">Transaksi MoneyTracker</h1>

    <!-- Tombol Tambah (kiri) -->
    <div class="flex justify-start mb-6">
        <button onclick="document.getElementById('formTransaksi').classList.toggle('hidden')"
            class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
            + Tambah Transaksi
        </button>
    </div>

    <!-- Form Tambah Transaksi -->
    <div id="formTransaksi" class="hidden mb-8 flex justify-center">
        <form method="POST" class="bg-white p-6 rounded shadow w-full max-w-xl">
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Keterangan</label>
                <input type="text" name="keterangan" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Jenis</label>
                <select name="tipe" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Jumlah</label>
                <input type="number" name="jumlah" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Daftar Transaksi</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-blue-100 text-left">
                        <th class="p-2 border">Tanggal</th>
                        <th class="p-2 border">Keterangan</th>
                        <th class="p-2 border">Jenis</th>
                        <th class="p-2 border">Jumlah</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border"><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="p-2 border capitalize"><?= htmlspecialchars($row['tipe']) ?></td>
                            <td class="p-2 border">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td class="p-2 border">
                                <div class="flex gap-2">
                                    <form method="GET" action="edit.php">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</button>
                                    </form>
                                    <form method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        <input type="hidden" name="hapus_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($result) === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center p-4 text-gray-500">Belum ada transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>

</body>
</html>
