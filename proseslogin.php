<?php

session_start();

require_once "../config.php";

if(isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $result = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$username' AND password = '$password'");

    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["sslogin"]   = true;
        $_SESSION["ssuser"]    = $username;
        $_SESSION["ssjabatan"] = $row['jabatan'];
        $_SESSION["ssjurusan"] = $row['jurusan']; // ambil dari DB saat login

        if($row['jabatan'] == 'Admin') {
            header("location:../index.php");
        } else {
            header("location:../dashboard-guru.php");
        }
        exit;
    } else {
        echo "<script>
        alert('Username atau password salah!');
        document.location.href= 'login.php';
        </script>";
    }
}
?>