<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

if($_SESSION["ssjabatan"] != 'Guru') {
    header("location:../index.php");
    exit;
}

require_once "../config.php";

if(isset($_POST['simpan'])) {
    $kelas   = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $tgl     = $_POST['tgl'];
    $guru    = $_POST['guru'];
    $status  = $_POST['status'];

    // cek apakah absensi hari ini sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM tbl_absensi WHERE tgl = '$tgl' AND kelas = '$kelas' AND jurusan = '$jurusan' AND username_guru = '$guru'");
    if(mysqli_num_rows($cek) > 0) {
        header("location:absensi.php?kelas=$kelas&msg=exists");
        exit;
    }

    foreach($status as $nis => $sts) {
        mysqli_query($koneksi, "INSERT INTO tbl_absensi VALUES(null, '$tgl', '$nis', '$kelas', '$jurusan', '$guru', '$sts')");
    }

    header("location:absensi.php?kelas=$kelas&msg=saved");
    exit;
}
?>