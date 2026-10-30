
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "laporwarga";

// Membuat koneksi database
$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengatur karakter database
mysqli_set_charset($conn, "utf8mb4");

?>
