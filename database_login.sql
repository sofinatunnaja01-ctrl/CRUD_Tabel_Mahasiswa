-- Jalankan di phpMyAdmin (tab SQL) pada database db_kampus
CREATE TABLE IF NOT EXISTS users (
    id_user  INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama     VARCHAR(100) NOT NULL
);
-- Setelah itu buka http://localhost/NAMA_FOLDER/buat_admin.php sekali
-- untuk membuat akun admin (password di-hash otomatis).
