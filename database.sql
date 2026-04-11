-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 11, 2026 lúc 03:21 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webdulich`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `UserAdmin` varchar(50) NOT NULL,
  `PassAdmin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`UserAdmin`, `PassAdmin`) VALUES
('trangthn', '123456');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdat`
--

CREATE TABLE `chitietdat` (
  `MaDat` int(11) NOT NULL,
  `MaTour` int(11) NOT NULL,
  `NgayDi` datetime NOT NULL,
  `SoLuongKhach` int(11) NOT NULL,
  `CapKS` varchar(10) DEFAULT NULL,
  `Khac` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdat`
--

INSERT INTO `chitietdat` (`MaDat`, `MaTour`, `NgayDi`, `SoLuongKhach`, `CapKS`, `Khac`) VALUES
(1, 1, '2026-03-15 00:00:00', 2, '3 sao', 'Yêu cầu phòng view biển'),
(3, 16, '2026-02-01 00:00:00', 10, '4*', 'phòng trên đồi, view full kính, xe đưa đón, có dịch vụ thuê xe'),
(4, 1, '2026-01-31 00:00:00', 3, '5*', 'pfdhk');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `MaCom` varchar(50) NOT NULL,
  `MaTVien` int(11) DEFAULT NULL,
  `MaTour` int(11) DEFAULT NULL,
  `NoiDungCom` varchar(3000) NOT NULL,
  `Vote` int(11) NOT NULL,
  `TrangThai` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comment`
--

INSERT INTO `comment` (`MaCom`, `MaTVien`, `MaTour`, `NoiDungCom`, `Vote`, `TrangThai`) VALUES
('C001', 1, 1, 'Tour rất tuyệt vời, hướng dẫn viên nhiệt tình!', 5, 0),
('MC69698e81e861c', 12, 3, 'happy', 5, 0),
('MC69698ff37ef5a', 12, 1, 'great', 5, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dattour`
--

