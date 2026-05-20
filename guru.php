<?php

session_start();

if (!isset($_SESSION["sslogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Guru - SMK PELITA";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = "";
}

$alert = '';
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data guru berhasil di hapus.....
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data guru berhasil di perbarui.....
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> Data guru gagal di perbarui, NIP sudah ada...!!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

?>

<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Guru</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                            <li class="breadcrumb-item active">Guru</li>
                        </ol>
                        <?php if ($msg != ""){
                            echo $alert;
                        }?>
                        <div class="card">
                            <div class="card-header">
                                <i class="fa-solid fa-list"></i> Data Guru
                                <a href="<?= $main_url ?>guru/add-guru.php" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-plus"></i>Tambah Guru</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover" id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th scope="col"><center>No</center></th>
                                            <th scope="col">
                                                <center>Foto</center>
                                            </th>
                                            <th scope="col">
                                                <center>NIP</center>
                                            </th>
                                            <th scope="col">
                                                <center>Nama</center>
                                            </th>
                                            <th scope="col">
                                                <center>Telpon</center>
                                            </th>
                                            <th scope="col">
                                                <center>Agama</center>
                                            </th>
                                            <th scope="col">
                                                <center>Alamat</center>
                                            </th>
                                            <th scope="col">
                                                <center>Edit</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
                                        while ($data = mysqli_fetch_array($queryGuru)) {

                                        ?>
                                        <tr>
                                            <th scope="row"><?= $no++ ?></th>
                                            <td align="center"><img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" width="60px" alt=""></td>
                                            <td><?= $data['nip'] ?></td>
                                            <td><?= $data['nama'] ?></td>
                                            <td><?= $data['telpon'] ?></td>
                                            <td><?= $data['agama'] ?></td>
                                            <td><?= $data['alamat'] ?></td>
                                            <td align="center">
                                                <a href="edit-guru.php?id=<?= $data['id']?>" class="btn btn-sm btn-warning" title="update guru"><i class="fa-solid fa-pen" ></i></a>
                                                <button type="button" class="btn btn-sm btn-danger" id="btnhapus" title="hapus guru" data-id="<?= $data['id'] ?>" data-foto="<?= $data['foto'] ?>"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main> 
                
                
                <div class="modal" id="mdlhapus" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus data ini ..?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <a href="" id="btnmdlhapus" class="btn btn-primary">Ya</a>
                            </div>
                        </div>
                    </div>
                </div>



                <script>
                    $(document).ready(function() {
                        $(document).on('click', "#btnhapus", function(){
                            $('#mdlhapus').modal('show');
                            let idGuru = $(this).data('id');
                            let fotoGuru = $(this).data('foto');
                            $('#btnmdlhapus').attr("href", "hapus-guru.php?id=" + idGuru + "&foto=" + fotoGuru);
                        })
                    })
                </script>

<?php

require_once "../template/footer.php";

?>