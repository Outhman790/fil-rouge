-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2023 at 05:12 AM
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
-- Database: `sandik`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Emergency meeting', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis dicta incidunts Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis dicta inciduntsLorem ipsum dolor sit amet consectetur adipisicing elit. Corporis dicta inciduntsLorem ipsum', 'aze.jpeg', '2023-06-15 12:01:50', '2023-06-15 12:01:50'),
(9, 'Stairs fixing', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad saepe architecto veritatis blanditiis praesentium asperiores esse pariatur reprehenderit nihil, ipsam deserunt dolore nisi quod laborum cum cupiditate ab nulla nemo!\r\nUnde repellat culpa obcaecat', 'stairs.jpeg', '2023-06-17 21:25:58', '2023-06-17 21:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `announcement_id`, `resident_id`, `comment`, `created_at`) VALUES
(1, 1, 8, 'I will be there', '2023-06-15 12:02:50'),
(2, 1, 31, 'I will attend', '2023-06-15 12:03:50'),
(3, 1, 32, 'I will be there', '2023-06-15 12:04:50'),
(4, 1, 33, 'I will attend', '2023-06-15 12:05:50'),
(5, 1, 34, 'I will be there', '2023-06-15 12:06:50'),
(6, 1, 35, 'I will attend', '2023-06-15 12:07:50'),
(7, 1, 38, 'I will be there', '2023-06-15 12:08:50'),
(8, 1, 39, 'I will attend', '2023-06-15 12:09:50'),
(9, 1, 40, 'I will be there', '2023-06-15 12:10:50'),
(10, 1, 41, 'I will attend', '2023-06-15 12:11:50'),
(11, 1, 42, 'I will be there', '2023-06-15 12:12:50'),
(12, 1, 43, 'I will attend', '2023-06-15 12:13:50'),
(13, 1, 44, 'I will be there', '2023-06-15 12:14:50'),
(14, 1, 45, 'I will attend', '2023-06-15 12:15:50'),
(15, 9, 8, 'I will be there', '2023-06-17 21:26:58'),
(16, 9, 31, 'I will attend', '2023-06-17 21:27:58'),
(17, 9, 32, 'I will be there', '2023-06-17 21:28:58'),
(18, 9, 33, 'I will attend', '2023-06-17 21:29:58'),
(19, 9, 34, 'I will be there', '2023-06-17 21:30:58'),
(20, 9, 35, 'I will attend', '2023-06-17 21:31:58'),
(21, 9, 38, 'I will be there', '2023-06-17 21:32:58'),
(22, 9, 39, 'I will attend', '2023-06-17 21:33:58'),
(23, 9, 40, 'I will be there', '2023-06-17 21:34:58'),
(24, 9, 41, 'I will attend', '2023-06-17 21:35:58'),
(25, 9, 42, 'I will be there', '2023-06-17 21:36:58'),
(26, 9, 43, 'I will attend', '2023-06-17 21:37:58'),
(27, 9, 44, 'I will be there', '2023-06-17 21:38:58'),
(28, 9, 45, 'I will attend', '2023-06-17 21:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `announcement_id`, `resident_id`, `created_at`) VALUES
(1, 1, 8, '2023-06-15 12:02:50'),
(2, 1, 31, '2023-06-15 12:03:50'),
(3, 1, 32, '2023-06-15 12:04:50'),
(4, 1, 33, '2023-06-15 12:05:50'),
(5, 1, 34, '2023-06-15 12:06:50'),
(6, 1, 35, '2023-06-15 12:07:50'),
(7, 1, 38, '2023-06-15 12:08:50'),
(8, 1, 39, '2023-06-15 12:09:50'),
(9, 1, 40, '2023-06-15 12:10:50'),
(10, 1, 41, '2023-06-15 12:11:50'),
(11, 1, 42, '2023-06-15 12:12:50'),
(12, 1, 43, '2023-06-15 12:13:50'),
(13, 1, 44, '2023-06-15 12:14:50'),
(14, 1, 45, '2023-06-15 12:15:50'),
(15, 9, 8, '2023-06-17 21:26:58'),
(16, 9, 31, '2023-06-17 21:27:58'),
(17, 9, 32, '2023-06-17 21:28:58'),
(18, 9, 33, '2023-06-17 21:29:58'),
(19, 9, 34, '2023-06-17 21:30:58'),
(20, 9, 35, '2023-06-17 21:31:58'),
(21, 9, 38, '2023-06-17 21:32:58'),
(22, 9, 39, '2023-06-17 21:33:58'),
(23, 9, 40, '2023-06-17 21:34:58'),
(24, 9, 41, '2023-06-17 21:35:58'),
(25, 9, 42, '2023-06-17 21:36:58'),
(26, 9, 43, '2023-06-17 21:37:58'),
(27, 9, 44, '2023-06-17 21:38:58'),
(28, 9, 45, '2023-06-17 21:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `payment_month` int(11) DEFAULT NULL,
  `payment_year` int(11) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `resident_id`, `payment_month`, `payment_year`, `transaction_id`) VALUES
(42, 29, 5, 2023, 'pi_3NIw6WJsAbiLXvIK0uEMHvmf'),
(185, 33, 12, 2021, 'pi_3NL67aJsAbiLXvIK0S0u0aQL'),
(191, 8, 11, 2022, 'pi_3NL6WEJsAbiLXvIK0dvC47HZ'),
(192, 8, 12, 2022, 'pi_3NL6WdJsAbiLXvIK0UcgaPZY'),
(193, 8, 1, 2023, 'pi_3NL6X6JsAbiLXvIK0PJ3ATYU'),
(194, 8, 2, 2023, 'pi_3NLD9CJsAbiLXvIK1NKYxGF4'),
(195, 32, 4, 2023, 'pi_3NLPbFJsAbiLXvIK1SwKdGFs'),
(196, 38, 4, 2023, 'pi_3NLPcoJsAbiLXvIK16XQkdD7'),
(197, 38, 5, 2023, 'pi_3NLPfHJsAbiLXvIK1N5UVSRk'),
(198, 38, 6, 2023, 'pi_3NLPfvJsAbiLXvIK10xjHGUc'),
(199, 8, 3, 2023, 'pi_3NLRIuJsAbiLXvIK1mwvk2tC');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `purchase_month` int(11) DEFAULT NULL,
  `purchase_year` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `purchase_month`, `purchase_year`, `description`, `invoice`, `amount`, `date`, `name`) VALUES
