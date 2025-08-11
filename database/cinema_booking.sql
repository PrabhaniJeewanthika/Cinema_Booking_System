-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 06:02 PM
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
-- Database: `cinema_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(6) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `admin_email` varchar(150) DEFAULT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
('A001', 'James Smith', 'kasunmkarunarathne@gmail.com', 'James#2525');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `seats` text NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `movie_id`, `seats`, `booking_date`) VALUES
(1, 1, 1, 'C8', '2025-08-08 16:28:54'),
(2, 1, 1, 'C5,D5,E5,E6', '2025-08-08 16:39:26'),
(3, 1, 1, 'J10', '2025-08-11 20:27:09'),
(4, 1, 1, 'B10', '2025-08-11 21:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'Test User', 'user@example.com', '+94770000000', 'Hello', 'This is a test message.', '2025-08-11 20:58:38');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `Movie_Title` varchar(255) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `Genre` varchar(50) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `release_date` date DEFAULT NULL,
  `show_Time` time NOT NULL,
  `Movie_poster` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `Movie_Title`, `duration`, `Genre`, `Description`, `release_date`, `show_Time`, `Movie_poster`) VALUES
(1, 'Sample Movie', 120, '', '', NULL, '00:00:00', ''),
(2, 'Test Movie 2025-08-10 14:18:13', 120, 'Action', 'Test description', '2024-01-01', '19:30:00', ''),
(8, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(9, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(10, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(11, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(12, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(13, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(14, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(15, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', ''),
(16, 'BRING HER BACK', 139, 'Horror', 'Bring Her Back\" is a 2025 Australian supernatural psychological horror film about two orphaned siblings, Andy and Piper, who are placed in a foster home with a former social worker, Laura, and her strange son, Oliver. Soon, the siblings uncover disturbing secrets and a terrifying occult ritual within Laura\'s secluded home, forcing them to fight for their survival. The film explores themes of grief, loss, and the blurring lines between reality and the supernatural. ', '2025-05-29', '10:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_method` enum('cash','online') DEFAULT 'cash',
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Test User', 'testuser@example.com', 'password', 'user', '2025-08-03 15:44:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_email` (`email`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
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
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
