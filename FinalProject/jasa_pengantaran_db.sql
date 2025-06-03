-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Jun 07, 2024 at 01:02 PM
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
-- Database: `jasa_pengantaran_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tracking_number` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `shipment_date` date NOT NULL,
  `sender` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `sender_address` varchar(255) NOT NULL,
  `receiver_address` varchar(255) NOT NULL,
  `service` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `user_id`, `tracking_number`, `status`, `shipment_date`, `sender`, `receiver`, `sender_address`, `receiver_address`, `service`) VALUES
(1, 3, 'TRACK123', 'Shipped', '2024-06-01', 'Christian Roeroe', 'Romal Lengkong', 'Teling', 'Tikala', 'Express'),
(2, 4, 'TRACK456', 'In Transit', '2024-06-02', 'Romal Lengkong', 'Feykha Koem', 'Tikala', 'Kampus Bahu', 'Kilat'),
(3, 5, 'TRACK789', 'Delivered', '2024-06-03', 'Feykha Koem', 'Romal Lengkong', 'Kampus Bahu', 'Tikala', 'Biasa'),
(4, 6, 'TRACK101', 'Pending', '2024-06-04', 'Marvell Palenewen', 'Christian Roeroe', 'Kotamobagu', 'Teling', 'Express');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `user_type` enum('admin','member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `phone`, `user_type`) VALUES
(1, 'admin1', 'adminpassword1', 'Durant', 'admin1@example.com', '08991111222', 'admin'),
(2, 'admin2', 'adminpassword2', 'Admin Two', 'admin2@example.com', '2222222222', 'admin'),
(3, 'croeroe', 'memberpassword1', 'Christian Roeroe', 'croeroe@example.com', '3333333333', 'member'),
(4, 'rlengkong', 'memberpassword2', 'Romal Lengkong', 'rlengkong@example.com', '4444444444', 'member'),
(5, 'fkoem', 'memberpassword3', 'Feykha Koem', 'fkoem@example.com', '5555555555', 'member'),
(6, 'mpalenewen', 'memberpassword4', 'Marvell Palenewen', 'mpalenewen@example.com', '6666666666', 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
