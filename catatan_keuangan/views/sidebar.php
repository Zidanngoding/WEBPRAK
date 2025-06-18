<!-- sidebar.php -->
<div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-[#4169E1] text-white shadow-lg z-40">
    <div class="px-6 py-4 text-2xl font-bold border-b border-blue-300">
        MoneyTracker
    </div>
    <nav class="mt-4 flex flex-col space-y-2 px-6 text-base">
        <!-- Menu Dashboard -->
        <a href="dashboard.php" class="flex items-center gap-2 px-3 py-2 rounded transition
            <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-blue-200 text-blue-900 font-semibold' : 'hover:bg-blue-600' ?>">
            🏠 Dashboard
        </a>

        <!-- Menu Transaksi -->
        <a href="transaksi.php" class="flex items-center gap-2 px-3 py-2 rounded transition
            <?= basename($_SERVER['PHP_SELF']) === 'transaksi.php' ? 'bg-blue-200 text-blue-900 font-semibold' : 'hover:bg-blue-600' ?>">
            💸 Transaksi
        </a>

        <!-- Menu Laporan -->
        <a href="riwayat.php" class="flex items-center gap-2 px-3 py-2 rounded transition
            <?= basename($_SERVER['PHP_SELF']) === 'riwayat.php' ? 'bg-blue-200 text-blue-900 font-semibold' : 'hover:bg-blue-600' ?>">
            📊 Laporan
        </a>

        <!-- Menu Ringkasan Bulanan -->
<a href="ringkasan.php" class="flex items-center gap-2 px-3 py-2 rounded transition
    <?= basename($_SERVER['PHP_SELF']) === 'ringkasan.php' ? 'bg-blue-200 text-blue-900 font-semibold' : 'hover:bg-blue-600' ?>">
    📅 Ringkasan Bulanan
</a>



        <!-- Menu Logout -->
        <a href="logout.php" class="flex items-center gap-2 px-3 py-2 mt-4 text-red-300 hover:text-red-500 transition">
            🚪 Logout
        </a>
    </nav>
</div>
