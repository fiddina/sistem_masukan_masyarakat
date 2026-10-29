CREATE DATABASE IF NOT EXISTS db_masukan_masyarakat
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_masukan_masyarakat;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE,
    keterangan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE masukan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pengirim VARCHAR(150) NOT NULL,
    no_hp VARCHAR(30) NULL,
    alamat TEXT NULL,
    tanggal_masuk DATE NOT NULL,
    kategori_id INT NOT NULL,
    judul_masukan VARCHAR(200) NOT NULL,
    isi_masukan TEXT NOT NULL,
    lokasi_masalah VARCHAR(255) NULL,
    status ENUM('Baru','Diverifikasi','Diteruskan','Diproses','Selesai') NOT NULL DEFAULT 'Baru',
    pihak_terkait VARCHAR(200) NULL,
    tanggal_diteruskan DATE NULL,
    keterangan_tindak_lanjut TEXT NULL,
    lampiran VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_masukan_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

INSERT INTO users (username,password,nama)
VALUES ('admin', '$2y$12$BGHBOfDzRqp0V5Ae6l0wy.K6pU/VIcJllFgdfE917GTmppal2VyPO', 'Administrator');

INSERT INTO kategori (nama_kategori,keterangan) VALUES
('Infrastruktur','Masukan terkait pembangunan dan fasilitas infrastruktur'),
('Jalan','Permasalahan jalan rusak atau akses jalan'),
('Drainase','Permasalahan drainase dan saluran air'),
('Banjir','Masukan terkait banjir'),
('Tata Ruang','Masukan terkait tata ruang wilayah'),
('Perizinan Bangunan','Masukan terkait perizinan bangunan'),
('Lingkungan','Masukan terkait lingkungan'),
('Lainnya','Masukan lain yang belum termasuk kategori di atas');
