<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if(isset($_POST['update'])) {
    $id      = $_POST['id'];
    $nama    = trim(htmlspecialchars($_POST['nama']));
    $jurusan = $_POST['jurusan'];
    $agama   = $_POST['agama'];
    $alamat  = trim(htmlspecialchars($_POST['alamat']));

    mysqli_query($koneksi, "UPDATE tbl_user SET nama='$nama', jurusan='$jurusan', agama='$agama', alamat='$alamat' WHERE id='$id'");

    // update tbl_guru juga jika ada
    mysqli_query($koneksi, "UPDATE tbl_guru SET agama='$agama', alamat='$alamat' WHERE nama='$nama'");

    header("location:list-user.php?msg=updated");
    exit;
}