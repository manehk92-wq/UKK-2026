<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pelanggaran Siswa</title>
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-success-subtle d-flex justify-content-center align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-4">
                        
                        <h3 class="text-center fw-bold mb-4 text-success">Login System</h3>

                        <!-- PESAN ERROR LOGIN -->
                        <?php if (isset($_GET['pesan'])): ?>
                            <?php if ($_GET['pesan'] == 'gagal'): ?>
                                <div class="alert alert-danger alert-dismissible fade show py-2 text-center" role="alert">
                                    <small>Email atau password salah!</small>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <form action="proses_login.php" method="POST">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email..." required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success py-2 fw-semibold">
                                    Login
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>