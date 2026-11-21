<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $id_jabatan = $_POST['id_jabatan'];
    
    $nama_kembar = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($nama_kembar) > 0) {
        $error = "Username sudah terdaftar, gunakan yang lain!";
    } else {
        $password_acak = password_hash($password, PASSWORD_DEFAULT);
        $simpan = mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, username, password, role, id_jabatan) VALUES ('$nama_lengkap', '$username', '$password_acak', '$role', '$id_jabatan')");

        if($simpan) {
            echo "<script>
                    alert('Berhasil! Akun telah disimpan.');
                    window.location='data_karyawan.php';
                </script>";
            exit();        
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }

    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Akun Karyawan Baru - HRIS Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark px-4" style="background-color: #0b1320;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">HRIS Payroll - Admin Panel</a>
            <a href="data_karyawan.php" class="btn btn-outline-light btn-sm">Kembali ke Data Karyawan</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Form Tambah Akun</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>

                        <form action="" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <select name="role" class="form-control">
                                    <option value="karyawan">Karyawan</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Jabatan</label>
                                <select name="id_jabatan" class="form-control" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php 
                                    $sql_user = mysqli_query($koneksi, "SELECT * FROM jabatan");
                                    while ($u = mysqli_fetch_assoc($sql_user)) {
                                        echo "<option value='{$u['id_jabatan']}'> {$u['nama_jabatan']} </option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" name="simpan" class="btn btn-primary w-100 fw-bold">Simpan Akun</button>
                            
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>