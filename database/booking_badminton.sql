-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 07, 2026 at 06:37 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `guest_name`, `guest_phone`, `court_id`, `booking_date`, `start_time`, `end_time`, `duration`, `total_price`, `status`, `payment_status`, `payment_proof`, `created_at`, `updated_at`) VALUES
(10, 'RP-20260705-7CAD', NULL, 'Muhamad Ramdhani Akbar', '083811655736', 4, '2026-07-12', '09:00:00', '12:00:00', '3.0', '150000.00', 'pending', 'unpaid', NULL, '2026-07-05 14:53:39', '2026-07-05 15:03:25'),
(11, 'RP-20260705-DCB8', NULL, 'Muhamad Ramdhani Akbar', '083811655736', 4, '2026-07-12', '18:00:00', '20:00:00', '2.0', '100000.00', 'pending', 'unpaid', NULL, '2026-07-05 15:09:25', '2026-07-05 15:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','maintenance') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`id`, `name`, `location`, `price`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Court 1 - Premium Vinyl', 'Hall A (Lantai Utama)', '80000.00', 'Lapangan dengan karpet vinyl standar internasional BWF. Empuk, tidak licin, mengurangi resiko cedera lutut. Dilengkapi penerangan LED anti silau.', 'img_6a49fd74a13dd_1783233908.jpeg', 'available', '2026-07-05 06:13:05', '2026-07-05 06:45:08'),
(2, 'Court 2 - Premium Vinyl', 'Hall A (Lantai Utama)', '80000.00', 'Lapangan dengan karpet vinyl standar internasional BWF. Kualitas pantulan shuttlecock yang sempurna dan pencahayaan optimal.', 'img_6a49fd5f7f77c_1783233887.jpeg', 'available', '2026-07-05 06:13:05', '2026-07-05 06:44:47'),
(3, 'Court 3 - Wood Classic', 'Hall B (Samping)', '65000.00', 'Lapangan lantai parket kayu jati klasik yang memiliki grip alami sangat baik. Sangat disukai oleh pemain veteran karena feel pantulannya.', 'img_6a49fcd018282_1783233744.jpeg', 'available', '2026-07-05 06:13:05', '2026-07-05 06:42:24'),
(4, 'Court 4 - Standard Synthetic', 'Hall B (Samping)', '50000.00', 'Lapangan dengan lantai synthetic ekonomis namun tetap nyaman dan memenuhi standar keamanan. Pilihan terbaik untuk latihan rutin harian.', 'img_6a49fbea58831_1783233514.jpeg', 'available', '2026-07-05 06:13:05', '2026-07-05 06:38:34');

-- --------------------------------------------------------

--
-- Table structure for table `court_facilities`
--

CREATE TABLE `court_facilities` (
  `id` int(11) NOT NULL,
  `court_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `court_facilities`
--

INSERT INTO `court_facilities` (`id`, `court_id`, `facility_id`) VALUES
(21, 4, 5),
(22, 4, 2),
(23, 3, 5),
(24, 3, 3),
(25, 3, 2),
(26, 3, 1),
(27, 2, 6),
(28, 2, 5),
(29, 2, 4),
(30, 2, 3),
(31, 2, 2),
(32, 2, 1),
(33, 1, 6),
(34, 1, 5),
(35, 1, 4),
(36, 1, 3),
(37, 1, 2),
(38, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `facility_name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `rating` int(11) NOT NULL DEFAULT '5',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@rallyplay.com', '081234567890', '$2y$12$.XkYD3c0o5dr3GaYZEx5FOp6B1E2zDp83PRbojffPp4GjQt3x9CZ2', 'admin', '2026-07-05 06:13:05', '2026-07-05 06:13:05');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `court_facilities`
--
ALTER TABLE `court_facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
