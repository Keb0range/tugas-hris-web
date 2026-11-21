<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

$id_user = $_SESSION['id_user'];
$nama_lengkap = $_SESSION['nama_lengkap'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - HRIS Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark px-4" style="background-color: #0b1320;">
        <div class="container-fluid">
           <a class="navbar-brand" href="index.php">
                <?php echo ($role == 'admin') ? 'HRIS Payroll - Admin Panel' : 'HRIS Payroll'; ?>
            </a>
            <ul class="navbar-nav me-auto ms-3">
                <?php if ($role == 'admin') { ?>
                    <li class="nav-item">
                        <a class="nav-link text-white bg-white bg-opacity-10 rounded px-3 me-2" href="data_karyawan.php">Data Karyawan</a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link text-white bg-white bg-opacity-10 rounded px-3 me-2" href="ganti_password.php">Ganti Password</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center text-white">
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="fw-normal">Halo, <strong><?php echo $nama_lengkap; ?></strong></h2>
                        <p class="text-muted">Selamat datang di sistem pengelolaan penggajian karyawan.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($role == 'admin') { ?>
            <!-- TAMPILAN KHUSUS ADMIN-->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Seluruh Penggajian Karyawan</h5>
                    <a href="proses_gaji.php" class="btn btn-light btn-sm fw-bold">+ Proses Gaji Baru</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Periode Bulan</th>
                                    <th>Potongan</th>
                                    <th>Total Take Home Pay</th>
                                    <th>Bukti Transfer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Menggunakan Query JOIN untuk menggabungkan tabel penggajian dan users
                                $query = mysqli_query($koneksi, "SELECT penggajian.*, users.nama_lengkap FROM penggajian JOIN users ON penggajian.id_user = users.id_user ORDER BY penggajian.id_penggajian DESC");
                                $no = 1;
                                if (mysqli_num_rows($query) > 0) {
                                    while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $data['nama_lengkap']; ?></td>
                                    <td><?php echo $data['periode_bulan']; ?></td>
                                    <td>Rp <?php echo number_format($data['potongan'], 0, ',', '.'); ?></td>
                                    <td>Rp <?php echo number_format($data['total_gaji'], 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="uploads/<?php echo $data['bukti_transfer']; ?>" target="_blank" class="btn btn-sm btn-info text-white">Lihat Bukti</a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Belum ada data penggajian yang diproses.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <!-- TAMPILAN KHUSUS KARYAWAN -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Slip Gaji & Dokumen Bukti Saya</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Periode Bulan</th>
                                    <th>Potongan</th>
                                    <th>Total Take Home Pay</th>
                                    <th>Dokumen Bukti Transfer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query difilter berdasarkan id_user yang sedang login agar karyawan hanya bisa melihat miliknya sendiri
                                $query = mysqli_query($koneksi, "SELECT * FROM penggajian WHERE id_user = '$id_user' ORDER BY id_penggajian DESC");
                                if (mysqli_num_rows($query) > 0) {
                                    while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr>
                                    <td><?php echo $data['periode_bulan']; ?></td>
                                    <td>Rp <?php echo number_format($data['potongan'], 0, ',', '.'); ?></td>
                                    <td>Rp <?php echo number_format($data['total_gaji'], 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="uploads/<?php echo $data['bukti_transfer']; ?>" target="_blank" class="btn btn-sm btn-success">Lihat Bukti</a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>Belum ada slip gaji yang tersedia untuk akun Anda.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>