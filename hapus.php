
<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/config/database.php";

// Pastikan penghapusan hanya melalui POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: pengaduan.php");
    exit;
}

// Mengambil ID pengaduan
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: pengaduan.php");
    exit;
}

// Menghapus data berdasarkan ID
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM pengaduan WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: pengaduan.php");
    exit;

} else {

    mysqli_stmt_close($stmt);

    die("Gagal menghapus data pengaduan.");

}
