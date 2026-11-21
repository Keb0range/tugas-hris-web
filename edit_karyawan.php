<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("location:data_karyawan.php");
    exit();
}

$id_edit = $_GET['id'];

$query_lama = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id_edit'");
$data = mysqli_fetch_assoc($query_lama);

if (!$data) {
    header("location:data_karyawan.php");
    exit();
}

if (isset($_POST['update'])) {
    $nama_lengkap  = $_POST['nama_lengkap'];
    $username      = $_POST['username'];
    $password_baru = $_POST['password_baru'];
    $role          = $_POST['role'];
    $id_jabatan    = $_POST['id_jabatan'];

    $cek_username = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' AND id_user != '$id_edit'");
    if (mysqli_num_rows($cek_username) > 0) {
        $error = "Username sudah digunakan oleh akun lain!";
    } else {
        if (!empty($password_baru)) {
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap = '$nama_lengkap', username = '$username', password = '$password_hash', role = '$role', id_jabatan = '$id_jabatan' WHERE id_user = '$id_edit'");
        } else {
            $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap = '$nama_lengkap', username = '$username', role = '$role', id_jabatan = '$id_jabatan' WHERE id_user = '$id_edit'");
        }

        if ($update) {
            echo "<script>
                    alert('Berhasil! Data akun telah diperbarui.');
                    window.location='data_karyawan.php';
                  </script>";
            exit();
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Akun Karyawan - HRIS Payroll</title>
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
                        <h4 class="mb-0 fw-bold">Form Edit Akun</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>

                        <form action="" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $data['nama_lengkap']; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Baru (Opsional)</label>
                                <input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <select name="role" class="form-control">
                                    <option value="karyawan" <?php if ($data['role'] == 'karyawan') echo 'selected'; ?>>Karyawan</option>
                                    <option value="admin" <?php if ($data['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                            </div>
                        
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Jabatan</label>
                                <select name="id_jabatan" class="form-control" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php 
                                    $sql_jabatan = mysqli_query($koneksi, "SELECT * FROM jabatan");
                                    while ($j = mysqli_fetch_assoc($sql_jabatan)) {
                                        $selected = ($j['id_jabatan'] == $data['id_jabatan']) ? 'selected' : '';
                                        echo "<option value='{$j['id_jabatan']}' $selected>{$j['nama_jabatan']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" name="update" class="btn btn-primary w-100 fw-bold">Simpan Perubahan</button>
                            
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>