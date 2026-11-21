<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

$id_admin_login = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Karyawan - HRIS Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark px-4" style="background-color: #0b1320;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">HRIS Payroll - Admin Panel</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Akun & Karyawan</h5>
                <a href="tambah_akun.php" class="btn btn-light btn-sm fw-bold">+ Tambah Akun</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Jabatan</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT users.*, jabatan.nama_jabatan FROM users LEFT JOIN jabatan ON users.id_jabatan = jabatan.id_jabatan ORDER BY users.id_user ASC");
                            $no = 1;

                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data['nama_lengkap']; ?></td>
                                <td><?php echo $data['username']; ?></td>
                                <td><?php echo !empty($data['nama_jabatan']) ? $data['nama_jabatan'] : '-'; ?></td>
                                <td>
                                    <?php if ($data['role'] == 'admin') { ?>
                                        <span class="badge bg-primary">Admin</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Karyawan</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <a href="edit_karyawan.php?id=<?php echo $data['id_user']; ?>" class="btn btn-warning btn-sm fw-bold">Edit</a>
                                    
                                    <?php if ($data['id_user'] != $id_admin_login) { ?>
                                        <a href="hapus_karyawan.php?id=<?php echo $data['id_user']; ?>" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Yakin ingin menghapus akun <?php echo $data['nama_lengkap']; ?>?')">Hapus</a>
                                    <?php } else { ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Akun Aktif</button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Belum ada data akun.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>