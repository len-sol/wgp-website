-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 31 Bulan Mei 2025 pada 20.41
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warranty`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `log`
--

CREATE TABLE `log` (
  `username` varchar(100) NOT NULL,
  `activity` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log`
--

INSERT INTO `log` (`username`, `activity`, `created_at`) VALUES
('admin', 'User login successful', '2025-05-29 12:17:40'),
('admin', 'Accessed warranty data', '2025-05-29 12:17:40'),
('admin', 'Accessed warranty data', '2025-05-29 12:18:36'),
('admin', 'Accessed warranty data', '2025-05-29 12:19:17'),
('admin', 'Accessed warranty data', '2025-05-29 12:19:25'),
('admin', 'Accessed warranty data', '2025-05-29 12:31:47'),
('admin', 'User login successful', '2025-05-30 16:49:42'),
('admin', 'User login successful', '2025-05-31 11:38:51'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 11:38:51'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 13:35:13'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 13:35:18'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 13:35:25'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 13:36:05'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 13:42:15'),
('admin', 'Accessed warranty data (pending verification)', '2025-05-31 13:46:04'),
('admin', 'Added new user: len with role: user', '2025-05-31 13:53:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(30) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `role`, `active`) VALUES
(2, 'admin', 'admin', 'admin', 1),
(3, 'len', '13n', 'user', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `warranty`
--

CREATE TABLE `warranty` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cp` int(15) NOT NULL,
  `serial_num` varchar(20) NOT NULL,
  `files` text NOT NULL,
  `warranty_start` datetime(6) DEFAULT NULL,
  `warranty_end` datetime(6) DEFAULT NULL,
  `delete` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `warranty`
--

INSERT INTO `warranty` (`id`, `name`, `cp`, `serial_num`, `files`, `warranty_start`, `warranty_end`, `delete`) VALUES
(1, 'Len', 123, '123', '', NULL, NULL, 1),
(2, 'len', 222, '222', '', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `warranty`
--
ALTER TABLE `warranty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `warranty`
--
ALTER TABLE `warranty`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
