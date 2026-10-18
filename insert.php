<?php
include 'auth.php';
include 'koneksi.php';

$query_jurusan = mysqli_query(
    $koneksi,
    "SELECT id_jurusan, nama_jurusan
     FROM jurusan
     ORDER BY nama_jurusan ASC"
);

if (!$query_jurusan) {
    die("Gagal mengambil data jurusan: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa Baru</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --laut-tua: #0077b6;
            --laut: #00b4d8;
            --pasir: #eaf8fb;
            --koral: #f2668b;
            --koral-hover: #e04a73;
        }

        body {
            background-color: var(--pasir);
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
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

        .form-container {
            max-width: 750px;
            margin: -10px auto 45px;
        }

        .form-card {
            background-color: white;
            padding: 34px;
            border-radius: 22px;
            border-top: 5px solid var(--koral);
            box-shadow: 0 8px 28px rgba(0, 150, 199, 0.15);
        }

        .page-title {
            color: var(--laut-tua);
            font-weight: 700;
        }

        .form-label {
            color: #35566a;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            padding: 11px 14px;
            border: 1px solid #bfe6ee;
            border-radius: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--laut);
            box-shadow: 0 0 0 0.2rem rgba(0, 180, 216, 0.18);
        }

        .btn-simpan {
            background-color: var(--koral);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(242, 102, 139, 0.35);
        }

        .btn-simpan:hover {
            background-color: var(--koral-hover);
            color: white;
        }

        .btn-reset {
            border-radius: 50px;
            padding: 10px 22px;
        }

        .btn-kembali {
            color: var(--laut-tua);
            text-decoration: none;
            font-weight: 600;
        }

        .btn-kembali:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .form-container {
                margin: -10px auto 24px;
            }

            .form-card {
                padding: 22px;
            }
        }
    </style>
</head>
<body>

    <div class="header-laut">
        <nav class="navbar">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand text-decoration-none" href="index.php">
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

    <div class="container form-container">
        <div class="form-card">

            <a href="index.php" class="btn-kembali">
                &larr; Kembali ke Daftar Mahasiswa
            </a>

            <div class="mt-4 mb-4">
                <h2 class="page-title">🐚 Tambah Mahasiswa Baru</h2>
                <p class="text-secondary mb-0">
                    Isi data mahasiswa dengan lengkap, lalu simpan ke database.
                </p>
            </div>

            <form action="proses.php?aksi=tambah" method="POST">

                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input
                        type="text"
                        class="form-control"
                        id="nim"
                        name="nim"
                        placeholder="Masukkan NIM"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <select
                        class="form-select"
                        id="jurusan"
                        name="id_jurusan"
                        required
                    >
                        <option value="">-- Pilih Jurusan --</option>

                        <?php while ($jurusan = mysqli_fetch_assoc($query_jurusan)) { ?>
                            <option value="<?= (int) $jurusan['id_jurusan']; ?>">
                                <?= htmlspecialchars($jurusan['nama_jurusan']); ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <div class="mb-4">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea
                        class="form-control"
                        id="alamat"
                        name="alamat"
                        rows="4"
                        placeholder="Masukkan alamat lengkap"
                        required
                    ></textarea>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" name="submit" class="btn btn-simpan">
                        Simpan Data
                    </button>

                    <button type="reset" class="btn btn-outline-secondary btn-reset">
                        Reset
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>
</html>