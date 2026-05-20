<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location: auth/login.php");
    exit;
}

if(!isset($_SESSION["ssjabatan"]) || $_SESSION["ssjabatan"] == '') {
    session_destroy();
    header("location: auth/login.php");
    exit;
}

if($_SESSION["ssjabatan"] != 'Guru') {
    header("location: index.php");
    exit;
}

require_once "config.php";

$title = "Dashboard Guru - SMK PELITA";
require_once "template/header.php";
require_once "template/navbar.php";

// Ambil data guru dari session
$namaGuru = $_SESSION["ssuser"];
$jurusan  = $_SESSION["ssjurusan"];

// Query data lengkap user/guru dari tbl_user
$queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '" . mysqli_real_escape_string($koneksi, $namaGuru) . "'");
$dataUser  = mysqli_fetch_array($queryUser);

// Query jadwal pelajaran yang diampu guru ini (berdasarkan nama di tbl_pelajaran)
$queryMapel = mysqli_query($koneksi, "SELECT * FROM tbl_pelajaran WHERE guru = '" . mysqli_real_escape_string($koneksi, $dataUser['nama']) . "' ORDER BY jurusan");

// Query daftar siswa berdasarkan jurusan guru
$querySiswaX   = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE jurusan = '" . mysqli_real_escape_string($koneksi, $jurusan) . "' AND kelas = 'X'");
$querySiswaXI  = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE jurusan = '" . mysqli_real_escape_string($koneksi, $jurusan) . "' AND kelas = 'XI'");
$querySiswaXII = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE jurusan = '" . mysqli_real_escape_string($koneksi, $jurusan) . "' AND kelas = 'XII'");

$jmlSiswaX   = mysqli_num_rows($querySiswaX);
$jmlSiswaXI  = mysqli_num_rows($querySiswaXI);
$jmlSiswaXII = mysqli_num_rows($querySiswaXII);
$totalSiswa  = $jmlSiswaX + $jmlSiswaXI + $jmlSiswaXII;

?>

