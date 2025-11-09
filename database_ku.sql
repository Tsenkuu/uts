-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Nov 2025 pada 20.09
-- Versi server: 11.4.8-MariaDB-cll-lve
-- Versi PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fzqcqgbi_pt`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$P5exB1Y53VMzycJHokj/vOvg78ng6lHDAz/pwfCYehDON9/ZfwNd6');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karya`
--

CREATE TABLE `karya` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `tipe` enum('image','video') NOT NULL DEFAULT 'image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karya`
--

INSERT INTO `karya` (`id`, `judul`, `deskripsi`, `file_url`, `tipe`) VALUES
(1, 'Video Bersih Masjid', 'video ini ada di youtube Lazismu Tulungagung', 'uploads/690711a6cccc8-Cuplikan layar 2025-11-02 150833.png', 'image'),
(3, 'Logo Mockup', 'logo', 'uploads/691091ad03aab-wd.jpg', 'image'),
(4, 'Logo', 'Logo tugas dari dosen', 'uploads/691083a7ef28f-1g.png', 'image');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keahlian`
--

CREATE TABLE `keahlian` (
  `id` int(11) NOT NULL,
  `ikon` varchar(100) NOT NULL DEFAULT 'fas fa-check',
  `judul` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keahlian`
--

INSERT INTO `keahlian` (`id`, `ikon`, `judul`, `deskripsi`) VALUES
(1, 'fas fa-palette', 'Desain Grafis', 'Mahir menggunakan Adobe Photoshop, Illustrator, dan Coreldraw.'),
(2, 'fas fa-video', 'Video Grafis', 'Video Editing, Motion Graphics, dan Storytelling. Menguasai Adobe Premiere Pro dan Capcut.'),
(3, 'fas fa-bullhorn', 'Media Sosial', 'Strategi Konten, Manajemen Kampanye, Copywriting, dan Analisis Performa (IG, TikTok, Facebook Ads).');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengalaman`
--

CREATE TABLE `pengalaman` (
  `id` int(11) NOT NULL,
  `posisi` varchar(255) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `tanggal` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `tipe` enum('kiri','kanan') NOT NULL DEFAULT 'kiri'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengalaman`
--

INSERT INTO `pengalaman` (`id`, `posisi`, `instansi`, `tanggal`, `deskripsi`, `tipe`) VALUES
(1, 'Graphic Designer', 'Lazismu Tulungagung', '2023 - Sekarang', 'Membuat Berbagai Desain Untuk PPT Pembicara dan keperluan acara seperti banner, dan poster.', 'kanan'),
(2, 'Social Media Specialist', 'DPRD PAN Tulungagung', '2025 - Sekarang', 'Mengelola dan mengembangkan strategi konten untuk Salahsatu anggota Partai PAN.', 'kiri'),
(3, 'Digital Fundraising', 'Lazismu Tulungagung', '2023 - Sekarang', 'Memproduksi dan mengedit video profil perusahaan, video event, dan konten video pendek untuk kebutuhan iklan digital.', 'kanan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal_kirim` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `nama_utama` varchar(100) NOT NULL,
  `deskripsi_singkat` varchar(255) NOT NULL,
  `link_wa` varchar(255) NOT NULL,
  `link_ig` varchar(255) NOT NULL,
  `foto_profil_url` varchar(500) NOT NULL,
  `profil_lengkap_1` text NOT NULL,
  `profil_lengkap_2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profil`
--

INSERT INTO `profil` (`id`, `nama_utama`, `deskripsi_singkat`, `link_wa`, `link_ig`, `foto_profil_url`, `profil_lengkap_1`, `profil_lengkap_2`) VALUES
(1, 'PIXEL YOGA PRATAMA', 'Desain Grafis dan Video Grafis — Media Sosial Spesialis', 'https://wa.me/625806917113', 'https://instagram.com/px.yoga', 'uploads/6903693236dba-Copy of Mari Tunaikan Kewajiban Zakat Melalui.png', 'Saya adalah seorang desainer grafis dan video grafis yang memiliki pengalaman profesional sebagai Media Sosial Spesialis di Lazismu Tulungagung selama 2 tahun.\r\nSaya berfokus pada pengembangan identitas visual, konten kreatif, dan strategi komunikasi digital yang efektif untuk memperkuat citra lembaga.', 'Dalam peran saya, saya bertanggung jawab atas pembuatan desain publikasi, produksi video, pengelolaan media sosial, serta analisis performa konten.\r\nKreativitas, konsistensi, dan kepekaan terhadap tren visual menjadi prinsip utama saya dalam berkarya, dengan tujuan menghadirkan pesan yang menarik dan berdampak positif bagi audiens.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `karya`
--
ALTER TABLE `karya`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keahlian`
--
ALTER TABLE `keahlian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengalaman`
--
ALTER TABLE `pengalaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `karya`
--
ALTER TABLE `karya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `keahlian`
--
ALTER TABLE `keahlian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengalaman`
--
ALTER TABLE `pengalaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