CREATE TABLE `dattour` (
  `MaDat` int(11) NOT NULL,
  `MaTVien` int(11) DEFAULT NULL,
  `NgayDat` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dattour`
--

INSERT INTO `dattour` (`MaDat`, `MaTVien`, `NgayDat`) VALUES
(1, 1, '2026-01-02 11:09:54'),
(3, 12, '2026-01-15 23:18:00'),
(4, 12, '2026-01-16 08:16:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaitour`
--

CREATE TABLE `loaitour` (
  `MaLoai` varchar(50) NOT NULL,
  `TenLoai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaitour`
--

INSERT INTO `loaitour` (`MaLoai`, `TenLoai`) VALUES
('LT01', 'Trong nước'),
('LT02', 'Quốc tế');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhvien`
--

CREATE TABLE `thanhvien` (
  `MaTVien` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PassWord` varchar(255) NOT NULL,
  `VaiTro` enum('admin','user') NOT NULL DEFAULT 'user',
  `HoTen` varchar(50) DEFAULT NULL,
  `EmailTVien` varchar(200) DEFAULT NULL,
  `DiaChi` varchar(200) DEFAULT NULL,
  `SoCMT` varchar(12) DEFAULT NULL,
  `SoDT` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhvien`
--

INSERT INTO `thanhvien` (`MaTVien`, `Username`, `PassWord`, `VaiTro`, `HoTen`, `EmailTVien`, `DiaChi`, `SoCMT`, `SoDT`) VALUES
(1, 'trang123', 'matkhau123', 'user', 'Nguyễn Thị Trang', 'trang@example.com', NULL, NULL, NULL),
(11, 'quanh', '$2y$10$k85HvFCuqbliLLkyNh8Os.CuV7ufeofybF4JAhTPF/7kBOzLKsdlC', 'user', 'Nguyen thi quynh anh', 'quanh@gmail.com', 'Thai binh', '001972348235', '0978236589'),
(12, 'trang', '$2y$10$lvEAKpwhXwAJyLkA0T9udOubkaTBRb5yo9O2PuctOSHTLq1CglIcK', 'user', 'nguyen thi kieu trang', 'trangthn71274@gmail.com', 'Hà Nội', '001827375895', '0998389569'),
(14, 'ngoc', '$2y$10$191BJ45qCoqKG1v4q2jVGuD1N/MKXqZn7nFuxgnANaAiCV1lZYjOG', 'user', 'duong thi ngoc', 'duongngoc@gmail.com', 'Phú Thọ', '001897362458', '0978236589'),
(15, 'linh', '$2y$10$PKZOG9cCM9Nd9pSIMjwcUupuuX/u2BaS7JVJb5K2L8Rhgv2BdziIW', 'user', 'nguyen hoai linh', 'linhxinhgai@gmail.com', 'Hà Nội', '082967536874', '0998389569'),
(19, 'trangnguyen', '$2y$10$y7L9OShv1CSXuTg3/23bTe4mcYq.dy8csGMw6JjtjGNNvbXTeL1ju', 'user', 'NGUYEN THI KIEU TRANG', 'tr234789@gmail.com', 'Hà Nội', '001438724599', '0365228949');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour`
--

CREATE TABLE `tour` (
  `MaTour` int(11) NOT NULL,
  `MaLoai` varchar(50) DEFAULT NULL,
  `TenTour` varchar(100) NOT NULL,
  `TGTour` varchar(500) NOT NULL,
  `GiaTour` double DEFAULT NULL,
  `NoiDungTour` text NOT NULL,
  `AnhTour` text DEFAULT NULL,
  `NgayKhoiHanh` datetime DEFAULT NULL,
  `DiemKhoiHanh` varchar(100) DEFAULT NULL,
  `NgayThem` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tour`
--

INSERT INTO `tour` (`MaTour`, `MaLoai`, `TenTour`, `TGTour`, `GiaTour`, `NoiDungTour`, `AnhTour`, `NgayKhoiHanh`, `DiemKhoiHanh`, `NgayThem`) VALUES
(1, 'LT01', 'Tour Đà Nẵng', 'Khám phá thành phố biển', 5500000, 'Tham quan Bà Nà Hills, biển Mỹ Khê...', 'danang.jpg', '2026-03-15 00:00:00', 'Hà Nội', '2026-01-02 11:09:54'),
(2, NULL, 'Tour Tây Bắc', '3 ngày 2 đêm', 2000000, 'Hà Nội-Sa Pa', 'sapa1.jpg', NULL, 'Hà Nội', '0000-00-00 00:00:00'),
(3, 'LT01', 'Tour Đà Nẵng', 'Khám phá thành phố biển Đà Nẵng', 3500000, 'Tham quan Bà Nà Hills, biển Mỹ Khê, cầu Rồng', 'danang.jpg', '2026-03-15 08:00:00', 'Hà Nội', '2026-01-13 10:07:57'),
(4, 'LT01', 'Tour Sapa', 'Trải nghiệm vùng núi Tây Bắc', 2800000, 'Tham quan Fansipan, bản Cát Cát, chợ đêm Sapa', 'sapa.jpg', '2026-04-10 07:30:00', 'Hà Nội', '2026-01-13 10:07:57'),
(7, 'LT02', 'Tour Singapore', 'Thành phố sạch đẹp và hiện đại', 8500000, 'Tham quan Marina Bay, Sentosa, Garden by the Bay', 'singapore.jpg', '2026-06-01 10:00:00', 'TP.HCM', '2026-01-13 10:07:57'),
(15, 'LT01', 'Tour Phú Quốc', 'Khám phá đảo ngọc Phú Quốc', 4590000, 'Tham quan VinWonders, Grand World, Bãi Sao, lặn ngắm san hô và nghỉ dưỡng cao cấp.', 'phuquoc.jpg', '2026-04-05 00:00:00', 'TP Hồ Chí Minh', '2026-01-15 00:28:27'),
(16, 'LT01', 'Tour Đà Lạt', 'Thành phố ngàn hoa', 2890000, 'Tham quan Thung lũng Tình Yêu, hồ Xuân Hương, đồi chè Cầu Đất, khí hậu mát mẻ quanh năm.', 'dalat.jpg', '2026-04-12 00:00:00', 'TP Hồ Chí Minh', '2026-01-15 00:28:27'),
(17, 'LT02', 'Tour Thái Lan', 'Bangkok – Pattaya', 7990000, 'Tham quan chùa Vàng, đảo Coral, show nghệ thuật đặc sắc và mua sắm tại Bangkok.', 'thailan.jpg', '2026-05-01 00:00:00', 'Hà Nội', '2026-01-15 00:28:27'),
(18, 'LT02', 'Tour Singapore', 'Singapore – Sentosa – Universal Studio', 15990000, 'Khám phá quốc đảo sư tử: Merlion Park, đảo Sentosa, Universal Studio và mua sắm.', 'singapore.jpg', '2026-05-20 00:00:00', 'Hà Nội', '2026-01-15 00:28:27'),
(19, NULL, 'ou9u9u9', '3 ngày ', 999374734, 'loikfdvmx', '1768526441_1768153166_sapa1.jpg,1768526441_1768275708_danang3.jpg,1768526441_1768275708_gallery1.jpg', '2026-01-30 08:20:00', 'Hà Nội', '0000-00-00 00:00:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`UserAdmin`);

--
-- Chỉ mục cho bảng `chitietdat`
--
ALTER TABLE `chitietdat`
  ADD PRIMARY KEY (`MaDat`,`MaTour`),
  ADD KEY `MaTour` (`MaTour`);

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`MaCom`),
  ADD KEY `fk_cm_thanhvien` (`MaTVien`),
  ADD KEY `fk_cm_tour` (`MaTour`);

--
-- Chỉ mục cho bảng `dattour`
--
ALTER TABLE `dattour`
  ADD PRIMARY KEY (`MaDat`),
  ADD KEY `MaTVien` (`MaTVien`);

--
-- Chỉ mục cho bảng `loaitour`
--
ALTER TABLE `loaitour`
  ADD PRIMARY KEY (`MaLoai`);

--
-- Chỉ mục cho bảng `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD PRIMARY KEY (`MaTVien`);

--
-- Chỉ mục cho bảng `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`MaTour`),
  ADD KEY `MaLoai` (`MaLoai`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `dattour`
--
ALTER TABLE `dattour`
  MODIFY `MaDat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `thanhvien`
--
ALTER TABLE `thanhvien`
  MODIFY `MaTVien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `tour`
--
ALTER TABLE `tour`
  MODIFY `MaTour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdat`
--
ALTER TABLE `chitietdat`
  ADD CONSTRAINT `chitietdat_ibfk_1` FOREIGN KEY (`MaDat`) REFERENCES `dattour` (`MaDat`),
  ADD CONSTRAINT `chitietdat_ibfk_2` FOREIGN KEY (`MaTour`) REFERENCES `tour` (`MaTour`);

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_cm_thanhvien` FOREIGN KEY (`MaTVien`) REFERENCES `thanhvien` (`MaTVien`),
  ADD CONSTRAINT `fk_cm_tour` FOREIGN KEY (`MaTour`) REFERENCES `tour` (`MaTour`);

--
-- Các ràng buộc cho bảng `dattour`
--
ALTER TABLE `dattour`
  ADD CONSTRAINT `dattour_ibfk_1` FOREIGN KEY (`MaTVien`) REFERENCES `thanhvien` (`MaTVien`);

--
-- Các ràng buộc cho bảng `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`MaLoai`) REFERENCES `loaitour` (`MaLoai`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