<body class="sb-nav-fixed">
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion bg-light" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link active" href="<?= $main_url ?>dashboard-guru.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                        Dashboard
                    </a>
                    <hr class="mb-0">
                    <div class="sb-sidenav-menu-heading">Absensi</div>
                    <a class="nav-link" href="<?= $main_url ?>absensi/absensi.php?kelas=X">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                        Kelas X
                    </a>
                    <a class="nav-link" href="<?= $main_url ?>absensi/absensi.php?kelas=XI">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                        Kelas XI
                    </a>
                    <a class="nav-link" href="<?= $main_url ?>absensi/absensi.php?kelas=XII">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-user"></i></div>
                        Kelas XII
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer border">
                <div class="small">Logged in as:</div>
                <?= htmlspecialchars($_SESSION["ssuser"]) ?>
            </div>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard Guru</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Home</li>
                </ol>

                <!-- ===== KARTU PROFIL GURU ===== -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="<?= $main_url ?>asset/image/<?= htmlspecialchars($dataUser['foto']) ?>"
                                     class="rounded-circle border"
                                     width="80" height="80"
                                     style="object-fit:cover;"
                                     alt="foto guru">
                            </div>
                            <div class="col">
                                <h4 class="mb-1"><?= htmlspecialchars($dataUser['nama']) ?></h4>
                                <span class="badge bg-primary me-1"><?= htmlspecialchars($dataUser['jabatan']) ?></span>
                                <span class="badge bg-secondary"><?= htmlspecialchars($jurusan) ?></span>
                                <p class="text-muted mb-0 mt-1 small">
                                    <i class="fa-solid fa-location-dot me-1"></i><?= htmlspecialchars($dataUser['alamat']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== STATISTIK SISWA ===== -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-white-50">Total Siswa</div>
                                        <div class="fs-4 fw-bold"><?= $totalSiswa ?> Orang</div>
                                    </div>
                                    <i class="fa-solid fa-users fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between py-1">
                                <small>Jurusan <?= htmlspecialchars($jurusan) ?></small>
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-white-50">Kelas X</div>
                                        <div class="fs-4 fw-bold"><?= $jmlSiswaX ?> Orang</div>
                                    </div>
                                    <i class="fa-solid fa-chalkboard-user fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between py-1">
                                <small>Siswa aktif</small>
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-3">
                            <div class="card-body pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-white-50">Kelas XI</div>
                                        <div class="fs-4 fw-bold"><?= $jmlSiswaXI ?> Orang</div>
                                    </div>
                                    <i class="fa-solid fa-chalkboard-user fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between py-1">
                                <small>Siswa aktif</small>
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-white-50">Kelas XII</div>
                                        <div class="fs-4 fw-bold"><?= $jmlSiswaXII ?> Orang</div>
                                    </div>
                                    <i class="fa-solid fa-chalkboard-user fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between py-1">
                                <small>Siswa aktif</small>
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- ===== JADWAL MATA PELAJARAN ===== -->
                    <div class="col-lg-5 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fa-solid fa-book me-2"></i> Mata Pelajaran yang Diampu
                            </div>
                            <div class="card-body p-0">
                                <?php if (mysqli_num_rows($queryMapel) == 0): ?>
                                    <div class="p-4 text-center text-muted">
                                        <i class="fa-solid fa-circle-info fa-2x mb-2 d-block"></i>
                                        Belum ada mata pelajaran yang ditugaskan.<br>
                                        <small>Hubungi admin untuk penugasan mata pelajaran.</small>
                                    </div>
                                <?php else: ?>
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">No</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Jurusan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            // Reset pointer karena sudah dipakai di atas
                                            mysqli_data_seek($queryMapel, 0);
                                            while ($mapel = mysqli_fetch_array($queryMapel)):
                                            ?>
                                            <tr>
                                                <td class="ps-3"><?= $no++ ?></td>
                                                <td>
                                                    <i class="fa-solid fa-circle-dot text-primary me-1 fa-xs"></i>
                                                    <?= htmlspecialchars($mapel['pelajaran']) ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        <?= htmlspecialchars($mapel['jurusan']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ===== DAFTAR SISWA (TABS PER KELAS) ===== -->
                    <div class="col-lg-7 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fa-solid fa-users me-2"></i> Daftar Siswa — Jurusan <?= htmlspecialchars($jurusan) ?>
                            </div>
                            <div class="card-body">
                                <!-- Tab navigation -->
                                <ul class="nav nav-tabs mb-3" id="tabSiswa" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tab-x" data-bs-toggle="tab" data-bs-target="#kelas-x" type="button" role="tab">
                                            Kelas X <span class="badge bg-info ms-1"><?= $jmlSiswaX ?></span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-xi" data-bs-toggle="tab" data-bs-target="#kelas-xi" type="button" role="tab">
                                            Kelas XI <span class="badge bg-warning ms-1"><?= $jmlSiswaXI ?></span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-xii" data-bs-toggle="tab" data-bs-target="#kelas-xii" type="button" role="tab">
                                            Kelas XII <span class="badge bg-success ms-1"><?= $jmlSiswaXII ?></span>
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="tabSiswaContent">
                                    <!-- Kelas X -->
                                    <div class="tab-pane fade show active" id="kelas-x" role="tabpanel">
                                        <?php if ($jmlSiswaX == 0): ?>
                                            <p class="text-muted text-center py-3">Belum ada siswa di kelas X jurusan ini.</p>
                                        <?php else: ?>
                                            <div class="table-responsive" style="max-height:280px; overflow-y:auto;">
                                                <table class="table table-sm table-hover">
                                                    <thead class="table-light sticky-top">
                                                        <tr><th>No</th><th>NIS</th><th>Nama Siswa</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no=1; mysqli_data_seek($querySiswaX, 0); while($s = mysqli_fetch_array($querySiswaX)): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><code><?= htmlspecialchars($s['nis']) ?></code></td>
                                                            <td><?= htmlspecialchars($s['nama']) ?></td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Kelas XI -->
                                    <div class="tab-pane fade" id="kelas-xi" role="tabpanel">
                                        <?php if ($jmlSiswaXI == 0): ?>
                                            <p class="text-muted text-center py-3">Belum ada siswa di kelas XI jurusan ini.</p>
                                        <?php else: ?>
                                            <div class="table-responsive" style="max-height:280px; overflow-y:auto;">
                                                <table class="table table-sm table-hover">
                                                    <thead class="table-light sticky-top">
                                                        <tr><th>No</th><th>NIS</th><th>Nama Siswa</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no=1; mysqli_data_seek($querySiswaXI, 0); while($s = mysqli_fetch_array($querySiswaXI)): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><code><?= htmlspecialchars($s['nis']) ?></code></td>
                                                            <td><?= htmlspecialchars($s['nama']) ?></td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Kelas XII -->
                                    <div class="tab-pane fade" id="kelas-xii" role="tabpanel">
                                        <?php if ($jmlSiswaXII == 0): ?>
                                            <p class="text-muted text-center py-3">Belum ada siswa di kelas XII jurusan ini.</p>
                                        <?php else: ?>
                                            <div class="table-responsive" style="max-height:280px; overflow-y:auto;">
                                                <table class="table table-sm table-hover">
                                                    <thead class="table-light sticky-top">
                                                        <tr><th>No</th><th>NIS</th><th>Nama Siswa</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no=1; mysqli_data_seek($querySiswaXII, 0); while($s = mysqli_fetch_array($querySiswaXII)): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><code><?= htmlspecialchars($s['nis']) ?></code></td>
                                                            <td><?= htmlspecialchars($s['nama']) ?></td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div><!-- end tab-content -->

                                <!-- Shortcut absensi -->
                                <div class="mt-3 d-flex gap-2">
                                    <a href="<?= $main_url ?>absensi/absensi.php?kelas=X" class="btn btn-sm btn-outline-info">
                                        <i class="fa-solid fa-clipboard-user me-1"></i> Absensi Kelas X
                                    </a>
                                    <a href="<?= $main_url ?>absensi/absensi.php?kelas=XI" class="btn btn-sm btn-outline-warning">
                                        <i class="fa-solid fa-clipboard-user me-1"></i> Absensi Kelas XI
                                    </a>
                                    <a href="<?= $main_url ?>absensi/absensi.php?kelas=XII" class="btn btn-sm btn-outline-success">
                                        <i class="fa-solid fa-clipboard-user me-1"></i> Absensi Kelas XII
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->

            </div>
        </main>

<?php require_once "template/footer.php"; ?>
