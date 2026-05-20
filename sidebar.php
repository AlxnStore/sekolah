<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion bg-light" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link" href="<?= $main_url ?>index.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                        Dashboard
                    </a>
                    <hr class="mb-0">

                    <?php if($_SESSION["ssjabatan"] == 'Admin') { ?>
                    <div class="sb-sidenav-menu-heading">Admin</div>
                    <a class="nav-link" href="<?= $main_url ?>user/list-user.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Data User
                    </a>
                    <a class="nav-link" href="<?= $main_url ?>user/add-user.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-user-plus"></i></div>
                        Tambah User
                    </a>
                    <a class="nav-link" href="<?= $main_url ?>user/password.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-key"></i></div>
                        Ganti Password
                    </a>
                    <hr class="mb-0">
                    <div class="sb-sidenav-menu-heading">Data</div>
                    <a class="nav-link" href="<?= $main_url ?>siswa/siswa.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Siswa
                    </a>
                    <a class="nav-link" href="<?= $main_url ?>guru/guru.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                        Guru
                    </a>
                    <a class="nav-link" href="<?= $main_url ?>pelajaran/pelajaran.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                        Mata Pelajaran
                    </a>
                
                    <?php } ?>

                    <hr class="mb-0">
                </div>
            </div>
            <div class="sb-sidenav-footer border">
                <div class="small">Logged in as:</div>
                <?= $_SESSION["ssuser"] ?>
            </div>
        </nav>
    </div>