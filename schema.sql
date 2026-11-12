CREATE DATABASE db_penggajian;
USE db_penggajian;

CREATE TABLE jabatan (
    id_jabatan INT AUTO_INCREMENT PRIMARY KEY,
    nama_jabatan VARCHAR(50) NOT NULL,
    gaji_pokok INT NOT NULL,
    tunjangan INT NOT NULL
);

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'karyawan') NOT NULL,
    id_jabatan INT NOT NULL,
    FOREIGN KEY (id_jabatan) REFERENCES jabatan(id_jabatan)
);

CREATE TABLE penggajian (
    id_penggajian INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    periode_bulan VARCHAR(20) NOT NULL,
    potongan INT NOT NULL,
    total_gaji INT NOT NULL,
    bukti_transfer VARCHAR(255),
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);

-- data cobaan
INSERT INTO jabatan (nama_jabatan, gaji_pokok, tunjangan) VALUES ('Admin HRD', 5000000, 1000000);

INSERT INTO users (nama_lengkap, username, password, role, id_jabatan) VALUES ('Yhasfi Dwi Syaifullah', 'admin', '12345', 'admin', 1);