
<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/language.php";
require_once __DIR__ . "/config/database.php";

// Bahasa aktif
$lang = $_SESSION['lang'] ?? 'id';

// Mengambil seluruh data pengaduan
$query = mysqli_query(
    $conn,
    "SELECT * FROM pengaduan ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= t('data_pengaduan') ?> - LaporWarga</title>

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

            <h2><?= t('data_pengaduan') ?></h2>

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
                        value="pengaduan.php"
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

        <!-- Content -->
        <div class="content-box">

            <div class="table-header">

                <h3>
                    <?= $lang === 'id'
                        ? 'Daftar Pengaduan Masyarakat'
                        : 'Community Complaint List' ?>
                </h3>

                <a href="tambah.php" class="btn-tambah">
                    + <?= t('tambah_pengaduan') ?>
                </a>

            </div>

            <div class="table-responsive">

                <table>

                    <thead>
                        <tr>
                            <th><?= $lang === 'id' ? 'No' : 'No.' ?></th>
                            <th><?= t('nama_pelapor') ?></th>
                            <th><?= t('kategori') ?></th>
                            <th><?= t('judul') ?></th>
                            <th><?= t('lokasi') ?></th>
                            <th><?= t('tanggal') ?></th>
                            <th><?= t('status') ?></th>
                            <th><?= t('aksi') ?></th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    if ($query && mysqli_num_rows($query) > 0):

                        while ($row = mysqli_fetch_assoc($query)):
                    ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($row['nama_pelapor']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['kategori']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['judul']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['lokasi']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal']) ?>
                            </td>

                            <td>
                                <?php
                                $status = $row['status'];

                                if ($lang === 'en') {
                                    $status_terjemahan = [
                                        'Menunggu' => 'Pending',
                                        'Diproses' => 'In Progress',
                                        'Sedang Diproses' => 'In Progress',
                                        'Selesai' => 'Completed'
                                    ];

                                    echo htmlspecialchars(
                                        $status_terjemahan[$status] ?? $status
                                    );
                                } else {
                                    echo htmlspecialchars($status);
                                }
                                ?>
                            </td>

                            <td>

                                <a href="edit.php?id=<?= (int)$row['id'] ?>">
                                    <?= t('edit') ?>
                                </a>

                                |

                                <form
                                    method="POST"
                                    action="hapus.php"
                                    style="display:inline;"
                                    onsubmit="return confirm('<?= $lang === 'id'
                                        ? 'Yakin ingin menghapus pengaduan ini?'
                                        : 'Are you sure you want to delete this complaint?' ?>')"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int)$row['id'] ?>"
                                    >

                                    <button type="submit" class="btn-hapus">
                                        <?= t('hapus') ?>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php
                        endwhile;

                    else:
                    ?>

                        <tr>
                            <td colspan="8" style="text-align:center;">
                                <?= $lang === 'id'
                                    ? 'Belum ada data pengaduan.'
                                    : 'No complaint data available.' ?>
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
```