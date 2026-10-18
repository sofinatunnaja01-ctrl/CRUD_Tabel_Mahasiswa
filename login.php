<?php
session_start();
include 'koneksi.php';

// Kalau sudah login, langsung ke daftar mahasiswa
if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($koneksi, "SELECT id_user, username, password, nama FROM pengguna WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama']     = $user['nama'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAK Kelautan</title>
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
            background: linear-gradient(120deg, #0096c7 0%, #00b4d8 55%, #48cae4 100%);
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 36px;
            border-radius: 22px;
            border-top: 5px solid var(--koral);
            box-shadow: 0 8px 28px rgba(0, 80, 120, 0.25);
        }
        .page-title { color: var(--laut-tua); font-weight: 700; }
        .form-label { color: #35566a; font-weight: 600; }
        .form-control {
            padding: 11px 14px;
            border: 1px solid #bfe6ee;
            border-radius: 12px;
        }
        .form-control:focus {
            border-color: var(--laut);
            box-shadow: 0 0 0 0.2rem rgba(0, 180, 216, 0.18);
        }
        .btn-login {
            background-color: var(--koral);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 11px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(242, 102, 139, 0.35);
        }
        .btn-login:hover { background-color: var(--koral-hover); color: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="page-title mb-1">🐚 Masuk</h2>
        <p class="text-secondary mb-4">Login untuk mengelola data mahasiswa.</p>

        <?php if ($error !== "") { ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error); ?></div>
        <?php } ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-login w-100">Masuk</button>
        </form>
    </div>
</body>
</html>