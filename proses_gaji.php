<?php
session_start();
// Validasi akses: Hanya admin yang boleh mengakses halaman ini
if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

// Proses simpan data ketika form disubmit
if (isset($_POST['proses'])) {
    $id_user        = $_POST['id_user'];
    $periode_bulan  = $_POST['periode_bulan'];
    $potongan       = $_POST['potongan'];

    // Ambil data gaji pokok dan tunjangan berdasarkan jabatan karyawan yang dipilih
    $q_karyawan = mysqli_query($koneksi, "SELECT users.*, jabatan.gaji_pokok, jabatan.tunjangan FROM users JOIN jabatan ON users.id_jabatan = jabatan.id_jabatan WHERE users.id_user = '$id_user'");
    $d_kar = mysqli_fetch_assoc($q_karyawan);

    $gaji_pokok = $d_kar['gaji_pokok'];
    $tunjangan  = $d_kar['tunjangan'];

    // Logika Kalkulasi Gaji: Gaji Pokok + Tunjangan - Potongan
    $total_gaji = ($gaji_pokok + $tunjangan) - $potongan;

    // Handle Upload Berkas Bukti Transfer
    $nama_file = $_FILES['bukti_transfer']['name'];
    $lokasi_tmp = $_FILES['bukti_transfer']['tmp_name'];
    $direktori = "uploads/";

    // Pastikan folder uploads sudah ada, jika belum bisa dibuat atau disiapkan manual
    if (!is_dir($direktori)) {
        mkdir($direktori, 0777, true);
    }

    if (!empty($nama_file)) {
        $ekstensi_file = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_baru = "bukti_" . time() . "." . $ekstensi_file;
        move_uploaded_file($lokasi_tmp, $direktori . $nama_file_baru);
    } else {
        $nama_file_baru = null;
    }

    // Simpan ke database tabel penggajian
    $query_insert = mysqli_query($koneksi, "INSERT INTO penggajian (id_user, periode_bulan, potongan, total_gaji, bukti_transfer) VALUES ('$id_user', '$periode_bulan', '$potongan', '$total_gaji', '$nama_file_baru')");

    if ($query_insert) {
        // Redirect kembali ke dashboard dengan pesan sukses
        header("location:index.php?pesan=sukses");
        exit();
    } else {
        $error = "Gagal memproses gaji: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proses Gaji Baru - HRIS Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark px-4" style="background-color: #0b1320;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">HRIS Payroll - Admin Panel</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Kembali ke Dashboard</a>
        </div>
    </nav>

    <!-- Form Proses Gaji -->
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Form Kalkulasi & Proses Gaji Karyawan</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>

                        <!-- Atribut enctype wajib ada untuk upload file -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Karyawan</label>
                                <select name="id_user" class="form-control" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    <?php 
                                    // Ambil data user yang berperan sebagai karyawan beserta info jabatannya
                                    $sql_user = mysqli_query($koneksi, "SELECT users.id_user, users.nama_lengkap, jabatan.nama_jabatan, jabatan.gaji_pokok, jabatan.tunjangan FROM users JOIN jabatan ON users.id_jabatan = jabatan.id_jabatan WHERE users.role = 'karyawan'");
                                    while ($u = mysqli_fetch_assoc($sql_user)) {
                                        echo "<option value='{$u['id_user']}'>{$u['nama_lengkap']} - {$u['nama_jabatan']} (Pokok: Rp " . number_format($u['gaji_pokok'], 0, ',', '.') . ")</option>";
                                    }
                                    ?>
                                </select>
                                <div class="form-text">Gaji pokok dan tunjangan akan otomatis dihitung berdasarkan jabatan karyawan.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Periode Bulan</label>
                                <input type="text" name="periode_bulan" class="form-control" placeholder="Contoh: November 2026" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Potongan (Absen / Lainnya)</label>
                                <input type="number" name="potongan" class="form-control" placeholder="Masukkan nominal angka (contoh: 100000)" value="0" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Unggah Dokumen Bukti Transfer (Opsional)</label>
                                <input type="file" name="bukti_transfer" class="form-control">
                                <div class="form-text">Format yang didukung: Gambar atau PDF.</div>
                            </div>

                            <button type="submit" name="proses" class="btn btn-primary w-100 fw-bold">Hitung & Simpan Take Home Pay</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>