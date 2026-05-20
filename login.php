<?php

session_start();

if (isset($_SESSION["sslogin"])) {
    header("location:../index.php");
    exit;
}

require_once "../config.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Login - SMK PELITA</title>
        <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/x-icon" href="<?= $main_url ?>asset/image/toga.png">

        <style>
            #bgLogin {
                /* Ganti URL di bawah ini dengan URL foto background kamu */
                background-image: url("https://2.bp.blogspot.com/-UonfkPnU0Aw/VOQCwsBvGTI/AAAAAAAAAgE/mgZKJDptJ2c/s1600/100_4026.JPG");
                background-size: cover;
                background-position: center center;
            }
            #bgLogin::before {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                z-index: 0;
            }
            #layoutAuthentication_content {
                position: relative;
                z-index: 1;
            }
        </style>
    </head>
    <body id="bgLogin">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header text-center">
                                        <h4 class="font-weight-light my-4">Login - SMK PELITA</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($msg == 'gagal') { ?>
                                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                            <i class="fa-solid fa-circle-xmark"></i> Username atau password salah!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                        <?php } ?>

                                        <form action="proseslogin.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control"
                                                       id="username"
                                                       name="username"
                                                       type="text"
                                                       pattern="[A-Za-z0-9]{3,}"
                                                       title="Kombinasi angka dan huruf minimal 3 karakter"
                                                       placeholder="username"
                                                       autocomplete="off"
                                                       required />
                                                <label for="username">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control"
                                                       id="inputPassword"
                                                       type="password"
                                                       placeholder="Password"
                                                       minlength="4"
                                                       name="password"
                                                       required />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <button type="submit" name="login" class="btn btn-primary col-12 rounded-pill my-2">
                                                <i class="fa-solid fa-right-to-bracket"></i> Login
                                            </button>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="text-muted small">Copyright &copy; SMK PELITA <?= date("Y") ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?= $main_url ?>asset/sb-admin/js/scripts.js"></script>
    </body>
</html>