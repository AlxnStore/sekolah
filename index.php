<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: auth/login.php");
    exit;
}

require_once "config.php";

if(!isset($_SESSION["ssjabatan"]) || $_SESSION["ssjabatan"] == '') {
    session_destroy();
    header("location: auth/login.php");
    exit;
}

if($_SESSION["ssjabatan"] == 'Guru') {
    header("location: dashboard-guru.php");
    exit;
}

$title = "Dashboard - SMK PELITA";
require_once "template/header.php";
require_once "template/navbar.php";
require_once "template/sidebar.php";

$querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa");
$jmlSiswa = mysqli_num_rows($querySiswa);

$queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
$jmlGuru = mysqli_num_rows($queryGuru);

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Home</li>
            </ol>
            <div class="row mt-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Jumlah Siswa</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?= $main_url ?>siswa/siswa.php"><?= $jmlSiswa . ' Orang' ?></a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Jumlah Guru</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="<?= $main_url ?>guru/guru.php"><?= $jmlGuru . ' Orang' ?></a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once "template/footer.php"; ?>