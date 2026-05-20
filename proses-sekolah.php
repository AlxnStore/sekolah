<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

// jika tombol simpan di tekan
if (isset($_POST['simpan'])) {
    // ambil value yang di tekan
    $id        = $_POST['id'];
    $nama      = trim(htmlspecialchars($_POST['nama']));
    $email     = trim(htmlspecialchars($_POST['email']));
    $status    = $_POST['status'];
    $akreditas= $_POST['akreditas'];
    $alamat    = trim(htmlspecialchars($_POST['alamat']));
    $visimisi  = trim(htmlspecialchars($_POST['visimisi']));
    $gbr       = trim(htmlspecialchars($_POST['gbrlama']));

    // cek gambar
    if ($_FILES['image']['error'] ===4) {
        $gbrsekolah = $gbr;
    } else {
        $url = 'profile-sekolah.php';
        $gbrsekolah = uploadimg($url);
        @unlink('../asset/image/' . $gbr);
    }

    // update data
    mysqli_query($koneksi, "UPDATE tbl_sekolah SET nama = '$nama', email='$email', status='$status', akreditas='$akreditas', alamat='$alamat', visimisi='$visimisi', gambar='$gbrsekolah' WHERE id = $id");
    header("location:profile-sekolah.php?msg=update");
    return;
}

?>