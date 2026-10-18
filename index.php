<?php
include 'auth.php';
include 'koneksi.php';

// Tampilkan 1 baris per NIM (ambil id terkecil) agar data dobel tidak muncul
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM mahasiswa
     WHERE id IN (SELECT MIN(id) FROM mahasiswa GROUP BY nim)
     ORDER BY id DESC"
);

if (!$query) {
    die("Gagal mengambil data mahasiswa: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa Kelautan</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --laut-tua: #0077b6;
            --laut: #00b4d8;
            --laut-muda: #90e0ef;
            --pasir: #eaf8fb;
            --koral: #f2668b;
            --koral-hover: #e04a73;
            --koral-muda: #ffe4ec;
        }

        body {
            background-color: var(--pasir);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        /* Header laut + ombak */
        .header-laut {
            position: relative;
            background: linear-gradient(120deg, #0096c7 0%, #00b4d8 55%, #48cae4 100%);
            padding-bottom: 55px;
        }

        .navbar {
            background: transparent;
            padding: 18px 0;
        }

        .navbar-brand {
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .navbar-brand:hover {
            color: #fff0f5;
        }

        .ombak {
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 100%;
            height: 45px;
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 80' preserveAspectRatio='none'><path d='M0,40 C240,80 480,0 720,40 C960,80 1200,0 1440,40 L1440,80 L0,80 Z' fill='%23eaf8fb'/></svg>") no-repeat;
            background-size: 100% 100%;
        }

        .main-container {
            max-width: 1150px;
            margin: -10px auto 45px;
        }

        .content-card {
            background-color: white;
            border-radius: 22px;
            padding: 30px;
            border-top: 5px solid var(--koral);
            box-shadow: 0 8px 28px rgba(0, 150, 199, 0.15);
        }

        .page-title {
            color: var(--laut-tua);
            font-weight: 700;
        }

        .page-description {
            color: #6b8794;
        }

        .table thead th {
            background: linear-gradient(120deg, #0096c7, #00b4d8);
            color: white;
            font-weight: 600;
            padding: 14px;
            white-space: nowrap;
            border-color: #0096c7;
        }

        .table tbody td {
            padding: 13px;
            vertical-align: middle;
            border-color: #d6eef4;
        }

        .table tbody tr:hover td {
            background-color: #fff5f8;
        }

        .badge-jurusan {
            background-color: var(--koral-muda);
            color: #c93d68;
            font-weight: 600;
            white-space: normal;
            text-align: left;
        }

        .btn-tambah {
            background-color: var(--koral);
            color: white;
            border-radius: 50px;
            padding: 10px 22px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(242, 102, 139, 0.35);
        }

        .btn-tambah:hover {
            background-color: var(--koral-hover);
            color: white;
        }

        .btn-aksi {
            border-radius: 50px;
            padding: 4px 14px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-edit {
            background-color: var(--laut-muda);
            color: var(--laut-tua);
            border: none;
        }

        .btn-edit:hover {
            background-color: var(--laut);
            color: white;
        }

        .btn-hapus {
            background-color: var(--koral-muda);
            color: #c93d68;
            border: none;
        }

        .btn-hapus:hover {
            background-color: var(--koral);
            color: white;
        }

        .footer-text {
            color: #7a9aa8;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="header-laut">
        <nav class="navbar">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="index.php">
                    🐚 SIMAK KELAUTAN
                </a>
                <span class="d-flex align-items-center gap-3">
                    <span class="text-white small">Halo, <?= htmlspecialchars($_SESSION['nama']); ?></span>
                    <a href="logout.php" class="btn btn-sm btn-light rounded-pill px-3">Keluar</a>
                </span>
            </div>
        </nav>
        <div class="ombak"></div>
    </div>

    <div class="container main-container">
        <div class="content-card">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="page-title mb-2">🌊 Daftar Mahasiswa Kelautan</h2>
                    <p class="page-description mb-0">
                        Data seluruh mahasiswa yang terdaftar pada setiap program studi.
                    </p>
                </div>

                <a href="insert.php" class="btn btn-tambah">
                    + Tambah Mahasiswa
                </a>
            </div>

            <?php
            $pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
            $daftar_pesan = [
                'tambah' => 'Data mahasiswa berhasil ditambahkan.',
                'update' => 'Data mahasiswa berhasil diperbarui.',
                'hapus'  => 'Data mahasiswa berhasil dihapus.',
            ];
            if (isset($daftar_pesan[$pesan])) {
            ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $daftar_pesan[$pesan]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Jurusan</th>
                            <th>Alamat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;

                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['nim']); ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($data['nama']); ?>
                                </td>

                                <td>
                                    <span class="badge badge-jurusan rounded-pill px-3 py-2">
                                        <?= htmlspecialchars($data['jurusan']); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['alamat']); ?>
                                </td>

                                <td class="text-center text-nowrap">
                                    <a href="edit.php?id=<?= (int) $data['id']; ?>"
                                       class="btn btn-aksi btn-edit">Edit</a>

                                    <form action="proses.php?aksi=hapus" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus data <?= htmlspecialchars(addslashes($data['nama']), ENT_QUOTES); ?>? Data yang dihapus tidak bisa dikembalikan.');">
                                        <input type="hidden" name="id" value="<?= (int) $data['id']; ?>">
                                        <button type="submit" class="btn btn-aksi btn-hapus">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="fw-semibold mb-1">Belum ada data mahasiswa</p>
                                    <span class="text-secondary">
                                        Klik tombol Tambah Mahasiswa untuk memasukkan data baru.
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="footer-text">
                    🐚 Sistem Informasi Mahasiswa Kelautan
                </span>
                <span class="footer-text">
                    Total data: <?= mysqli_num_rows($query); ?> mahasiswa
                </span>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>