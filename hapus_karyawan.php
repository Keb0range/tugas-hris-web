<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_hapus = $_GET['id'];
    $id_admin_login = $_SESSION['id_user'];

    if ($id_hapus == $id_admin_login) {
        echo "<script>
                alert('Gagal! Anda tidak dapat menghapus akun yang sedang aktif digunakan.');
                window.location='data_karyawan.php';
              </script>";
        exit();
    }

    try {
        $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id_hapus'");
        echo "<script>
            alert('Berhasil! Akun telah dihapus.');
            window.location='data_karyawan.php';
            </script>";
    } catch (mysqli_sql_exception $e) {
        echo "<script>
            alert('Gagal menghapus akun! Kemungkinan akun ini masih memiliki riwayat data penggajian.');
            window.location='data_karyawan.php';
            </script>";
    }
} else {
    header("location:data_karyawan.php");
}
exit();
?>