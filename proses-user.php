<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    $username = trim(htmlspecialchars($_POST['username']));
    $nama     = trim(htmlspecialchars($_POST['nama']));
    $agama    = trim(htmlspecialchars($_POST['agama']));
    $jabatan  = $_POST['jabatan'];
    $jurusan  = $_POST['jurusan']; // Diambil langsung karena input selalu ada di form
    $alamat   = trim(htmlspecialchars($_POST['alamat']));
    
    // Ambil data NIP dan Telpon jika jabatan yang dipilih adalah Guru
    $nip      = isset($_POST['nip']) ? trim(htmlspecialchars($_POST['nip'])) : '';
    $telpon   = isset($_POST['telpon']) ? trim(htmlspecialchars($_POST['telpon'])) : '';
    $pass     = '1234';

    // 1. Cek apakah username sudah digunakan sebelumnya
    $cekUsername = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$username'");
    if (mysqli_num_rows($cekUsername) > 0) {
        header("location:add-user.php?msg=cancel");
        return;
    }

    // 2. Memproses upload gambar foto profile
    $gambar = $_FILES['image']['name'];
    if ($gambar != '') {
        $url = 'add-user.php?';
        $gambar = uploadimg($url);
    } else {
        $gambar = 'default.png';
    }

    // 3. Masukkan data ke dalam tbl_user (Termasuk kolom jurusan)
    mysqli_query($koneksi, "INSERT INTO tbl_user (username, password, nama, agama, alamat, jabatan, foto, jurusan) 
        VALUES('$username', '$pass', '$nama', '$agama', '$alamat', '$jabatan', '$gambar', '$jurusan')");

    // 4. Jika jabatan adalah Guru, otomatis masukkan juga datanya ke dalam tbl_guru agar sinkron
    if ($jabatan == 'Guru') {
        $cekGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru WHERE nama = '$nama'");
        if (mysqli_num_rows($cekGuru) == 0) {
            mysqli_query($koneksi, "INSERT INTO tbl_guru (nip, nama, alamat, telpon, agama, foto) 
                VALUES('$nip', '$nama', '$alamat', '$telpon', '$agama', '$gambar')");
        }
    }

    // Alihkan kembali ke halaman form dengan pesan sukses
    header("location:add-user.php?msg=added");
    return;
}
?>