<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/language.php";
require_once __DIR__ . "/config/database.php";

// Menghitung total pengaduan
$total = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM pengaduan");
$total_pengaduan = mysqli_fetch_assoc($total)['jumlah'];

// Menghitung pengaduan menunggu
$menunggu = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah FROM pengaduan WHERE status = 'Menunggu'"
);
$total_menunggu = mysqli_fetch_assoc($menunggu)['jumlah'];

// Menghitung pengaduan diproses
$diproses = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah FROM pengaduan WHERE status = 'Diproses'"
);
$total_diproses = mysqli_fetch_assoc($diproses)['jumlah'];

// Menghitung pengaduan selesai
$selesai = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah FROM pengaduan WHERE status = 'Selesai'"
);
$total_selesai = mysqli_fetch_assoc($selesai)['jumlah'];

$lang = $_SESSION['lang'] ?? 'id';

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= t('dashboard') ?> - LaporWarga</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">

        <div class="logo">LaporWarga</div>

        <p>
            <?= $lang === 'id'
                ? 'SISTEM PENGADUAN MASYARAKAT'
                : 'COMMUNITY COMPLAINT SYSTEM' ?>
        </p>

        <a href="dashboard.php" class="active">
            <?= t('dashboard') ?>
        </a>

        <a href="pengaduan.php">
            <?= t('data_pengaduan') ?>
        </a>

        <a href="tambah.php">
            <?= t('tambah_pengaduan') ?>
        </a>

        <a href="logout.php">
            <?= t('logout') ?>
        </a>

    </div>

    <!-- Main Content -->
    <div class="main">

        <!-- Topbar -->
        <div class="topbar">

            <h2><?= t('dashboard') ?></h2>

            <div class="topbar-actions">

                <form method="POST" action="bahasa.php">

                    <input
                        type="hidden"
                        name="lang"
                        value="<?= $lang === 'id' ? 'en' : 'id' ?>"
                    >

                    <input
                        type="hidden"
                        name="kembali"
                        value="dashboard.php"
                    >

                    <button type="submit" class="btn-bahasa">
                        <?= $lang === 'id' ? '🇬🇧 English' : '🇮🇩 Indonesia' ?>
                    </button>

                </form>

                <a href="logout.php" class="logout">
                    <?= t('logout') ?>
                </a>

            </div>

        </div>

        <!-- Ringkasan -->
        <h3 style="margin-bottom:20px;">
            <?= $lang === 'id'
                ? 'Ringkasan Pengaduan Masyarakat'
                : 'Community Complaint Summary' ?>
        </h3>

        <!-- Statistics Cards -->
        <div class="cards">

            <div class="card">
                <h3><?= t('total_pengaduan') ?></h3>
                <h1><?= $total_pengaduan ?></h1>
            </div>

            <div class="card">
                <h3><?= t('menunggu') ?></h3>
                <h1><?= $total_menunggu ?></h1>
            </div>

            <div class="card">
                <h3><?= t('diproses') ?></h3>
                <h1><?= $total_diproses ?></h1>
            </div>

            <div class="card">
                <h3><?= t('selesai') ?></h3>
                <h1><?= $total_selesai ?></h1>
            </div>

        </div>

        <!-- Welcome Content -->
        <div class="content-box">

            <h3>
                <?= $lang === 'id'
                    ? 'Selamat Datang di LaporWarga'
                    : 'Welcome to LaporWarga' ?>
            </h3>

            <p>
                <?= $lang === 'id'
                    ? 'LaporWarga merupakan sistem informasi manajemen data pengaduan masyarakat yang digunakan untuk mengelola laporan permasalahan di lingkungan masyarakat.'
                    : 'LaporWarga is a community complaint management information system used to manage reports of problems within the community.' ?>
            </p>

            <br>

            <p>
                <?= $lang === 'id'
                    ? 'Melalui aplikasi ini, admin dapat mengelola data pengaduan, melihat laporan masyarakat, serta memantau status penyelesaian pengaduan.'
                    : 'Through this application, administrators can manage complaint data, view community reports, and monitor the resolution status of complaints.' ?>
            </p>

        </div>

    </div>

</div>

</body>
</html>
```