<?php
include 'auth.php';
include 'koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

/* ---------- Fungsi bantu: ambil nama jurusan dari id_jurusan ---------- */
function ambilNamaJurusan($koneksi, $id_jurusan)
{
    $stmt = mysqli_prepare($koneksi, "SELECT nama_jurusan FROM jurusan WHERE id_jurusan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_jurusan);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $row   = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);

    return $row ? $row['nama_jurusan'] : null;
}

/* ============================ TAMBAH ============================ */
if ($aksi == 'tambah') {

    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);

    // Cegah NIM ganda
    $cek_nim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '$nim'");
    if ($cek_nim && mysqli_num_rows($cek_nim) > 0) {
        die("NIM sudah terdaftar. <a href='javascript:history.back()'>Kembali</a>");
    }

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $id_jurusan = isset($_POST['id_jurusan']) ? (int) $_POST['id_jurusan'] : 0;

    // Periksa apakah jurusan tersedia
    $cek_jurusan = mysqli_query(
        $koneksi,
        "SELECT nama_jurusan FROM jurusan WHERE id_jurusan = $id_jurusan"
    );

    if (!$cek_jurusan || mysqli_num_rows($cek_jurusan) == 0) {
        die("Jurusan tidak ditemukan. Silakan kembali dan pilih jurusan.");
    }

    $data_jurusan = mysqli_fetch_assoc($cek_jurusan);
    $jurusan = mysqli_real_escape_string($koneksi, $data_jurusan['nama_jurusan']);

    // Simpan data mahasiswa
    $query = mysqli_query(
        $koneksi,
        "INSERT INTO mahasiswa (nim, nama, jurusan, id_jurusan, alamat)
         VALUES ('$nim', '$nama', '$jurusan', $id_jurusan, '$alamat')"
    );

    if ($query) {
        header("Location: index.php?pesan=tambah");
        exit();
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }

/* ============================ UPDATE ============================ */
} elseif ($aksi == 'update') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }

    $id         = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nim        = trim($_POST['nim']);
    $nama       = trim($_POST['nama']);
    $alamat     = trim($_POST['alamat']);
    $id_jurusan = isset($_POST['id_jurusan']) ? (int) $_POST['id_jurusan'] : 0;

    if ($id <= 0 || $nim === '' || $nama === '' || $alamat === '') {
        die("Data tidak lengkap. Silakan kembali dan isi semua kolom.");
    }

    // Cegah NIM ganda (selain milik data ini sendiri)
    $stmt_cek = mysqli_prepare($koneksi, "SELECT id FROM mahasiswa WHERE nim = ? AND id <> ?");
    mysqli_stmt_bind_param($stmt_cek, "si", $nim, $id);
    mysqli_stmt_execute($stmt_cek);
    mysqli_stmt_store_result($stmt_cek);
    if (mysqli_stmt_num_rows($stmt_cek) > 0) {
        die("NIM sudah dipakai mahasiswa lain. <a href='javascript:history.back()'>Kembali</a>");
    }
    mysqli_stmt_close($stmt_cek);

    // Ambil nama jurusan (kolom 'jurusan' di tabel mahasiswa menyimpan nama)
    $jurusan = ambilNamaJurusan($koneksi, $id_jurusan);

    if ($jurusan === null) {
        die("Jurusan tidak ditemukan. Silakan kembali dan pilih jurusan.");
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE mahasiswa
         SET nim = ?, nama = ?, jurusan = ?, id_jurusan = ?, alamat = ?
         WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "sssisi", $nim, $nama, $jurusan, $id_jurusan, $alamat, $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: index.php?pesan=update");
        exit();
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }

/* ============================ HAPUS ============================ */
} elseif ($aksi == 'hapus') {

    // Hapus hanya lewat POST agar tidak terhapus tanpa sengaja lewat URL
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id <= 0) {
        die("ID mahasiswa tidak valid.");
    }

    $stmt = mysqli_prepare($koneksi, "DELETE FROM mahasiswa WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: index.php?pesan=hapus");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }

} else {
    header("Location: index.php");
    exit();
}
?>