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

$title = "Rekap Absensi - SMK PELITA";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$kelas   = isset($_GET['kelas']) ? $_GET['kelas'] : 'X';
$jurusan = $_SESSION["ssjurusan"];
$guru    = $_SESSION["ssuser"]; // Menggunakan username guru dari session

// Mengambil parameter tanggal dari URL jika ada
$tgl_pilih = isset($_GET['tgl']) ? $_GET['tgl'] : '';
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Rekap Absensi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="rekap_absensi.php?kelas=<?= $kelas ?>">Rekap Absensi</a></li>
                <?php if ($tgl_pilih != '') { ?>
                    <li class="breadcrumb-item active">Detail Tanggal <?= date('d-m-Y', strtotime($tgl_pilih)) ?></li>
                <?php } else { ?>
                    <li class="breadcrumb-item active">Rangkuman</li>
                <?php } ?>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table me-1"></i>
                        <?php if ($tgl_pilih != '') { ?>
                            Detail Absensi Kelas <?= htmlspecialchars($kelas) ?> - Tgl: <?= date('d-m-Y', strtotime($tgl_pilih)) ?>
                        <?php } else { ?>
                            Rekap Absensi Kelas <?= htmlspecialchars($kelas) ?> - <?= htmlspecialchars($jurusan) ?>
                        <?php } ?>
                    </div>
                    
                    <?php if ($tgl_pilih != '') { ?>
                        <a href="rekap_absensi.php?kelas=<?= $kelas ?>" class="btn btn-sm btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                    <?php } ?>
                </div>
                <div class="card-body">
                    
                    <?php 
                    // ========================================================
                    // KONDISI 1: JIKA PARAMETER TANGGAL ADA (TAMPILAN DETAIL PER SISWA)
                    // ========================================================
                    if ($tgl_pilih != '') { 
                        $queryDetail = mysqli_query($koneksi, "
                            SELECT a.status, s.nis, s.nama 
                            FROM tbl_absensi a
                            JOIN tbl_siswa s ON a.nis = s.nis
                            WHERE a.tgl = '$tgl_pilih' 
                              AND a.kelas = '$kelas' 
                              AND a.jurusan = '$jurusan'
                            ORDER BY s.nama ASC
                        ");
                    ?>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="20%">NIS</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center" width="25%">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($detail = mysqli_fetch_array($queryDetail)) { 
                                    $badge = 'bg-success';
                                    if($detail['status'] == 'Sakit') $badge = 'bg-warning text-dark';
                                    if($detail['status'] == 'Izin') $badge = 'bg-info text-dark';
                                    if($detail['status'] == 'Alpha') $badge = 'bg-danger';
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= $detail['nis'] ?></td>
                                    <td><?= htmlspecialchars($detail['nama']) ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $badge ?> px-3 py-2">
                                            <?= $detail['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    <?php 
                    // ========================================================
                    // KONDISI 2: TAMPILAN AWAL (RANGKUMAN GRUP TOTAL PER TANGGAL)
                    // ========================================================
                    } else { 
                        // Menggunakan nama file rekap_absensi.php yang benar pada link Lihat
                        $queryRekap = mysqli_query($koneksi, "
                            SELECT tgl,
                                   SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                                   SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
                                   SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as izin,
                                   SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) as alpha,
                                   COUNT(status) as total
                            FROM tbl_absensi
                            WHERE kelas = '$kelas' AND jurusan = '$jurusan' AND username_guru = '$guru'
                            GROUP BY tgl
                            ORDER BY tgl DESC
                        ");

                        if(mysqli_num_rows($queryRekap) == 0) {
                            echo "<div class='alert alert-info text-center my-3'>Belum ada data absensi untuk kelas ini.</div>";
                        } else {
                        ?>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Alpha</th>
                                    <th class="text-center">Total Siswa</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($rw = mysqli_fetch_array($queryRekap)) { 
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= date('d-m-Y', strtotime($rw['tgl'])) ?></td>
                                    <td class="text-center"><span class="badge bg-success"><?= $rw['hadir'] ?></span></td>
                                    <td class="text-center"><span class="badge bg-warning"><?= $rw['sakit'] ?></span></td>
                                    <td class="text-center"><span class="badge bg-info"><?= $rw['izin'] ?></span></td>
                                    <td class="text-center"><span class="badge bg-danger"><?= $rw['alpha'] ?></span></td>
                                    <td class="text-center"><?= $rw['total'] ?></td>
                                    <td class="text-center">
                                        <a href="rekap_absensi.php?kelas=<?= $kelas ?>&tgl=<?= $rw['tgl'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    <?php } ?>

                </div>
            </div>
        </div>
    </main>

<?php require_once "../template/footer.php"; ?>