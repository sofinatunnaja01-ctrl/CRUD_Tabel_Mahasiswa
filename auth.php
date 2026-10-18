<?php
// Dipasang di setiap halaman yang harus login dulu
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
?>