(22, 5, 2023, 'Dyeing and reparation ', 'Invoice9961605.png', 1500, '2023-05-31', 'Utilities repair'),
(23, 1, 2023, 'I relied on Med Inc., a trusted construction supplier, for staircase repair materials. Their quality components enabled me to repair the staircase.', 'Invoice1661601.png', 500, '2023-05-31', 'Staircase reparation'),
(24, 2, 2023, 'Fixed the door wrist.', 'Invoice5991202.png', 150, '2023-05-31', 'Door wrist'),
(25, 3, 2023, 'Replaced all old message boxes with new ones.', 'Invoice2912603.png', 2000, '2023-05-31', 'Message boxes '),
(26, 4, 2023, 'I changed two broken lamps', 'Invoice29126-104.png', 200, '2023-05-31', 'Lamps'),
(28, 6, 2023, 'affaf', 'Invoice9961605.png', 2000, '2023-06-01', 'test'),
(46, 12, 2023, 'Laboris non adipisic', 'stairs.jpeg', 2500, '2023-06-21', 'Magee Benjamin');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `resident_id` int(11) NOT NULL,
  `fName` varchar(255) DEFAULT NULL,
  `lName` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Resident',
  `username` varchar(255) DEFAULT NULL,
  `joinedIn` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`resident_id`, `fName`, `lName`, `email`, `password`, `status`, `username`, `joinedIn`) VALUES
