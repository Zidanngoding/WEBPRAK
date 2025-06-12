<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('config/db.php');

$user_id = $_SESSION['user_id'];

// Total Pemasukan
$pemasukan_result = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE id_user = $user_id");
$total_pemasukan = mysqli_fetch_assoc($pemasukan_result)['total'] ?? 0;

// Total Pengeluaran
$pengeluaran_result = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pengeluaran WHERE id_user = $user_id");
$total_pengeluaran = mysqli_fetch_assoc($pengeluaran_result)['total'] ?? 0;

// Riwayat transaksi
$riwayat = [];

// Ambil pemasukan
$query1 = mysqli_query($conn, "SELECT id, tanggal, keterangan, jumlah, 'Pemasukan' AS tipe FROM pemasukan WHERE id_user = $user_id");
while ($row = mysqli_fetch_assoc($query1)) {
    $riwayat[] = $row;
}

// Ambil pengeluaran
$query2 = mysqli_query($conn, "SELECT id, tanggal, keterangan, jumlah, 'Pengeluaran' AS tipe FROM pengeluaran WHERE id_user = $user_id");
while ($row = mysqli_fetch_assoc($query2)) {
    $riwayat[] = $row;
}

// Urutkan berdasarkan tanggal DESC
usort($riwayat, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - MoneyTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .navbar {
            background-color: #4169E1;
        }
        .navbar-brand, .nav-link, .text-white {
            color: #fff !important;
        }
        .card-title {
            font-weight: bold;
        }
        .card {
            border-left: 5px solid #4169E1;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #4169E1;
            padding: 1rem;
            display: none;
            z-index: 999;
        }

        #sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
        }

        #sidebar a:hover {
            background-color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-3">
    <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
    <a class="navbar-brand" href="#">MoneyTracker</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a href="logout.php" class="nav-link text-white">Logout</a></li>
        </ul>
    </div>
</nav>

<?php include('sidebar.php'); ?>

<div class="container mt-4">
    <h2 class="mb-4">Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="card-title text-success">Total Pemasukan</h5>
                <p class="card-text">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="card-title text-danger">Total Pengeluaran</h5>
                <p class="card-text">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <h4>Riwayat Transaksi</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
                <th>Tipe</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($riwayat as $r) : ?>
                <tr>
                    <td><?= htmlspecialchars($r['tanggal']) ?></td>
                    <td><?= htmlspecialchars($r['keterangan']) ?></td>
                    <td>Rp <?= number_format($r['jumlah'], 0, ',', '.') ?></td>
                    <td><?= $r['tipe'] ?></td>
                    <td>
                        <a href="transaksi.php?edit=<?= $r['tipe'] ?>&id=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="transaksi.php?delete=<?= $r['tipe'] ?>&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    const btn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    btn.addEventListener('click', () => {
        sidebar.style.display = sidebar.style.display === 'block' ? 'none' : 'block';
    });
</script>

</body>
</html>
