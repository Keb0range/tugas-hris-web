<?php
session_start();
require 'koneksi.php'; 

// logika baca cookie
$nilai_username = "";

if (isset($_COOKIE['remember_username'])) {
    $nilai_username = $_COOKIE['remember_username'];
}

// logika mencari uss dan pass di database
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['status'] = "sudah_login";

        if (isset($_POST['ingat_saya'])) {
            setcookie("remember_username", $username, time() + (60 * 60 * 24 * 7), "/");
        } else {
            setcookie("remember_username", "", time() - 3600, "/");
        }
        
        header("location:index.php"); 
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Penggajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <!-- Navbar -->
    <div class="navbar">
        <h3>HRIS Payroll</h3>
    </div>

    <!-- Kotak Form Login -->
    <div class="container" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-5">
                
                <div class="kotak-login">
                    
                    <!-- Bagian Atas Kotak (Header) -->
                    <div class="judul-login">
                        <h3>Login HRIS Payroll</h3>
                    </div>
                    
                    <!-- Bagian Isi Kotak (Form) -->
                    <div class="isi-login">
                        
                        <!-- Menampilkan pesan error jika login gagal -->
                        <?php if(isset($error)) { ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $error; ?>
                            </div>
                        <?php } ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="label-teks">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $nilai_username ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="label-teks">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="ingat_saya" class="form-check-input" id="ingatCuy">
                                <label class="form-check-label" for="ingatCuy" style="color: #555">Ingat Saya</label>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 tombol-login">Login</button>
                        </form>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>