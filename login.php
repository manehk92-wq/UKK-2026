<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Login - Pelanggaran Siswa</title>
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            padding: 2rem;
            width: 100%;
            max-width: 420px;
        }
        .login-title {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #212529;
        }
        .btn-login {
            padding: 0.6rem 1rem;
            font-weight: 500;
        }
        .alert-error {
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1 class="login-title">Login Aplikasi Pelanggaran Siswa</h1>

    <?php
    // Cegah session_start() ganda
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['pesan_error'])) {
        echo '<div class="alert alert-danger alert-error">' . $_SESSION['pesan_error'] . '</div>';
        unset($_SESSION['pesan_error']);
    }
    ?>

    <form action="proses_login.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" required>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan kata sandi" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-login">Masuk</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>
