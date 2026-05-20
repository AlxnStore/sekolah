<?php

session_start();

if(!isset($_SESSION["sslogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

$title = "Tambah User - SMK PELITA";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah user gagal, username sudah ada...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah user berhasil, password default: 1234
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Tambah User</li>
            </ol>
            <form action="proses-user.php" method="POST" enctype="multipart/form-data">
                <?php if($msg !== '') echo $alert; ?>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah User</span>
                    <button type="submit" name="simpan" class="btn btn-primary float-end">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                    <button type="reset" name="reset" class="btn btn-danger float-end me-1" onclick="resetForm()">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Username</label>
                                <label class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left:-50px">
                                    <input type="text" pattern="[A-Za-z0-9]{3,}" title="minimal 3 karakter" class="form-control border-0 border-bottom" name="username" maxlength="20" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Nama</label>
                                <label class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left:-50px">
                                    <input type="text" class="form-control border-0 border-bottom" name="nama" maxlength="100" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Jabatan</label>
                                <label class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left:-50px">
                                    <select name="jabatan" id="jabatan" class="form-select border-0 border-bottom" required onchange="toggleGuru()">
                                        <option value="">--Pilih Jabatan--</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Guru">Guru</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Jurusan</label>
                                <label class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left:-50px">
                                    <select name="jurusan" id="jurusan" class="form-select border-0 border-bottom" required>
                                        <option value="">--Pilih Jurusan--</option>
                                        <option value="KIMIA">KIMIA</option>
                                        <option value="TEKNIK KOMPUTER JARINGAN">TEKNIK KOMPUTER JARINGAN</option>
                                        <option value="ADMINISTRASI">ADMINISTRASI</option>
                                        <option value="AKUTANSI">AKUTANSI</option>
                                        <option value="ELEKTRONIKA INDUSTRI">ELEKTRONIKA INDUSTRI</option>
                                    </select>
                                </div>
                            </div>

                            <div id="rowGuru" style="display:none">
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">NIP</label>
                                    <label class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px">
                                        <input type="text" id="nip" name="nip" pattern="[0-9]{18,}" title="minimal 18 angka" class="form-control border-0 border-bottom" maxlength="20">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Telpon</label>
                                    <label class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px">
                                        <input type="tel" id="telpon" name="telpon" pattern="[0-9]{5,}" title="minimal 5 angka" class="form-control border-0 border-bottom" maxlength="15">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Agama</label>
                                <label class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left:-50px">
                                    <select name="agama" class="form-select border-0 border-bottom" required>
                                        <option value="">--Pilih Agama--</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Alamat</label>
                                <label class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left:-50px">
                                    <textarea name="alamat" rows="3" class="form-control" required></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="col-4 text-center px-5">
                            <img id="previewFoto" src="../asset/image/default.png" alt="gambar user" class="mb-3 rounded-circle" width="150px">
                            <input type="file" name="image" class="form-control form-control-sm" accept=".jpg,.jpeg,.png" onchange="previewImage(this)">
                            <small class="text-secondary">pilih foto PNG, JPG atau JPEG</small>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </main>

<script>
function toggleGuru() {
    let jabatan = document.getElementById('jabatan').value;
    let rowGuru = document.getElementById('rowGuru');
    let nip     = document.getElementById('nip');
    let telpon  = document.getElementById('telpon');

    if (jabatan === 'Guru') {
        rowGuru.style.display = 'block';
        nip.setAttribute('required', 'required');
        telpon.setAttribute('required', 'required');
    } else {
        rowGuru.style.display = 'none';
        nip.removeAttribute('required');
        telpon.removeAttribute('required');
        nip.value = '';
        telpon.value = '';
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFoto').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetForm() {
    document.getElementById('rowGuru').style.display = 'none';
    document.getElementById('previewFoto').src = '../asset/image/default.png';
}
</script>

<?php require_once "../template/footer.php"; ?>