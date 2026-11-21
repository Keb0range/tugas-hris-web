-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Nov 2026 pada 16.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penggajian`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL,
  `gaji_pokok` int(11) NOT NULL,
  `tunjangan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `gaji_pokok`, `tunjangan`) VALUES
(2, 'HR Manager', 8500000, 2000000),
(3, 'Staff HRD', 5500000, 1000000),
(4, 'Software Engineer', 7500000, 1500000),
(5, 'Finance & Accounting', 6000000, 1200000),
(6, 'Marketing Executive', 5000000, 1000000),
(7, 'Staff Operasional', 4500000, 750000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penggajian`
--

CREATE TABLE `penggajian` (
  `id_penggajian` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `periode_bulan` varchar(20) NOT NULL,
  `potongan` int(11) NOT NULL,
  `total_gaji` int(11) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penggajian`
--

INSERT INTO `penggajian` (`id_penggajian`, `id_user`, `periode_bulan`, `potongan`, `total_gaji`, `bukti_transfer`) VALUES
(1, 6, 'November 2026', 0, 9000000, 'bukti_1795272648.png'),
(2, 7, 'January 2026', 1000000, 5000000, 'bukti_1795272680.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','karyawan') NOT NULL,
  `id_jabatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `id_jabatan`) VALUES
(3, 'Yhasfi Dwi Syaifullah', 'Keb0range', '$2y$10$f5m8cZlyqXhGF/cMMtGVuuEUC92Exu.D4P7qXAtgjMLKXbBfDruRO', 'admin', 2),
(5, 'Hamdallah', 'Hamdallah', '$2y$10$f5m8cZlyqXhGF/cMMtGVuuEUC92Exu.D4P7qXAtgjMLKXbBfDruRO', 'karyawan', 5),
(6, 'Ronaldo', 'jr', '$2y$10$pbtFlWonDfbc9GS9Eq/qVOt0CyPufz.hJ6ur//GpwexnQyn9Xm.GS', 'karyawan', 4),
(7, 'Karyawan', 'karyawan', '$2y$10$GT0wfRW4MX6W2ZhJjcbi8e1Mc/GEL2DldSAJrU8a4GsT1lp8z7Btm', 'karyawan', 6);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indeks untuk tabel `penggajian`
--
ALTER TABLE `penggajian`
  ADD PRIMARY KEY (`id_penggajian`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `username_3` (`username`),
  ADD UNIQUE KEY `username_4` (`username`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `penggajian`
--
ALTER TABLE `penggajian`
  MODIFY `id_penggajian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penggajian`
--
ALTER TABLE `penggajian`
  ADD CONSTRAINT `penggajian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
