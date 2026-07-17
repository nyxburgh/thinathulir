-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 09:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `thinathulir`
--

-- --------------------------------------------------------

--
-- Table structure for table `tn_activity_log`
--

CREATE TABLE `tn_activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity` varchar(50) DEFAULT NULL,
  `entity_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_clicks`
--

CREATE TABLE `tn_ad_clicks` (
  `id` int(10) UNSIGNED NOT NULL,
  `ad_id` int(10) UNSIGNED NOT NULL,
  `image_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_hash` varchar(64) DEFAULT NULL,
  `clicked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_ad_clicks`
--

INSERT INTO `tn_ad_clicks` (`id`, `ad_id`, `image_id`, `ip_hash`, `clicked_at`) VALUES
(1, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:02:07'),
(2, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:10:16'),
(3, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:11:33'),
(4, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:12:12'),
(5, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:12:16'),
(6, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:12:19'),
(7, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:23:36'),
(8, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:23:38'),
(9, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:23:54'),
(10, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:24:02'),
(11, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:24:08'),
(12, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:24:12'),
(13, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:24:27'),
(14, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:24:51'),
(15, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:24:55'),
(16, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:25:22'),
(17, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:25:24'),
(18, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:25:25'),
(19, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:25:27'),
(20, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-20 07:25:28'),
(30, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-21 07:47:47'),
(31, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-21 07:47:49'),
(33, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 07:28:03'),
(34, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 09:13:11'),
(35, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 09:13:14'),
(36, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 09:18:18'),
(37, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 13:08:56'),
(39, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 14:17:34'),
(40, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-22 14:25:27'),
(46, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 07:10:05'),
(47, 8, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 07:10:14'),
(50, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 11:42:11'),
(51, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 11:42:35'),
(52, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 11:42:36'),
(53, 8, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 11:42:56'),
(54, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 11:43:42'),
(55, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 11:43:45'),
(57, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 13:10:09'),
(58, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-24 13:10:18'),
(59, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-25 14:31:13'),
(61, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 06:06:36'),
(62, 4, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 06:06:39'),
(63, 8, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 06:06:42'),
(65, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 14:30:52'),
(66, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 14:32:37'),
(68, 13, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 15:06:59'),
(69, 13, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-26 15:07:39'),
(70, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 07:28:26'),
(71, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 07:31:08'),
(72, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 07:31:52'),
(73, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 07:48:57'),
(74, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 08:50:10'),
(75, 13, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 09:01:31'),
(76, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 14:07:25'),
(77, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 14:22:09'),
(78, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 14:22:12'),
(79, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-27 14:25:46'),
(80, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-29 06:48:15'),
(81, 3, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-29 06:50:56'),
(82, 1, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-29 06:51:03'),
(83, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-29 14:43:10'),
(84, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-29 17:32:12'),
(85, 15, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-06-30 07:39:50'),
(86, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 05:57:33'),
(87, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 06:27:24'),
(88, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 06:27:30'),
(89, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 08:32:35'),
(90, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 08:54:29'),
(91, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 08:54:32'),
(92, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 09:04:37'),
(93, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 09:04:39'),
(94, 9, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 09:09:06'),
(95, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 09:09:36'),
(96, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:46:36'),
(97, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:47:18'),
(98, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:47:22'),
(99, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:47:26'),
(100, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:48:35'),
(101, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:48:38'),
(102, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:48:44'),
(103, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:48:49'),
(104, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:48:52'),
(105, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:49:25'),
(106, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:49:28'),
(107, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:49:30'),
(108, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:50:32'),
(109, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 12:50:37'),
(110, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:03:31'),
(111, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:03:36'),
(112, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:03:51'),
(113, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:04:30'),
(114, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:04:33'),
(115, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:04:42'),
(116, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:06:56'),
(117, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:20:08'),
(118, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:22:55'),
(119, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:23:02'),
(120, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:37:51'),
(121, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:38:06'),
(122, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:38:24'),
(123, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:40:11'),
(124, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:40:18'),
(125, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 13:55:56'),
(126, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:03:51'),
(127, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:16:00'),
(128, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:16:36'),
(129, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:16:38'),
(130, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:16:42'),
(131, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:16:46'),
(132, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:16:55'),
(133, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:18:12'),
(134, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:18:17'),
(135, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:26:47'),
(136, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:27:00'),
(137, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:41:21'),
(138, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:41:40'),
(139, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:42:13'),
(140, 14, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:49:23'),
(141, 16, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-13 14:49:48'),
(142, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-14 06:07:25'),
(143, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-14 06:07:48'),
(144, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-14 06:07:56'),
(145, 7, NULL, 'eff8e7ca506627fe15dda5e0e512fcaad70b6d520f37cc76597fdb4f2d83a1a3', '2026-07-14 06:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_enquiries`
--

CREATE TABLE `tn_ad_enquiries` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `contact_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `package_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','contacted','converted','closed') NOT NULL DEFAULT 'new',
  `handled_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_images`
--

CREATE TABLE `tn_ad_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `ad_id` int(10) UNSIGNED NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `display_type` enum('square','horizontal','vertical') NOT NULL DEFAULT 'square',
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_ad_images`
--

INSERT INTO `tn_ad_images` (`id`, `ad_id`, `filepath`, `alt_text`, `link_url`, `display_type`, `sort_order`, `is_active`, `added_at`) VALUES
(3, 3, '/uploads/ads/ad_3_6a2acb7a9d0c5.png', '', '', 'square', 1, 1, '2026-06-11 14:51:38'),
(4, 1, '/uploads/ads/ad_1_6a340c8e8a686.png', '', '', 'square', 2, 1, '2026-06-18 15:19:42'),
(5, 4, '/uploads/ads/ad_4_6a3502e6d9f3c.jpg', '', '', 'square', 1, 1, '2026-06-19 08:50:46'),
(6, 4, '/uploads/ads/ad_4_6a3505708331e.webp', '', '', 'square', 2, 1, '2026-06-19 09:01:36'),
(13, 7, '/uploads/ads/ad_7_6a3a46f1e6e2d.webp', '', '', 'square', 1, 1, '2026-06-23 08:42:26'),
(14, 8, '/uploads/ads/ad_8_6a3a95bdc6623.webp', '', '', 'vertical', 1, 1, '2026-06-23 14:18:38'),
(15, 9, '/uploads/ads/ad_9_6a3e8ccd1dc85.webp', '', '', 'horizontal', 1, 1, '2026-06-26 14:29:33'),
(19, 13, '/uploads/ads/ad_13_6a3e9061cf2b8.webp', '', '', 'horizontal', 1, 1, '2026-06-26 14:44:49'),
(20, 14, '/uploads/ads/ad_14_6a3e943339bb0.webp', '', '', 'vertical', 1, 1, '2026-06-26 15:01:07'),
(24, 9, '/uploads/ads/ad_9_6a54911d9e958.webp', '', '', 'horizontal', 2, 1, '2026-07-13 07:17:49'),
(25, 9, '/uploads/ads/ad_9_6a549178257c5.webp', '', '', 'horizontal', 3, 1, '2026-07-13 07:19:20'),
(26, 9, '/uploads/ads/ad_9_6a5495b1e2dcb.webp', '', '', 'horizontal', 4, 1, '2026-07-13 07:37:21'),
(27, 15, '/uploads/ads/ad_15_6a549689514b3.webp', '', '', 'vertical', 1, 1, '2026-07-13 07:40:57'),
(28, 16, '/uploads/ads/ad_16_6a54a1f0bb6a4.jpg', '', '', 'square', 1, 1, '2026-07-13 08:29:36'),
(29, 16, '/uploads/ads/ad_16_6a54a1f6de3a8.webp', '', '', 'square', 2, 1, '2026-07-13 08:29:42'),
(30, 7, '/uploads/ads/ad_7_6a54a952c74fd.jpeg', '', '', 'horizontal', 2, 1, '2026-07-13 09:01:06'),
(31, 7, '/uploads/ads/ad_7_6a54a95ae75cf.jpeg', '', '', 'vertical', 3, 1, '2026-07-13 09:01:14'),
(32, 7, '/uploads/ads/ad_7_6a54f592d953a.png', '', '', 'square', 4, 1, '2026-07-13 14:26:26'),
(33, 7, '/uploads/ads/ad_7_6a54f599f0395.png', '', '', 'horizontal', 5, 1, '2026-07-13 14:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_packages`
--

CREATE TABLE `tn_ad_packages` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `includes_square` tinyint(1) NOT NULL DEFAULT 0,
  `includes_horizontal` tinyint(1) NOT NULL DEFAULT 0,
  `includes_vertical` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `name_tamil` varchar(100) DEFAULT NULL,
  `slot_type` enum('any','square','horizontal','vertical') NOT NULL DEFAULT 'any',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `type` enum('free','paid_ad','paid_ad_news','paid_ad_news_video') NOT NULL DEFAULT 'free',
  `description` text DEFAULT NULL,
  `price_inr` decimal(10,2) NOT NULL DEFAULT 0.00,
  `rate_per_day` decimal(8,2) NOT NULL DEFAULT 0.00,
  `min_days` smallint(6) NOT NULL DEFAULT 7,
  `max_days` smallint(6) DEFAULT NULL,
  `allow_images` tinyint(1) NOT NULL DEFAULT 0,
  `image_change_days` smallint(6) NOT NULL DEFAULT 30,
  `allow_news` tinyint(1) NOT NULL DEFAULT 0,
  `news_quota` tinyint(4) NOT NULL DEFAULT 0,
  `news_interval_days` smallint(6) NOT NULL DEFAULT 0,
  `is_trial` tinyint(1) NOT NULL DEFAULT 0,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `duration_days` smallint(5) UNSIGNED NOT NULL DEFAULT 7,
  `max_images` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `includes_news` tinyint(1) NOT NULL DEFAULT 0,
  `includes_video` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sq_duration_months` tinyint(4) DEFAULT NULL,
  `hr_duration_months` tinyint(4) DEFAULT NULL,
  `vt_duration_days` smallint(6) DEFAULT NULL,
  `yearly_discount_pct` tinyint(4) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_ad_packages`
--

INSERT INTO `tn_ad_packages` (`id`, `code`, `includes_square`, `includes_horizontal`, `includes_vertical`, `name`, `name_tamil`, `slot_type`, `amount`, `type`, `description`, `price_inr`, `rate_per_day`, `min_days`, `max_days`, `allow_images`, `image_change_days`, `allow_news`, `news_quota`, `news_interval_days`, `is_trial`, `qr_code_path`, `duration_days`, `max_images`, `includes_news`, `includes_video`, `is_active`, `sort_order`, `created_at`, `sq_duration_months`, `hr_duration_months`, `vt_duration_days`, `yearly_discount_pct`) VALUES
(1, NULL, 1, 0, 0, 'Free Trial', 'இலவச சோதனை', 'any', 0.00, 'free', NULL, 0.00, 0.00, 7, 7, 0, 0, 0, 0, 0, 1, NULL, 7, 0, 0, 0, 1, 1, '2026-06-23 09:09:39', NULL, NULL, NULL, 10),
(2, NULL, 1, 0, 0, 'Square Basic', 'சதுர விளம்பரம்', 'square', 3600.00, 'free', NULL, 3600.00, 20.00, 180, NULL, 1, 30, 0, 0, 0, 0, NULL, 180, 5, 0, 0, 1, 2, '2026-06-23 09:09:39', NULL, NULL, NULL, 10),
(3, NULL, 1, 0, 0, 'Square + News', 'சதுர விளம்பரம் + செய்தி', 'square', 5000.00, 'free', NULL, 5000.00, 28.00, 180, NULL, 1, 30, 1, 1, 30, 0, NULL, 180, 5, 1, 0, 1, 3, '2026-06-23 09:09:39', NULL, NULL, NULL, 10),
(4, NULL, 0, 1, 0, 'Horizontal Basic', 'கிடை விளம்பரம்', 'horizontal', 4500.00, 'free', NULL, 4500.00, 50.00, 90, NULL, 1, 30, 0, 0, 0, 0, NULL, 90, 5, 0, 0, 1, 4, '2026-06-23 09:09:39', NULL, NULL, NULL, 10),
(5, NULL, 0, 1, 0, 'Horizontal + News', 'கிடை விளம்பரம் + செய்தி', 'horizontal', 6000.00, 'free', NULL, 6000.00, 67.00, 90, NULL, 1, 30, 1, 5, 18, 0, NULL, 90, 5, 1, 0, 1, 5, '2026-06-23 09:09:39', NULL, NULL, NULL, 10),
(6, NULL, 0, 0, 1, 'Vertical + News (Daily)', 'நேர் விளம்பரம் + செய்தி', 'vertical', 0.00, 'free', NULL, 0.00, 100.00, 10, 30, 0, 0, 1, 0, 1, 0, NULL, 30, 0, 1, 0, 1, 6, '2026-06-23 09:09:39', NULL, NULL, NULL, 10),
(7, 'SQ', 1, 0, 0, 'Square', NULL, 'square', 0.00, 'free', NULL, 5000.00, 0.00, 7, NULL, 1, 30, 1, 3, 0, 0, NULL, 7, 5, 0, 0, 1, 1, '2026-07-13 06:17:29', 6, NULL, NULL, 10),
(8, 'HR', 0, 1, 0, 'Horizontal', NULL, 'horizontal', 0.00, 'free', NULL, 5000.00, 0.00, 7, NULL, 1, 30, 1, 1, 0, 0, NULL, 7, 5, 0, 0, 1, 2, '2026-07-13 06:17:29', NULL, 3, NULL, 10),
(9, 'VT', 0, 0, 1, 'Vertical (per day)', NULL, 'vertical', 0.00, 'free', NULL, 100.00, 0.00, 7, NULL, 1, 30, 1, 1, 0, 0, NULL, 7, 5, 0, 0, 1, 3, '2026-07-13 06:17:29', NULL, NULL, 30, 10),
(10, 'C1', 1, 0, 1, 'Square + Vertical', NULL, 'any', 0.00, 'free', NULL, 7500.00, 0.00, 7, NULL, 1, 30, 1, 5, 0, 0, NULL, 7, 5, 0, 0, 1, 4, '2026-07-13 06:17:29', 6, NULL, 30, 10),
(11, 'C1A', 0, 1, 1, 'Horizontal + Vertical', NULL, 'any', 0.00, 'free', NULL, 7500.00, 0.00, 7, NULL, 1, 30, 1, 5, 0, 0, NULL, 7, 5, 0, 0, 1, 5, '2026-07-13 06:17:29', NULL, 3, 30, 10),
(12, 'C2', 1, 1, 0, 'Square + Horizontal', NULL, 'any', 0.00, 'free', NULL, 9000.00, 0.00, 7, NULL, 1, 30, 1, 5, 0, 0, NULL, 7, 5, 0, 0, 1, 6, '2026-07-13 06:17:29', 6, 3, NULL, 10),
(13, 'C3', 1, 1, 1, 'Square + Horizontal + Vertical', NULL, 'any', 0.00, 'free', NULL, 11500.00, 0.00, 7, NULL, 1, 30, 1, 8, 0, 0, NULL, 7, 5, 0, 0, 1, 7, '2026-07-13 06:17:29', 6, 3, 30, 10),
(14, 'C4', 1, 1, 1, 'Premium (All 6 Months)', NULL, 'any', 0.00, 'free', NULL, 25000.00, 0.00, 7, NULL, 1, 30, 1, 10, 0, 0, NULL, 7, 5, 0, 0, 1, 8, '2026-07-13 06:17:29', 6, 6, 180, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_package_requests`
--

CREATE TABLE `tn_ad_package_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `ad_id` int(10) UNSIGNED NOT NULL,
  `requested_by` int(10) UNSIGNED NOT NULL,
  `current_pkg_id` tinyint(3) UNSIGNED NOT NULL,
  `requested_pkg_id` tinyint(3) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `note` varchar(500) DEFAULT NULL,
  `admin_note` varchar(500) DEFAULT NULL,
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tn_ad_package_requests`
--

INSERT INTO `tn_ad_package_requests` (`id`, `ad_id`, `requested_by`, `current_pkg_id`, `requested_pkg_id`, `status`, `note`, `admin_note`, `reviewed_by`, `reviewed_at`, `created_at`) VALUES
(1, 5, 4, 1, 2, 'approved', '', NULL, 5, '2026-06-24 19:21:53', '2026-06-24 13:41:53'),
(2, 8, 5, 2, 3, 'approved', '', NULL, 5, '2026-06-24 19:21:46', '2026-06-24 13:48:01'),
(3, 8, 5, 3, 1, 'approved', '', NULL, 5, '2026-06-24 19:23:27', '2026-06-24 13:53:21'),
(4, 6, 5, 1, 2, 'approved', '', NULL, 5, '2026-06-24 20:02:23', '2026-06-24 13:56:03'),
(5, 5, 4, 2, 4, 'approved', '', NULL, 5, '2026-06-24 20:02:18', '2026-06-24 14:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_slots`
--

CREATE TABLE `tn_ad_slots` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `type` enum('square','horizontal','vertical') NOT NULL,
  `desktop_size` varchar(20) NOT NULL,
  `mobile_size` varchar(20) DEFAULT NULL,
  `ad_code` text DEFAULT NULL COMMENT 'Manual fallback HTML/JS',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_ad_slots`
--

INSERT INTO `tn_ad_slots` (`id`, `name`, `slug`, `type`, `desktop_size`, `mobile_size`, `ad_code`, `is_active`, `updated_at`) VALUES
(1, 'Square Ad', 'square', 'square', '300/600/900 × 150-45', '200x100', '/uploads/ads/defaults/square_default.png', 1, '2026-06-21 07:22:47'),
(2, 'Horizontal Ad', 'horizontal', 'horizontal', '900 × 150', '320x100', NULL, 1, '2026-06-21 07:22:47'),
(3, 'Vertical Ad', 'vertical', 'vertical', '220 × 750', '300x250', '/uploads/ads/defaults/vertical_default.png', 1, '2026-06-21 07:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `tn_ad_subscriptions`
--

CREATE TABLE `tn_ad_subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `ad_id` int(10) UNSIGNED NOT NULL,
  `package_id` tinyint(3) UNSIGNED NOT NULL,
  `owner_user_id` int(10) UNSIGNED DEFAULT NULL,
  `assigned_by` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','active','expired','suspended') NOT NULL DEFAULT 'pending',
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `selected_days` smallint(6) DEFAULT NULL,
  `image_last_changed` date DEFAULT NULL,
  `news_used` tinyint(4) NOT NULL DEFAULT 0,
  `expiry_notified_7d` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_notified_3d` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_notified_1d` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tn_ad_subscriptions`
--

INSERT INTO `tn_ad_subscriptions` (`id`, `ad_id`, `package_id`, `owner_user_id`, `assigned_by`, `status`, `amount_paid`, `valid_from`, `valid_until`, `selected_days`, `image_last_changed`, `news_used`, `expiry_notified_7d`, `expiry_notified_3d`, `expiry_notified_1d`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 3, 6, 1, 'pending', 5000.00, '2026-06-23', '2026-12-20', 10, NULL, 0, 0, 0, 0, '', '2026-06-23 08:42:43', '2026-06-23 08:56:25'),
(2, 6, 2, 7, 5, 'active', 3600.00, '2026-06-24', '2026-12-21', 10, NULL, 0, 0, 0, 0, '', '2026-06-24 13:56:13', '2026-06-24 13:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `tn_analytics_daily`
--

CREATE TABLE `tn_analytics_daily` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_analytics_daily`
--

INSERT INTO `tn_analytics_daily` (`id`, `article_id`, `date`, `views`) VALUES
(2, 111, '2026-06-11', 4),
(5, 112, '2026-06-11', 5),
(6, 131, '2026-06-11', 21),
(13, 118, '2026-06-11', 1),
(14, 136, '2026-06-11', 2),
(20, 147, '2026-06-11', 2),
(33, 117, '2026-06-11', 1),
(38, 131, '2026-06-15', 7),
(45, 116, '2026-06-15', 2),
(47, 131, '2026-06-16', 9),
(52, 167, '2026-06-16', 47),
(103, 167, '2026-06-18', 113),
(155, 111, '2026-06-18', 3),
(188, 131, '2026-06-18', 2),
(197, 114, '2026-06-18', 2),
(223, 169, '2026-06-19', 2),
(225, 167, '2026-06-19', 1),
(226, 167, '2026-06-20', 1),
(227, 121, '2026-06-20', 1),
(228, 170, '2026-06-20', 57),
(229, 166, '2026-06-20', 1),
(286, 131, '2026-06-20', 2),
(288, 162, '2026-06-22', 1),
(289, 131, '2026-06-22', 2),
(290, 170, '2026-06-22', 3),
(293, 128, '2026-06-22', 1),
(294, 166, '2026-06-22', 1),
(296, 171, '2026-06-23', 3),
(299, 147, '2026-06-23', 1),
(300, 114, '2026-06-23', 1),
(301, 170, '2026-06-23', 2),
(303, 131, '2026-06-24', 2),
(305, 167, '2026-06-24', 1),
(306, 170, '2026-06-25', 1),
(307, 131, '2026-06-26', 7),
(308, 126, '2026-06-26', 1),
(309, 171, '2026-06-26', 3),
(310, 118, '2026-06-26', 1),
(311, 170, '2026-06-26', 2),
(315, 172, '2026-06-26', 8),
(321, 136, '2026-06-26', 1),
(326, 167, '2026-06-26', 1),
(331, 169, '2026-06-26', 1),
(332, 116, '2026-06-26', 1),
(333, 116, '2026-06-27', 12),
(345, 172, '2026-06-27', 38),
(347, 131, '2026-06-27', 17),
(349, 126, '2026-06-27', 1),
(351, 170, '2026-06-27', 1),
(402, 117, '2026-06-28', 1),
(403, 172, '2026-06-29', 3),
(405, 169, '2026-06-29', 1),
(406, 171, '2026-06-29', 1),
(408, 170, '2026-06-29', 1),
(409, 173, '2026-06-29', 4),
(412, 147, '2026-06-29', 2),
(415, 173, '2026-06-30', 9),
(424, 131, '2026-07-10', 1),
(425, 131, '2026-07-13', 1),
(426, 126, '2026-07-13', 6),
(429, 173, '2026-07-13', 19),
(437, 117, '2026-07-13', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tn_articles`
--

CREATE TABLE `tn_articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `contributor_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` smallint(5) UNSIGNED NOT NULL,
  `district_id` smallint(5) UNSIGNED DEFAULT NULL,
  `city_id` smallint(5) UNSIGNED DEFAULT NULL,
  `city_text` varchar(200) DEFAULT NULL,
  `media_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `slug` varchar(550) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `content_type` enum('news','video','short_news','live_update','gallery','special','sponsored') NOT NULL DEFAULT 'news',
  `sponsored_subscription_id` int(10) UNSIGNED DEFAULT NULL,
  `language` enum('ta','en','both') NOT NULL DEFAULT 'ta',
  `youtube_url` varchar(500) DEFAULT NULL,
  `youtube_video_id` varchar(20) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `news_card_image` varchar(500) DEFAULT NULL,
  `thumb_url` varchar(500) DEFAULT NULL,
  `image_caption` varchar(300) DEFAULT NULL,
  `image_credit` varchar(150) DEFAULT NULL,
  `status` enum('draft','review','approved','published','scheduled','rejected','archived') NOT NULL DEFAULT 'draft',
  `approval_stage` enum('reporter','district_editor','editor','chief_editor','published') NOT NULL DEFAULT 'reporter',
  `rejection_reason` varchar(300) DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `is_breaking` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_editors_pick` tinyint(1) NOT NULL DEFAULT 0,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `is_sponsored` tinyint(1) NOT NULL DEFAULT 0,
  `meta_title` varchar(300) DEFAULT NULL,
  `meta_desc` varchar(500) DEFAULT NULL,
  `view_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `share_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `comment_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `read_time` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rating_avg` decimal(3,2) DEFAULT NULL,
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_articles`
--

INSERT INTO `tn_articles` (`id`, `user_id`, `contributor_id`, `category_id`, `district_id`, `city_id`, `city_text`, `media_id`, `title`, `slug`, `excerpt`, `content`, `content_type`, `sponsored_subscription_id`, `language`, `youtube_url`, `youtube_video_id`, `image_url`, `news_card_image`, `thumb_url`, `image_caption`, `image_credit`, `status`, `approval_stage`, `rejection_reason`, `approved_by`, `approved_at`, `scheduled_at`, `published_at`, `is_breaking`, `is_featured`, `is_editors_pick`, `is_premium`, `is_sponsored`, `meta_title`, `meta_desc`, `view_count`, `share_count`, `comment_count`, `read_time`, `created_at`, `updated_at`, `rating_avg`, `rating_count`) VALUES
(111, 1, NULL, 1, NULL, NULL, NULL, NULL, 'தமிழ்நாட்டில் கனமழை எச்சரிக்கை — 12 மாவட்டங்களுக்கு ஆரஞ்சு அலர்ட்', 'tamilnadu-heavy-rain-orange-alert-12-districts-june-2026', 'தென்மேற்கு பருவமழை வலுப்பெறுவதால் 12 மாவட்டங்களில் கனமழை பெய்யும் என சென்னை வானிலை ஆய்வு மையம் எச்சரிக்கை விடுத்துள்ளது.', '<p>தமிழ்நாட்டில் தென்மேற்கு பருவமழை வலுப்பெற்று வருவதால் நீலகிரி, கோயம்புத்தூர், திண்டுக்கல், தேனி, திருச்சிராப்பள்ளி உள்பட 12 மாவட்டங்களுக்கு ஆரஞ்சு அலர்ட் விடுக்கப்பட்டுள்ளது.</p><p>மாவட்ட ஆட்சியர்கள் பள்ளிகளுக்கு விடுமுறை அறிவித்துள்ளனர். மீனவர்களை கடலுக்கு செல்ல வேண்டாம் என கோரிக்கை விடுக்கப்பட்டுள்ளது. மழைநீர் வடிகால் சுத்தம் செய்யப்படுகிறது.</p><p>முதல்வர் அலுவலகம் அனைத்து மாவட்ட ஆட்சியர்களுக்கும் அவசர கட்டளை பிறப்பிக்கப்பட்டுள்ளது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 05:55:00', NULL, '2026-06-10 06:00:00', 1, 1, 0, 0, 0, NULL, NULL, 18507, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-18 09:24:47', NULL, 0),
(112, 1, NULL, 1, NULL, NULL, NULL, NULL, 'SSLC மதிப்பெண் மறுசரிபார்ப்பு — ஆன்லைனில் விண்ணப்பிக்கலாம்', 'sslc-revaluation-application-online-june-2026', '10ஆம் வகுப்பு பொதுத்தேர்வு முடிவுகளில் திருப்தியில்லாத மாணவர்கள் மறுசரிபார்ப்புக்கு ஆன்லைனில் விண்ணப்பிக்கலாம்.', '<p>தமிழ்நாடு பள்ளிக்கல்வித்துறை அறிவிப்பின்படி SSLC மதிப்பெண் மறுசரிபார்ப்புக்கு ஜூன் 20 வரை விண்ணப்பிக்கலாம்.</p><p>ஒரு பாடத்திற்கு ₹500 கட்டணம் நிர்ணயிக்கப்பட்டுள்ளது. dge.tn.gov.in இணையதளம் வழியாக விண்ணப்பிக்கலாம்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1588072432836-e10032774350?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1588072432836-e10032774350?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 08:55:00', NULL, '2026-06-09 09:00:00', 0, 0, 1, 0, 0, NULL, NULL, 12305, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-11 15:30:42', NULL, 0),
(113, 1, NULL, 1, NULL, NULL, NULL, NULL, 'சென்னை மெட்ரோ — ஷோலிங்கநல்லூர் வரை நீட்டிப்பு திட்டம் அறிவிப்பு', 'chennai-metro-sholinganallur-extension-announced', 'சென்னை மெட்ரோ ரயில் இரண்டாம் கட்ட திட்டத்தில் ஷோலிங்கநல்லூர் வரை நீட்டிக்கப்படும் என அரசு அறிவித்தது.', '<p>சென்னை மெட்ரோ ரயில் இரண்டாம் கட்ட திட்டத்தின் கீழ் நாப்பேர்தான் முதல் ஷோலிங்கநல்லூர் வரை 11.5 கிலோமீட்டர் தூரத்தில் 9 நிறுத்தங்கள் அமைக்கப்படும்.</p><p>₹3,200 கோடியில் நிறைவேற்றப்படும் இந்த திட்டம் 2027 ஆண்டுக்குள் முடிவடையும் என்று அரசு தெரிவித்தது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1581262208435-41726149a759?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1581262208435-41726149a759?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 09:55:00', NULL, '2026-06-08 10:00:00', 0, 1, 0, 0, 0, NULL, NULL, 9400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(114, 1, NULL, 1, NULL, NULL, NULL, NULL, 'தமிழகத்தில் 500 புதிய அரசு மருத்துவமனைகள் — முதல்வர் அறிவிப்பு', '500-new-government-hospitals-tamilnadu-cm-announcement', 'தமிழ்நாடு முழுவதும் 500 புதிய அரசு மருத்துவமனைகள் அமைக்கப்படும் என முதல்வர் அறிவித்தார்.', '<p>தமிழ்நாடு முதல்வர் 500 புதிய அரசு மருத்துவமனைகள் மூன்று ஆண்டுகளில் அமைக்கப்படும் என சட்டசபையில் அறிவித்தார்.</p><p>₹8,500 கோடி நிதி ஒதுக்கீடு அறிவிக்கப்பட்டுள்ளது. ஒவ்வொரு தாலுகாவிலும் குறைந்தது ஒரு மருத்துவமனை அமைக்கப்படும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 10:55:00', NULL, '2026-06-07 11:00:00', 1, 0, 1, 0, 0, NULL, NULL, 15603, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-23 12:34:50', NULL, 0),
(115, 1, NULL, 1, NULL, NULL, NULL, NULL, 'மதுரை மீனாட்சி கோவில் — ஆடி உத்சவம் ஜூலை 20 தொடக்கம்', 'madurai-meenakshi-temple-aadi-festival-july-20', 'மதுரை மீனாட்சியம்மன் கோவிலில் ஆடி மாத சிறப்பு உத்சவம் ஜூலை 20 முதல் தொடங்குகிறது.', '<p>மதுரை மீனாட்சியம்மன் கோவிலில் ஆடி மாதம் சிறப்பு உத்சவங்கள் நடைபெறும். ஆடி பூரம் விழா 50 ஆண்டுகளில் இல்லாத அளவில் பிரமாண்டமாக கொண்டாடப்படும் என்று கோவில் நிர்வாகம் அறிவித்தது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 08:55:00', NULL, '2026-06-06 09:00:00', 0, 0, 0, 0, 0, NULL, NULL, 7800, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(116, 1, NULL, 2, NULL, NULL, NULL, NULL, 'பிரதமர் மோடி — G7 உச்சி மாநாட்டில் இந்தியாவின் வலிமையான நிலைப்பாடு', 'pm-modi-g7-summit-india-strong-position-june-2026', 'இத்தாலியில் நடைபெறும் G7 உச்சி மாநாட்டில் பிரதமர் நரேந்திர மோடி இந்தியாவின் கொள்கைகளை வலியுறுத்தினார்.', '<p>G7 உச்சி மாநாட்டில் பிரதமர் நரேந்திர மோடி காலநிலை மாற்றம், டிஜிட்டல் உள்கட்டமைப்பு, தொழில்நுட்ப பரிமாற்றம் குறித்து உரையாற்றினார்.</p><p>இந்தியா-அமெரிக்கா இடையே ₹2.5 லட்சம் கோடி மதிப்பிலான வர்த்தக ஒப்பந்தம் கையெழுத்தானது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1555848962-6e79363ec58f?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1555848962-6e79363ec58f?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 06:55:00', NULL, '2026-06-10 07:00:00', 1, 1, 0, 0, 0, NULL, NULL, 22415, 0, 0, 4, '2026-06-10 13:42:26', '2026-06-27 06:49:26', NULL, 0),
(117, 1, NULL, 2, NULL, NULL, NULL, NULL, 'RBI வட்டி விகிதம் — 25 பேஸிஸ் பாயிண்ட் குறைப்பு அறிவிப்பு', 'rbi-repo-rate-cut-25-basis-points-june-2026', 'ரிசர்வ் வங்கி ரெப்போ வட்டி விகிதத்தை 25 பேஸிஸ் பாயிண்ட் குறைத்து 5.75% ஆக நிர்ணயித்துள்ளது.', '<p>ரிசர்வ் வங்கி நாணயக் கொள்கை குழு கூட்டத்தில் ரெப்போ விகிதம் 6% இலிருந்து 5.75% ஆக குறைக்கப்பட்டது.</p><p>இதனால் வீட்டு கடன், கார் கடன் வட்டி விகிதங்கள் குறையும். EMI சுமை குறையும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 13:55:00', NULL, '2026-06-09 14:00:00', 0, 1, 1, 0, 0, NULL, NULL, 19804, 0, 0, 3, '2026-06-10 13:42:26', '2026-07-13 13:39:32', NULL, 0),
(118, 1, NULL, 2, NULL, NULL, NULL, NULL, 'NEET 2026 — AIIMSல் 100% மதிப்பெண் பெற்ற சிவகாசி மாணவர்', 'neet-2026-sivakasi-student-perfect-score-aiims', 'NEET 2026 தேர்வில் 720/720 மதிப்பெண் பெற்று AIIMSல் சேர்ந்த சிவகாசி மாணவர் கதை.', '<p>சிவகாசியை சேர்ந்த அர்ஜுன் பாலகிருஷ்ணன் NEET 2026 தேர்வில் 720/720 என முழு மதிப்பெண் பெற்று All India Rank 1 பெற்றார்.</p><p>அரசு பள்ளியில் படித்த இவருக்கு முதல்வர் வாழ்த்து தெரிவித்தார்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 07:55:00', NULL, '2026-06-08 08:00:00', 0, 0, 1, 0, 0, NULL, NULL, 28902, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-26 06:07:04', NULL, 0),
(119, 1, NULL, 2, NULL, NULL, NULL, NULL, 'UPI பரிவர்த்தனை — மே மாதம் 20 பில்லியன் சாதனை', 'upi-transactions-20-billion-may-2026-record', 'UPI டிஜிட்டல் கட்டண முறை மே மாதம் 20 பில்லியன் பரிவர்த்தனைகளை கடந்து சாதனை படைத்தது.', '<p>NPCI தரவின்படி மே 2026ல் UPI மூலம் 20.4 பில்லியன் பரிவர்த்தனைகள் நடைபெற்றன. மொத்த மதிப்பு ₹23 லட்சம் கோடி.</p><p>இது இந்திய டிஜிட்டல் பொருளாதார வரலாற்றில் மைல்கல்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 09:55:00', NULL, '2026-06-07 10:00:00', 0, 0, 0, 0, 0, NULL, NULL, 16700, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(120, 1, NULL, 2, NULL, NULL, NULL, NULL, 'விண்வெளி வீரர்கள் — ககன்யான் டிசம்பர் 2026 புறப்பாடு உறுதி', 'gaganyaan-december-2026-launch-confirmed-isro', 'ISRO ககன்யான் திட்டத்தின் முதல் மனித விண்வெளி பயணம் டிசம்பர் 14 அன்று நடைபெறும் என உறுதிப்படுத்தப்பட்டது.', '<p>ISRO தலைவர் டாக்டர் வி.நாரயணன் ககன்யான் திட்டம் அட்டவணைப்படி முன்னேறுவதாக தெரிவித்தார். 4 விண்வெளி வீரர்கள் தயார் நிலையில் உள்ளனர்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 10:55:00', NULL, '2026-06-06 11:00:00', 0, 1, 0, 0, 0, NULL, NULL, 13400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(121, 1, NULL, 3, NULL, NULL, NULL, NULL, 'மத்திய கிழக்கு — இஸ்ரேல்-ஹமாஸ் போர்நிறுத்த பேச்சுவார்த்தை முடிவில்லாமல் முடிந்தது', 'israel-hamas-ceasefire-talks-inconclusive-june-2026', 'கத்தார் மத்தியஸ்தத்தில் நடைபெற்ற இஸ்ரேல்-ஹமாஸ் போர்நிறுத்த பேச்சுவார்த்தை முடிவில்லாமல் முடிந்தது.', '<p>டோஹாவில் நடைபெற்ற இஸ்ரேல்-ஹமாஸ் போர்நிறுத்த பேச்சுவார்த்தை 3 நாட்கள் நடந்தும் முடிவு எட்டப்படவில்லை. பந்தியாயினர் விடுவிப்பு முக்கிய தடையாக உள்ளது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1578496479914-7ef3b0193be3?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1578496479914-7ef3b0193be3?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 07:55:00', NULL, '2026-06-10 08:00:00', 1, 0, 0, 0, 0, NULL, NULL, 24501, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-20 05:42:03', NULL, 0),
(122, 1, NULL, 3, NULL, NULL, NULL, NULL, 'அமெரிக்கா — AI சட்டம் கான்கிரஸில் நிறைவேற்றம்', 'usa-ai-regulation-law-congress-passed-2026', 'செயற்கை நுண்ணறிவை கட்டுப்படுத்தும் வரலாற்று சிறப்பு AI சட்டம் அமெரிக்க கான்கிரஸில் நிறைவேற்றப்பட்டது.', '<p>அமெரிக்க கான்கிரஸ் AI Accountability and Safety Act 2026ஐ நிறைவேற்றியது. Tech நிறுவனங்கள் AI வெளிப்படைத்தன்மை அளிக்க வேண்டும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 09:55:00', NULL, '2026-06-09 10:00:00', 0, 1, 0, 0, 0, NULL, NULL, 18200, 0, 0, 4, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(123, 1, NULL, 3, NULL, NULL, NULL, NULL, 'சீனா — மூன்றாம் நிலா இறங்கு திட்டம் வெற்றி', 'china-chang-e-7-moon-landing-success-2026', 'சீனாவின் Chang-e 7 விண்கலம் சந்திரனின் தென் துருவத்தில் வெற்றிகரமாக இறங்கியது.', '<p>சீனா விண்வெளி ஆராய்ச்சி நிலையம் Chang-e 7 விண்கலம் சந்திரனின் தென் துருவத்தில் நீர் ஐஸ் தேடும் பணியை தொடங்கியது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 08:55:00', NULL, '2026-06-08 09:00:00', 0, 0, 1, 0, 0, NULL, NULL, 14600, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(124, 1, NULL, 3, NULL, NULL, NULL, NULL, 'உக்ரைன் — ரஷ்யா ஆயுத பரிமாற்றம் தொடர்பு விசாரணை', 'ukraine-russia-arms-deal-investigation-2026', 'ரஷ்யா-உக்ரைன் போரில் ஆயுத கடத்தல் குறித்த சர்வதேச விசாரணை தொடங்கியது.', '<p>ஐ.நா. விசாரணை குழு ரஷ்யாவிற்கு ஆயுதம் வழங்கும் நாடுகள் குறித்து விசாரிக்கின்றது. 12 நாடுகள் கேள்விக்குட்படுகின்றன.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1580674285054-bed31e145f59?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1580674285054-bed31e145f59?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 07:55:00', NULL, '2026-06-07 08:00:00', 0, 0, 0, 0, 0, NULL, NULL, 11200, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(125, 1, NULL, 3, NULL, NULL, NULL, NULL, 'பாகிஸ்தான் — பொருளாதார நெருக்கடி தீவிரம்; IMF நிபந்தனை', 'pakistan-economic-crisis-imf-conditions-june-2026', 'பாகிஸ்தான் பொருளாதார நெருக்கடி மோசமடைவதால் IMF புதிய நிபந்தனைகளை விதித்தது.', '<p>பாகிஸ்தான் அரசு IMF நிபந்தனைகளை ஏற்று மின்சாரம், பெட்ரோல் மானியங்களை நீக்கியது. இதனால் விலைவாசி கடுமையாக உயர்ந்தது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 09:55:00', NULL, '2026-06-06 10:00:00', 0, 0, 0, 0, 0, NULL, NULL, 9800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(126, 1, NULL, 4, NULL, NULL, NULL, NULL, 'விஜய் \"கோட்டை\" — டீஸர் 100 மில்லியன் views சாதனை', 'vijay-kottai-teaser-100-million-views-record', 'தளபதி விஜய் நடிக்கும் \"கோட்டை\" படத்தின் டீஸர் 24 மணி நேரத்தில் 100 மில்லியன் views சாதனை படைத்தது.', '<p>பா.ரஞ்சித் இயக்கும் \"கோட்டை\" படத்தின் டீஸர் YouTube-ல் 24 மணி நேரத்தில் 100 மில்லியன் views பெற்றது. தமிழ் சினிமா வரலாற்றில் புதிய சாதனை.</p><p>தீபாவளி 2026ல் வெளியாகும் என அறிவிக்கப்பட்டது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 08:55:00', NULL, '2026-06-10 09:00:00', 1, 1, 1, 0, 0, NULL, NULL, 56708, 0, 0, 3, '2026-06-10 13:42:26', '2026-07-13 14:49:33', NULL, 0),
(127, 1, NULL, 4, NULL, NULL, NULL, NULL, 'ரஜினிகாந்த் புதிய படம் — ஜப்பானில் படப்பிடிப்பு தொடக்கம்', 'rajinikanth-new-film-japan-shoot-begins-2026', 'சூப்பர் ஸ்டார் ரஜினிகாந்தின் 171வது படம் ஜப்பான் டோக்கியோவில் படப்பிடிப்பு தொடங்கியது.', '<p>வெ.பிரபுதேவா இயக்கும் ரஜினிகாந்தின் 171வது படத்தின் முக்கிய காட்சிகள் டோக்கியோவில் படப்பிடிப்பு நடைபெற்றது. 2027 தொடக்கத்தில் வெளியாகும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 10:55:00', NULL, '2026-06-09 11:00:00', 0, 1, 0, 0, 0, NULL, NULL, 41200, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(128, 1, NULL, 4, NULL, NULL, NULL, NULL, 'ஆஸ்கர் 2027 — இந்தியாவின் அதிகாரப்பூர்வ நுழைவு \"மைக்கேல்\"', 'oscar-2027-india-official-entry-michael-tamil-film', 'Tamil திரைப்படம் \"மைக்கேல்\" ஆஸ்கர் 2027க்கான இந்தியாவின் அதிகாரப்பூர்வ நுழைவாக தேர்வு செய்யப்பட்டது.', '<p>Film Federation of India \"மைக்கேல்\" படத்தை Best International Feature Film பிரிவில் இந்தியாவின் அதிகாரப்பூர்வ நுழைவாக அறிவித்தது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 09:55:00', NULL, '2026-06-08 10:00:00', 0, 0, 1, 0, 0, NULL, NULL, 34801, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-22 14:39:13', NULL, 0),
(129, 1, NULL, 4, NULL, NULL, NULL, NULL, 'நயன்தாரா Netflix சீரிஸ் — வர்ல்ட்வைட் ஸ்ட்ரீமிங் தொடக்கம்', 'nayanthara-netflix-series-worldwide-streaming-begins', 'நயன்தாரா நடித்த Netflix Original Tamil சீரிஸ் உலகளவில் ஸ்ட்ரீம் ஆகத் தொடங்கியது.', '<p>நயன்தாரா நடித்த Action-Thriller Netflix Tamil சீரிஸ் 180 நாடுகளில் ஒரே நேரத்தில் வெளியானது. முதல் 24 மணி நேரத்தில் 5 மில்லியன் views.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 11:55:00', NULL, '2026-06-07 12:00:00', 0, 0, 0, 0, 0, NULL, NULL, 27600, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(130, 1, NULL, 4, NULL, NULL, NULL, NULL, 'கமல்ஹாசன் 60 ஆண்டு திரைவாழ்க்கை — சிறப்பு விருது விழா', 'kamalhaasan-60-years-cinema-special-award-ceremony', 'கமல்ஹாசன் 60 ஆண்டு திரை வாழ்க்கையை கொண்டாட சிறப்பு விருது விழா சென்னையில் நடைபெற்றது.', '<p>கமல்ஹாசனின் 60 ஆண்டு திரைவாழ்க்கையை கொண்டாட தமிழக அரசு சிறப்பு விருது விழா ஏற்பாடு செய்தது. 500க்கும் மேற்பட்ட சினிமா பிரமுகர்கள் கலந்துகொண்டனர்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 13:55:00', NULL, '2026-06-06 14:00:00', 0, 1, 0, 0, 0, NULL, NULL, 19400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(131, 1, NULL, 5, NULL, NULL, NULL, NULL, 'IPL 2026 — Chennai Super Kings ஐந்தாவது சாம்பியன்', 'ipl-2026-csk-five-time-champions-final', 'IPL 2026 இறுதிப் போட்டியில் Chennai Super Kings Mumbai Indians ஐ 8 ரன் வித்தியாசத்தில் தோற்கடித்து ஐந்தாவது பட்டம் வென்றது.', '<p>அகமதாபாத் நரேந்திர மோடி ஸ்டேடியத்தில் நடைபெற்ற IPL 2026 Final-ல் CSK 184/6 என்ற கணக்கில் ஆட, MI 176/9 என்று முடித்தது.</p><p>Ruturaj Gaikwad Man of the Match.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-01 21:55:00', NULL, '2026-06-01 22:00:00', 1, 1, 1, 0, 0, NULL, NULL, 68471, 0, 0, 3, '2026-06-10 13:42:26', '2026-07-13 06:26:58', NULL, 0),
(132, 1, NULL, 5, NULL, NULL, NULL, NULL, 'நீரஜ் சோப்ரா — 92.08m ஈட்டி எறிந்து உலக சாதனை', 'neeraj-chopra-world-record-92-08m-javelin-2026', 'ஒலிம்பிக் சாம்பியன் நீரஜ் சோப்ரா ஜூரிச் Diamond League-ல் 92.08 மீட்டர் ஈட்டி எறிந்து உலக சாதனை படைத்தார்.', '<p>ஜூரிச் Diamond League-ல் நீரஜ் சோப்ரா 92.08 மீட்டர் ஈட்டி எறிந்து Jan Železný-யின் 98.48 மீட்டர் உலக சாதனைக்கு நெருங்கினார். இது இந்திய புதிய சாதனை.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 19:55:00', NULL, '2026-06-08 20:00:00', 0, 1, 0, 0, 0, NULL, NULL, 32100, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(133, 1, NULL, 5, NULL, NULL, NULL, NULL, 'French Open 2026 — Carlos Alcaraz இரண்டாவது பட்டம்', 'french-open-2026-alcaraz-second-title-roland-garros', 'Carlos Alcaraz French Open 2026 Final-ல் Jannik Sinner ஐ 3-1 செட்டில் தோற்கடித்து இரண்டாவது Roland Garros பட்டம் வென்றார்.', '<p>Roland Garros Final-ல் Carlos Alcaraz Jannik Sinner ஐ 6-4, 3-6, 6-3, 6-2 என்ற கணக்கில் தோற்கடித்தார்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 20:55:00', NULL, '2026-06-08 21:00:00', 0, 0, 1, 0, 0, NULL, NULL, 21800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(134, 1, NULL, 5, NULL, NULL, NULL, NULL, 'Chess World Championship — அர்ஜுன் எரிகைசி உலக சாம்பியன்', 'arjun-erigaisi-chess-world-champion-2026', 'இந்தியாவின் அர்ஜுன் எரிகைசி Chess World Championship 2026-ல் Magnus Carlsen ஐ தோற்கடித்து உலக சாம்பியன் பட்டம் வென்றார்.', '<p>Chess World Championship 2026 Final-ல் அர்ஜுன் எரிகைசி Magnus Carlsen ஐ 7.5-6.5 என்ற கணக்கில் தோற்கடித்தார். இந்தியாவின் இரண்டாவது World Chess Champion.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1529699211952-734e80c4d42b?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1529699211952-734e80c4d42b?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-05 17:55:00', NULL, '2026-06-05 18:00:00', 0, 1, 0, 0, 0, NULL, NULL, 29400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(135, 1, NULL, 5, NULL, NULL, NULL, NULL, 'TNPL Season 6 — CSG vs Ruby Trichy Warriors Final', 'tnpl-season-6-final-csg-vs-ruby-trichy-warriors', 'TNPL Season 6 இறுதிப் போட்டி Chepauk Super Gillies vs Ruby Trichy Warriors இடையே நடைபெறும்.', '<p>TNPL Season 6 இறுதிப் போட்டி ஜூலை 28 அன்று சென்னை M.A.Chidambaram Stadium-ல் நடைபெறும். இரு அணிகளும் அதிரடி கிரிக்கட் ஆடி Final எட்டின.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1540747913346-19212a4b32a6?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1540747913346-19212a4b32a6?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-04 09:55:00', NULL, '2026-06-04 10:00:00', 0, 0, 0, 0, 0, NULL, NULL, 16800, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(136, 1, NULL, 6, NULL, NULL, NULL, NULL, 'ChatGPT-5 — தமிழ் மொழி Native Support அறிவிப்பு', 'chatgpt-5-tamil-native-support-announcement', 'OpenAI ChatGPT-5 தமிழ் உட்பட 50 மொழிகளில் Native Language Support வழங்குவதாக அறிவித்தது.', '<p>OpenAI ChatGPT-5 தமிழ், தெலுங்கு, மலையாளம், கன்னடம் உட்பட 50 இந்திய மொழிகளில் Native support வழங்கும். கணிதம், அறிவியல் சூத்திரங்கள் Tamil-ல் விளக்கும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 09:55:00', NULL, '2026-06-10 10:00:00', 1, 1, 0, 0, 0, NULL, NULL, 48203, 0, 0, 4, '2026-06-10 13:42:26', '2026-06-26 08:54:43', NULL, 0),
(137, 1, NULL, 6, NULL, NULL, NULL, NULL, 'Apple iPhone 17 — Folding iPhone விலை விவரம் வெளியீடு', 'apple-iphone-17-fold-price-specs-leaked', 'Apple iPhone 17 Fold விலை $1,599 என்று வெளியானது. செப்டம்பர் 12 அன்று வெளியாகும்.', '<p>Apple iPhone 17 Fold $1,599 விலையில் கிடைக்கும். 7.8 இஞ்ச் foldable display, 48MP கேமரா, A19 Pro சிப் கொண்டது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 11:55:00', NULL, '2026-06-09 12:00:00', 0, 0, 1, 0, 0, NULL, NULL, 38600, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(138, 1, NULL, 6, NULL, NULL, NULL, NULL, 'Jio 6G — இந்தியாவில் 2027 மார்ச் வணிக தொடக்கம் உறுதி', 'jio-6g-commercial-launch-india-march-2027', 'Reliance Jio 6G சேவை 2027 மார்ச்சில் இந்தியாவில் வணிக அளவில் தொடங்கும் என Mukesh Ambani உறுதிப்படுத்தினார்.', '<p>Reliance AGM-ல் Mukesh Ambani Jio 6G 10 Gbps வேகத்தில் 2027 மார்ச் தொடங்கும் என அறிவித்தார். ₹20,000 கோடி முதலீடு.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 10:55:00', NULL, '2026-06-08 11:00:00', 0, 1, 0, 0, 0, NULL, NULL, 26400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(139, 1, NULL, 6, NULL, NULL, NULL, NULL, 'Ola Electric — ₹5,000 கோடி Gigafactory விரிவாக்கம்', 'ola-electric-gigafactory-expansion-5000-crore', 'Ola Electric ₹5,000 கோடியில் Bangalore Gigafactory விரிவாக்கம் செய்து ஆண்டுக்கு 1 கோடி EV உற்பத்தி இலக்கு.', '<p>Ola Electric Gigafactory-ல் Phase 2 விரிவாக்கம் அறிவிக்கப்பட்டது. 2028ல் ஆண்டுக்கு 1 கோடி electric vehicle உற்பத்தி திறன் அடையும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 08:55:00', NULL, '2026-06-07 09:00:00', 0, 0, 0, 0, 0, NULL, NULL, 19800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(140, 1, NULL, 6, NULL, NULL, NULL, NULL, 'ISRO SpaDeX — 2 செயற்கைகோள்கள் சேர்த்து Docking வெற்றி', 'isro-spadex-satellite-docking-success', 'ISRO SpaDeX திட்டத்தில் இரண்டு செயற்கைக்கோள்கள் விண்வெளியில் வெற்றிகரமாக Docking செய்யப்பட்டன.', '<p>ISRO SpaDeX (Space Docking Experiment) திட்டத்தில் SDX01 மற்றும் SDX02 செயற்கைக்கோள்கள் 470km உயரத்தில் Docking வெற்றி பெற்றது. இது Gaganyaan-க்கான முக்கிய படி.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1517976487492-5750f3195933?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1517976487492-5750f3195933?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 07:55:00', NULL, '2026-06-06 08:00:00', 0, 1, 1, 0, 0, NULL, NULL, 31200, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(141, 1, NULL, 7, NULL, NULL, NULL, NULL, 'திருப்பதி திருமலை — ரத சப்தமி விழா 5 லட்சம் பக்தர்கள்', 'tirupati-tirumala-ratha-saptami-festival-5-lakh', 'திருப்பதி திருமலை ஏழுமலையான் கோவிலில் ரத சப்தமி விழாவிற்கு 5 லட்சம் பக்தர்கள் வருகை.', '<p>திருமலை திருப்பதி தேவஸ்தானம் தெரிவிப்பதாவது, ரத சப்தமி மற்றும் ஐந்தாம் நாள் சிறப்பு அலங்காரத்திற்கு 5 லட்சம் பக்தர்கள் வருகை புரிந்தனர். Online booking முழுமையாக நிரம்பியது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1551009175-8a68da93d5f9?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1551009175-8a68da93d5f9?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 07:55:00', NULL, '2026-06-09 08:00:00', 0, 0, 1, 0, 0, NULL, NULL, 21400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(142, 1, NULL, 7, NULL, NULL, NULL, NULL, 'கைலாசம் மானசரோவர் யாத்திரை 2026 — தமிழக 48 பயணிகள்', 'kailash-mansarovar-yatra-2026-48-tamilnadu-pilgrims', 'கைலாசம் மானசரோவர் யாத்திரை 2026-ல் தமிழ்நாட்டிலிருந்து 48 பக்தர்கள் திருப்பயணம் தொடங்கினர்.', '<p>கைலாசம் மானசரோவர் யாத்திரை இரண்டாம் கட்டத்தில் தமிழ்நாட்டிலிருந்து 48 பக்தர்கள் தொடர்ந்தனர். ITBP வழிகாட்டலில் பயணம் தொடங்கியது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 06:55:00', NULL, '2026-06-08 07:00:00', 0, 0, 0, 0, 0, NULL, NULL, 12800, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(143, 1, NULL, 7, NULL, NULL, NULL, NULL, 'ரிஷிகேஷ் — சர்வதேச யோகா தினம் 50,000 பங்கேற்பு', 'rishikesh-international-yoga-day-50000-participants', 'ஐ.நா. சர்வதேச யோகா தினம் ரிஷிகேஷ் கங்கை கரையில் 50,000 பேர் கலந்துகொண்டனர்.', '<p>ஜூன் 21 சர்வதேச யோகா தினத்தில் ரிஷிகேஷ் கங்கை கரையில் 95 நாடுகளிலிருந்து 50,000க்கும் மேற்பட்டோர் யோகா பயிற்சியில் கலந்துகொண்டனர்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 06:55:00', NULL, '2026-06-07 07:00:00', 0, 1, 0, 0, 0, NULL, NULL, 16600, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(144, 1, NULL, 7, NULL, NULL, NULL, NULL, 'சிதம்பரம் நடராஜர் கோவில் — 1000 ஆண்டு மகோத்சவம் அறிவிப்பு', 'chidambaram-nataraja-temple-1000-year-festival', 'சிதம்பரம் நடராஜர் கோவில் 1000 ஆண்டு திருவிழா அடுத்த ஆண்டு நடைபெறும் என அறிவிக்கப்பட்டது.', '<p>சிதம்பரம் நடராஜர் கோவில் கும்பாபிஷேகம் 1000 ஆண்டு நிறைவை ஒட்டி 2027ல் மகோத்சவம் நடைபெறும். ₹100 கோடி நிதி ஒதுக்கீடு.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1548013146-72479768bada?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1548013146-72479768bada?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 07:55:00', NULL, '2026-06-06 08:00:00', 0, 0, 0, 0, 0, NULL, NULL, 9400, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(145, 1, NULL, 7, NULL, NULL, NULL, NULL, 'வேதாரண்யம் — 5000 ஆண்டு பழைமை கடலடி கோவில் கண்டுபிடிப்பு', 'vedaranyam-5000-year-underwater-temple-discovery', 'வேதாரண்யம் கடற்கரையில் நடத்தப்பட்ட ஆராய்ச்சியில் 5000 ஆண்டு பழைமையான கடலடி சிவன் கோவில் கண்டுபிடிக்கப்பட்டது.', '<p>ASI ஆராய்ச்சியாளர்கள் வேதாரண்யம் கடலடியில் 5000 ஆண்டு பழைமையான சிவன் கோவில் இடிபாடுகளை கண்டுபிடித்தனர். சங்க கால தமிழர் கட்டிடக்கலை வெளிப்பட்டது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-05 08:55:00', NULL, '2026-06-05 09:00:00', 1, 1, 1, 0, 0, NULL, NULL, 38700, 0, 0, 4, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(146, 1, NULL, 8, NULL, NULL, NULL, NULL, 'TCS Infosys Wipro — 2026-27ல் 2 லட்சம் Freshers நியமனம்', 'tcs-infosys-wipro-2-lakh-freshers-hiring-2026-27', 'IT துறை மீட்சி — TCS, Infosys, Wipro சேர்ந்து 2026-27 நிதியாண்டில் 2 லட்சம் fresher பட்டதாரிகளை நியமிக்கும்.', '<p>IT துறை மந்தநிலையிலிருந்து மீண்டு வரும் நிலையில் TCS 80,000, Infosys 50,000, Wipro 40,000 என மொத்தம் 2 லட்சம் பட்டதாரிகளை நியமிக்க திட்டமிட்டுள்ளன.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 07:55:00', NULL, '2026-06-10 08:00:00', 1, 1, 0, 0, 0, NULL, NULL, 42800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(147, 1, NULL, 8, NULL, NULL, NULL, NULL, 'TNPSC Group 2 — 2500 காலியிடங்கள் விண்ணப்பம் ஜூலை 1', 'tnpsc-group-2-2500-vacancies-application-july-1', 'TNPSC Group 2 2026 அறிவிப்பு வெளியானது. 2500 காலியிடங்களுக்கு ஜூலை 1 முதல் விண்ணப்பிக்கலாம்.', '<p>தமிழ்நாடு அரசு பணிகள் தேர்வாணையம் Group 2 A & 2 தேர்வுக்கு 2500 காலியிடங்களை அறிவித்தது. தகுதி: எந்த பாடத்தில் பட்டதாரி பட்டம்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 09:55:00', NULL, '2026-06-09 10:00:00', 0, 1, 1, 0, 0, NULL, NULL, 56405, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-29 18:22:32', NULL, 0),
(148, 1, NULL, 8, NULL, NULL, NULL, NULL, 'IIT Madras — AI & Data Science புதிய பட்டப்படிப்பு தொடக்கம்', 'iit-madras-ai-data-science-new-degree-programme', 'IIT Madras 2026-27 கல்வியாண்டு முதல் AI & Data Science B.Tech படிப்பு தொடங்குகிறது.', '<p>IIT Madras AI & Data Science B.Tech திட்டம் 2026-27 முதல் 60 இடங்களில் தொடங்குகிறது. JEE Advanced மதிப்பெண் அடிப்படையில் சேர்க்கை.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 08:55:00', NULL, '2026-06-08 09:00:00', 0, 0, 1, 0, 0, NULL, NULL, 28900, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(149, 1, NULL, 8, NULL, NULL, NULL, NULL, 'UPSC CSE 2025 — Final Result: 1016 பேர் தேர்வு, TN Top 5', 'upsc-cse-2025-final-result-1016-selected', 'UPSC Civil Services Exam 2025 இறுதி முடிவுகள் வெளியானது. 1016 பேர் தேர்வு, தமிழகத்திலிருந்து 5 பேர் Top 50.', '<p>UPSC CSE 2025 இறுதி முடிவுகளில் மொத்தம் 1016 பேர் தேர்வாகினர். சென்னையை சேர்ந்த Priya IAS Rank 8 பெற்றார்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 10:55:00', NULL, '2026-06-07 11:00:00', 0, 0, 0, 0, 0, NULL, NULL, 34700, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(150, 1, NULL, 8, NULL, NULL, NULL, NULL, 'Central Govt — SSC MTS 2026 அறிவிப்பு: 15,547 காலியிடங்கள்', 'ssc-mts-2026-notification-15547-vacancies', 'மத்திய அரசு SSC Multi Tasking Staff 2026 தேர்வுக்கு 15,547 காலியிடங்கள் அறிவிக்கப்பட்டன.', '<p>Staff Selection Commission MTS 2026 அறிவிப்பு. தகுதி: 10ஆம் வகுப்பு தேர்ச்சி. ஜூலையில் தேர்வு. ஆன்லைனில் விண்ணப்பிக்கலாம்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 08:55:00', NULL, '2026-06-06 09:00:00', 0, 1, 0, 0, 0, NULL, NULL, 48200, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(151, 1, NULL, 9, NULL, NULL, NULL, NULL, 'Sensex 90,000 கடந்தது — FII முதலீடு ₹50,000 கோடி', 'sensex-crosses-90000-fii-investment-50000-crore', 'BSE Sensex முதல்முறையாக 90,000 புள்ளிகளை கடந்தது. FII முதலீடு ₹50,000 கோடி.', '<p>BSE Sensex 90,000 புள்ளிகளை கடந்து வரலாற்று சாதனை படைத்தது. RBI வட்டி குறைப்பு மற்றும் வலுவான GDP வளர்ச்சி முதலீட்டாளர் நம்பிக்கையை ஏற்படுத்தியது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-10 08:55:00', NULL, '2026-06-10 09:00:00', 1, 1, 0, 0, 0, NULL, NULL, 38400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(152, 1, NULL, 9, NULL, NULL, NULL, NULL, 'Amazon India — ₹1 லட்சம் கோடி 5 ஆண்டு முதலீட்டு திட்டம்', 'amazon-india-1-lakh-crore-5-year-investment-plan', 'Amazon India 2030 வரை ₹1 லட்சம் கோடி முதலீடு செய்வதாக CEO Andy Jassy அறிவித்தார்.', '<p>Amazon India CEO Andy Jassy நரேந்திர மோடி சந்திப்பில் 5 ஆண்டுகளில் ₹1 லட்சம் கோடி முதலீட்டை உறுதிப்படுத்தினார். 2 லட்சம் வேலைவாய்ப்புகள் உருவாகும்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1523474253046-8cd2748b5fd2?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1523474253046-8cd2748b5fd2?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 10:55:00', NULL, '2026-06-09 11:00:00', 0, 0, 1, 0, 0, NULL, NULL, 24600, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(153, 1, NULL, 9, NULL, NULL, NULL, NULL, 'Tata Motors EV — Nexon Electric 2.0 ₹12 லட்சத்தில் வெளியீடு', 'tata-motors-nexon-electric-2-launch-12-lakh', 'Tata Motors Nexon EV 2.0 ₹12 லட்சத்தில் வெளியிட்டது. 600km range, 30 நிமிடத்தில் 80% charging.', '<p>Tata Motors Nexon EV 2.0 ₹11.99 லட்சத்தில் (ex-showroom) வெளியிட்டது. 600km real-world range, 11.2kW fast charging, V2H technology.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 11:55:00', NULL, '2026-06-08 12:00:00', 0, 1, 0, 0, 0, NULL, NULL, 19800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(154, 1, NULL, 9, NULL, NULL, NULL, NULL, 'Gold விலை — ₹80,000 கடந்தது; Akshaya Tritiya தேவை உயர்வு', 'gold-price-crosses-80000-per-10-gram-june-2026', 'தங்கம் விலை 10 கிராமுக்கு ₹80,000 கடந்தது. சர்வதேச அளவில் Dollar பலவீனமே காரணம்.', '<p>சென்னை சர்ரட்டா தங்கம் விலை 10 கிராமுக்கு ₹80,250 என்ற சாதனை விலையை தொட்டது. Silver ₹95,000/kg. Dollar index குறைவே காரணம்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 09:55:00', NULL, '2026-06-07 10:00:00', 1, 0, 1, 0, 0, NULL, NULL, 29400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(155, 1, NULL, 9, NULL, NULL, NULL, NULL, 'MSMEs — மத்திய அரசு ₹10,000 கோடி வட்டி இல்லா கடன் திட்டம்', 'msme-central-govt-10000-crore-interest-free-loan', 'மத்திய அரசு MSMEs-க்கு ₹10,000 கோடி வட்டி இல்லா கடன் திட்டம் அறிவித்தது.', '<p>மத்திய MSME அமைச்சகம் 50 லட்சம் சிறு, குறு நிறுவனங்களுக்கு ₹2 லட்சம் வரை வட்டி இல்லா கடன் வழங்கும் திட்டம் அறிவித்தது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 09:55:00', NULL, '2026-06-06 10:00:00', 0, 0, 0, 0, 0, NULL, NULL, 14200, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(156, 1, NULL, 10, NULL, NULL, NULL, NULL, 'ICMR — புதிய இந்திய உணவு வழிகாட்டுதல்கள் வெளியீடு', 'icmr-new-indian-dietary-guidelines-2026', 'ICMR இந்தியர்களுக்கான புதிய உணவு வழிகாட்டுதல்கள் வெளியிட்டது. Ultra-processed food எச்சரிக்கை.', '<p>ICMR புதிய Dietary Guidelines For Indians 2026 வெளியிட்டது. Ultra-processed food குறைக்க வலியுறுத்தல், பாரம்பரிய உணவுக்கு முன்னுரிமை.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-09 08:55:00', NULL, '2026-06-09 09:00:00', 0, 1, 0, 0, 0, NULL, NULL, 22800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(157, 1, NULL, 10, NULL, NULL, NULL, NULL, 'தமிழ்நாடு — Dengue எச்சரிக்கை; 500 பேருக்கு சிகிச்சை', 'tamilnadu-dengue-warning-500-cases-june-2026', 'தமிழ்நாட்டில் டெங்கு காய்ச்சல் பரவல் அதிகரிப்பால் சுகாதாரத்துறை எச்சரிக்கை விடுத்தது.', '<p>தமிழ்நாட்டில் ஜூன் மாதம் மட்டும் 500 டெங்கு காய்ச்சல் வழக்குகள் பதிவாகின. வீட்டில் தண்ணீர் தேக்கம் இல்லாமல் பார்த்துக்கொள்ள சுகாதாரத்துறை வேண்டுகோள்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1584515933487-779824d29309?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1584515933487-779824d29309?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 07:55:00', NULL, '2026-06-08 08:00:00', 1, 0, 0, 0, 0, NULL, NULL, 18600, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(158, 1, NULL, 10, NULL, NULL, NULL, NULL, 'AI Doctor — இந்திய ஸ்டார்ட்அப் AI கண்டுபிடிப்பு சாதனை', 'indian-startup-ai-doctor-disease-detection-breakthrough', 'Chennai AI ஸ்டார்ட்அப் 98% துல்லியத்துடன் 20 நோய்களை கண்டுபிடிக்கும் AI கண்டுபிடித்தது.', '<p>சென்னை AI ஸ்டார்ட்அப் MedAI 98.7% துல்லியத்துடன் Cancer, Diabetes, Heart Disease உட்பட 20 நோய்களை கண்டுபிடிக்கும் AI Algorithm கண்டுபிடித்தது. WHO அங்கீகாரம் பெற்றது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 09:55:00', NULL, '2026-06-07 10:00:00', 0, 1, 1, 0, 0, NULL, NULL, 34200, 0, 0, 4, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(159, 1, NULL, 10, NULL, NULL, NULL, NULL, 'யோகா — தினசரி 30 நிமிட பயிற்சி ரத்த அழுத்தம் குறைக்கும்: ஆய்வு', 'yoga-30-minutes-daily-reduces-blood-pressure-study', 'AIIMS புதிய ஆய்வு: தினசரி 30 நிமிட யோகா 12 வாரத்தில் ரத்த அழுத்தத்தை 15% குறைக்கும்.', '<p>AIIMS New Delhi நடத்திய ஆய்வில் 500 பேரிடம் 12 வாரம் யோகா பயிற்சி செய்தபோது systolic BP 15%, diastolic BP 10% குறைந்தது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 06:55:00', NULL, '2026-06-06 07:00:00', 0, 0, 0, 0, 0, NULL, NULL, 16400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(160, 1, NULL, 10, NULL, NULL, NULL, NULL, 'AIIMS Chennai — Liver Transplant 1000வது வெற்றிகரமான அறுவை சிகிச்சை', 'aiims-chennai-1000th-successful-liver-transplant', 'AIIMS Chennai 1000வது Liver Transplant அறுவை சிகிச்சை வெற்றிகரமாக நடைபெற்றது.', '<p>AIIMS Chennai Liver Transplant Unit 1000வது சிகிச்சையை வெற்றிகரமாக நிறைவேற்றியது. 98.5% வெற்றி விகிதம் உலக தரத்தில் உள்ளது.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-05 10:55:00', NULL, '2026-06-05 11:00:00', 0, 1, 0, 0, 0, NULL, NULL, 12800, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(161, 1, NULL, 11, NULL, NULL, NULL, NULL, 'Vikatan TV — தமிழ்நாடு பட்ஜெட் 2026 நேரடி ஒளிபரப்பு', 'vikatan-tv-tamilnadu-budget-2026-live-coverage', 'தமிழ்நாடு பட்ஜெட் 2026 நேரடி ஒளிபரப்பு Vikatan TV YouTube-ல் 2 மில்லியன் பார்வையாளர்கள்.', '<p>தமிழ்நாடு சட்டசபையில் நிதியமைச்சர் நிதிநிலை அறிக்கை வாசிக்கும்போது Vikatan TV YouTube நேரடி ஒளிபரப்பில் 2 மில்லியன் பேர் பார்த்தனர். Record.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-08 10:55:00', NULL, '2026-06-08 11:00:00', 0, 0, 0, 0, 0, NULL, NULL, 14800, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(162, 1, NULL, 11, NULL, NULL, NULL, NULL, 'Rajinikanth Speech — Superstar பிறந்தநாள் FANS கொண்டாட்டம்', 'rajinikanth-birthday-fans-celebration-video', 'சூப்பர் ஸ்டார் ரஜினிகாந்த் 76வது பிறந்தநாளில் ரசிகர்கள் பிரமாண்ட கொண்டாட்டம் VIDEO.', '<p>ரஜினிகாந்தின் 76வது பிறந்தநாளில் உலகம் முழுவதும் ரசிகர்கள் கொண்டாட்டங்கள் நடத்தினர். Chennai Rajini Makkal Mandram கேக் வெட்டி கொண்டாட்டம்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-07 11:55:00', NULL, '2026-06-07 12:00:00', 0, 1, 0, 0, 0, NULL, NULL, 28601, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-22 14:20:24', NULL, 0),
(163, 1, NULL, 11, NULL, NULL, NULL, NULL, 'Chennai Floods — மழை நாசம் Aerial Video', 'chennai-floods-aerial-view-video-june-2026', 'சென்னையில் கனமழை ஏற்படுத்திய வெள்ளம் — Drone Aerial Video பரவி பரபரப்பு.', '<p>சென்னை வெள்ளத்தை Drone மூலம் எடுத்த Aerial Video Social Media-ல் வைரல். Velachery, Tambaram பகுதிகளில் மோசமான நிலை.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-06 15:55:00', NULL, '2026-06-06 16:00:00', 1, 0, 1, 0, 0, NULL, NULL, 44200, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(164, 1, NULL, 11, NULL, NULL, NULL, NULL, 'ISRO SpaDeX Docking — வரலாற்று தருணம் LIVE VIDEO', 'isro-spadex-docking-live-video-historic-moment', 'ISRO SpaDeX Docking வரலாற்று தருணம் — YouTube Live-ல் 5 மில்லியன் பேர் பார்த்தனர்.', '<p>ISRO SpaDeX Docking Live Stream-ல் 5.2 மில்லியன் பேர் ஒரே நேரத்தில் பார்த்து சாதனை. விண்வெளி வீரர்கள் கட்டுப்பாட்டு அறையில் கொண்டாட்டம்.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1517976487492-5750f3195933?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1517976487492-5750f3195933?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-05 08:55:00', NULL, '2026-06-05 09:00:00', 0, 1, 0, 0, 0, NULL, NULL, 32400, 0, 0, 3, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(165, 1, NULL, 11, NULL, NULL, NULL, NULL, 'Viral Video — கோவை யானை குட்டி மீட்பு நெகிழ்ச்சி காட்சி', 'coimbatore-baby-elephant-rescue-viral-video', 'கோவை காட்டில் ஆற்றில் சிக்கிய யானை குட்டியை காட்டுயிர் அதிகாரிகள் மீட்ட நெகிழ்ச்சி VIDEO வைரல்.', '<p>கோவை டாப்ஸ்லிப் அருகே ஆற்றில் சிக்கிய யானை குட்டியை Forest Department அதிகாரிகள் 3 மணி நேர முயற்சிக்கு பிறகு மீட்டனர். Video 10 மில்லியன் views.</p>', 'news', NULL, 'ta', NULL, NULL, 'https://images.unsplash.com/photo-1551316679-9c6ae9dec224?w=800&q=80', NULL, 'https://images.unsplash.com/photo-1551316679-9c6ae9dec224?w=400&q=70', NULL, NULL, 'published', 'published', NULL, 1, '2026-06-04 13:55:00', NULL, '2026-06-04 14:00:00', 0, 0, 0, 0, 0, NULL, NULL, 18900, 0, 0, 2, '2026-06-10 13:42:26', '2026-06-10 13:42:26', NULL, 0),
(166, 4, NULL, 1, NULL, NULL, NULL, 6, 'அதிமுக விஜயபாஸ்கர் ராஜினாமா', '80d72058', 'அதிமுகவைச் சேர்ந்த முன்னாள் அமைச்சரும், விராலிமலை தொகுதி சட்டமன்ற உறுப்பினருமான டாக்டர் சி.விஜயபாஸ்கர் தனது எம்.எல்.ஏ பதவியை சமீபத்தில் ராஜினாமா செய்துள்ளார். இ…', 'அதிமுகவைச் சேர்ந்த முன்னாள் அமைச்சரும், விராலிமலை தொகுதி சட்டமன்ற உறுப்பினருமான டாக்டர் சி.விஜயபாஸ்கர் தனது எம்.எல்.ஏ பதவியை சமீபத்தில் ராஜினாமா செய்துள்ளார். இவரது ராஜினாமாவை சபாநாயகர் ஜே.சி.டி பிரபாகர் ஏற்றுக்கொண்டுள்ளார். 2026 ஆம் ஆண்டு நடைபெற்ற சட்டமன்றத் தேர்தலுக்குப் பிறகு, அதிமுகவில் இருந்து ராஜினாமா செய்த ஐந்தாவது சட்டமன்ற உறுப்பினர் இவர் என்பது குறிப்பிடத்தக்கது.தலைநகர் டெல்லியில் அரசு முறைப் பயணமாக சுற்றுப்பயணம் மேற்கொண்டுள்ள தமிழக முதலமைச்சர் மு.க.ஸ்டாலின் அவர்கள், குடியரசுத் தலைவர் மற்றும் துணைக் குடியரசுத் தலைவர் உள்ளிட்டோரைச் சந்தித்து முக்கியப் பேச்சுவார்த்தைகளை நடத்தி வருகிறார்.', 'news', NULL, 'ta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-18 19:44:39', 0, 0, 0, 0, 0, NULL, NULL, 2, 0, 0, 1, '2026-06-16 13:56:28', '2026-06-22 14:42:33', NULL, 0),
(167, 4, NULL, 5, NULL, NULL, NULL, 5, 'ராயல் சேலஞ்சர்ஸ் பெங்களூரு (RCB) அணி சாம்பியன் பட்டத்தை வென்றுள்ளது', 'rcb', 'மே 31, 2026 அன்று நடைபெற்ற ஐபிஎல் (IPL 2026) இறுதிப் போட்டியில் குஜராத் டைட்டன்ஸ் (Gujarat Titans) அணியை 5 விக்கெட் வித்தியாசத்தில் வீழ்த்தி ராயல் சேலஞ்சர்ஸ் பெ…', 'மே 31, 2026 அன்று நடைபெற்ற ஐபிஎல் (IPL 2026) இறுதிப் போட்டியில் குஜராத் டைட்டன்ஸ் (Gujarat Titans) அணியை 5 விக்கெட் வித்தியாசத்தில் வீழ்த்தி ராயல் சேலஞ்சர்ஸ் <h2>பெங்களூரு (RCB) அணி சாம்பியன் </h2>பட்டத்தை வென்றுள்ளது.\r\n\r\nஐபிஎல் 2026 இறுதிப் போட்டி விவரங்கள்:இடம்: நரேந்திர மோடி ஸ்டேடியம், அகமதாபாத்.போட்டி முடிவு: ஆர்சிபி (RCB) 5 விக்கெட் வித்தியாசத்தில் அபார வெற்றி.சிறப்பு ஆட்டம்: <strong>விராட் கோலி 42 பந்துகளில் 75 ரன்கள்</strong> எடுத்து அணியின் வெற்றிக்கு முக்கியப் பங்காற்றினார்.குஜராத் டைட்டன்ஸ் நிர்ணயித்த 155 ரன்கள் என்ற வெற்றி இலக்கை நோக்கி களமிறங்கிய பெங்களூரு அணி, கடைசி ஓவர்களில் சிறப்பாக விளையாடி கோப்பையைத் தட்டிச் சென்றது. \r\n\r\nஇந்த வரலாற்று வெற்றியைத் தொடர்ந்து, கிரிக்கெட் ஜாம்பவான்களான சச்சின் டெண்டுல்கர், சுரேஷ் ரெய்னா போன்ற பிரபலங்களும், சென்னை சூப்பர் கிங்ஸ் (CSK) அணியும் ஆர்சிபிக்கு வாழ்த்துகளைத் தெரிவித்தனர்.', 'news', NULL, 'ta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-16 19:39:52', 0, 1, 0, 0, 0, NULL, NULL, 164, 0, 0, 1, '2026-06-16 14:08:34', '2026-06-26 14:46:38', NULL, 0),
(169, 5, NULL, 1, NULL, NULL, NULL, 12, 'fasdfasdf', 'fasdfasdf', 'asfasdf aasdf df as fasdf asd', 'asfasdf aasdf df as fasdf asd', 'news', NULL, 'ta', NULL, NULL, '/uploads/2026/06/media_6a423a5dcc4435.89964829.png', NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-19 19:11:10', 0, 0, 0, 0, 0, 'fasdfasdf', 'asfasdf aasdf df as fasdf asd', 4, 0, 0, 1, '2026-06-19 13:41:10', '2026-06-30 07:45:53', NULL, 0);
INSERT INTO `tn_articles` (`id`, `user_id`, `contributor_id`, `category_id`, `district_id`, `city_id`, `city_text`, `media_id`, `title`, `slug`, `excerpt`, `content`, `content_type`, `sponsored_subscription_id`, `language`, `youtube_url`, `youtube_video_id`, `image_url`, `news_card_image`, `thumb_url`, `image_caption`, `image_credit`, `status`, `approval_stage`, `rejection_reason`, `approved_by`, `approved_at`, `scheduled_at`, `published_at`, `is_breaking`, `is_featured`, `is_editors_pick`, `is_premium`, `is_sponsored`, `meta_title`, `meta_desc`, `view_count`, `share_count`, `comment_count`, `read_time`, `created_at`, `updated_at`, `rating_avg`, `rating_count`) VALUES
(170, 4, NULL, 1, NULL, NULL, NULL, 8, 'பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு', 'பூந்தமல்லி-வடபழனி-மெட்ரோ-சேவை-தொடக்கம்-எப்போது-பயணிகளின்-எதிர்பார்ப்பு-அதிகரிப்பு', '# பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு\r\n\r\nசென்னையின் இரண்டாம் கட்ட மெட்ரோ ரயில் திட்டத்தில் முக்கியமான பகுதியாகக…', '#<strong> பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு</strong>\r\n\r\n<h2>சென்னையின் இரண்டாம் கட்ட மெட்ரோ ரயில் திட்டத்தில் முக்கியமான பகுதியாகக் கருதப்படும் பூந்தமல்லி – வடபழனி வழித்தடம் இயக்கத்திற்குத் தயாராக இருப்பதாக தகவல்கள் வெளியாகியுள்ளன. தேவையான பாதுகாப்பு ஆய்வுகள் மற்றும் தொழில்நுட்ப அனுமதிகள் பெரும்பாலும் நிறைவடைந்துள்ள நிலையில், இந்த சேவை எப்போது பொதுமக்கள் பயன்பாட்டுக்கு திறக்கப்படும் என்ற எதிர்பார்ப்பு அதிகரித்துள்ளது.</h2>\r\n\r\n<blockquote>14.6 கிலோமீட்டர் நீளமுள்ள இந்த வழித்தடத்தில் ரயில் இயக்கத்திற்கான இறுதி அனுமதி கிடைத்துள்ளதாக கூறப்படுகிறது. இதன் காரணமாக, தொடக்க விழா தொடர்பான முன்னேற்பாடுகளை சென்னை மெட்ரோ நிர்வாகம் மேற்கொண்டு வருகிறது.</blockquote>\r\n\r\nமுன்னதாக பாதுகாப்பு அதிகாரிகள் சில கட்டுப்பாடுகளுடன் மட்டுமே அனுமதி வழங்கியிருந்தனர். இதனால் குறைந்த எண்ணிக்கையிலான ரயில்களை மட்டுமே இயக்க முடிந்தது. தற்போது அந்த நிபந்தனைகள் தளர்த்தப்பட்டுள்ளதால், குறுகிய நேர இடைவெளியில் தொடர்ந்து சேவைகளை வழங்கும் வாய்ப்பு உருவாகியுள்ளது.\r\n\r\nஇந்த வழித்தடத்தில் பூந்தமல்லி பைபாஸ், பூந்தமல்லி, முல்லைத்தோட்டம், காரஞ்சாவடி, குமணன்சாவடி, காட்டுப்பாக்கம், ஐயப்பன்தாங்கல், தெள்ளியகரம், போரூர் பைபாஸ் மற்றும் போரூர் சந்திப்பு உள்ளிட்ட நிலையங்கள் பயன்பாட்டுக்கு தயாராக உள்ளன.\r\n\r\nஎனினும், ஆலப்பாக்கம், காரம்பாக்கம், வளசரவாக்கம், ஆழ்வார்திருநகர், சாலிகிராமம் கிடங்கு மற்றும் சாலிகிராமம் ஆகிய ஆறு இடைநிலை நிலையங்களில் கட்டுமானப் பணிகள் இன்னும் முழுமையடையவில்லை. இதனால் தொடக்ககட்டத்தில் போரூர் சந்திப்பிலிருந்து வடபழனி வரை இடைநிறுத்தமின்றி நேரடி சேவை இயக்கப்படும் என எதிர்பார்க்கப்படுகிறது.\r\n\r\nபாதுகாப்பு ஆய்வுக் குழுக்கள் பல கட்டங்களில் ஆய்வு மேற்கொண்டு தேவையான பரிந்துரைகளை வழங்கியுள்ளன. மீதமுள்ள நிலையங்களின் பணிகள் முடிவடைந்த பின்னரே முழுமையான நிரந்தர அங்கீகாரம் வழங்கப்படும் என அதிகாரப்பூர்வ வட்டாரங்கள் தெரிவித்துள்ளன.\r\n\r\nஇந்த மெட்ரோ பாதை செயல்பாட்டுக்கு வந்தால், மேற்குத் சென்னை பகுதிகளில் வசிக்கும் ஆயிரக்கணக்கான மாணவர்கள், அலுவலக ஊழியர்கள் மற்றும் மருத்துவமனைகளுக்குச் செல்லும் பொதுமக்கள் பெரிதும் பயன்பெறுவார்கள். பல கல்வி நிறுவனங்கள், வணிக வளாகங்கள் மற்றும் முக்கிய மருத்துவமனைகள் இந்த வழித்தடத்தை ஒட்டியுள்ளதால், பயண நேரம் கணிசமாகக் குறையும் என்று எதிர்பார்க்கப்படுகிறது.\r\n\r\nதற்போது முக்கிய நிலையங்கள் தயாராக இருந்தாலும், மீதமுள்ள கட்டுமானப் பணிகள் நிறைவடைந்த பிறகே சேவை தொடக்க தேதி அறிவிக்கப்படும் என மெட்ரோ நிர்வாகம் தெரிவித்துள்ளது. அனைத்து பாதுகாப்பு அனுமதிகளும் கிடைத்துள்ள நிலையில், பூந்தமல்லி – வடபழனி மெட்ரோ சேவை விரைவில் பொதுமக்கள் பயன்பாட்டிற்கு திறக்கப்படும் என்ற நம்பிக்கை நிலவுகிறது.\r\n', 'news', NULL, 'ta', NULL, NULL, '/uploads/2026/06/media_6a363ca7f1b9e7.92068323.webp', NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-20 11:11:49', 0, 1, 0, 0, 0, 'பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு', '# பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு\r\n\r\nசென்னையின் இரண்டாம் கட்ட மெட்ரோ ரயில் திட்டத்தில் முக்கியமான பகுதியாகக', 67, 0, 0, 1, '2026-06-20 05:39:36', '2026-06-30 07:45:53', NULL, 0),
(171, 5, NULL, 1, NULL, NULL, NULL, 10, 'எதிர்க்கட்சிகளின் குற்றச்சாட்டுகளுக்கு சட்டப்பேரவையில் பதிலளிக்கும் விஜய்', 'எதிர்க்கட்சிகளின்-குற்றச்சாட்டுகளுக்கு-சட்டப்பேரவையில்-பதிலளிக்கும்-விஜய்', 'முதலமைச்சர் விஜய் தலைமையிலான அரசின் முதல் சட்டப்பேரவை கூட்டத் தொடர் கடந்த 18ஆம் தேதி ஆளுநரின் உரையுடன் அதிகாரப்பூர்வமாக தொடங்கியது. அதனைத் தொடர்ந்து நடைபெற்ற அல…', 'முதலமைச்சர் விஜய் தலைமையிலான அரசின் முதல் சட்டப்பேரவை கூட்டத் தொடர் கடந்த 18ஆம் தேதி ஆளுநரின் உரையுடன் அதிகாரப்பூர்வமாக தொடங்கியது. அதனைத் தொடர்ந்து நடைபெற்ற அலுவல் ஆய்வுக் குழுக் கூட்டத்தில், சட்டப்பேரவை அமர்வை மூன்று நாட்கள் நடத்த தீர்மானிக்கப்பட்டது. அதன் அடிப்படையில் கூட்டத் தொடர் திட்டமிட்டபடி நடைபெற்று வருகிறது.\r\n\r\nகூட்டத் தொடரின் முதல் நாளில், காவிரி நதியின் குறுக்கே மேகதாது அணையை அமைக்க கர்நாடக அரசு மேற்கொண்டுள்ள முயற்சிக்கு எதிராக முதலமைச்சர் விஜய் தனித் தீர்மானத்தை சட்டப்பேரவையில் முன்வைத்தார். தமிழகத்தின் நீர்வள உரிமைகளை பாதுகாக்கும் நோக்கில் இந்த தீர்மானம் கொண்டு வரப்பட்டதாக தெரிவிக்கப்பட்டது.\r\n\r\nஇதற்கிடையில், சட்டப்பேரவையின் முதல் நாள் அமர்விலேயே தி.மு.க. மற்றும் த.வெ.க. உறுப்பினர்களுக்கிடையே கடுமையான கருத்து மோதல்கள் ஏற்பட்டன. பல்வேறு விவகாரங்கள் தொடர்பாக இரு தரப்பினரும் தங்களது நிலைப்பாடுகளை வலியுறுத்தியதால் அவையில் பரபரப்பான சூழல் நிலவியது. பின்னர் இரண்டு நாட்கள் விடுமுறைக்குப் பிறகு நேற்று மீண்டும் சட்டப்பேரவை அமர்வு தொடங்கியது.\r\n\r\nநேற்றைய அமர்வில் புதிய நடுவர் மன்றம் அமைப்பது தொடர்பான கோரிக்கை விவாதத்திற்கு வந்தது. இந்த விவகாரத்தில் அ.தி.மு.க. உள்ளிட்ட எதிர்க்கட்சிகள் கடுமையான எதிர்ப்பை பதிவு செய்தன. புதிய நடுவர் மன்றம் அமைக்கும் முடிவை அரசு மறுபரிசீலனை செய்து திரும்பப் பெற வேண்டும் என்றும் அவர்கள் வலியுறுத்தினர். இதனால் அவையில் மீண்டும் காரசாரமான விவாதங்கள் நடைபெற்றன.\r\n\r\nஇந்த நிலையில், ஆளுநர் உரைக்கு நன்றி தெரிவிக்கும் தீர்மானம் மீதான விவாதத்திற்கு இன்று முதலமைச்சர் விஜய் பதிலுரை வழங்க உள்ளார். கடந்த சில நாட்களாக உறுப்பினர்கள் முன்வைத்த கருத்துகள், விமர்சனங்கள் மற்றும் ஆலோசனைகளுக்கு அவர் விரிவாக விளக்கம் அளிப்பார் என எதிர்பார்க்கப்படுகிறது.\r\n\r\nமேலும், மாநிலத்தின் சட்டம் மற்றும் ஒழுங்கு நிலைமை தொடர்பாக எதிர்க்கட்சிகள் எழுப்பிய பல்வேறு கேள்விகளுக்கும் முதலமைச்சர் இன்று சட்டப்பேரவையில் பதிலளிக்க உள்ளார். மாநிலத்தில் நிலவும் பாதுகாப்பு சூழல், அரசின் நடவடிக்கைகள் மற்றும் பொதுமக்கள் நலன் சார்ந்த அம்சங்கள் குறித்து விரிவான விளக்கங்களை அவர் வழங்குவார் என அரசியல் வட்டாரங்கள் தெரிவித்துள்ளன.\r\n', 'news', NULL, 'ta', NULL, NULL, '/uploads/2026/06/media_6a3a2958794838.85250353.webp', NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-23 12:12:31', 0, 0, 0, 0, 0, NULL, NULL, 7, 0, 0, 1, '2026-06-23 06:36:10', '2026-06-30 07:45:53', NULL, 0),
(172, 4, NULL, 1, NULL, NULL, NULL, 5, 'போதைப்பொருள் எதிர்ப்பு தினம்: சென்னையில் விழிப்புணர்வு மாரத்தானில் முதல்வர் விஜய் பங்கேற்பு', 'போதைப்பொருள்-எதிர்ப்பு-தினம்-சென்னையில்-விழிப்புணர்வு-மாரத்தானில்-முதல்வர்-விஜய்-பங்கேற்பு', 'சர்வதேச போதைப்பொருள் எதிர்ப்பு தினத்தை முன்னிட்டு, சென்னையில் போதைப்பொருள் ஒழிப்பு குறித்த விழிப்புணர்வை ஏற்படுத்தும் வகையில் மாரத்தான் ஓட்டம் நடைபெற்றது.\r\n\r\nஇந…', '<p>சர்வதேச போதைப்பொருள் எதிர்ப்பு தினத்தை முன்னிட்டு, சென்னையில் போதைப்பொருள் ஒழிப்பு குறித்த விழிப்புணர்வை ஏற்படுத்தும் வகையில் மாரத்தான் ஓட்டம் நடைபெற்றது.</p>\r\n\r\n<p>இந்த நிகழ்ச்சியை முதல்வர் விஜய் தொடங்கி வைத்து, பொதுமக்கள், மாணவர்கள் மற்றும் இளைஞர்களுடன் இணைந்து மாரத்தானில் பங்கேற்றார். மேலும், போதைப்பொருள் பழக்கத்துக்கு எதிராக உறுதிமொழி எடுக்கப்பட்டு, சமூகத்தில் விழிப்புணர்வை அதிகரிக்க வேண்டியதன் அவசியம் வலியுறுத்தப்பட்டது.</p>\r\n\r\n<blockquote>போதைப்பொருள் இல்லாத சமூகத்தை உருவாக்க அனைவரும் ஒன்றிணைந்து செயல்பட வேண்டும் என நிகழ்வில் வலியுறுத்தப்பட்டது. \"சென்னை மாரத்தானில் முதல்வர் விஜய் ஓட்டம்.. போதைப்பொருள் ஒழிப்பு விழிப்புணர்வில் பங்கேற்பு</blockquote>', 'news', NULL, 'ta', NULL, NULL, '/uploads/2026/06/media_6a315f2e8a0ae0.67488960.webp', NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-26 14:09:29', 0, 1, 0, 0, 0, 'போதைப்பொருள் எதிர்ப்பு தினம்: சென்னையில் விழிப்புணர்வு மாரத்தானில் முதல்வர் விஜய் பங்கேற்பு', 'சர்வதேச போதைப்பொருள் எதிர்ப்பு தினத்தை முன்னிட்டு, சென்னையில் போதைப்பொருள் ஒழிப்பு குறித்த விழிப்புணர்வை ஏற்படுத்தும் வகையில் மாரத்தான் ஓட்டம் நடைபெற்றது.\r\n\r\nஇந', 49, 0, 0, 1, '2026-06-26 08:39:08', '2026-06-30 07:45:53', NULL, 0),
(173, 4, NULL, 1, NULL, NULL, NULL, 14, 'அன்பில் மகேஷ் - APAAR', 'apaar', 'ஒளிந்திருந்த பூனை இவ்வளவு சீக்கிரம் வெளியே வரும் என எதிர்பார்க்கவில்லை! \r\n\r\nதேசியக் கல்விக் கொள்கை - 2020ன் ஒரு அங்கமாக APAAR(Automated Permanent Academic Accou…', 'ஒளிந்திருந்த பூனை இவ்வளவு சீக்கிரம் வெளியே வரும் என எதிர்பார்க்கவில்லை! \r\n\r\nதேசியக் கல்விக் கொள்கை - 2020ன் ஒரு அங்கமாக APAAR(Automated Permanent Academic Account Registry) Card வழங்கும் திட்டம் உள்ளது. அதாவது “ஒரே நாடு ஒரே அடையாள அட்டை” என்ற அடிப்படையில் நமது தமிழ்நாட்டு மாணவர்களின் சுயவிவரத்தை ஒன்றிய அரசிடம் (அதிகாரப்பூர்வமாக!) ஒப்படைக்கும் திட்டம். இதைத்தான் வேறு வடிவத்தில் அமல்படுத்த இருக்கிறார்கள் என்பது த.வெ.க. அரசின் அமைச்சர் பேசியிருப்பதில் இருந்து தெரியவருகிறது. அதுவும் மாண்புமிகு முதலமைச்சர் அவர்களின் ஆணைக்கிணங்க! \r\n\r\nதேசியக் கல்விக் கொள்கையை ஏற்கவே மாட்டோம் என்றவர்கள், இதுபோன்ற செயல்களில் இறங்கியுள்ளார்கள்.\r\n\r\n“இரண்டாயிரம் கோடி என்ன? 10000 கோடி கொடுத்தாலும் NEP-2020ஐ ஏற்க மாட்டோம்” எனச் சொன்ன எங்கள் கழகத்தலைவர் அவர்களின் நெஞ்சுரம் இந்த ஆட்சியாளர்களுக்கும் வேண்டும். அது இல்லாமல் தேவையின்றி நமது மாணவர்களின் அடிப்படைத் தகவல்களை ஒன்றிய அரசிடம் தட்டில் வைத்து மரியாதையோடு கொடுக்கும் வேலையை அறவே கைவிட வேண்டும்.\r\n-அன்பில் மகேஷ் பொய்யாமொழி\r\n', 'news', NULL, 'ta', NULL, NULL, '/uploads/2026/06/media_6a43743f1ec607.87950965.png', NULL, NULL, NULL, NULL, 'published', 'reporter', NULL, NULL, NULL, NULL, '2026-06-29 23:35:00', 0, 1, 0, 0, 0, 'அன்பில் மகேஷ் - APAAR', 'ஒளிந்திருந்த பூனை இவ்வளவு சீக்கிரம் வெளியே வரும் என எதிர்பார்க்கவில்லை! \r\n\r\nதேசியக் கல்விக் கொள்கை - 2020ன் ஒரு அங்கமாக APAAR(Automated Permanent Academic Accou', 32, 0, 0, 1, '2026-06-29 18:03:55', '2026-07-13 14:44:22', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tn_article_ratings`
--

CREATE TABLE `tn_article_ratings` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `reader_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_hash` varchar(64) DEFAULT NULL,
  `rating` tinyint(1) UNSIGNED NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_article_ratings`
--

INSERT INTO `tn_article_ratings` (`id`, `article_id`, `reader_id`, `ip_hash`, `rating`, `review`, `created_at`) VALUES
(1, 172, 1, NULL, 5, 'super', '2026-06-27 14:22:28');

-- --------------------------------------------------------

--
-- Table structure for table `tn_article_tags`
--

CREATE TABLE `tn_article_tags` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_article_tags`
--

INSERT INTO `tn_article_tags` (`article_id`, `tag_id`) VALUES
(170, 1),
(170, 2),
(170, 3),
(173, 4),
(173, 5),
(173, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tn_business_ads`
--

CREATE TABLE `tn_business_ads` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `website_url` varchar(300) DEFAULT NULL,
  `facebook_url` varchar(500) DEFAULT NULL,
  `instagram_url` varchar(500) DEFAULT NULL,
  `youtube_url` varchar(500) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `small_desc` text DEFAULT NULL,
  `package_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 2,
  `slot_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `display_type` enum('global','location','category') NOT NULL DEFAULT 'global',
  `district_id` smallint(5) UNSIGNED DEFAULT NULL,
  `city_id` smallint(5) UNSIGNED DEFAULT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `linked_article_id` int(10) UNSIGNED DEFAULT NULL,
  `linked_video_id` int(10) UNSIGNED DEFAULT NULL,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `payment_status` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_note` varchar(300) DEFAULT NULL,
  `payment_ref` varchar(200) DEFAULT NULL,
  `payment_confirmed_by` int(10) UNSIGNED DEFAULT NULL,
  `payment_confirmed_at` datetime DEFAULT NULL,
  `status` enum('pending','approved','rejected','active','expired','paused') NOT NULL DEFAULT 'pending',
  `rejection_reason` varchar(300) DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `impression_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `click_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `submitted_by` int(10) UNSIGNED NOT NULL,
  `owner_user_id` int(10) UNSIGNED DEFAULT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `owner_username` varchar(100) DEFAULT NULL,
  `owner_password` varchar(255) DEFAULT NULL,
  `owner_last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_business_ads`
--

INSERT INTO `tn_business_ads` (`id`, `business_name`, `contact_person`, `contact_phone`, `contact_email`, `website_url`, `facebook_url`, `instagram_url`, `youtube_url`, `address`, `small_desc`, `package_id`, `slot_id`, `display_type`, `district_id`, `city_id`, `category_id`, `linked_article_id`, `linked_video_id`, `valid_from`, `valid_until`, `payment_status`, `payment_amount`, `payment_note`, `payment_ref`, `payment_confirmed_by`, `payment_confirmed_at`, `status`, `rejection_reason`, `approved_by`, `approved_at`, `impression_count`, `click_count`, `submitted_by`, `owner_user_id`, `is_free`, `notes`, `created_at`, `updated_at`, `owner_username`, `owner_password`, `owner_last_login`) VALUES
(1, 'SK PHOTOS', NULL, '9976930364', 'sk@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 'global', 3, NULL, NULL, NULL, NULL, '2026-06-10', '2026-07-10', 'confirmed', 5000.00, 'kaila vangiten', NULL, 5, '2026-06-10 19:33:02', 'active', NULL, 5, '2026-06-10 19:30:32', 2556, 9, 4, NULL, 0, 'sk phots', '2026-06-10 13:56:40', '2026-07-06 06:01:53', NULL, NULL, NULL),
(3, 'Balaji', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 'global', 3, NULL, NULL, NULL, NULL, '2026-06-10', '2026-07-11', 'confirmed', 5000.00, 'ggsdgd', NULL, 1, '2026-06-11 20:21:50', 'active', NULL, 1, '2026-06-11 20:21:44', 2528, 12, 1, NULL, 0, '', '2026-06-11 14:51:38', '2026-07-06 08:23:33', NULL, NULL, NULL),
(4, 'MICRO SECONDMONEY TRANSFER', NULL, '9095195194', 'mdu.msmt@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 'global', 3, NULL, NULL, NULL, NULL, '2026-06-19', '2026-06-26', 'confirmed', NULL, '', NULL, 5, '2026-06-19 14:21:46', 'active', NULL, 5, '2026-06-19 14:21:43', 3872, 19, 4, NULL, 0, 'MoneyTransfer , Currency Exchange', '2026-06-19 08:50:46', '2026-06-26 15:23:10', NULL, NULL, NULL),
(7, 'Care to Cure Clinic', '', '9090909090', '', '', '', '', '', '', '', 13, 1, 'global', NULL, NULL, NULL, NULL, NULL, '2026-06-23', '2026-12-23', 'confirmed', 3600.00, '', NULL, 1, '2026-06-23 14:20:49', 'active', NULL, 1, '2026-06-23 14:20:47', 1316, 35, 1, NULL, 0, 'physio clinic', '2026-06-23 08:42:25', '2026-07-14 06:46:18', NULL, NULL, NULL),
(8, 'PTR ENG COLLEGE', '', '', '', '', '', '', '', '', '', 9, 3, 'global', NULL, NULL, NULL, NULL, NULL, '2026-06-23', '2026-07-23', 'confirmed', NULL, '', NULL, 5, '2026-06-24 19:25:28', 'active', NULL, 5, '2026-06-23 19:48:59', 1654, 3, 5, NULL, 0, '', '2026-06-23 14:18:37', '2026-07-13 14:41:25', NULL, NULL, NULL),
(9, 'Remote World', '', '9942670342', '', '', '', '', '', '', 'Madurai remote world velusamy', 8, 2, 'global', NULL, NULL, NULL, NULL, NULL, '2026-07-08', '2026-10-08', 'confirmed', 5000.00, '', NULL, 5, '2026-06-26 20:00:22', 'active', NULL, 5, '2026-06-26 20:00:11', 91, 10, 4, NULL, 0, 'Madurai remote world velusamy', '2026-06-26 14:29:33', '2026-07-14 06:07:55', NULL, NULL, NULL),
(13, 'VTECH', '', '', '', '', '', '', '', '', '', 5, 2, 'global', NULL, NULL, NULL, NULL, NULL, '2026-06-26', '2026-12-26', 'confirmed', 5000.00, '', NULL, 5, '2026-06-26 20:14:56', 'active', NULL, 5, '2026-06-26 20:14:50', 62, 3, 5, NULL, 0, '', '2026-06-26 14:44:49', '2026-07-13 12:48:07', NULL, NULL, NULL),
(14, 'ThinaThulir', '', '9363958850', '', '', '', '', '', '', '', 9, 3, 'global', NULL, NULL, NULL, NULL, NULL, '2026-06-26', '2026-07-26', 'confirmed', NULL, '', NULL, 5, '2026-06-26 20:31:09', 'active', NULL, 5, '2026-06-26 20:31:07', 57, 7, 5, NULL, 0, '', '2026-06-26 15:01:07', '2026-07-14 06:07:41', NULL, NULL, NULL),
(15, 'newad', '', '', '', '', '', '', '', '', '', 9, 3, 'global', NULL, NULL, NULL, NULL, NULL, '2026-06-30', '2026-07-30', 'confirmed', 3600.00, '', NULL, 5, '2026-06-30 13:07:13', 'active', NULL, 5, '2026-06-30 13:07:09', 25, 1, 5, NULL, 0, '', '2026-06-30 07:35:03', '2026-07-13 13:09:57', NULL, NULL, NULL),
(16, 'Micro Second Money Transfer', 'Kannan', '9865558850', '', '', '', '', '', '', '', 3, 1, 'global', NULL, NULL, NULL, NULL, NULL, '2026-07-13', '2027-01-13', 'confirmed', 5000.00, 'gsgfdsg', '34534543', 5, '2026-07-13 13:59:29', 'active', NULL, NULL, NULL, 46, 24, 5, NULL, 0, '', '2026-07-13 08:29:19', '2026-07-14 06:07:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tn_categories`
--

CREATE TABLE `tn_categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `parent_id` smallint(5) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `name_tamil` varchar(100) DEFAULT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#C0001A',
  `icon` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_in_nav` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_categories`
--

INSERT INTO `tn_categories` (`id`, `parent_id`, `name`, `name_tamil`, `slug`, `description`, `color`, `icon`, `image`, `sort_order`, `is_active`, `show_in_nav`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Tamil Nadu', 'தமிழ்நாடு', 'tamil-nadu', NULL, '#C0001A', NULL, NULL, 1, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(2, NULL, 'India', 'இந்தியா', 'india', NULL, '#1877F2', NULL, NULL, 2, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(3, NULL, 'World', 'உலகம்', 'world', NULL, '#0891B2', NULL, NULL, 3, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(4, NULL, 'Cinema', 'சினிமா', 'cinema', NULL, '#7F77DD', NULL, NULL, 4, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(5, NULL, 'Sports', 'விளையாட்டு', 'sports', NULL, '#1B6B2E', NULL, NULL, 5, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(6, NULL, 'Technology', 'தொழில்நுட்பம்', 'technology', NULL, '#0369A1', NULL, NULL, 6, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(7, NULL, 'Spiritual', 'ஆன்மீகம்', 'spiritual', NULL, '#B45309', NULL, NULL, 7, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(8, NULL, 'Jobs & Education', 'வேலை & கல்வி', 'jobs-education', NULL, '#047857', NULL, NULL, 8, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(9, NULL, 'Business', 'வணிகம்', 'business', NULL, '#6B21A8', NULL, NULL, 9, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(10, NULL, 'Health', 'சுகாதாரம்', 'health', NULL, '#BE185D', NULL, NULL, 10, 1, 1, '2026-06-10 13:05:48', '2026-06-10 13:05:48'),
(11, NULL, 'Video', 'வீடியோ', 'video', NULL, '#DC2626', NULL, NULL, 11, 1, 0, '2026-06-10 13:05:48', '2026-06-10 13:05:48');

-- --------------------------------------------------------

--
-- Table structure for table `tn_cities`
--

CREATE TABLE `tn_cities` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `district_id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_ta` varchar(100) DEFAULT NULL,
  `slug` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_citizen_reports`
--

CREATE TABLE `tn_citizen_reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `district_id` int(10) UNSIGNED DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','published') NOT NULL DEFAULT 'pending',
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `rejection_reason` varchar(300) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_contributors`
--

CREATE TABLE `tn_contributors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `district_id` int(10) UNSIGNED DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `article_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_contributor_notifications`
--

CREATE TABLE `tn_contributor_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `contributor_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_cron_logs`
--

CREATE TABLE `tn_cron_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `job` varchar(100) NOT NULL,
  `status` enum('success','failed') NOT NULL DEFAULT 'success',
  `message` text DEFAULT NULL,
  `ran_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_csrf_tokens`
--

CREATE TABLE `tn_csrf_tokens` (
  `token` varchar(64) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_districts`
--

CREATE TABLE `tn_districts` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `state_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(100) NOT NULL,
  `name_ta` varchar(100) DEFAULT NULL,
  `slug` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_districts`
--

INSERT INTO `tn_districts` (`id`, `state_id`, `name`, `name_ta`, `slug`) VALUES
(1, 1, 'Chennai', 'சென்னை', 'chennai'),
(2, 1, 'Coimbatore', 'கோயம்புத்தூர்', 'coimbatore'),
(3, 1, 'Madurai', 'மதுரை', 'madurai'),
(4, 1, 'Tiruchirappalli', 'திருச்சிராப்பள்ளி', 'tiruchirappalli'),
(5, 1, 'Salem', 'சேலம்', 'salem'),
(6, 1, 'Tirunelveli', 'திருநெல்வேலி', 'tirunelveli'),
(7, 1, 'Vellore', 'வேலூர்', 'vellore'),
(8, 1, 'Erode', 'ஈரோடு', 'erode'),
(9, 1, 'Thoothukudi', 'தூத்துக்குடி', 'thoothukudi'),
(10, 1, 'Thanjavur', 'தஞ்சாவூர்', 'thanjavur'),
(11, 1, 'Dindigul', 'டிண்டுக்கல்', 'dindigul'),
(12, 1, 'Villupuram', 'விழுப்புரம்', 'villupuram'),
(13, 1, 'Cuddalore', 'கடலூர்', 'cuddalore'),
(14, 1, 'Nagapattinam', 'நாகப்பட்டினம்', 'nagapattinam'),
(15, 1, 'Kanyakumari', 'கன்னியாகுமரி', 'kanyakumari'),
(16, 1, 'Tiruppur', 'திருப்பூர்', 'tiruppur'),
(17, 1, 'The Nilgiris', 'நீலகிரி', 'nilgiris'),
(18, 1, 'Krishnagiri', 'கிருஷ்ணகிரி', 'krishnagiri'),
(19, 1, 'Dharmapuri', 'தர்மபுரி', 'dharmapuri'),
(20, 1, 'Namakkal', 'நாமக்கல்', 'namakkal'),
(21, 1, 'Karur', 'கரூர்', 'karur'),
(22, 1, 'Tiruvarur', 'திருவாரூர்', 'tiruvarur'),
(23, 1, 'Sivaganga', 'சிவகங்கை', 'sivaganga'),
(24, 1, 'Virudhunagar', 'விருதுநகர்', 'virudhunagar'),
(25, 1, 'Ramanathapuram', 'ராமநாதபுரம்', 'ramanathapuram'),
(26, 1, 'Tenkasi', 'தென்காசி', 'tenkasi'),
(27, 1, 'Tirupathur', 'திருப்பத்தூர்', 'tirupathur'),
(28, 1, 'Ranipet', 'ராணிப்பேட்டை', 'ranipet'),
(29, 1, 'Chengalpattu', 'செங்கல்பட்டு', 'chengalpattu'),
(30, 1, 'Kallakurichi', 'கள்ளக்குறிச்சி', 'kallakurichi'),
(31, 1, 'Mayiladuthurai', 'மயிலாடுதுறை', 'mayiladuthurai'),
(32, 1, 'Perambalur', 'பெரம்பலூர்', 'perambalur'),
(33, 1, 'Tiruvannamalai', 'திருவண்ணாமலை', 'tiruvannamalai'),
(34, 1, 'Pudukkottai', 'புதுக்கோட்டை', 'pudukkottai'),
(35, 1, 'Ariyalur', 'அரியலூர்', 'ariyalur'),
(36, 1, 'Kancheepuram', 'காஞ்சிபுரம்', 'kancheepuram'),
(37, 1, 'Tiruvallur', 'திருவள்ளூர்', 'tiruvallur'),
(38, 2, 'Puducherry', 'புதுச்சேரி', 'puducherry');

-- --------------------------------------------------------

--
-- Table structure for table `tn_editor_permissions`
--

CREATE TABLE `tn_editor_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `perm_type` enum('district','category') NOT NULL DEFAULT 'district',
  `district_id` smallint(5) UNSIGNED DEFAULT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_fcm_topics`
--

CREATE TABLE `tn_fcm_topics` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_fcm_topics`
--

INSERT INTO `tn_fcm_topics` (`id`, `name`, `slug`) VALUES
(1, 'All News', 'all-news'),
(2, 'Breaking News', 'breaking'),
(3, 'Tamil Nadu', 'tamil-nadu'),
(4, 'India', 'india'),
(5, 'Cinema', 'cinema'),
(6, 'Sports', 'sports');

-- --------------------------------------------------------

--
-- Table structure for table `tn_live_blogs`
--

CREATE TABLE `tn_live_blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(320) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('general','election','cricket','football','sports','disaster','budget') NOT NULL DEFAULT 'general',
  `team_home` varchar(100) DEFAULT NULL,
  `team_away` varchar(100) DEFAULT NULL,
  `score_home` varchar(50) DEFAULT NULL,
  `score_away` varchar(50) DEFAULT NULL,
  `status` enum('active','ended') NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `entry_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_live_entries`
--

CREATE TABLE `tn_live_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `blog_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `entry_type` enum('update','goal','wicket','highlight','alert','photo') NOT NULL DEFAULT 'update',
  `image_url` varchar(500) DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_breaking` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_media`
--

CREATE TABLE `tn_media` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `thumb_path` varchar(500) DEFAULT NULL,
  `mime_type` varchar(100) NOT NULL,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `width` smallint(5) UNSIGNED DEFAULT NULL,
  `height` smallint(5) UNSIGNED DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` varchar(300) DEFAULT NULL,
  `photo_credit` varchar(150) DEFAULT NULL,
  `folder` varchar(100) NOT NULL DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_media`
--

INSERT INTO `tn_media` (`id`, `user_id`, `filename`, `filepath`, `thumb_path`, `mime_type`, `size`, `width`, `height`, `alt_text`, `caption`, `photo_credit`, `folder`, `created_at`) VALUES
(5, 5, 'kholi.webp', '/uploads/2026/06/media_6a315f2e8a0ae0.67488960.webp', '/uploads/2026/06/thumb_media_6a315f2e8a0ae0.67488960.webp', 'image/webp', 15762, 500, 500, NULL, NULL, NULL, '2026/06', '2026-06-16 14:35:26'),
(6, 4, 'images.webp', '/uploads/2026/06/media_6a33fd2553b407.34964336.webp', '/uploads/2026/06/thumb_media_6a33fd2553b407.34964336.webp', 'image/webp', 19308, 416, 413, NULL, NULL, NULL, '2026/06', '2026-06-18 14:13:57'),
(8, 5, 'cmrl-5.jpg', '/uploads/2026/06/media_6a363ca7f1b9e7.92068323.webp', '/uploads/2026/06/thumb_media_6a363ca7f1b9e7.92068323.webp', 'image/webp', 81294, 856, 462, NULL, NULL, NULL, '2026/06', '2026-06-20 07:09:28'),
(10, 5, 'vijay-2026-06-23-08-22-27.webp', '/uploads/2026/06/media_6a3a2958794838.85250353.webp', '/uploads/2026/06/thumb_media_6a3a2958794838.85250353.webp', 'image/webp', 63074, 1200, 666, NULL, NULL, NULL, '2026/06', '2026-06-23 06:36:08'),
(12, 5, 'bzqr.png', '/uploads/2026/06/media_6a423a5dcc4435.89964829.png', '/uploads/2026/06/thumb_media_6a423a5dcc4435.89964829.png', 'image/png', 10332, 300, 300, NULL, NULL, NULL, '2026/06', '2026-06-29 09:26:53'),
(13, 5, 'anbil.png', '/uploads/2026/06/media_6a437274f1af45.37593392.png', '/uploads/2026/06/thumb_media_6a437274f1af45.37593392.png', 'image/png', 1882375, 1054, 1492, NULL, NULL, NULL, '2026/06', '2026-06-30 07:38:28'),
(14, 5, 'anbil.png', '/uploads/2026/06/media_6a43743f1ec607.87950965.png', '/uploads/2026/06/thumb_media_6a43743f1ec607.87950965.png', 'image/png', 1882375, 1054, 1492, NULL, NULL, NULL, '2026/06', '2026-06-30 07:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `tn_newspapers`
--

CREATE TABLE `tn_newspapers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `edition_date` date NOT NULL,
  `pdf_path` varchar(500) DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `download_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_notifications`
--

CREATE TABLE `tn_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `from_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(60) NOT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `message` varchar(300) NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_notifications`
--

INSERT INTO `tn_notifications` (`id`, `user_id`, `from_id`, `type`, `article_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(26, 1, 4, 'edit_submitted', 170, 'Thina Thulir  Reporter submitted an edit for: \"பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு\"', NULL, 0, '2026-06-24 06:58:19'),
(27, 5, 4, 'edit_submitted', 170, 'Thina Thulir  Reporter submitted an edit for: \"பூந்தமல்லி – வடபழனி மெட்ரோ சேவை தொடக்கம் எப்போது? பயணிகளின் எதிர்பார்ப்பு அதிகரிப்பு\"', NULL, 1, '2026-06-24 06:58:19'),
(43, 1, 4, 'edit_submitted', 172, 'Thina Thulir  Reporter submitted an edit for: \"போதைப்பொருள் எதிர்ப்பு தினம்: சென்னையில் விழிப்புணர்வு மாரத்தானில் முதல்வர் விஜய் பங்கேற்பு\"', NULL, 0, '2026-06-29 17:19:11'),
(44, 5, 4, 'edit_submitted', 172, 'Thina Thulir  Reporter submitted an edit for: \"போதைப்பொருள் எதிர்ப்பு தினம்: சென்னையில் விழிப்புணர்வு மாரத்தானில் முதல்வர் விஜய் பங்கேற்பு\"', NULL, 1, '2026-06-29 17:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `tn_permissions`
--

CREATE TABLE `tn_permissions` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `slug` varchar(80) NOT NULL,
  `label` varchar(150) NOT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_permissions`
--

INSERT INTO `tn_permissions` (`id`, `slug`, `label`, `group`) VALUES
(1, 'create_article', 'Write & submit articles', 'articles'),
(2, 'edit_own_article', 'Edit own articles', 'articles'),
(3, 'edit_any_article', 'Edit any article', 'articles'),
(4, 'delete_article', 'Delete articles', 'articles'),
(5, 'approve_article', 'Approve district/category level', 'articles'),
(6, 'publish_article', 'Final publish articles', 'articles'),
(7, 'approve_escalated', 'Approve escalated (chief editor)', 'articles'),
(8, 'feature_article', 'Mark article as featured', 'articles'),
(9, 'breaking_article', 'Mark article as breaking', 'articles'),
(10, 'create_ad', 'Create advertisements', 'ads'),
(11, 'manage_own_ads', 'Manage own ads', 'ads'),
(12, 'manage_all_ads', 'Manage all ads', 'ads'),
(13, 'approve_ad', 'Approve/reject ads', 'ads'),
(14, 'confirm_ad_payment', 'Confirm ad payments', 'ads'),
(15, 'manage_users', 'Create/edit/delete users', 'users'),
(16, 'manage_roles', 'Assign/change roles', 'users'),
(17, 'promote_user', 'Promote reporter roles', 'users'),
(18, 'manage_categories', 'Manage categories', 'content'),
(19, 'manage_tags', 'Manage tags', 'content'),
(20, 'manage_media', 'Manage media library', 'content'),
(21, 'manage_widgets', 'Manage sidebar widgets', 'content'),
(22, 'manage_districts', 'Manage districts/cities', 'content'),
(23, 'manage_settings', 'Manage site settings', 'settings'),
(24, 'manage_packages', 'Manage ad packages', 'settings'),
(25, 'manage_seo', 'Manage SEO config', 'settings'),
(26, 'view_analytics', 'View analytics', 'settings'),
(27, 'manage_push', 'Send push notifications', 'settings'),
(28, 'auto_approve', 'Auto-approve own articles', 'articles'),
(29, 'set_auto_approve', 'Grant auto-approve to reporters', 'articles'),
(30, 'manage_district_news', 'Manage news in assigned district', 'district');

-- --------------------------------------------------------

--
-- Table structure for table `tn_photo_news`
--

CREATE TABLE `tn_photo_news` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `slug` varchar(500) NOT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tn_photo_news`
--

INSERT INTO `tn_photo_news` (`id`, `article_id`, `title`, `slug`, `image_path`, `status`, `approval_status`, `created_by`, `created_at`, `updated_at`) VALUES
(4, NULL, 'Anbil Mahes', 'anbil-mahes', '/uploads/photo-news/pn_4_1782757715.png', 'published', 'approved', 5, '2026-06-29 23:58:35', '2026-06-30 13:03:43'),
(5, NULL, 'அன்பில் மகேஷ்', 'அன்பில்-மகேஷ்', '/uploads/photo-news/pn_5_1782805063.png', 'published', 'approved', 5, '2026-06-30 13:06:14', '2026-06-30 13:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `tn_photo_news_tags`
--

CREATE TABLE `tn_photo_news_tags` (
  `photo_news_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_polls`
--

CREATE TABLE `tn_polls` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `question` varchar(300) NOT NULL,
  `question_ta` varchar(300) DEFAULT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `total_votes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_poll_options`
--

CREATE TABLE `tn_poll_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `poll_id` int(10) UNSIGNED NOT NULL,
  `option_text` varchar(200) NOT NULL,
  `option_text_ta` varchar(200) DEFAULT NULL,
  `vote_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_poll_votes`
--

CREATE TABLE `tn_poll_votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `poll_id` int(10) UNSIGNED NOT NULL,
  `option_id` int(10) UNSIGNED NOT NULL,
  `reader_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_hash` varchar(64) DEFAULT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_premium_access`
--

CREATE TABLE `tn_premium_access` (
  `id` int(10) UNSIGNED NOT NULL,
  `reader_id` int(10) UNSIGNED NOT NULL,
  `plan_id` tinyint(3) UNSIGNED NOT NULL,
  `paid_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `payment_ref` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_premium_plans`
--

CREATE TABLE `tn_premium_plans` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_tamil` varchar(100) DEFAULT NULL,
  `price_inr` decimal(8,2) NOT NULL DEFAULT 0.00,
  `duration_days` smallint(5) UNSIGNED NOT NULL DEFAULT 30,
  `features` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_premium_plans`
--

INSERT INTO `tn_premium_plans` (`id`, `name`, `name_tamil`, `price_inr`, `duration_days`, `features`, `is_active`, `created_at`) VALUES
(1, 'Monthly', 'மாதாந்திர', 99.00, 30, NULL, 1, '2026-06-10 13:05:49'),
(2, 'Yearly', 'ஆண்டு', 799.00, 365, NULL, 1, '2026-06-10 13:05:49');

-- --------------------------------------------------------

--
-- Table structure for table `tn_print_editions`
--

CREATE TABLE `tn_print_editions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `edition_date` date NOT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `pdf_path` varchar(500) DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `pages` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `download_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_print_edition_articles`
--

CREATE TABLE `tn_print_edition_articles` (
  `edition_id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `page_no` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_push_logs`
--

CREATE TABLE `tn_push_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('article','ad','manual') NOT NULL,
  `ref_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `click_url` varchar(500) DEFAULT NULL,
  `districts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'null = all, array of district_ids' CHECK (json_valid(`districts`)),
  `sent_count` int(11) DEFAULT 0,
  `fail_count` int(11) DEFAULT 0,
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  `sent_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tn_push_logs`
--

INSERT INTO `tn_push_logs` (`id`, `type`, `ref_id`, `title`, `body`, `image_url`, `click_url`, `districts`, `sent_count`, `fail_count`, `status`, `sent_by`, `created_at`) VALUES
(1, 'article', 169, 'fasdfasdf', 'asfasdf aasdf df as fasdf asd', '/tamilnews/public/uploads/2026/06/media_6a3e3aa3573e46.86416118.webp', 'http://localhost/thinathulir/public/article/fasdfasdf', NULL, 0, 0, 'failed', NULL, '2026-06-29 14:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `tn_push_notifications`
--

CREATE TABLE `tn_push_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `topic_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `click_url` varchar(500) DEFAULT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_push_subscribers`
--

CREATE TABLE `tn_push_subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `fcm_token` text NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `district_id` int(10) UNSIGNED DEFAULT NULL,
  `platform` enum('web','android','ios') DEFAULT 'web',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_rates`
--

CREATE TABLE `tn_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('gold','silver','petrol','diesel','currency_usd','currency_gbp','currency_eur') NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `value` decimal(12,2) NOT NULL,
  `unit` varchar(20) NOT NULL DEFAULT 'gram',
  `change_val` decimal(8,2) DEFAULT NULL,
  `change_pct` decimal(5,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_rates`
--

INSERT INTO `tn_rates` (`id`, `type`, `city`, `value`, `unit`, `change_val`, `change_pct`, `updated_at`) VALUES
(1, 'gold', 'Madurai', 13000.00, 'gram', NULL, NULL, '2026-06-24 12:03:36'),
(2, 'silver', NULL, 240.00, 'gram', NULL, NULL, '2026-06-24 12:03:46'),
(3, 'petrol', NULL, 108.36, 'gram', NULL, NULL, '2026-06-24 12:03:55'),
(4, 'diesel', NULL, 98.00, 'gram', NULL, NULL, '2026-06-24 12:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `tn_readers`
--

CREATE TABLE `tn_readers` (
  `id` int(10) UNSIGNED NOT NULL,
  `google_id` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `district_id` int(10) UNSIGNED DEFAULT NULL,
  `has_agreed_terms` tinyint(1) DEFAULT 0,
  `agreed_at` datetime DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_readers`
--

INSERT INTO `tn_readers` (`id`, `google_id`, `name`, `email`, `avatar`, `district_id`, `has_agreed_terms`, `agreed_at`, `is_blocked`, `last_login`, `created_at`, `updated_at`) VALUES
(1, '106673871740418440977', 'Nyx Burgh', 'nyxburgh@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocIercQN4bOhHUtO7l-_ivulYck-Pmu8SQKNCRSUq7JXxMih=s96-c', 3, 1, '2026-06-27 20:17:46', 0, '2026-06-27 20:17:07', '2026-06-27 14:17:08', '2026-06-27 14:47:46');

-- --------------------------------------------------------

--
-- Table structure for table `tn_reporter_assignments`
--

CREATE TABLE `tn_reporter_assignments` (
  `id` int(10) UNSIGNED NOT NULL,
  `chief_editor_id` int(10) UNSIGNED NOT NULL,
  `reporter_id` int(10) UNSIGNED NOT NULL,
  `district_editor_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_reporter_performance`
--

CREATE TABLE `tn_reporter_performance` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `month` date NOT NULL COMMENT 'YYYY-MM-01',
  `articles_submitted` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `articles_published` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `articles_rejected` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `total_views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_shares` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_rating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_roles`
--

CREATE TABLE `tn_roles` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_roles`
--

INSERT INTO `tn_roles` (`id`, `name`, `slug`, `sort_order`, `created_at`) VALUES
(1, 'Admin', 'admin', 1, '2026-06-10 13:05:48'),
(2, 'Chief Editor', 'chief_editor', 2, '2026-06-10 13:05:48'),
(3, 'Editor', 'editor', 3, '2026-06-10 13:05:48'),
(4, 'District Editor', 'district_editor', 4, '2026-06-10 13:05:48'),
(5, 'Category Editor', 'category_editor', 5, '2026-06-10 13:05:48'),
(6, 'Senior Reporter', 'senior_reporter', 6, '2026-06-10 13:05:48'),
(7, 'Reporter', 'reporter', 7, '2026-06-10 13:05:48'),
(8, 'Ads Manager', 'ads_manager', 8, '2026-06-10 13:05:48'),
(9, 'Ad Owner', 'ad_owner', 0, '2026-06-23 06:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `tn_role_permissions`
--

CREATE TABLE `tn_role_permissions` (
  `role_id` tinyint(3) UNSIGNED NOT NULL,
  `permission_id` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_role_permissions`
--

INSERT INTO `tn_role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(2, 1),
(2, 3),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 12),
(2, 13),
(2, 14),
(2, 19),
(2, 20),
(2, 21),
(2, 26),
(2, 29),
(3, 1),
(3, 2),
(3, 3),
(3, 5),
(3, 6),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 19),
(3, 20),
(3, 26),
(4, 1),
(4, 2),
(4, 5),
(4, 10),
(4, 11),
(4, 20),
(4, 30),
(5, 1),
(5, 2),
(5, 5),
(5, 10),
(5, 11),
(5, 20),
(6, 1),
(6, 2),
(6, 10),
(6, 11),
(6, 20),
(6, 28),
(7, 1),
(7, 2),
(7, 10),
(7, 11),
(7, 20),
(8, 10),
(8, 11),
(8, 20);

-- --------------------------------------------------------

--
-- Table structure for table `tn_rss_feeds`
--

CREATE TABLE `tn_rss_feeds` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `url` varchar(500) NOT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_sync` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_rss_imports`
--

CREATE TABLE `tn_rss_imports` (
  `id` int(10) UNSIGNED NOT NULL,
  `feed_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(300) NOT NULL,
  `url` varchar(500) NOT NULL,
  `content` text DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('pending','imported','rejected') NOT NULL DEFAULT 'pending',
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_seo_config`
--

CREATE TABLE `tn_seo_config` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `page_type` varchar(50) NOT NULL,
  `meta_title` varchar(300) DEFAULT NULL,
  `meta_desc` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_sessions`
--

CREATE TABLE `tn_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_settings`
--

CREATE TABLE `tn_settings` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `label` varchar(200) DEFAULT NULL,
  `input_type` varchar(30) NOT NULL DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_settings`
--

INSERT INTO `tn_settings` (`id`, `group`, `key`, `value`, `label`, `input_type`, `updated_at`) VALUES
(1, 'general', 'site_name', 'தினத்துளிர்', 'Site Name', 'text', '2026-06-10 13:05:49'),
(2, 'general', 'site_name_en', 'Thina Thulir', 'Site Name (English)', 'text', '2026-06-10 13:05:49'),
(3, 'general', 'site_url', 'https://thinathulir.com', 'Site URL', 'url', '2026-06-10 13:05:49'),
(4, 'general', 'site_tagline', 'அரசியல் பழகு · அறம் செய்', 'Tagline', 'text', '2026-06-10 13:05:49'),
(5, 'general', 'site_email', 'info@thinathulir.com', 'Contact Email', 'email', '2026-06-10 13:05:49'),
(6, 'general', 'site_phone', '', 'Contact Phone', 'text', '2026-06-10 13:05:49'),
(7, 'general', 'reg_no', 'TN/2024/12345', 'Registration No', 'text', '2026-06-10 13:05:49'),
(8, 'general', 'google_analytics', '', 'GA Tracking ID', 'text', '2026-06-10 13:05:49'),
(9, 'social', 'facebook_url', '', 'Facebook Page', 'url', '2026-06-10 13:05:49'),
(10, 'social', 'twitter_url', '', 'Twitter/X Handle', 'url', '2026-06-10 13:05:49'),
(11, 'social', 'whatsapp_channel', '', 'WhatsApp Channel', 'url', '2026-06-10 13:05:49'),
(12, 'social', 'youtube_url', '', 'YouTube Channel', 'url', '2026-06-10 13:05:49'),
(13, 'social', 'instagram_url', '', 'Instagram', 'url', '2026-06-10 13:05:49'),
(14, 'seo', 'default_og_image', '', 'Default OG Image', 'text', '2026-06-10 13:05:49'),
(15, 'seo', 'google_news_pub', '', 'Google News Publisher', 'text', '2026-06-10 13:05:49'),
(16, 'ads', 'adsense_id', '', 'AdSense Client ID', 'text', '2026-06-10 13:05:49'),
(17, 'features', 'auto_approve_default', '0', 'Auto-Approve Default', 'checkbox', '2026-06-10 13:05:49'),
(18, 'features', 'breaking_count', '5', 'Breaking News Count', 'number', '2026-06-10 13:05:49'),
(19, 'features', 'trending_days', '7', 'Trending Period (days)', 'number', '2026-06-10 13:05:49'),
(20, 'features', 'articles_per_page', '12', 'Articles Per Page', 'number', '2026-06-10 13:05:49'),
(21, 'features', 'fcm_server_key', '', 'FCM Server Key', 'text', '2026-06-10 13:05:49'),
(22, 'features', 'razorpay_key', '', 'Razorpay Key', 'text', '2026-06-10 13:05:49'),
(23, 'features', 'razorpay_secret', '', 'Razorpay Secret', 'password', '2026-06-10 13:05:49');

-- --------------------------------------------------------

--
-- Table structure for table `tn_short_urls`
--

CREATE TABLE `tn_short_urls` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(8) NOT NULL,
  `target_url` varchar(500) NOT NULL,
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `clicks` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tn_short_urls`
--

INSERT INTO `tn_short_urls` (`id`, `code`, `target_url`, `article_id`, `clicks`, `created_at`) VALUES
(1, '1vjkvy', 'http://localhost/tamilnews/public/article/பூந்தமல்லி-வடபழனி-மெட்ரோ-சேவை-தொடக்கம்-எப்போது-பயணிகளின்-எதிர்பார்ப்பு-அதிகரிப்பு', 170, 0, '2026-06-23 14:12:59'),
(2, '4eiqhr', 'http://localhost/tamilnews/public/article/ipl-2026-csk-five-time-champions-final', 131, 0, '2026-06-24 11:42:08'),
(3, 'kyflod', 'http://localhost/tamilnews/public/article/rcb', 167, 0, '2026-06-24 13:12:09'),
(4, 'vz1mo9', 'http://localhost/tamilnews/public/article/vijay-kottai-teaser-100-million-views-record', 126, 0, '2026-06-26 06:04:28'),
(5, 'lb4q0p', 'http://localhost/tamilnews/public/article/எதிர்க்கட்சிகளின்-குற்றச்சாட்டுகளுக்கு-சட்டப்பேரவையில்-பதிலளிக்கும்-விஜய்', 171, 0, '2026-06-26 06:06:19'),
(6, 'x8maef', 'http://localhost/tamilnews/public/article/neet-2026-sivakasi-student-perfect-score-aiims', 118, 0, '2026-06-26 06:07:04'),
(7, 'd2vdl6', 'http://localhost/tamilnews/public/article/போதைப்பொருள்-எதிர்ப்பு-தினம்-சென்னையில்-விழிப்புணர்வு-மாரத்தானில்-முதல்வர்-விஜய்-பங்கேற்பு', 172, 0, '2026-06-26 08:39:37'),
(8, '1fi0go', 'http://localhost/tamilnews/public/article/chatgpt-5-tamil-native-support-announcement', 136, 0, '2026-06-26 08:54:43'),
(9, 'uoyp8o', 'http://localhost/tamilnews/public/article/fasdfasdf', 169, 0, '2026-06-26 15:11:55'),
(10, '10kx6v', 'http://localhost/tamilnews/public/article/pm-modi-g7-summit-india-strong-position-june-2026', 116, 0, '2026-06-26 15:23:13'),
(11, 'a4fzm9', 'http://localhost/thinathulir/public/article/rbi-repo-rate-cut-25-basis-points-june-2026', 117, 0, '2026-06-28 08:56:19'),
(12, 'e44mxc', 'http://localhost/thinathulir/public/article/apaar', 173, 1, '2026-06-29 18:05:10'),
(13, '1cqumq', 'http://localhost/thinathulir/public/article/tnpsc-group-2-2500-vacancies-application-july-1', 147, 1, '2026-06-29 18:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `tn_sitemap_log`
--

CREATE TABLE `tn_sitemap_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `sitemap_type` varchar(50) NOT NULL,
  `url_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_site_counter`
--

CREATE TABLE `tn_site_counter` (
  `id` int(10) UNSIGNED NOT NULL,
  `total_views` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tn_site_counter`
--

INSERT INTO `tn_site_counter` (`id`, `total_views`) VALUES
(1, 1778);

-- --------------------------------------------------------

--
-- Table structure for table `tn_special_categories`
--

CREATE TABLE `tn_special_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_tamil` varchar(150) DEFAULT NULL,
  `slug` varchar(160) NOT NULL,
  `type` enum('election','festival','event','disaster','sports','budget') NOT NULL DEFAULT 'event',
  `description` text DEFAULT NULL,
  `banner_color` varchar(20) NOT NULL DEFAULT '#C0001A',
  `banner_icon` varchar(10) DEFAULT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `starts_at` date DEFAULT NULL,
  `ends_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_special_categories`
--

INSERT INTO `tn_special_categories` (`id`, `name`, `name_tamil`, `slug`, `type`, `description`, `banner_color`, `banner_icon`, `category_id`, `is_active`, `starts_at`, `ends_at`, `created_at`) VALUES
(1, 'New Raasi', NULL, 'new-raasi', 'election', NULL, '#a81a2d', NULL, NULL, 1, NULL, NULL, '2026-06-11 10:13:49');

-- --------------------------------------------------------

--
-- Table structure for table `tn_special_category_articles`
--

CREATE TABLE `tn_special_category_articles` (
  `special_category_id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `special_id` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_sponsored_news`
--

CREATE TABLE `tn_sponsored_news` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscription_id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `scheduled_date` date DEFAULT NULL,
  `status` enum('draft','pending_approval','approved','published','rejected') NOT NULL DEFAULT 'draft',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_states`
--

CREATE TABLE `tn_states` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_states`
--

INSERT INTO `tn_states` (`id`, `name`) VALUES
(1, 'Tamil Nadu'),
(2, 'Puducherry');

-- --------------------------------------------------------

--
-- Table structure for table `tn_tags`
--

CREATE TABLE `tn_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_tamil` varchar(100) DEFAULT NULL,
  `slug` varchar(120) NOT NULL,
  `usage_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_tags`
--

INSERT INTO `tn_tags` (`id`, `name`, `name_tamil`, `slug`, `usage_count`, `created_at`) VALUES
(1, 'chennai', NULL, 'chennai', 1, '2026-06-20 07:01:24'),
(2, 'Metro', NULL, 'metro', 1, '2026-06-20 07:01:28'),
(3, 'chennai Metro', NULL, 'chennai-metro', 1, '2026-06-20 07:01:42'),
(4, 'anbil', NULL, 'anbil', 1, '2026-06-29 18:03:33'),
(5, 'tvk', NULL, 'tvk', 1, '2026-06-29 18:03:37'),
(6, 'dmk', NULL, 'dmk', 1, '2026-06-29 18:03:41');

-- --------------------------------------------------------

--
-- Table structure for table `tn_users`
--

CREATE TABLE `tn_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 7,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `assigned_district_id` smallint(5) UNSIGNED DEFAULT NULL,
  `assigned_category_ids` varchar(200) DEFAULT NULL,
  `auto_approve` tinyint(1) NOT NULL DEFAULT 0,
  `article_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_users`
--

INSERT INTO `tn_users` (`id`, `role_id`, `name`, `email`, `password`, `avatar`, `phone`, `bio`, `is_active`, `is_blocked`, `assigned_district_id`, `assigned_category_ids`, `auto_approve`, `article_count`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'admin@thinathulir.com', '$2b$12$fvqctASRIq11Jbk8hJbFGuv8GURSvMlADDo6zGRPW9zNdeCshGJb.', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 55, '2026-06-24 17:30:46', '2026-06-10 13:05:48', '2026-06-24 12:00:46'),
(2, 3, 'Kavitha Editor', 'editor@thinathulir.com', '$2b$12$PbQ.m9SWpJRdcALrSm0FguSe/PuZ2on9FgA5B1k/F9rrysyCqlLNO', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, '2026-06-10 19:27:37', '2026-06-10 13:43:51', '2026-06-10 13:57:37'),
(3, 4, 'Rajan District', 'district@thinathulir.com', '$2b$12$72UxYjxBRkahX/Zf00sUx.YBQJ82aFkSJbamHaXjE74EM/Lg/igfu', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, NULL, '2026-06-10 13:43:51', '2026-06-10 13:43:51'),
(4, 7, 'Thina Thulir  Reporter', 'reporter@thinathulir.com', '$2b$12$fc699cxupUSs/2cX7LhZt.NiTRIKOylQVrUop/9I5P5aZQ65IgE4K', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, '2026-06-30 22:21:43', '2026-06-10 13:43:51', '2026-06-30 16:51:43'),
(5, 2, 'Senthil Chief Editor', 'chiefeditor@thinathulir.com', '$2b$12$x2uk.YFIvKUid2ydt7L54OUKLteM6xxFmNYo5skBoV55V/7vA6zoy', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, '2026-07-14 11:38:28', '2026-06-10 13:59:49', '2026-07-14 06:08:28'),
(6, 9, 'Care to Cure Clinic', 'c2c@thinathulir.com', '$2y$10$fSwDxaMOlvjmtK7moctqW.tWSy.z78rwbzRng96jc6ks.93vIQWNa', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, '2026-06-23 14:43:00', '2026-06-23 08:56:25', '2026-06-23 09:13:00'),
(7, 9, 'remote world', 'rw@thinathulir.com', '$2y$10$XKRmjn9dHybZ45/9ch7LVuB6gXxQUCg9XF8AoPgxjm2qvLAVNrFJy', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, NULL, '2026-06-24 13:56:42', '2026-06-24 13:56:42'),
(8, 9, 'remote world', 'remote@thinathulir.com', '$2y$10$4YF9zoy1cbaURy3NCa/4V.XLeJqdLjlvonVm0p8SgRxghqurK8VLm', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, '2026-06-24 20:28:02', '2026-06-24 14:52:13', '2026-06-24 14:58:02'),
(9, 9, 'Remote world', 'Rem@thinathulir.com', '$2y$10$.xm2O1LNqV8yku65bYHvFuQpBCgMfpNfsS8mtrZAN4/.zU1lvqhGK', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, 0, '2026-06-25 11:57:16', '2026-06-25 06:26:52', '2026-06-25 06:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `tn_user_badges`
--

CREATE TABLE `tn_user_badges` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `name_tamil` varchar(80) DEFAULT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#C0001A',
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_user_badge_assignments`
--

CREATE TABLE `tn_user_badge_assignments` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `badge_id` tinyint(3) UNSIGNED NOT NULL,
  `awarded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_widgets`
--

CREATE TABLE `tn_widgets` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('ad_square','ad_horizontal','ad_vertical','trending_news','breaking_news','category_news','district_news','rate_gold','rate_silver','rate_petrol','rate_diesel','rate_currency','cricket_score','sports_widget','youtube_feed','facebook_feed','poll','custom_html','banner') NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `title_tamil` varchar(150) DEFAULT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'widget-specific settings' CHECK (json_valid(`config`)),
  `position` enum('sidebar','before_footer','inline') NOT NULL DEFAULT 'sidebar',
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `show_desktop` tinyint(1) NOT NULL DEFAULT 1,
  `show_mobile` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tn_widgets`
--

INSERT INTO `tn_widgets` (`id`, `name`, `type`, `title`, `title_tamil`, `config`, `position`, `sort_order`, `show_desktop`, `show_mobile`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Square Ad Top', 'ad_square', 'Advertisement', 'விளம்பரம்', NULL, 'sidebar', 3, 1, 0, 1, '2026-06-10 13:05:49', '2026-06-11 14:42:08'),
(2, 'Trending News', 'trending_news', 'Trending', 'பிரபலமான', NULL, 'sidebar', 1, 1, 0, 1, '2026-06-10 13:05:49', '2026-06-11 14:41:50'),
(3, 'Breaking News', 'breaking_news', 'Breaking', 'உடனடி', NULL, 'sidebar', 4, 1, 0, 1, '2026-06-10 13:05:49', '2026-06-11 14:41:50'),
(4, 'Gold Rate', 'rate_gold', 'Gold Rate', 'தங்க விலை', NULL, 'sidebar', 2, 1, 0, 1, '2026-06-10 13:05:49', '2026-06-11 14:41:50'),
(5, 'Petrol Rate', 'rate_petrol', 'Petrol', 'பெட்ரோல்', NULL, 'sidebar', 5, 1, 0, 1, '2026-06-10 13:05:49', '2026-06-10 13:05:49');

-- --------------------------------------------------------

--
-- Table structure for table `tn_youtube_channels`
--

CREATE TABLE `tn_youtube_channels` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `channel_id` varchar(50) NOT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_sync` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tn_youtube_imports`
--

CREATE TABLE `tn_youtube_imports` (
  `id` int(10) UNSIGNED NOT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `video_id` varchar(20) NOT NULL,
  `title` varchar(300) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('pending','imported','rejected') NOT NULL DEFAULT 'pending',
  `article_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tn_activity_log`
--
ALTER TABLE `tn_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_user` (`user_id`),
  ADD KEY `idx_log_entity` (`entity`,`entity_id`),
  ADD KEY `idx_log_time` (`created_at`);

--
-- Indexes for table `tn_ad_clicks`
--
ALTER TABLE `tn_ad_clicks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ac_ad` (`ad_id`);

--
-- Indexes for table `tn_ad_enquiries`
--
ALTER TABLE `tn_ad_enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ae_package` (`package_id`);

--
-- Indexes for table `tn_ad_images`
--
ALTER TABLE `tn_ad_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ai_ad` (`ad_id`);

--
-- Indexes for table `tn_ad_packages`
--
ALTER TABLE `tn_ad_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_ad_package_requests`
--
ALTER TABLE `tn_ad_package_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ad` (`ad_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `tn_ad_slots`
--
ALTER TABLE `tn_ad_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_slot_slug` (`slug`);

--
-- Indexes for table `tn_ad_subscriptions`
--
ALTER TABLE `tn_ad_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ad_id` (`ad_id`),
  ADD KEY `idx_owner` (`owner_user_id`),
  ADD KEY `idx_status_expiry` (`status`,`valid_until`);

--
-- Indexes for table `tn_analytics_daily`
--
ALTER TABLE `tn_analytics_daily`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_analytics_date` (`article_id`,`date`);

--
-- Indexes for table `tn_articles`
--
ALTER TABLE `tn_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_article_slug` (`slug`),
  ADD KEY `idx_art_status` (`status`,`published_at`),
  ADD KEY `idx_art_category` (`category_id`,`status`),
  ADD KEY `idx_art_district` (`district_id`),
  ADD KEY `idx_art_breaking` (`is_breaking`,`status`),
  ADD KEY `idx_art_featured` (`is_featured`,`status`),
  ADD KEY `idx_art_type` (`content_type`,`status`),
  ADD KEY `fk_art_user` (`user_id`),
  ADD KEY `fk_art_contrib` (`contributor_id`),
  ADD KEY `fk_art_approved` (`approved_by`),
  ADD KEY `fk_art_city` (`city_id`),
  ADD KEY `idx_article_district` (`district_id`);

--
-- Indexes for table `tn_article_ratings`
--
ALTER TABLE `tn_article_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ar_article` (`article_id`),
  ADD KEY `fk_ar_reader` (`reader_id`);

--
-- Indexes for table `tn_article_tags`
--
ALTER TABLE `tn_article_tags`
  ADD PRIMARY KEY (`article_id`,`tag_id`),
  ADD KEY `fk_at_tag` (`tag_id`);

--
-- Indexes for table `tn_business_ads`
--
ALTER TABLE `tn_business_ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bad_status` (`status`,`valid_from`,`valid_until`),
  ADD KEY `idx_bad_slot` (`slot_id`,`display_type`),
  ADD KEY `idx_bad_district` (`district_id`),
  ADD KEY `idx_bad_category` (`category_id`),
  ADD KEY `fk_bad_submitted` (`submitted_by`),
  ADD KEY `fk_bad_approved` (`approved_by`),
  ADD KEY `fk_bad_payment_by` (`payment_confirmed_by`),
  ADD KEY `fk_bad_package` (`package_id`),
  ADD KEY `fk_bad_city` (`city_id`),
  ADD KEY `idx_owner_user` (`owner_user_id`);

--
-- Indexes for table `tn_categories`
--
ALTER TABLE `tn_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cat_slug` (`slug`),
  ADD KEY `idx_cat_parent` (`parent_id`);

--
-- Indexes for table `tn_cities`
--
ALTER TABLE `tn_cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_city_dist` (`district_id`);

--
-- Indexes for table `tn_citizen_reports`
--
ALTER TABLE `tn_citizen_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `tn_contributors`
--
ALTER TABLE `tn_contributors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_contrib_email` (`email`),
  ADD KEY `idx_google_id` (`google_id`);

--
-- Indexes for table `tn_contributor_notifications`
--
ALTER TABLE `tn_contributor_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_cron_logs`
--
ALTER TABLE `tn_cron_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cron_job` (`job`);

--
-- Indexes for table `tn_csrf_tokens`
--
ALTER TABLE `tn_csrf_tokens`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `tn_districts`
--
ALTER TABLE `tn_districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dist_state` (`state_id`);

--
-- Indexes for table `tn_editor_permissions`
--
ALTER TABLE `tn_editor_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ep_user` (`user_id`),
  ADD KEY `fk_ep_district` (`district_id`);

--
-- Indexes for table `tn_fcm_topics`
--
ALTER TABLE `tn_fcm_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_fcm_slug` (`slug`);

--
-- Indexes for table `tn_live_blogs`
--
ALTER TABLE `tn_live_blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lb_slug` (`slug`),
  ADD KEY `fk_lb_user` (`user_id`),
  ADD KEY `fk_lb_article` (`article_id`),
  ADD KEY `idx_lb_status` (`status`);

--
-- Indexes for table `tn_live_entries`
--
ALTER TABLE `tn_live_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_le_blog` (`blog_id`),
  ADD KEY `fk_le_user` (`user_id`);

--
-- Indexes for table `tn_media`
--
ALTER TABLE `tn_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_media_user` (`user_id`),
  ADD KEY `idx_media_folder` (`folder`);

--
-- Indexes for table `tn_newspapers`
--
ALTER TABLE `tn_newspapers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_np_user` (`user_id`);

--
-- Indexes for table `tn_notifications`
--
ALTER TABLE `tn_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notif_user` (`user_id`,`is_read`),
  ADD KEY `fk_notif_from` (`from_id`),
  ADD KEY `fk_notif_article` (`article_id`);

--
-- Indexes for table `tn_permissions`
--
ALTER TABLE `tn_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_perm_slug` (`slug`);

--
-- Indexes for table `tn_photo_news`
--
ALTER TABLE `tn_photo_news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_slug` (`slug`);

--
-- Indexes for table `tn_photo_news_tags`
--
ALTER TABLE `tn_photo_news_tags`
  ADD PRIMARY KEY (`photo_news_id`,`tag_id`);

--
-- Indexes for table `tn_polls`
--
ALTER TABLE `tn_polls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_poll_user` (`created_by`),
  ADD KEY `fk_poll_article` (`article_id`);

--
-- Indexes for table `tn_poll_options`
--
ALTER TABLE `tn_poll_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_po_poll` (`poll_id`);

--
-- Indexes for table `tn_poll_votes`
--
ALTER TABLE `tn_poll_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pv_poll` (`poll_id`),
  ADD KEY `fk_pv_option` (`option_id`),
  ADD KEY `fk_pv_reader` (`reader_id`);

--
-- Indexes for table `tn_premium_access`
--
ALTER TABLE `tn_premium_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pa_reader` (`reader_id`),
  ADD KEY `fk_pa_plan` (`plan_id`);

--
-- Indexes for table `tn_premium_plans`
--
ALTER TABLE `tn_premium_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_print_editions`
--
ALTER TABLE `tn_print_editions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_print_edition_articles`
--
ALTER TABLE `tn_print_edition_articles`
  ADD PRIMARY KEY (`edition_id`,`article_id`);

--
-- Indexes for table `tn_push_logs`
--
ALTER TABLE `tn_push_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_push_notifications`
--
ALTER TABLE `tn_push_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pn_user` (`user_id`),
  ADD KEY `fk_pn_topic` (`topic_id`),
  ADD KEY `fk_pn_article` (`article_id`);

--
-- Indexes for table `tn_push_subscribers`
--
ALTER TABLE `tn_push_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_district` (`district_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `tn_rates`
--
ALTER TABLE `tn_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rates_type` (`type`,`city`);

--
-- Indexes for table `tn_readers`
--
ALTER TABLE `tn_readers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reader_google` (`google_id`),
  ADD KEY `idx_reader_email` (`email`);

--
-- Indexes for table `tn_reporter_assignments`
--
ALTER TABLE `tn_reporter_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rep_assign` (`reporter_id`),
  ADD KEY `fk_ra_chief` (`chief_editor_id`),
  ADD KEY `fk_ra_district` (`district_editor_id`);

--
-- Indexes for table `tn_reporter_performance`
--
ALTER TABLE `tn_reporter_performance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rp_month` (`user_id`,`month`);

--
-- Indexes for table `tn_roles`
--
ALTER TABLE `tn_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_role_slug` (`slug`);

--
-- Indexes for table `tn_role_permissions`
--
ALTER TABLE `tn_role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fk_rp_perm` (`permission_id`);

--
-- Indexes for table `tn_rss_feeds`
--
ALTER TABLE `tn_rss_feeds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rss_cat` (`category_id`);

--
-- Indexes for table `tn_rss_imports`
--
ALTER TABLE `tn_rss_imports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rss_url` (`url`(255));

--
-- Indexes for table `tn_seo_config`
--
ALTER TABLE `tn_seo_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_seo_page` (`page_type`);

--
-- Indexes for table `tn_sessions`
--
ALTER TABLE `tn_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sess_user` (`user_id`);

--
-- Indexes for table `tn_settings`
--
ALTER TABLE `tn_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_setting_key` (`key`);

--
-- Indexes for table `tn_short_urls`
--
ALTER TABLE `tn_short_urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_article` (`article_id`);

--
-- Indexes for table `tn_sitemap_log`
--
ALTER TABLE `tn_sitemap_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_site_counter`
--
ALTER TABLE `tn_site_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_special_categories`
--
ALTER TABLE `tn_special_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sc_slug` (`slug`),
  ADD KEY `fk_sc_cat` (`category_id`);

--
-- Indexes for table `tn_special_category_articles`
--
ALTER TABLE `tn_special_category_articles`
  ADD PRIMARY KEY (`special_category_id`,`article_id`),
  ADD KEY `fk_sca_article` (`article_id`);

--
-- Indexes for table `tn_sponsored_news`
--
ALTER TABLE `tn_sponsored_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sub` (`subscription_id`),
  ADD KEY `idx_article` (`article_id`);

--
-- Indexes for table `tn_states`
--
ALTER TABLE `tn_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_tags`
--
ALTER TABLE `tn_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tag_slug` (`slug`);

--
-- Indexes for table `tn_users`
--
ALTER TABLE `tn_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_email` (`email`),
  ADD KEY `idx_user_role` (`role_id`),
  ADD KEY `fk_user_district` (`assigned_district_id`);

--
-- Indexes for table `tn_user_badges`
--
ALTER TABLE `tn_user_badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_user_badge_assignments`
--
ALTER TABLE `tn_user_badge_assignments`
  ADD PRIMARY KEY (`user_id`,`badge_id`),
  ADD KEY `fk_uba_badge` (`badge_id`);

--
-- Indexes for table `tn_widgets`
--
ALTER TABLE `tn_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tn_youtube_channels`
--
ALTER TABLE `tn_youtube_channels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_yt_channel` (`channel_id`),
  ADD KEY `fk_ytc_cat` (`category_id`);

--
-- Indexes for table `tn_youtube_imports`
--
ALTER TABLE `tn_youtube_imports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_yt_video` (`video_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tn_activity_log`
--
ALTER TABLE `tn_activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_ad_clicks`
--
ALTER TABLE `tn_ad_clicks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `tn_ad_enquiries`
--
ALTER TABLE `tn_ad_enquiries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_ad_images`
--
ALTER TABLE `tn_ad_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tn_ad_packages`
--
ALTER TABLE `tn_ad_packages`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tn_ad_package_requests`
--
ALTER TABLE `tn_ad_package_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tn_ad_slots`
--
ALTER TABLE `tn_ad_slots`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tn_ad_subscriptions`
--
ALTER TABLE `tn_ad_subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tn_analytics_daily`
--
ALTER TABLE `tn_analytics_daily`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=453;

--
-- AUTO_INCREMENT for table `tn_articles`
--
ALTER TABLE `tn_articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `tn_article_ratings`
--
ALTER TABLE `tn_article_ratings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tn_business_ads`
--
ALTER TABLE `tn_business_ads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tn_categories`
--
ALTER TABLE `tn_categories`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tn_cities`
--
ALTER TABLE `tn_cities`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_citizen_reports`
--
ALTER TABLE `tn_citizen_reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_contributors`
--
ALTER TABLE `tn_contributors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_cron_logs`
--
ALTER TABLE `tn_cron_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_districts`
--
ALTER TABLE `tn_districts`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tn_editor_permissions`
--
ALTER TABLE `tn_editor_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_fcm_topics`
--
ALTER TABLE `tn_fcm_topics`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tn_live_blogs`
--
ALTER TABLE `tn_live_blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_live_entries`
--
ALTER TABLE `tn_live_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_media`
--
ALTER TABLE `tn_media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tn_newspapers`
--
ALTER TABLE `tn_newspapers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_notifications`
--
ALTER TABLE `tn_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tn_permissions`
--
ALTER TABLE `tn_permissions`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tn_photo_news`
--
ALTER TABLE `tn_photo_news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tn_polls`
--
ALTER TABLE `tn_polls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_poll_options`
--
ALTER TABLE `tn_poll_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_poll_votes`
--
ALTER TABLE `tn_poll_votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_premium_access`
--
ALTER TABLE `tn_premium_access`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_premium_plans`
--
ALTER TABLE `tn_premium_plans`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tn_print_editions`
--
ALTER TABLE `tn_print_editions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_push_logs`
--
ALTER TABLE `tn_push_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tn_push_notifications`
--
ALTER TABLE `tn_push_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_push_subscribers`
--
ALTER TABLE `tn_push_subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_rates`
--
ALTER TABLE `tn_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tn_readers`
--
ALTER TABLE `tn_readers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tn_reporter_assignments`
--
ALTER TABLE `tn_reporter_assignments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_reporter_performance`
--
ALTER TABLE `tn_reporter_performance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_roles`
--
ALTER TABLE `tn_roles`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tn_rss_feeds`
--
ALTER TABLE `tn_rss_feeds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_rss_imports`
--
ALTER TABLE `tn_rss_imports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_seo_config`
--
ALTER TABLE `tn_seo_config`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_settings`
--
ALTER TABLE `tn_settings`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tn_short_urls`
--
ALTER TABLE `tn_short_urls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tn_sitemap_log`
--
ALTER TABLE `tn_sitemap_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_special_categories`
--
ALTER TABLE `tn_special_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tn_sponsored_news`
--
ALTER TABLE `tn_sponsored_news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_states`
--
ALTER TABLE `tn_states`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tn_tags`
--
ALTER TABLE `tn_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tn_users`
--
ALTER TABLE `tn_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tn_user_badges`
--
ALTER TABLE `tn_user_badges`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_widgets`
--
ALTER TABLE `tn_widgets`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tn_youtube_channels`
--
ALTER TABLE `tn_youtube_channels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tn_youtube_imports`
--
ALTER TABLE `tn_youtube_imports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tn_ad_clicks`
--
ALTER TABLE `tn_ad_clicks`
  ADD CONSTRAINT `fk_ac_ad` FOREIGN KEY (`ad_id`) REFERENCES `tn_business_ads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_ad_enquiries`
--
ALTER TABLE `tn_ad_enquiries`
  ADD CONSTRAINT `fk_ae_package` FOREIGN KEY (`package_id`) REFERENCES `tn_ad_packages` (`id`);

--
-- Constraints for table `tn_ad_images`
--
ALTER TABLE `tn_ad_images`
  ADD CONSTRAINT `fk_ai_ad` FOREIGN KEY (`ad_id`) REFERENCES `tn_business_ads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_analytics_daily`
--
ALTER TABLE `tn_analytics_daily`
  ADD CONSTRAINT `fk_anal_art` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_articles`
--
ALTER TABLE `tn_articles`
  ADD CONSTRAINT `fk_art_approved` FOREIGN KEY (`approved_by`) REFERENCES `tn_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_art_category` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`),
  ADD CONSTRAINT `fk_art_city` FOREIGN KEY (`city_id`) REFERENCES `tn_cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_art_district` FOREIGN KEY (`district_id`) REFERENCES `tn_districts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_art_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`);

--
-- Constraints for table `tn_article_ratings`
--
ALTER TABLE `tn_article_ratings`
  ADD CONSTRAINT `fk_ar_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ar_reader` FOREIGN KEY (`reader_id`) REFERENCES `tn_readers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_article_tags`
--
ALTER TABLE `tn_article_tags`
  ADD CONSTRAINT `fk_at_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_at_tag` FOREIGN KEY (`tag_id`) REFERENCES `tn_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_business_ads`
--
ALTER TABLE `tn_business_ads`
  ADD CONSTRAINT `fk_bad_approved` FOREIGN KEY (`approved_by`) REFERENCES `tn_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bad_category` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bad_city` FOREIGN KEY (`city_id`) REFERENCES `tn_cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bad_district` FOREIGN KEY (`district_id`) REFERENCES `tn_districts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bad_package` FOREIGN KEY (`package_id`) REFERENCES `tn_ad_packages` (`id`),
  ADD CONSTRAINT `fk_bad_payment_by` FOREIGN KEY (`payment_confirmed_by`) REFERENCES `tn_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bad_slot` FOREIGN KEY (`slot_id`) REFERENCES `tn_ad_slots` (`id`),
  ADD CONSTRAINT `fk_bad_submitted` FOREIGN KEY (`submitted_by`) REFERENCES `tn_users` (`id`);

--
-- Constraints for table `tn_categories`
--
ALTER TABLE `tn_categories`
  ADD CONSTRAINT `fk_cat_parent` FOREIGN KEY (`parent_id`) REFERENCES `tn_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_cities`
--
ALTER TABLE `tn_cities`
  ADD CONSTRAINT `fk_city_dist` FOREIGN KEY (`district_id`) REFERENCES `tn_districts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_districts`
--
ALTER TABLE `tn_districts`
  ADD CONSTRAINT `fk_dist_state` FOREIGN KEY (`state_id`) REFERENCES `tn_states` (`id`);

--
-- Constraints for table `tn_editor_permissions`
--
ALTER TABLE `tn_editor_permissions`
  ADD CONSTRAINT `fk_ep_district` FOREIGN KEY (`district_id`) REFERENCES `tn_districts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ep_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_live_blogs`
--
ALTER TABLE `tn_live_blogs`
  ADD CONSTRAINT `fk_lb_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_lb_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`);

--
-- Constraints for table `tn_live_entries`
--
ALTER TABLE `tn_live_entries`
  ADD CONSTRAINT `fk_le_blog` FOREIGN KEY (`blog_id`) REFERENCES `tn_live_blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_le_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`);

--
-- Constraints for table `tn_media`
--
ALTER TABLE `tn_media`
  ADD CONSTRAINT `fk_media_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_newspapers`
--
ALTER TABLE `tn_newspapers`
  ADD CONSTRAINT `fk_np_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`);

--
-- Constraints for table `tn_notifications`
--
ALTER TABLE `tn_notifications`
  ADD CONSTRAINT `fk_notif_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_notif_from` FOREIGN KEY (`from_id`) REFERENCES `tn_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_polls`
--
ALTER TABLE `tn_polls`
  ADD CONSTRAINT `fk_poll_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_poll_user` FOREIGN KEY (`created_by`) REFERENCES `tn_users` (`id`);

--
-- Constraints for table `tn_poll_options`
--
ALTER TABLE `tn_poll_options`
  ADD CONSTRAINT `fk_po_poll` FOREIGN KEY (`poll_id`) REFERENCES `tn_polls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_poll_votes`
--
ALTER TABLE `tn_poll_votes`
  ADD CONSTRAINT `fk_pv_option` FOREIGN KEY (`option_id`) REFERENCES `tn_poll_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pv_poll` FOREIGN KEY (`poll_id`) REFERENCES `tn_polls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pv_reader` FOREIGN KEY (`reader_id`) REFERENCES `tn_readers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_premium_access`
--
ALTER TABLE `tn_premium_access`
  ADD CONSTRAINT `fk_pa_plan` FOREIGN KEY (`plan_id`) REFERENCES `tn_premium_plans` (`id`),
  ADD CONSTRAINT `fk_pa_reader` FOREIGN KEY (`reader_id`) REFERENCES `tn_readers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_push_notifications`
--
ALTER TABLE `tn_push_notifications`
  ADD CONSTRAINT `fk_pn_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pn_topic` FOREIGN KEY (`topic_id`) REFERENCES `tn_fcm_topics` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_reporter_assignments`
--
ALTER TABLE `tn_reporter_assignments`
  ADD CONSTRAINT `fk_ra_chief` FOREIGN KEY (`chief_editor_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ra_district` FOREIGN KEY (`district_editor_id`) REFERENCES `tn_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ra_reporter` FOREIGN KEY (`reporter_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_reporter_performance`
--
ALTER TABLE `tn_reporter_performance`
  ADD CONSTRAINT `fk_rp_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_role_permissions`
--
ALTER TABLE `tn_role_permissions`
  ADD CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `tn_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `tn_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_rss_feeds`
--
ALTER TABLE `tn_rss_feeds`
  ADD CONSTRAINT `fk_rss_cat` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_sessions`
--
ALTER TABLE `tn_sessions`
  ADD CONSTRAINT `fk_sess_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_special_categories`
--
ALTER TABLE `tn_special_categories`
  ADD CONSTRAINT `fk_sc_cat` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tn_special_category_articles`
--
ALTER TABLE `tn_special_category_articles`
  ADD CONSTRAINT `fk_sca_article` FOREIGN KEY (`article_id`) REFERENCES `tn_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sca_sc` FOREIGN KEY (`special_category_id`) REFERENCES `tn_special_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_users`
--
ALTER TABLE `tn_users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `tn_roles` (`id`);

--
-- Constraints for table `tn_user_badge_assignments`
--
ALTER TABLE `tn_user_badge_assignments`
  ADD CONSTRAINT `fk_uba_badge` FOREIGN KEY (`badge_id`) REFERENCES `tn_user_badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uba_user` FOREIGN KEY (`user_id`) REFERENCES `tn_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tn_youtube_channels`
--
ALTER TABLE `tn_youtube_channels`
  ADD CONSTRAINT `fk_ytc_cat` FOREIGN KEY (`category_id`) REFERENCES `tn_categories` (`id`) ON DELETE SET NULL;
COMMIT;
