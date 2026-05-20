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

// selalu ambil jurusan langsung dari database, bukan dari session
// ini memastikan tidak pernah kosong meski session lama
$username    = $_SESSION["ssuser"];
$queryJurusan = mysqli_query($koneksi, "SELECT jurusan FROM tbl_user WHERE username = '$username'");
$dataJurusan  = mysqli_fetch_array($queryJurusan);
$jurusan      = $dataJurusan['jurusan'];

// update session sekalian supaya sinkron
$_SESSION["ssjurusan"] = $jurusan;

$title = "Absensi - SMK PELITA";
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : 'X';
$guru  = $_SESSION["ssuser"];
$tgl   = date('Y-m-d');

// ... sisa kode sama seperti sebelumnya
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$alert = '';
if ($msg == 'saved') {
    $alert = '<div class="alert alert-success alert-dismissible fade show">
    <i class="fa-solid fa-circle-check"></i> Absensi berhasil disimpan!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>';
}
if ($msg == 'exists') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show">
    <i class="fa-solid fa-triangle-exclamation"></i> Absensi untuk tanggal ini sudah pernah disimpan!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>';
}

$querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE kelas = '$kelas' AND jurusan = '$jurusan'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= $title ?></title>
    <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="<?= $main_url ?>index.php">SMK PELITA</a>
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= $main_url ?>auth/logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion bg-light">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link" href="<?= $main_url ?>dashboard-guru.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                        Dashboard
                    </a>
                    <hr class="mb-0">
                    <div class="sb-sidenav-menu-heading">Absensi</div>
                    <a class="nav-link <?= $kelas=='X'?'active':'' ?>" href="absensi.php?kelas=X">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                        Kelas X
                    </a>
                    <a class="nav-link <?= $kelas=='XI'?'active':'' ?>" href="absensi.php?kelas=XI">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                        Kelas XI
                    </a>
                    <a class="nav-link <?= $kelas=='XII'?'active':'' ?>" href="absensi.php?kelas=XII">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                        Kelas XII
                    </a>
                    <hr class="mb-0">
                    <div class="sb-sidenav-menu-heading">Rekap</div>
                    <a class="nav-link" href="rekap_absensi.php?kelas=<?= $kelas ?>">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                        Lihat Rekap
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer border">
                <div class="small">Logged in as:</div>
                <?= $_SESSION["ssuser"] ?>
            </div>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Absensi Kelas <?= $kelas ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard-guru.php">Home</a></li>
                    <li class="breadcrumb-item active">Absensi Kelas <?= $kelas ?></li>
                </ol>

                <?php if($msg != '') echo $alert; ?>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa-solid fa-clipboard-user me-1"></i>
                            Absensi Kelas <?= $kelas ?> — Jurusan <?= $jurusan ?>
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small"><?= date('d-m-Y') ?></span>
                            <a href="rekap_absensi.php?kelas=<?= $kelas ?>" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-list"></i> Lihat Rekap
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($querySiswa) == 0) { ?>
                            <div class="alert alert-info">
                                Tidak ada siswa di kelas <?= $kelas ?> jurusan <?= $jurusan ?>.
                            </div>
                        <?php } else { ?>
                        <form action="proses-absensi.php" method="POST">
                            <input type="hidden" name="kelas" value="<?= $kelas ?>">
                            <input type="hidden" name="jurusan" value="<?= $jurusan ?>">
                            <input type="hidden" name="tgl" value="<?= $tgl ?>">
                            <input type="hidden" name="guru" value="<?= $guru ?>">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Sakit</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while($siswa = mysqli_fetch_array($querySiswa)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $siswa['nis'] ?></td>
                                        <td><?= $siswa['nama'] ?></td>
                                        <td class="text-center"><input type="radio" name="status[<?= $siswa['nis'] ?>]" value="Hadir" checked></td>
                                        <td class="text-center"><input type="radio" name="status[<?= $siswa['nis'] ?>]" value="Sakit"></td>
                                        <td class="text-center"><input type="radio" name="status[<?= $siswa['nis'] ?>]" value="Izin"></td>
                                        <td class="text-center"><input type="radio" name="status[<?= $siswa['nis'] ?>]" value="Alpha"></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="submit" name="simpan" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Absensi
                            </button>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-4 bg-light mt-auto border">
            <div class="container-fluid px-4">
                <div class="small text-muted">Copyright &copy; SMK PELITA <?= date('Y') ?></div>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $main_url ?>asset/sb-admin/js/scripts.js"></script>
</body>
</html>