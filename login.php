<?php
session_start();

// Periksa jika pengguna sudah login, langsung arahkan ke index.php
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header('Location: index.php');
    exit;
}

// --- PENGATURAN USERNAME & PASSWORD TERENKRIPSI ---
$USERNAME = 'Tovani';
// Password asli adalah 'ToVani' (tetap case-sensitive). Ini adalah hash-nya.
$PASSWORD_HASH = '$2y$10$/f6dSWcLavtUpL8xIWdG/OZ1.DRJDuVG8Wy/vOZDl0IUq3dxt.fkK';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_input = $_POST['username'];
    $pass_input = $_POST['password'];

    // Verifikasi: Username tidak case-sensitive, Password case-sensitive
    if (strcasecmp($user_input, $USERNAME) === 0 && password_verify($pass_input, $PASSWORD_HASH)) {
        // Jika berhasil, simpan session dan arahkan
        $_SESSION['login'] = true;
        header('Location: index.php');
        exit;
    } else {
        // Jika gagal, tampilkan error
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kos Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-house-heart-fill text-primary" style="font-size: 3rem;"></i>
                        <h3 class="card-title mt-2">Kos Dashboard</h3>
                        <p class="text-muted">Silakan login untuk melanjutkan</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Masukkan password" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>