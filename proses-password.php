<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if(isset($_POST['simpan'])) {
    $curpass  = trim($_POST['curpass']);
    $newpass  = trim($_POST['newpass']);
    $confpass = trim($_POST['confpass']);

    $username = $_SESSION['ssuser'];
    $queryuser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$username' AND password = '$curpass'");

    if(mysqli_num_rows($queryuser) == 0) {
        header("location:password.php?msg=err2");
        exit;
    }

    if($newpass !== $confpass) {
        header("location:password.php?msg=err1");
        exit;
    }

    mysqli_query($koneksi, "UPDATE tbl_user SET password = '$newpass' WHERE username = '$username'");
    header("location:password.php?msg=updated");
    exit;
}

?>