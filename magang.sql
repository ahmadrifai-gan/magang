-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2026 at 10:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `year` year NOT NULL,
  `total_quota` int NOT NULL DEFAULT '12',
  `used_quota` int NOT NULL DEFAULT '0',
  `remaining_quota` int NOT NULL DEFAULT '12',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `user_id`, `year`, `total_quota`, `used_quota`, `remaining_quota`, `created_at`, `updated_at`) VALUES
(1, 1, 2026, 12, 0, 12, '2026-04-14 11:00:12', '2026-04-14 11:00:12'),
(7, 8, 2026, 12, 10, 2, '2026-04-15 02:38:48', '2026-05-13 02:50:24'),
(10, 11, 2026, 12, 0, 12, '2026-05-11 19:02:35', '2026-05-11 19:02:35'),
(12, 13, 2026, 12, 0, 12, '2026-05-13 02:32:56', '2026-05-13 02:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approval_notes` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `start_date`, `end_date`, `reason`, `attachment_path`, `status`, `approved_by`, `approval_notes`, `approved_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 7, '2026-04-16', '2026-04-16', 'sakit dan harus dirumah sakit', NULL, 'rejected', 1, 'skadaksmdlas', '2026-04-15 08:55:15', NULL, '2026-04-15 02:28:41', '2026-04-15 08:55:15'),
(2, 8, '2026-04-16', '2026-04-16', 'dirumah sakit lagi sakit', NULL, 'approved', 1, NULL, '2026-04-15 03:09:31', NULL, '2026-04-15 02:43:41', '2026-04-15 03:09:31'),
(3, 8, '2026-05-20', '2026-05-22', 'Liburan keluarga', NULL, 'approved', 1, NULL, '2026-04-15 08:55:00', NULL, '2026-04-15 08:50:28', '2026-04-15 08:55:00'),
(4, 8, '2026-05-20', '2026-05-22', 'Liburan keluarga', NULL, 'approved', 1, NULL, '2026-05-13 02:50:24', NULL, '2026-05-11 18:28:35', '2026-05-13 02:50:24'),
(6, 8, '2026-05-13', '2026-05-14', 'fgfgbjkhjbjbj', 'leave-attachments/VuAg1G4l4wkdhQo7YoDeyoX8qSGtu6v4FCgS3yB3.pdf', 'approved', 1, 'Disetujui, semoga liburnya menyenangkan', '2026-05-11 19:31:32', NULL, '2026-05-11 19:30:26', '2026-05-11 19:31:32'),
(7, 8, '2026-05-14', '2026-05-14', 'jalan jalan', 'leave-attachments/c0KgW5OuR5ofvj4PDP7DVVrMsK69TzWojp7IzoAD.jpg', 'approved', 1, NULL, '2026-05-13 02:50:06', NULL, '2026-05-13 02:49:24', '2026-05-13 02:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_04_15_000000_add_oauth_columns_to_users_table', 1),
(6, '2026_04_15_000001_create_leave_requests_table', 1),
(7, '2026_04_15_000002_create_leave_balances_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 7, 'auth_token', 'b98befa05675624bd7a9f597fd28c3cb3322387f252ed12c4ca003f892a08714', '[\"*\"]', NULL, NULL, '2026-04-14 12:18:01', '2026-04-14 12:18:01'),
(2, 'App\\Models\\User', 1, 'auth_token', '0d6d04e45f09a21e2d3dfd0594acacd241571303c1ee986cdd0c69e22cece497', '[\"*\"]', '2026-04-15 00:04:57', NULL, '2026-04-15 00:04:45', '2026-04-15 00:04:57'),
(3, 'App\\Models\\User', 7, 'auth_token', '625a71b1d02906763816864231e1263dad2318f190a706cad07fa3270b1c577d', '[\"*\"]', '2026-04-15 00:08:43', NULL, '2026-04-15 00:08:41', '2026-04-15 00:08:43'),
(4, 'App\\Models\\User', 1, 'auth_token', '5bfcceb65662f70909c438989d8216c14227abc20b94106bfb576e07aea7072b', '[\"*\"]', '2026-04-15 02:21:45', NULL, '2026-04-15 00:10:40', '2026-04-15 02:21:45'),
(5, 'App\\Models\\User', 7, 'auth_token', '367ce518a241eb06e163b2d9678660356ef7b8e1207ad82e29eaa0ac75b0527b', '[\"*\"]', '2026-04-15 02:29:19', NULL, '2026-04-15 02:22:29', '2026-04-15 02:29:19'),
(6, 'App\\Models\\User', 1, 'auth_token', 'a2adf0867bccb0be2fbbbb942e3cdd47c1382df55c2f729f576862c7aac14e45', '[\"*\"]', '2026-04-15 02:37:52', NULL, '2026-04-15 02:29:51', '2026-04-15 02:37:52'),
(7, 'App\\Models\\User', 8, 'auth_token', 'b87bb1d271c6778dbe741a124ab043e2b810407203cda21f006cd71e1c9c399f', '[\"*\"]', '2026-04-15 02:44:02', NULL, '2026-04-15 02:38:48', '2026-04-15 02:44:02'),
(8, 'App\\Models\\User', 1, 'auth_token', '1560c917c75d392761aaf78dcc02d83ee33581cec810bed39e2f98cfd058bc5c', '[\"*\"]', '2026-04-15 02:54:07', NULL, '2026-04-15 02:44:56', '2026-04-15 02:54:07'),
(9, 'App\\Models\\User', 8, 'auth_token', '394552c516408a6f9fed198770203544a5ee1b5c378e61537d54a1b0d3baf67b', '[\"*\"]', '2026-04-15 02:54:31', NULL, '2026-04-15 02:54:29', '2026-04-15 02:54:31'),
(10, 'App\\Models\\User', 1, 'auth_token', '311428b4cc89ab6a1b990059d046ba4566b9f562261db5e98d857dbeae9997a8', '[\"*\"]', NULL, NULL, '2026-04-15 03:00:51', '2026-04-15 03:00:51'),
(11, 'App\\Models\\User', 8, 'auth_token', 'f0e2020c8f90d7726bf5f9c271253a65cb3fe1fc2c0a3cbcf3c93b6fe3437613', '[\"*\"]', NULL, NULL, '2026-04-15 03:10:32', '2026-04-15 03:10:32'),
(12, 'App\\Models\\User', 8, 'auth_token', '80c5ca69f08a0d2a6f5bf5bb373e6ee87f528d5b1d393182d3902d3f739f6910', '[\"*\"]', NULL, NULL, '2026-04-15 08:18:23', '2026-04-15 08:18:23'),
(13, 'App\\Models\\User', 8, 'auth_token', '85a3e01fcb126e2e927a2b36a48eb46c07f0b6851747823d6eaf3876befd4d34', '[\"*\"]', NULL, NULL, '2026-04-15 08:53:07', '2026-04-15 08:53:07'),
(14, 'App\\Models\\User', 1, 'auth_token', '620428b8166aedcd9605faef469d78218307e535c93b0903c6111678f7919dec', '[\"*\"]', NULL, NULL, '2026-04-15 08:54:32', '2026-04-15 08:54:32'),
(15, 'App\\Models\\User', 8, 'auth_token', '0b2b73763e7ff83c6bebcc982fb6b2b1cd0757c2faf1b53b1d05183a75a8f664', '[\"*\"]', NULL, NULL, '2026-04-15 08:56:10', '2026-04-15 08:56:10'),
(16, 'App\\Models\\User', 1, 'auth_token', '3c9031b44785f4439232a8f50be867fe0ee95b6c2ffaaf480e3791d8716fb022', '[\"*\"]', NULL, NULL, '2026-04-15 09:58:08', '2026-04-15 09:58:08'),
(17, 'App\\Models\\User', 1, 'auth_token', 'a89d87c6c3c2100b96eea9f0448e4c36cbe59bde72011719c8500574beb86057', '[\"*\"]', NULL, NULL, '2026-04-15 10:43:20', '2026-04-15 10:43:20'),
(18, 'App\\Models\\User', 1, 'auth_token', 'fc700ed029cde92b2a0a4b23be848f17bc43ce35b1186b8f357dfaed044de417', '[\"*\"]', NULL, NULL, '2026-04-16 09:36:54', '2026-04-16 09:36:54'),
(19, 'App\\Models\\User', 8, 'auth_token', 'b4645806a6d725b0793a40908fd0c2c523f382d616b1be6b6a51586904e4a85e', '[\"*\"]', '2026-04-18 04:33:09', NULL, '2026-04-18 04:32:22', '2026-04-18 04:33:09'),
(20, 'App\\Models\\User', 9, 'auth_token', '69c0867f29faa3a78f115195b67f4270454dfc3e4222942ab3ecd2052fdb6717', '[\"*\"]', NULL, NULL, '2026-04-18 04:33:35', '2026-04-18 04:33:35'),
(21, 'App\\Models\\User', 9, 'auth_token', '7581aaf3b3223343c6f813b511463248ce9a186449ace08512ede338556aaf0d', '[\"*\"]', NULL, NULL, '2026-04-18 04:38:38', '2026-04-18 04:38:38'),
(22, 'App\\Models\\User', 8, 'auth_token', '5c41a29f2b8129c56d02cbb2fa711b82879b9d96bd6710c6f8768214b6816f44', '[\"*\"]', NULL, NULL, '2026-04-18 04:42:35', '2026-04-18 04:42:35'),
(23, 'App\\Models\\User', 8, 'auth_token', 'e75fb6d945062532b6ad162e56c89d051d3dad0e59ead03270838ac6753374a6', '[\"*\"]', NULL, NULL, '2026-05-11 18:28:19', '2026-05-11 18:28:19'),
(24, 'App\\Models\\User', 10, 'auth_token', '5a43aec65846b7585fde43eb6e5402cd389f846f7da1862832348e32f0275c53', '[\"*\"]', NULL, NULL, '2026-05-11 19:01:46', '2026-05-11 19:01:46'),
(25, 'App\\Models\\User', 11, 'auth_token', 'd618f0e9573d214104289822ed656dd6d4ee4674adecd8d78d18fbd6c9532c47', '[\"*\"]', NULL, NULL, '2026-05-11 19:02:35', '2026-05-11 19:02:35'),
(26, 'App\\Models\\User', 12, 'auth_token', '2f6dcd1f310be4e7db946129884867496546e67eab4f39b8d857c39eae56c1f2', '[\"*\"]', NULL, NULL, '2026-05-11 19:04:03', '2026-05-11 19:04:03'),
(27, 'App\\Models\\User', 12, 'auth_token', '7eb4ca9e463e5ce80ca8b3995045cf569f6a75e466682293bac8981765e0bf17', '[\"*\"]', NULL, NULL, '2026-05-11 19:06:33', '2026-05-11 19:06:33'),
(28, 'App\\Models\\User', 1, 'auth_token', 'c34dd2501efc8c140099d8e69ae5b784e5433623525e608799c6e9ac2e0634c2', '[\"*\"]', NULL, NULL, '2026-05-11 19:17:56', '2026-05-11 19:17:56'),
(29, 'App\\Models\\User', 1, 'auth_token', '7c035d6fb647711962952b436d35022676fc292b1670e4e69bd5d1a5de70e8a0', '[\"*\"]', NULL, NULL, '2026-05-11 19:28:58', '2026-05-11 19:28:58'),
(30, 'App\\Models\\User', 8, 'auth_token', 'f0b388f8fea847ce30db03cf791f6d6919261e52f013b19ed69de8384ffa3722', '[\"*\"]', NULL, NULL, '2026-05-11 19:29:35', '2026-05-11 19:29:35'),
(31, 'App\\Models\\User', 1, 'auth_token', '3e4cbbeb17238f6479ceed2d0a5a08b38096be9d9d554cd4df937878ca0cfdd7', '[\"*\"]', NULL, NULL, '2026-05-11 19:32:28', '2026-05-11 19:32:28'),
(32, 'App\\Models\\User', 8, 'auth_token', 'a39293fad4b23b7e745ef052ea55f57faa741922b3995fbf5791c68965abf88c', '[\"*\"]', NULL, NULL, '2026-05-13 02:22:03', '2026-05-13 02:22:03'),
(33, 'App\\Models\\User', 12, 'auth_token', 'fd999adc93c57f96d825bc0e509b6b914cd77492443e81490720af707c5bd03b', '[\"*\"]', NULL, NULL, '2026-05-13 02:30:29', '2026-05-13 02:30:29'),
(34, 'App\\Models\\User', 12, 'auth_token', '640949777e5d68bd60dbcd93a655e27fb32c6476006cc82a73cc7560ba5af8da', '[\"*\"]', NULL, NULL, '2026-05-13 02:31:14', '2026-05-13 02:31:14'),
(35, 'App\\Models\\User', 13, 'auth_token', '29d633be2c2cbb9e006e4f39a53f3eb9ad5380cd7f071371eedb55ea19f4b6ec', '[\"*\"]', NULL, NULL, '2026-05-13 02:32:56', '2026-05-13 02:32:56'),
(36, 'App\\Models\\User', 8, 'auth_token', '4ad631bac704abac736c4676f1c9fb3276fa2edd72453307dfdfe0ba9a9d25e4', '[\"*\"]', NULL, NULL, '2026-05-13 02:48:23', '2026-05-13 02:48:23'),
(37, 'App\\Models\\User', 1, 'auth_token', 'f83715db15c4083c7fd27c2fb1a26439d3d06e5a89cdf7dc9b16c9e60cc926d9', '[\"*\"]', NULL, NULL, '2026-05-13 02:49:53', '2026-05-13 02:49:53'),
(38, 'App\\Models\\User', 8, 'auth_token', '7d344b2a85fb078214e34e24f5ef91715f6fb60f0c8fe59fba3ecb157ef052b1', '[\"*\"]', NULL, NULL, '2026-05-13 02:50:45', '2026-05-13 02:50:45'),
(39, 'App\\Models\\User', 8, 'auth_token', '0922b0fe7e275bcc75890d9fa9d20e071cd6ce9283d8589ce586750f863cd459', '[\"*\"]', NULL, NULL, '2026-05-13 03:40:15', '2026-05-13 03:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `oauth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oauth_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `oauth_id`, `oauth_provider`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', 'admin', NULL, NULL, '2026-04-14 11:00:12', '$2y$10$ivFkoPboREHiYaMg.aZVeePINIrijm0yAzOyw13T9K3HLCuZhIXSK', NULL, '2026-04-14 11:00:12', '2026-04-14 11:00:12'),
(7, 'John Doe', 'john.doe@example.com', 'employee', NULL, NULL, NULL, '$2y$10$Q4uksmD4E6M5AcJ2JS7VMOTFBB85WSDMm9R4SBssD6voqONNv5rke', NULL, '2026-04-14 12:18:00', '2026-04-14 12:18:00'),
(8, 'Ahmad Rifa\'i', 'rifaiuye241@gmail.com', 'employee', '102065668258472158732', 'google', NULL, '$2y$10$Bn6zTuDY4I/HOum2ccWmJONcP7dI8hRfZfgoUuhqCRywXKG0OuY16', NULL, '2026-04-15 02:38:48', '2026-05-13 03:40:15'),
(11, 'aku sukses', 'paisukses59@gmail.com', 'employee', NULL, NULL, NULL, '$2y$10$Jmm8OuCXttOIPGiF4A/quexRPCv1fAi08CzMjeAXNbJq2gvlmD3Oi', NULL, '2026-05-11 19:02:35', '2026-05-11 19:02:35'),
(13, 'Ahmad Rifai', 'ahmadrifai58291@gmail.com', 'employee', NULL, NULL, NULL, '$2y$10$2nMlR8Nbf3bzu1mge2.nF..ps7hdfK4ZZXK9Qr8tvqAdfshNbqgWy', NULL, '2026-05-13 02:32:56', '2026-05-13 02:32:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_balances_user_id_year_unique` (`user_id`,`year`),
  ADD KEY `leave_balances_user_id_index` (`user_id`),
  ADD KEY `leave_balances_year_index` (`year`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `leave_requests_user_id_index` (`user_id`),
  ADD KEY `leave_requests_status_index` (`status`),
  ADD KEY `leave_requests_start_date_index` (`start_date`),
  ADD KEY `leave_requests_end_date_index` (`end_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_oauth_id_unique` (`oauth_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
