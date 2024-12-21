-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 03:57 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE `bookmark` (
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmark`
--

INSERT INTO `bookmark` (`user_id`, `playlist_id`) VALUES
('JYr9yylhZrzgPLEhJveD', '8TOZQRx9aNkS8YZDe2E0');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `user_id`, `tutor_id`, `comment`, `date`) VALUES
('7LtYzBVgKz7Bf9ipYdke', 'Zau3dXAL1FUgGVoHUe2L', 'JYr9yylhZrzgPLEhJveD', 'm759mwj51LR82SifizEy', 'Love this', '2024-12-17'),
('oNRbh04Ru1HLTHSslPWh', 'Zau3dXAL1FUgGVoHUe2L', 'JYr9yylhZrzgPLEhJveD', 'm759mwj51LR82SifizEy', 'hi', '2024-12-18'),
('EWoxLDVDcEZ3untTypGx', 'tRfHwrbEq1HQidxebx87', 'Pkhuc71zYX1CZ8zybJdq', 'S0qvb4GlFzev4teNz7Fi', 'Greate', '2024-12-18'),
('VX0BrWXXT1R1k3YbtmuP', 'tRfHwrbEq1HQidxebx87', 'Pkhuc71zYX1CZ8zybJdq', 'S0qvb4GlFzev4teNz7Fi', 'hi', '2024-12-18');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` int(10) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `video` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `tutor_id`, `playlist_id`, `title`, `description`, `video`, `thumb`, `date`, `status`) VALUES
('Zau3dXAL1FUgGVoHUe2L', 'm759mwj51LR82SifizEy', '8TOZQRx9aNkS8YZDe2E0', 'How to code', 'hgfghjk', 'k5UUIIlStSJP6WYLbD5p.mp4', 'zxJnMj6U10XYHAlY21Vi.jpg', '2024-11-30', 'active'),
('tRfHwrbEq1HQidxebx87', 'S0qvb4GlFzev4teNz7Fi', 'gcWqoaM05PgSVZw4rCXO', 'Coding', 'learn coding', 'kPIUL44qeJbicfJve6Sy.mp4', '5rQdE3mmpRRKRDvheqUo.jpg', '2024-12-18', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`user_id`, `tutor_id`, `content_id`) VALUES
('JYr9yylhZrzgPLEhJveD', 'm759mwj51LR82SifizEy', 'Zau3dXAL1FUgGVoHUe2L'),
('Pkhuc71zYX1CZ8zybJdq', 'S0qvb4GlFzev4teNz7Fi', 'tRfHwrbEq1HQidxebx87');

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`id`, `tutor_id`, `title`, `description`, `thumb`, `date`, `status`) VALUES
('8TOZQRx9aNkS8YZDe2E0', 'm759mwj51LR82SifizEy', 'Web', 'How to', 'BUi45piZnPCFiLQ87QPE.JPG', '2024-11-30', 'active'),
('gcWqoaM05PgSVZw4rCXO', 'S0qvb4GlFzev4teNz7Fi', 'Programming', 'Programming', 'ixFETyfCwcCU2fjQctNb.png', '2024-12-18', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `profession` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `name`, `profession`, `email`, `password`, `image`) VALUES
('wQf2wxzNJtSzHbs9XwOs', 'John Paul', 'developer', 'ugo@gmail.com', '011c945f30ce2cbafc452f39840f025693339c42', 'XDjTKHMaUqGPWCJKP1SO.png'),
('fhWg3CYtHE2OturIYq0R', 'Peter', 'teacher', 'ik@gmail.com', '011c945f30ce2cbafc452f39840f025693339c42', '9e6Ahm79nsWdndxpXoIA.jpg'),
('m759mwj51LR82SifizEy', 'ugo', 'teacher', 'admin@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964', 'ijceLjnaP9seSC89qGuj.jpg'),
('CHEisQQx9SjRhabyWL1o', 'kico', 'teacher', 'kico@gmail.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'uPHtWGeyfqxGDWLYqps4.png'),
('S0qvb4GlFzev4teNz7Fi', 'Kinsly', 'developer', 'kin@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964', 'Z5wBdcZT3GhTrzL2qigY.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `time_spent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `time_spent`) VALUES
('', 'Admin', 'info@communitieswillconnect.com', 'Hello123@$', '', 0),
('', 'Admin', 'info@communitieswillconnect.com', 'Hello123@$', '', 0),
('0KFsZiHNqcZ8BNQsqleP', 'John Paul', 'admin@gmail.com', '011c945f30ce2cbafc452f39840f025693339c42', 'jx3YLNtB5DNPZ8d5xKIe.png', 0),
('RlA9EZdHBsljEixvsBHa', 'Peters', 'ch@gmail.com', '7b21848ac9af35be0ddb2d6b9fc3851934db8420', 'kJTioCYxpxLuy0SHBZOd.jpg', 0),
('JYr9yylhZrzgPLEhJveD', 'User1', 'user1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'PV4RrvbafG1Xmy8muFRN.png', 0),
('Pkhuc71zYX1CZ8zybJdq', 'Chi', 'chi@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964', 'lUJ5pt5aZAVI8DhVXpwO.png', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
