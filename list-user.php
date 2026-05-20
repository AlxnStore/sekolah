<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

if($_SESSION["ssjabatan"] != 'Admin') {
    header("location:../index.php");
    exit;
}

require_once "../config.php";
$title = "Data User - SMK PELITA";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$alert = '';
if($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show">
    <i class="fa-solid fa-circle-check"></i> Data user berhasil diupdate!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>';
}
if($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show">
    <i class="fa-solid fa-circle-check"></i> Data user berhasil dihapus!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Data User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Data User</li>
            </ol>

            <?php if($msg != '') echo $alert; ?>

            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-users"></i> Data User
                    <a href="add-user.php" class="btn btn-sm btn-primary float-end">
                        <i class="fa-solid fa-plus"></i> Tambah User
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Jurusan</th>
                                <th>Agama</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user");
                            while($data = mysqli_fetch_array($queryUser)) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['username'] ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td>
                                    <span class="badge <?= $data['jabatan']=='Admin' ? 'bg-danger' : 'bg-primary' ?>">
                                        <?= $data['jabatan'] ?>
                                    </span>
                                </td>
                                <td><?= $data['jurusan'] != '' ? $data['jurusan'] : '<span class="text-danger">Belum diset</span>' ?></td>
                                <td><?= $data['agama'] ?></td>
                                <td class="text-center">
                                    <a href="edit-user.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

<?php require_once "../template/footer.php"; ?>