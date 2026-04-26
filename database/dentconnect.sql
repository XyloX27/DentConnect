-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 05:04 AM
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
-- Database: `dentconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `dentist_name` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `dentist_name`, `appointment_date`, `appointment_time`, `reason`, `status`, `created_at`) VALUES
(1, 2, 'Dr. Williams', '2026-03-12', '14:19:00', 'sick', 'confirmed', '2026-03-03 08:17:52'),
(2, 2, 'Doctor', '2026-03-03', '14:32:00', 'sick', 'completed', '2026-03-03 08:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','doctor','staff','admin') NOT NULL DEFAULT 'patient',
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `created_at`) VALUES
(1, 'Staff\r\n', 'staff@dentconnect.com', '$2y$10$VREUnCzJ2JDWUgivMJ7A6eXzFmAXXqR7py.18COF96qOW4JN4a86G', 'staff', NULL, '2026-03-03 04:05:16'),
(2, 'Tahmid Tasin', 'tasin.bd77@gmail.com', '$2y$10$pjZWSG9SqNTX6xibvR1MQuk/l/tULm0IA5DSpL0cCYA0E/5YnCyIO', 'patient', '01866718971', '2026-03-03 04:25:01'),
(3, 'Administrator', 'admin@dentconnect.com', '$2y$10$WU5u8YP4wJb0g7jK9ykZyOYuE8b0bYeDe4/UrCnLu1GYJNgNvpv9q', 'admin', '', '2026-03-03 04:05:16'),
(4, 'Doctor', 'doctor@dentconnect.com', '$2y$10$F7HdTvapP92bhnxPT.8UWej6gYoFgQlaSPDDRi0B7XGLE8pwVNxpy', 'doctor', NULL, '2026-03-03 04:05:16');

-- --------------------------------------------------------

--
-- Table structure for table `xray_uploads`
--

CREATE TABLE `xray_uploads` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ai_result` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `xray_uploads`
--
ALTER TABLE `xray_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `xray_uploads`
--
ALTER TABLE `xray_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `xray_uploads`
--
ALTER TABLE `xray_uploads`
  ADD CONSTRAINT `xray_uploads_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
