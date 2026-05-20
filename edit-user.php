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
$title = "Edit User - SMK PELITA";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];
$queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE id = '$id'");
$data = mysqli_fetch_array($queryUser);

if(isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show">
    <i class="fa-solid fa-circle-check"></i> Data user berhasil diupdate!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="list-user.php">Data User</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>

            <?php if($msg != '') echo $alert; ?>

            <form action="proses-edit-user.php" method="POST">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <div class="card">
                <div class="card-header">
                    <span class="h5"><i class="fa-solid fa-pen-to-square"></i> Edit User</span>
                    <button type="submit" name="update" class="btn btn-primary float-end">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Username</label>
                        <label class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-4" style="margin-left:-50px">
                            <input type="text" class="form-control-plaintext border-bottom ps-2" value="<?= $data['username'] ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <label class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-4" style="margin-left:-50px">
                            <input type="text" name="nama" class="form-control border-0 border-bottom" value="<?= $data['nama'] ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Jabatan</label>
                        <label class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-4" style="margin-left:-50px">
                            <input type="text" class="form-control-plaintext border-bottom ps-2" value="<?= $data['jabatan'] ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Jurusan</label>
                        <label class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-4" style="margin-left:-50px">
                            <select name="jurusan" class="form-select border-0 border-bottom">
                                <option value="">--Pilih Jurusan--</option>
                                <?php
                                $jurusanList = ['KIMIA', 'TEKNIK KOMPUTER JARINGAN', 'ADMINISTRASI', 'AKUTANSI', 'ELEKTRONIKA INDUSTRI'];
                                foreach($jurusanList as $jrs) {
                                    $selected = ($data['jurusan'] == $jrs) ? 'selected' : '';
                                    echo "<option value='$jrs' $selected>$jrs</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Agama</label>
                        <label class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-4" style="margin-left:-50px">
                            <select name="agama" class="form-select border-0 border-bottom">
                                <?php
                                $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha'];
                                foreach($agamaList as $agm) {
                                    $selected = ($data['agama'] == $agm) ? 'selected' : '';
                                    echo "<option value='$agm' $selected>$agm</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Alamat</label>
                        <label class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-4" style="margin-left:-50px">
                            <textarea name="alamat" rows="3" class="form-control"><?= $data['alamat'] ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </main>

<?php require_once "../template/footer.php"; ?>