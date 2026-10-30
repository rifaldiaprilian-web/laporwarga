<?php

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id';
}

$translations = [
    'id' => [
        'dashboard' => 'Dashboard',
        'data_pengaduan' => 'Data Pengaduan',
        'tambah_pengaduan' => 'Tambah Pengaduan',
        'logout' => 'Keluar',
        'welcome' => 'Selamat Datang',
        'total_pengaduan' => 'Total Pengaduan',
        'menunggu' => 'Menunggu',
        'diproses' => 'Sedang Diproses',
        'selesai' => 'Selesai',
        'nama_pelapor' => 'Nama Pelapor',
        'kategori' => 'Kategori',
        'judul' => 'Judul',
        'lokasi' => 'Lokasi',
        'tanggal' => 'Tanggal',
        'status' => 'Status',
        'aksi' => 'Aksi',
        'edit' => 'Edit',
        'hapus' => 'Hapus',
        'simpan' => 'Simpan',
        'batal' => 'Batal',
    ],

    'en' => [
        'dashboard' => 'Dashboard',
        'data_pengaduan' => 'Complaint Data',
        'tambah_pengaduan' => 'Add Complaint',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'total_pengaduan' => 'Total Complaints',
        'menunggu' => 'Pending',
        'diproses' => 'In Progress',
        'selesai' => 'Completed',
        'nama_pelapor' => 'Reporter Name',
        'kategori' => 'Category',
        'judul' => 'Title',
        'lokasi' => 'Location',
        'tanggal' => 'Date',
        'status' => 'Status',
        'aksi' => 'Action',
        'edit' => 'Edit',
        'hapus' => 'Delete',
        'simpan' => 'Save',
        'batal' => 'Cancel',
    ]
];

function t($key)
{
    global $translations;

    $lang = $_SESSION['lang'] ?? 'id';

    return $translations[$lang][$key] ?? $key;
}