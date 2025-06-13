<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('config/db.php');

$user_id = $_SESSION['user_id'];

if (isset($_GET['hapus']) && isset($_GET['tipe'])) {
    $id = intval($_GET['hapus']);
    $tipe = $_GET['tipe'];
    $tabel = $tipe === 'Pemasukan' ? 'pemasukan' : 'pengeluaran';
    mysqli_query($conn, "DELETE FROM $tabel WHERE id = $id AND id_user = $user_id");
    header("Location: transaksi.php");
    exit();
}

$edit_mode = false;
$edit_data = [];

if (isset($_GET['edit']) && isset($_GET['tipe'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $tipe = $_GET['tipe'];
    $tabel = $tipe === 'Pemasukan' ? 'pemasukan' : 'pengeluaran';
    $result = mysqli_query($conn, "SELECT * FROM $tabel WHERE id = $id AND id_user = $user_id");
    $edit_data = mysqli_fetch_assoc($result);
    $edit_data['tipe'] = $tipe;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $tabel = $kategori === 'pemasukan' ? 'pemasukan' : 'pengeluaran';
        mysqli_query($conn, "UPDATE $tabel SET keterangan='$keterangan', jumlah='$jumlah', tanggal='$tanggal' WHERE id=$id AND id_user=$user_id");
    } else {
        $tabel = $kategori === 'pemasukan' ? 'pemasukan' : 'pengeluaran';
        mysqli_query($conn, "INSERT INTO $tabel (id_user, keterangan, jumlah, tanggal) VALUES ('$user_id', '$keterangan', '$jumlah', '$tanggal')");
    }

    header("Location: transaksi.php");
    exit();
}

$pemasukan = mysqli_query($conn, "SELECT id, 'Pemasukan' AS tipe, keterangan, jumlah, tanggal FROM pemasukan WHERE id_user = $user_id");
$pengeluaran = mysqli_query($conn, "SELECT id, 'Pengeluaran' AS tipe, keterangan, jumlah, tanggal FROM pengeluaran WHERE id_user = $user_id");

$transaksiGabungan = [];
while ($row = mysqli_fetch_assoc($pemasukan)) $transaksiGabungan[] = $row;
while ($row = mysqli_fetch_assoc($pengeluaran)) $transaksiGabungan[] = $row;

usort($transaksiGabungan, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi - MoneyTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<nav class="flex items-center justify-between bg-[#4169E1] px-6 py-4 text-white">
    <button id="toggleSidebar" class="text-white text-2xl">☰</button>
    <h1 class="text-xl font-bold">MoneyTracker</h1>
    <a href="logout.php" class="hover:underline">Logout</a>
</nav>

<?php include 'sidebar.php'; ?>

<div class="ml-0 lg:ml-64 p-6">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-semibold mb-4"><?= $edit_mode ? '✏️ Edit Transaksi' : '📝 Tambah Transaksi' ?></h2>
        <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block mb-1 font-medium">Kategori</label>
                <select name="kategori" class="w-full border rounded px-3 py-2" required <?= $edit_mode ? 'disabled' : '' ?>>
                    <option value="pemasukan" <?= ($edit_data['tipe'] ?? '') === 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                    <option value="pengeluaran" <?= ($edit_data['tipe'] ?? '') === 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="kategori" value="<?= strtolower($edit_data['tipe']) ?>">
                <?php endif; ?>
            </div>

            <div>
                <label class="block mb-1 font-medium">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="w-full border rounded px-3 py-2" required value="<?= $edit_data['jumlah'] ?? '' ?>">
            </div>

            <div>
                <label class="block mb-1 font-medium">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2" required value="<?= $edit_data['tanggal'] ?? '' ?>">
            </div>

            <div>
                <label class="block mb-1 font-medium">Keterangan</label>
                <input type="text" name="keterangan" class="w-full border rounded px-3 py-2" required value="<?= $edit_data['keterangan'] ?? '' ?>">
            </div>

            <div class="col-span-full text-right">
                <button type="submit" class="bg-<?= $edit_mode ? 'yellow-400' : 'blue-600' ?> text-white px-6 py-2 rounded hover:opacity-90">
                    <?= $edit_mode ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h4 class="text-xl font-semibold mb-4">📊 Riwayat Transaksi</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Keterangan</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksiGabungan as $row): ?>
                        <tr class="border-t">
                            <td class="p-3"><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="p-3">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-white text-xs <?= $row['tipe'] === 'Pemasukan' ? 'bg-green-500' : 'bg-red-500' ?>">
                                    <?= $row['tipe'] ?>
                                </span>
                            </td>
                            <td class="p-3 space-x-1">
                                <a href="?edit=<?= $row['id'] ?>&tipe=<?= $row['tipe'] ?>" class="bg-yellow-400 text-white px-2 py-1 rounded text-xs hover:opacity-90">Edit</a>
                                <a href="?hapus=<?= $row['id'] ?>&tipe=<?= $row['tipe'] ?>" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:opacity-90" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const btn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    btn?.addEventListener('click', () => {
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    });
</script>
</body>
</html>
