<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

$id_user = $_SESSION['id_user'];

if (isset($_POST['ganti'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $query = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user = '$id_user'");
    $data  = mysqli_fetch_assoc($query);

    if (!password_verify($password_lama, $data['password'])) {
        $error = "Password lama yang Anda masukkan salah!";
    } elseif ($password_baru != $konfirmasi_password) {
        $error = "Konfirmasi password baru tidak cocok!";
    } else {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi, "UPDATE users SET password = '$password_hash' WHERE id_user = '$id_user'");

        if ($update) {
            echo "<script>
                    alert('Berhasil! Password Anda telah diperbarui.');
                    window.location='index.php';
                  </script>";
            exit();
        } else {
            $error = "Gagal mengubah password: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ganti Password - HRIS Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark px-4" style="background-color: #0b1320;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">HRIS Payroll</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Form Ganti Password</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>

                        <form action="" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Lama</label>
                                <input type="password" name="password_lama" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Baru</label>
                                <input type="password" name="password_baru" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                                <input type="password" name="konfirmasi_password" class="form-control" required>
                            </div>

                            <button type="submit" name="ganti" class="btn btn-primary w-100 fw-bold">Ganti Password</button>
                            
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>