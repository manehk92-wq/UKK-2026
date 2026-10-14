<?php

session_start();

if (isset($_SESSION['user_id'])) {

    header("Location: ../dashboard.php");
    exit;

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Sekolah</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body class="login-page">

    <div class="login-box">

        <h1>Login</h1>

        <p>Sistem Informasi Sekolah</p>

        <?php if (isset($_GET['error'])): ?>

            <div class="alert">
                Email atau password salah.
            </div>

        <?php endif; ?>


        <form action="proses_login.php" method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >


            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
            >


            <button type="submit">
                Login
            </button>

        </form>

    </div>

</body>

</html>