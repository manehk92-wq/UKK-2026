
<?php
session_start();

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$pesan_error = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan_error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #007bff, #0056b3);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,.2);
        }

        .login-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>

<div class="login-card">
    <h3 class="login-title">Login Aplikasi Pelanggaran Siswa</h3>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($pesan_error) ?>
        </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control"
                   placeholder="Masukkan email"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kata Sandi</label>
            <input type="password" name="password"
                   class="form-control"
                   placeholder="Masukkan kata sandi"
                   required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Masuk
        </button>

    </form>
</div>

</body>
</html>
