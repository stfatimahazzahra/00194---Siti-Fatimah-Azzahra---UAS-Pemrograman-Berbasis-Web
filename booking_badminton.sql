-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 05:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_badminton`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `court_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration` decimal(3,1) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid',
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `guest_name`, `guest_phone`, `court_id`, `booking_date`, `start_time`, `end_time`, `duration`, `total_price`, `status`, `payment_status`, `payment_proof`, `created_at`, `updated_at`) VALUES
(10, 'RP-20260705-7CAD', NULL, 'Muhamad Ramdhani Akbar', '083811655736', 4, '2026-07-12', '09:00:00', '12:00:00', 3.0, 150000.00, 'cancelled', 'unpaid', NULL, '2026-07-05 14:53:39', '2026-07-13 06:26:32'),
(11, 'RP-20260705-DCB8', NULL, 'Muhamad Ramdhani Akbar', '083811655736', 4, '2026-07-12', '18:00:00', '20:00:00', 2.0, 100000.00, 'completed', 'paid', NULL, '2026-07-05 15:09:25', '2026-07-13 06:26:12'),
(17, 'RP-20260713-A953', 4, 'Jane Doe', NULL, 3, '2026-07-13', '09:00:00', '11:00:00', 2.0, 130000.00, 'completed', 'paid', NULL, '2026-07-13 06:55:49', '2026-07-13 06:57:22'),
(18, 'RP-20260713-1FC3', NULL, 'Siti Fatimah Azzahra', '081289329699', 3, '2026-07-13', '17:00:00', '18:00:00', 1.0, 65000.00, 'cancelled', 'unpaid', NULL, '2026-07-13 08:27:39', '2026-07-13 08:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','maintenance') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`id`, `name`, `location`, `price`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Court 1 - Premium Vinyl', 'Hall A (Lantai Utama)', 80000.00, 'Lapangan dengan karpet vinyl standar internasional BWF. Empuk, tidak licin, mengurangi resiko cedera lutut. Dilengkapi penerangan LED anti silau.', 'court_1783922919_6a5480e7b8ed3.jpg', 'available', '2026-07-05 06:13:05', '2026-07-13 06:08:39'),
(2, 'Court 2 - Premium Vinyl', 'Hall A (Lantai Utama)', 80000.00, 'Lapangan dengan karpet vinyl standar internasional BWF. Kualitas pantulan shuttlecock yang sempurna dan pencahayaan optimal.', 'court_1783923677_6a5483dd4d758.jpg', 'available', '2026-07-05 06:13:05', '2026-07-13 06:21:17'),
(3, 'Court 3 - Wood Classic', 'Hall B (Samping)', 65000.00, 'Lapangan lantai parket kayu jati klasik yang memiliki grip alami sangat baik. Sangat disukai oleh pemain veteran karena feel pantulannya.', 'court_1783923187_6a5481f39ff34.jpg', 'available', '2026-07-05 06:13:05', '2026-07-13 08:30:25'),
(4, 'Court 4 - Standard Synthetic', 'Hall B (Samping)', 50000.00, 'Lapangan dengan lantai synthetic ekonomis namun tetap nyaman dan memenuhi standar keamanan. Pilihan terbaik untuk latihan rutin harian.', 'court_1783922937_6a5480f96d910.jpg', 'available', '2026-07-05 06:13:05', '2026-07-13 06:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `court_facilities`
--

CREATE TABLE `court_facilities` (
  `id` int(11) NOT NULL,
  `court_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `court_facilities`
--

INSERT INTO `court_facilities` (`id`, `court_id`, `facility_id`) VALUES
(59, 1, 4),
(60, 1, 3),
(61, 1, 2),
(62, 1, 6),
(63, 1, 5),
(64, 1, 1),
(65, 4, 2),
(66, 4, 5),
(79, 2, 4),
(80, 2, 3),
(81, 2, 2),
(82, 2, 6),
(83, 2, 5),
(84, 2, 1),
(113, 3, 2),
(114, 3, 6),
(115, 3, 5),
(116, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `facility_name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `facility_name`, `icon`) VALUES
(1, 'WiFi Gratis', 'fa-wifi'),
(2, 'Parkir Luas', 'fa-parking'),
(3, 'Loker & Ruang Ganti', 'fa-lock'),
(4, 'Kantin & Cafe', 'fa-coffee'),
(5, 'Toilet & Shower', 'fa-shower'),
(6, 'Penyewaan Raket', 'fa-tools');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `court_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `court_id`, `image`) VALUES
(1, 1, 'court1_detail1.jpg'),
(2, 1, 'court1_detail2.jpg'),
(3, 2, 'court2_detail1.jpg'),
(6, 4, 'img_6a49fe495896a_1783234121.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `court_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('booked','maintenance','blocked') DEFAULT 'booked',
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `court_id`, `date`, `start_time`, `end_time`, `status`, `notes`) VALUES
(1, 4, '2026-07-12', '13:00:00', '16:00:00', 'maintenance', '');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@rallyplay.com', '081234567890', '$2y$10$qa9Ku8wDnk5XM4bIaV1eeuP2G2w8Z1uTmWGslCnAZn9UsjDzhsIgK', 'admin', '2026-07-05 06:13:05', '2026-07-13 05:47:07'),
(2, 'SITI FATIMAH AZZAHRA', '118202500194@mhs.dinus.ac.id', '081289329699', '$2y$10$kt.vWdQU4u2Y4EMEYr9tCeS1DzBsRpsKJGYeqT0mtHOeKWL/0PNfW', 'customer', '2026-07-13 06:30:07', '2026-07-13 06:30:07'),
(3, 'Jhon Doe', 'jhondoe@gmail.com', '081209098787', '$2y$10$K6sMsdgm4GSnmXvRbXBhS.yRrHaCupLCifX3cCKYZ8P9uHVZR2ox2', 'customer', '2026-07-13 06:35:26', '2026-07-13 06:35:26'),
(4, 'Jane Doe', 'janedoe@gmail.com', '081209098787', '$2y$10$upWjTfkUoWEviY2lq03pjOSJhoW1DZYDBxF80dMg.CQApD0VITEHa', 'customer', '2026-07-13 06:54:41', '2026-07-13 06:54:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `court_id` (`court_id`);

--
-- Indexes for table `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `court_facilities`
--
ALTER TABLE `court_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `court_id` (`court_id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `court_id` (`court_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `court_id` (`court_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `court_facilities`
--
ALTER TABLE `court_facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `court_facilities`
--
ALTER TABLE `court_facilities`
  ADD CONSTRAINT `court_facilities_ibfk_1` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `court_facilities_ibfk_2` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
