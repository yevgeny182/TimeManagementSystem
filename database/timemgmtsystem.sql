-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 02:27 PM
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
-- Database: `timemgmtsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_10_21_132332_add_name_to_users_table', 1),
(2, '2024_10_21_132933_drop_name_from_users_table', 2),
(3, '2024_10_21_133216_drop_password_from_users_table', 2),
(4, '2024_10_21_133614_add_details_to_users_table', 2),
(5, '2024_10_21_133649_remove_email_verified_and_remember_token_from_users_table', 3),
(6, '2024_10_21_142644_add_login_logout_time_to_users_table', 3),
(7, '2024_10_22_005249_create_sessions_table', 4),
(8, '2024_10_22_010307_create_users_table', 5),
(9, '2024_10_22_011328_create_sessions_table', 6),
(10, '2024_10_22_011827_add_email_to_users_table', 7),
(11, '2024_10_22_012048_create_cache_table', 8),
(12, '2024_10_22_091207_add_missing_fields_to_users_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('UKSC0QpcglPdnXMKY0irF8sisQwoh1ZNOGMT2x8L', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXZZUFQwQ2lpOVRRUlAzb08xblkySEZTTnBkRXh0TndUbGJFdnRWVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9UTVMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1729682126);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `login_time` varchar(255) DEFAULT NULL,
  `logout_time` varchar(255) DEFAULT NULL,
  `time_remaining` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created_at`, `updated_at`, `first_name`, `last_name`, `email`, `phone_number`, `login_time`, `logout_time`, `time_remaining`) VALUES
(1, '2024-10-22 01:14:25', '2024-10-23 02:59:01', 'test_user', 'test', 'yevalbano420@gmail.com', '09212707941', '2024-10-23 11:17:02', '2024-10-23 11:20:27', '04:00:00'),
(2, '2024-10-22 01:31:48', '2024-10-23 02:58:55', 'Yevgeny', 'Albano', 'mikeoxlong@gmail.com', '1110002929', '2024-10-23 10:12:43', '2024-10-23 10:24:20', '08:00:00'),
(7, '2024-10-22 02:56:49', '2024-10-22 18:24:19', 'Magnus', 'Carlsen', 'MCarlsen@gmail.com', '33091087391', '2024-10-23 10:12:44', '2024-10-23 10:24:19', NULL),
(8, '2024-10-22 03:21:37', '2024-10-22 18:24:18', 'Michael', 'Che', 'Mche@mail.com', '2012001998', '2024-10-23 10:12:44', '2024-10-23 10:24:18', NULL),
(11, '2024-10-22 04:23:25', '2024-10-22 06:16:22', 'Che', 'Guevarra', 'Cguevarra@gmail.com', '9210009918', '2024-10-22 21:10:39', '2024-10-22 22:16:22', NULL),
(12, '2024-10-22 04:47:35', '2024-10-22 06:16:22', 'new', 'user', 'testing@newMail.com', '9990001193', '2024-10-22 21:10:40', '2024-10-22 22:16:22', NULL),
(35, '2024-10-23 01:11:04', '2024-10-23 02:08:55', 'Zotac', 'Gaming', 'Zotac_Gaming@yahoo.com', 'NA', '2024-10-23 18:06:22', '2024-10-23 18:08:55', NULL),
(36, '2024-10-23 02:05:57', '2024-10-23 02:09:05', 'bag o', 'nga user', 'bagonisiya@mail.com', 'walay phone', '2024-10-23 18:06:00', '2024-10-23 18:09:05', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
