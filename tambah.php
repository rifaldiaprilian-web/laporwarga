
<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/language.php";
require_once __DIR__ . "/config/database.php";

$lang = $_SESSION['lang'] ?? 'id';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Mengambil data dari form
    $nama = trim($_POST['nama_pelapor'] ?? "");
    $nik = trim($_POST['nik'] ?? "");
    $telepon = trim($_POST['no_telepon'] ?? "");
    $kategori = trim($_POST['kategori'] ?? "");
    $judul = trim($_POST['judul'] ?? "");
    $isi = trim($_POST['isi_pengaduan'] ?? "");
    $lokasi = trim($_POST['lokasi'] ?? "");
    $tanggal = $_POST['tanggal'] ?? "";

    // Validasi kelengkapan data
    if (
        empty($nama) ||
        empty($nik) ||
        empty($telepon) ||
        empty($kategori) ||
        empty($judul) ||
        empty($isi) ||
        empty($lokasi) ||
        empty($tanggal)
    ) {

        $error = $lang === 'id'
            ? "Semua kolom wajib diisi!"
            : "All fields are required!";

    } elseif (!preg_match('/^[0-9]{16}$/', $nik)) {

        $error = $lang === 'id'
            ? "NIK harus terdiri dari 16 angka!"
            : "National ID must contain exactly 16 digits!";

    } elseif (!preg_match('/^[0-9+\-\s]{10,20}$/', $telepon)) {

        $error = $lang === 'id'
            ? "Nomor telepon tidak valid!"
            : "Invalid phone number!";

    } else {

        // Menyimpan data menggunakan prepared statement
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO pengaduan
            (nama_pelapor, nik, no_telepon, kategori,
            judul, isi_pengaduan, lokasi, tanggal, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu')"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssss",
            $nama,
            $nik,
            $telepon,
            $kategori,
            $judul,
            $isi,
            $lokasi,
            $tanggal
        );

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);
            header("Location: pengaduan.php");
            exit;

        } else {

            $error = $lang === 'id'
                ? "Gagal menyimpan data pengaduan."
                : "Failed to save complaint data.";

            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= t('tambah_pengaduan') ?> - LaporWarga
    </title>

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

        <a href="dashboard.php">
            <?= t('dashboard') ?>
        </a>

        <a href="pengaduan.php">
            <?= t('data_pengaduan') ?>
        </a>

        <a href="tambah.php" class="active">
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

            <h2><?= t('tambah_pengaduan') ?></h2>

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
                        value="tambah.php"
                    >

                    <button type="submit" class="btn-bahasa">
                        <?= $lang === 'id'
                            ? '🇬🇧 English'
                            : '🇮🇩 Indonesia' ?>
                    </button>

                </form>

                <a href="logout.php" class="logout">
                    <?= t('logout') ?>
                </a>

            </div>

        </div>

        <!-- Form Pengaduan -->
        <div class="content-box">

            <div class="form-container">

                <h3>
                    <?= $lang === 'id'
                        ? 'Form Pengaduan Masyarakat'
                        : 'Community Complaint Form' ?>
                </h3>

                <?php if (!empty($error)): ?>
                    <p style="color:red; margin-bottom:15px;">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="">

                    <div class="form-group">
                        <label>
                            <?= t('nama_pelapor') ?>
                        </label>

                        <input
                            type="text"
                            name="nama_pelapor"
                            maxlength="100"
                            value="<?= htmlspecialchars($nama ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>NIK</label>

                        <input
                            type="text"
                            name="nik"
                            maxlength="16"
                            inputmode="numeric"
                            value="<?= htmlspecialchars($nik ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Nomor Telepon'
                                : 'Phone Number' ?>
                        </label>

                        <input
                            type="text"
                            name="no_telepon"
                            maxlength="20"
                            value="<?= htmlspecialchars($telepon ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Kategori Pengaduan'
                                : 'Complaint Category' ?>
                        </label>

                        <select name="kategori" required>

                            <option value="">
                                <?= $lang === 'id'
                                    ? '-- Pilih Kategori --'
                                    : '-- Select Category --' ?>
                            </option>

                            <?php
                            $kategori_list = [
                                'Infrastruktur' => 'Infrastructure',
                                'Lingkungan' => 'Environment',
                                'Keamanan' => 'Security',
                                'Pelayanan Publik' => 'Public Services',
                                'Sosial' => 'Social',
                                'Lainnya' => 'Others'
                            ];

                            foreach ($kategori_list as $id => $en):
                            ?>

                                <option
                                    value="<?= htmlspecialchars($id) ?>"
                                    <?= ($kategori ?? '') === $id ? 'selected' : '' ?>
                                >
                                    <?= $lang === 'id' ? $id : $en ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Judul Pengaduan'
                                : 'Complaint Title' ?>
                        </label>

                        <input
                            type="text"
                            name="judul"
                            maxlength="150"
                            value="<?= htmlspecialchars($judul ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Isi Pengaduan'
                                : 'Complaint Description' ?>
                        </label>

                        <textarea
                            name="isi_pengaduan"
                            rows="5"
                            required
                        ><?= htmlspecialchars($isi ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Lokasi Kejadian'
                                : 'Incident Location' ?>
                        </label>

                        <input
                            type="text"
                            name="lokasi"
                            maxlength="255"
                            value="<?= htmlspecialchars($lokasi ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Tanggal Pengaduan'
                                : 'Complaint Date' ?>
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            value="<?= htmlspecialchars($tanggal ?: date('Y-m-d')) ?>"
                            required
                        >
                    </div>

                    <div class="form-actions">

                        <button type="submit" class="btn-simpan">
                            <?= $lang === 'id'
                                ? 'Simpan Pengaduan'
                                : 'Submit Complaint' ?>
                        </button>

                        <a href="pengaduan.php" class="btn-batal">
                            <?= t('batal') ?>
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
```