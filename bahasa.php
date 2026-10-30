<?php

require_once __DIR__ . "/includes/auth.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $lang = $_POST['lang'] ?? 'id';

    if (in_array($lang, ['id', 'en'], true)) {
        $_SESSION['lang'] = $lang;
    }

    $kembali = $_POST['kembali'] ?? 'dashboard.php';

    // Hanya izinkan kembali ke halaman aplikasi yang dikenal
    $halaman_diizinkan = [
        'dashboard.php',
        'pengaduan.php',
        'tambah.php',
        'edit.php'
    ];

    $kembali = basename($kembali);

    if (!in_array($kembali, $halaman_diizinkan, true)) {
        $kembali = 'dashboard.php';
    }

    // Pertahankan ID saat kembali dari halaman edit
    if ($kembali === 'edit.php' && isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        if ($id && $id > 0) {
            $kembali .= '?id=' . $id;
        } else {
            $kembali = 'pengaduan.php';
        }
    }

    header("Location: " . $kembali);
    exit;
}

header("Location: dashboard.php");
exit;