(8, 'Igor', 'Pennington', 'ficezihel@mailinator.com', '$2y$10$NM7K9fTLKtNeKKdRMx9rMOi4/0zH0gIkIXjvdDZ2hhpVrX9RmVYjG', 'Resident', 'hasyvud', '2022-11-01 11:21:07'),
(29, 'Yetta ', 'Beck ', 'lasuzy@mailinator.com', '123', 'Previous resident', 'wykahewan', '2023-03-15 08:36:59'),
(30, 'Bert', 'Roth', 'gehoj@mailinator.com', '$2y$10$Nj1g57itbYZSR2BhYewOTeYqXnAruOSo/gYdNo0PlrESN/hmeOLDi', 'Previous resident', 'kivugu', '2023-03-15 08:24:56'),
(31, 'Dalton', 'Sims', 'woxecib@mailinator.com', '$2y$10$pE2d00jySXugs6lVXIsxNeIHJIHazx1TfG//cLSpubbB.bav7qIoW', 'Resident', 'sugizo', '2023-03-14 20:13:13'),
(32, 'Noah', 'Alston', 'manilyb@mailinator.com', '$2y$10$O5cKEyQ62zQ1.zmovQtsWuVkLz1SxsVs3h.U7YEJmJletcJeoWD2m', 'Resident', 'dizehofyxa', '2023-03-14 20:13:22'),
(33, 'Halla', 'Fitzpatrick', 'nuju@mailinator.com', '$2y$10$Uuvwd2bUBROPPbAsblSpKuhSJCHt/RtLex9JlNLw/CxDzgzH0isVm', 'Resident', 'sytuca', '2021-12-14 20:13:33'),
(34, 'Damon', 'Pacheco', 'xylugitet@mailinator.com', '$2y$10$4GxqHT.n6Pa1yRFIHPZcZ.AeXaL1oggEKnsOf/ovSjUaFYM5Hx6pC', 'Resident', 'puredu', '2023-03-14 20:13:42'),
(35, 'Seth', 'Frank', 'xewixyve@mailinator.com', '$2y$10$AMJ27PaTx5p4xyEsdWOJgeB8W8flYDaF7.WeK4kl0PI8zXbOeVW0S', 'Resident', 'wymywuwo', '2023-03-14 20:13:48'),
(36, 'Abdul', 'Morton', 'rubucoze@mailinator.com', '$2y$10$s8uzDdGojTvCV1xuJfa.FOS54o8T8hkKgXvDp8wQXIBaUlQDYILzK', 'Previous resident', 'qitiwovi', '2023-03-19 15:22:08'),
(37, 'Forrest', 'Case', 'gujo@mailinator.com', '$2y$10$kdB9XITtppRixoHvmxvYQeAn5E.BLdi7VYgSKk0FZYBMYsyV3hROa', 'Previous resident', 'hovuq', '2023-03-19 15:22:44'),
(38, 'Malcolm', 'Lambert', 'solipeg@mailinator.com', '$2y$10$C.i0Gd8JrVIBgj96TYVEiOV84fxFpN7l7fj2wD6UhkQEnDahVqgBe', 'Resident', 'jytesisom', '2023-03-14 20:14:12'),
(39, 'Hannah', 'Montana', 'saf@asf.asf', '$2y$10$IBKV2hJ8cskkL7jYdk7SCurSHRvgb6jMcBxOVAY14aULWOf.WEy.a', 'Resident', 'asf9', '2023-05-19 14:48:40'),
(40, 'Outhman', 'Moumou', 'tangerino2011@gmail.com', '$2y$10$K4zi6XZlPY7kTYiROL/etOVAt2gtw4qD6uvwwSsawr9eK0DVWTDVm', 'Admin', 'Outhman790', '2025-07-14 02:51:07'),
(41, 'Outhman', 'MOUMOU', 'afaf@afa.afa', '$2y$10$nMgZ4ZPMGD0h60OAin/1jOrkP60QYJh4yc39zZHmUg/wGd..eXoPW', 'Resident', 'out790', '2023-06-21 14:03:50'),
(42, 'Amine', 'Almchattab', 'afasf@faf.asf', '$2y$10$cWZqbaIOfmT/Xy0wpn7sA./oHDMcc76kPSoeaitHGEPDXLxb6Zybq', 'Previous resident', 'asf94', '2023-06-21 14:03:28'),
(43, 'Camden', 'Chavez', 'gegasuvy@mailinator.com', '$2y$10$rL.ejoqUUmp.Y8yRO/vsiuLO28vmWaYsldNFyL4YNrNAo5pX4lpWe', 'Resident', 'munuzyfyq', '2023-06-21 14:04:40'),
(44, 'Meagan', 'Hermann', 'fakedata12817@gmail.com', '$2y$10$diOcfq9BmzofqF2zz0vRQed0fczQphkNhp8pK05x6VKDja5gVh6WW', 'Resident', 'Fidel17', '2025-07-11 19:09:35'),
(45, 'Pete', 'Ratke', 'fakedata17319@gmail.com', '$2y$10$CANGvDWlsa2CezM4Oh5EROtlHKL573He1PKUUOy/B81d/guQk4IRG', 'Resident', 'Ryan234', '2025-07-11 19:10:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `unique_like` (`announcement_id`,`resident_id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`resident_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `resident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`announcement_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`resident_id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`announcement_id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`resident_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`resident_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */; 