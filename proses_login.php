<?php

session_start();

include "config/koneksi.php";

$email = trim($_POST['email']);
$password = $_POST['password'];

$query = "SELECT
            id,
            name,
            email,
            password,
            role
          FROM users
          WHERE email = ?
          LIMIT 1";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    header("Location: dashboard.php");
    exit;

} else {

    header("Location: login.php?error=1");
    exit;

}