
<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/language.php";
require_once __DIR__ . "/config/database.php";

$lang = $_SESSION['lang'] ?? 'id';
$error = "";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Mengambil ID pengaduan
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    die($lang === 'id'
        ? "ID pengaduan tidak valid."
        : "Invalid complaint ID.");
}

// Mengambil data berdasarkan ID
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM pengaduan WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die($lang === 'id'
        ? "Data pengaduan tidak ditemukan."
        : "Complaint data not found.");
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST['nama_pelapor'] ?? "");
    $nik = trim($_POST['nik'] ?? "");
    $telepon = trim($_POST['no_telepon'] ?? "");
    $kategori = trim($_POST['kategori'] ?? "");
    $judul = trim($_POST['judul'] ?? "");
    $isi = trim($_POST['isi_pengaduan'] ?? "");
    $lokasi = trim($_POST['lokasi'] ?? "");
    $tanggal = $_POST['tanggal'] ?? "";
    $status = $_POST['status'] ?? "";

    // Mempertahankan data yang diinput jika validasi gagal
    $data = array_merge($data, [
        'nama_pelapor' => $nama,
        'nik' => $nik,
        'no_telepon' => $telepon,
        'kategori' => $kategori,
        'judul' => $judul,
        'isi_pengaduan' => $isi,
        'lokasi' => $lokasi,
        'tanggal' => $tanggal,
        'status' => $status
    ]);

    $kategoriValid = [
        'Infrastruktur',
        'Lingkungan',
        'Keamanan',
        'Pelayanan Publik',
        'Sosial',
        'Lainnya'
    ];

    $statusValid = [
        'Menunggu',
        'Sedang Diproses',
        'Selesai'
    ];

    if (
        empty($nama) ||
        empty($nik) ||
        empty($telepon) ||
        empty($kategori) ||
        empty($judul) ||
        empty($isi) ||
        empty($lokasi) ||
        empty($tanggal) ||
        empty($status)
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

    } elseif (
        !in_array($kategori, $kategoriValid, true) ||
        !in_array($status, $statusValid, true)
    ) {

        $error = $lang === 'id'
            ? "Kategori atau status tidak valid!"
            : "Invalid category or status!";

    } elseif (
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) ||
        !checkdate(
            (int)substr($tanggal, 5, 2),
            (int)substr($tanggal, 8, 2),
            (int)substr($tanggal, 0, 4)
        )
    ) {

        $error = $lang === 'id'
            ? "Tanggal pengaduan tidak valid!"
            : "Invalid complaint date!";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE pengaduan SET
                nama_pelapor = ?,
                nik = ?,
                no_telepon = ?,
                kategori = ?,
                judul = ?,
                isi_pengaduan = ?,
                lokasi = ?,
                tanggal = ?,
                status = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssi",
            $nama,
            $nik,
            $telepon,
            $kategori,
            $judul,
            $isi,
            $lokasi,
            $tanggal,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);

            header("Location: pengaduan.php");
            exit;

        } else {

            $error = $lang === 'id'
                ? "Gagal memperbarui data pengaduan."
                : "Failed to update complaint data.";

            mysqli_stmt_close($stmt);
        }
    }
}

// Terjemahan kategori
$kategoriList = [
    'Infrastruktur' => 'Infrastructure',
    'Lingkungan' => 'Environment',
    'Keamanan' => 'Security',
    'Pelayanan Publik' => 'Public Services',
    'Sosial' => 'Social',
    'Lainnya' => 'Others'
];

// Terjemahan status
$statusList = [
    'Menunggu' => 'Pending',
    'Sedang Diproses' => 'In Progress',
    'Selesai' => 'Completed'
];

?>

<!DOCTYPE html>
<html lang="<?= e($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $lang === 'id' ? 'Edit Pengaduan' : 'Edit Complaint' ?> - LaporWarga
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

        <a href="pengaduan.php" class="active">
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

            <h2>
                <?= $lang === 'id' ? 'Edit Pengaduan' : 'Edit Complaint' ?>
            </h2>

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
                        value="edit.php"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$id ?>"
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

        <!-- Form Edit -->
        <div class="content-box">

            <div class="form-container">

                <h3>
                    <?= $lang === 'id'
                        ? 'Form Edit Pengaduan'
                        : 'Edit Complaint Form' ?>
                </h3>

                <?php if (!empty($error)): ?>
                    <p style="color:red; margin-bottom:15px;">
                        <?= e($error) ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$id ?>"
                    >

                    <div class="form-group">
                        <label>
                            <?= t('nama_pelapor') ?>
                        </label>

                        <input
                            type="text"
                            name="nama_pelapor"
                            maxlength="100"
                            value="<?= e($data['nama_pelapor']) ?>"
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
                            value="<?= e($data['nik']) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id' ? 'Nomor Telepon' : 'Phone Number' ?>
                        </label>

                        <input
                            type="text"
                            name="no_telepon"
                            maxlength="20"
                            value="<?= e($data['no_telepon']) ?>"
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

                            <?php foreach ($kategoriList as $item => $english): ?>

                                <option
                                    value="<?= e($item) ?>"
                                    <?= $data['kategori'] === $item ? 'selected' : '' ?>
                                >
                                    <?= $lang === 'id' ? e($item) : e($english) ?>
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
                            value="<?= e($data['judul']) ?>"
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
                        ><?= e($data['isi_pengaduan']) ?></textarea>
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
                            value="<?= e($data['lokasi']) ?>"
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
                            value="<?= e($data['tanggal']) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <?= $lang === 'id'
                                ? 'Status Pengaduan'
                                : 'Complaint Status' ?>
                        </label>

                        <select name="status" required>

                            <?php foreach ($statusList as $item => $english): ?>

                                <option
                                    value="<?= e($item) ?>"
                                    <?= $data['status'] === $item ? 'selected' : '' ?>
                                >
                                    <?= $lang === 'id' ? e($item) : e($english) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="form-actions">

                        <button type="submit" class="btn-simpan">
                            <?= $lang === 'id'
                                ? 'Simpan Perubahan'
                                : 'Save Changes' ?>
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