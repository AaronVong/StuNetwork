-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 22, 2021 at 05:19 AM
-- Server version: 8.0.21
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stunetwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `commenter_id` varchar(255) DEFAULT NULL,
  `commenter_type` varchar(255) DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `commentable_type` varchar(255) NOT NULL,
  `commentable_id` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `child_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_commenter_id_commenter_type_index` (`commenter_id`,`commenter_type`),
  KEY `comments_commentable_type_commentable_id_index` (`commentable_type`,`commentable_id`),
  KEY `comments_child_id_foreign` (`child_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `commenter_id`, `commenter_type`, `guest_name`, `guest_email`, `commentable_type`, `commentable_id`, `comment`, `approved`, `child_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', 'App\\Models\\User', NULL, NULL, 'App\\Models\\Toast', '1', 'STUNetwork báo cáo', 1, NULL, NULL, '2021-08-21 08:05:05', '2021-08-21 08:05:05'),
(2, '3', 'App\\Models\\User', NULL, NULL, 'App\\Models\\Toast', '1', 'love it', 1, 1, NULL, '2021-08-21 08:05:28', '2021-08-21 08:05:28');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'user_id',
  `likeable_type` varchar(255) NOT NULL,
  `likeable_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `likes_likeable_type_likeable_id_index` (`likeable_type`,`likeable_id`),
  KEY `likes_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `likeable_type`, `likeable_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Toast', 1, '2021-08-21 08:01:33', '2021-08-21 08:01:33'),
(2, 3, 'App\\Models\\Toast', 1, '2021-08-21 08:01:42', '2021-08-21 08:01:42'),
(3, 4, 'App\\Models\\Toast', 1, '2021-08-21 21:38:22', '2021-08-21 21:38:22'),
(4, 4, 'App\\Models\\Toast', 2, '2021-08-21 21:39:22', '2021-08-21 21:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `message` varchar(255) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `seen`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'xin chào', 0, '2021-08-21 07:48:54', '2021-08-21 07:48:54'),
(2, 3, 2, 'hey hey hey', 0, '2021-08-21 07:52:44', '2021-08-21 07:52:44'),
(3, 2, 3, 'dã nhận được tin nhắn', 0, '2021-08-21 07:53:10', '2021-08-21 07:53:10'),
(4, 3, 1, 'xin chào', 0, '2021-08-21 08:03:58', '2021-08-21 08:03:58'),
(5, 1, 3, 'chào', 0, '2021-08-21 08:04:14', '2021-08-21 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_06_30_113500_create_comments_table', 1),
(4, '2018_12_14_000000_create_likes_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2021_05_18_144227_create_profiles_table', 1),
(7, '2021_05_21_051322_create_toasts_table', 1),
(8, '2021_06_03_123517_create_toast_files_table', 1),
(9, '2021_06_14_125429_create_user_follows_table', 1),
(10, '2021_07_01_133920_create_messages_table', 1),
(11, '2021_07_06_123353_create_permission_tables', 1),
(12, '2021_08_19_143235_create_settings_table', 1),
(13, '2021_08_19_143827_create_setting_user_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 2),
(8, 'App\\Models\\User', 2),
(9, 'App\\Models\\User', 2),
(10, 'App\\Models\\User', 2),
(11, 'App\\Models\\User', 2),
(14, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 3),
(6, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 3),
(8, 'App\\Models\\User', 3),
(9, 'App\\Models\\User', 3),
(10, 'App\\Models\\User', 3),
(11, 'App\\Models\\User', 3),
(14, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'upload toast', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(2, 'upload file', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(3, 'edit toast', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(4, 'delete file', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(5, 'delete toast', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(6, 'comment toast', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(7, 'like', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(8, 'follow', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(9, 'login', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(10, 'send message', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(11, 'delete message', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(12, 'create role', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(13, 'create user', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(14, 'edit profile', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `avatarUrl` varchar(255) DEFAULT NULL,
  `backgroundUrl` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profiles_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `fullname`, `phone`, `birthday`, `address`, `gender`, `avatarUrl`, `backgroundUrl`, `created_at`, `updated_at`) VALUES
(1, 1, 'Vòng Quyền Minh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'Lê Văn Hiếu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'Chẫu Nguyễn Quốc Anh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'Aaron Vòng', NULL, NULL, NULL, NULL, NULL, NULL, '2021-08-21 21:37:18', '2021-08-21 21:37:18');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'user', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15'),
(2, 'super-admin', 'web', '2021-08-21 07:44:15', '2021-08-21 07:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(14, 1),
(12, 2),
(13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'student message', NULL, NULL),
(2, 'teacher message', NULL, NULL),
(3, 'stranger message', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting_user`
--

DROP TABLE IF EXISTS `setting_user`;
CREATE TABLE IF NOT EXISTS `setting_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setting_user_setting_id_foreign` (`setting_id`),
  KEY `setting_user_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting_user`
--

INSERT INTO `setting_user` (`id`, `setting_id`, `user_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, NULL, '2021-08-21 08:03:00'),
(2, 2, 1, 0, NULL, '2021-08-21 08:03:00'),
(3, 3, 1, 0, NULL, '2021-08-21 08:03:00'),
(4, 1, 2, 0, NULL, '2021-08-21 07:52:03'),
(5, 2, 2, 1, NULL, NULL),
(6, 3, 2, 0, NULL, '2021-08-21 07:48:13'),
(7, 1, 3, 1, NULL, NULL),
(8, 2, 3, 1, NULL, NULL),
(9, 3, 3, 1, NULL, NULL),
(10, 1, 4, 1, NULL, NULL),
(11, 2, 4, 1, NULL, NULL),
(12, 3, 4, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `toasts`
--

DROP TABLE IF EXISTS `toasts`;
CREATE TABLE IF NOT EXISTS `toasts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toasts_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `toasts`
--

INSERT INTO `toasts` (`id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'STUNetwork', '2021-08-21 07:57:26', '2021-08-21 07:57:26'),
(2, 4, 'Xin chào', '2021-08-21 21:38:55', '2021-08-21 21:38:55'),
(3, 1, 'Veniam rerum nihil rerum animi ut a sint sint totam rem.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(4, 1, 'Molestias eum dolorum dolor possimus non ut similique ratione.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(5, 1, 'Vel modi et qui molestiae molestiae ut unde eius qui laborum ipsa.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(6, 1, 'Illum praesentium corrupti voluptas in beatae et quas excepturi qui reprehenderit atque similique.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(7, 1, 'Nihil cumque quos unde asperiores quam et aut.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(8, 1, 'Voluptatibus modi cumque quia non quibusdam rerum quae qui odit molestias cum expedita.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(9, 1, 'Praesentium reiciendis qui quia saepe maiores adipisci aut molestiae voluptas reprehenderit in assumenda.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(10, 1, 'Sint nulla quis totam voluptatibus commodi sed.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(11, 1, 'Magnam blanditiis eum ipsum praesentium quia inventore dolor.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(12, 1, 'Consequatur molestiae cupiditate aliquid accusantium animi temporibus recusandae adipisci quos omnis repellendus at.', '2021-08-21 21:47:25', '2021-08-21 21:47:25'),
(13, 2, 'Fugiat est qui asperiores laborum blanditiis illum.', '2021-08-21 21:47:27', '2021-08-21 21:47:27'),
(14, 2, 'Iste laboriosam facilis voluptatem quis optio nostrum soluta nihil.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(15, 2, 'Ad consectetur illum dolorem aliquid laudantium debitis ea.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(16, 2, 'Distinctio asperiores veniam suscipit repellendus natus aut rem.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(17, 2, 'Fuga illo qui qui quibusdam autem cupiditate.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(18, 2, 'Ut est reiciendis numquam est adipisci nam est dolor a molestiae et velit.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(19, 2, 'Doloremque sunt placeat nesciunt dolore sit nobis rerum voluptatibus.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(20, 2, 'Non ut dolores amet enim assumenda necessitatibus.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(21, 2, 'Sapiente excepturi ratione architecto quod molestiae nulla ab cupiditate qui dolorem non odit.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(22, 2, 'Alias veniam et inventore sit et architecto est accusamus vel unde inventore.', '2021-08-21 21:47:28', '2021-08-21 21:47:28'),
(23, 3, 'Explicabo doloremque quibusdam soluta ut autem tenetur et fugiat.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(24, 3, 'Ratione distinctio reiciendis et nesciunt laboriosam a deleniti officiis est corporis corporis possimus officiis.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(25, 3, 'Fugit autem corrupti sed excepturi fugiat culpa dolores aut laudantium.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(26, 3, 'Vel et soluta ratione doloremque occaecati rem et.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(27, 3, 'Est consequatur minus earum quaerat eveniet minus.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(28, 3, 'Labore quia illo reiciendis est ea et.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(29, 3, 'Ut enim totam fugit dolores quia delectus eos beatae fugiat temporibus quia.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(30, 3, 'Nulla repellat id officiis ut laudantium rem qui suscipit tempora tempore corrupti quam fugit.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(31, 3, 'Blanditiis qui dolores esse rerum voluptates deserunt neque error.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(32, 3, 'Quam sit veniam a qui asperiores quo dolorem aut nam.', '2021-08-21 21:47:31', '2021-08-21 21:47:31'),
(33, 4, 'Aut cum quis sed veritatis earum voluptates nihil.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(34, 4, 'Recusandae corrupti non nemo recusandae dolores et voluptas consequatur molestiae.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(35, 4, 'Porro voluptatibus autem ab ipsa optio autem quis ea harum deleniti.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(36, 4, 'Facilis rem quo deleniti aut quia qui aut et et ducimus odio.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(37, 4, 'Consequatur nesciunt sunt quod ut omnis et consequatur at rerum.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(38, 4, 'Inventore reiciendis nam officiis inventore et ut.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(39, 4, 'Consequatur dignissimos deserunt eius ea aliquid aut modi dolore accusamus in sed rerum est.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(40, 4, 'Voluptas optio architecto distinctio delectus cupiditate aliquid quo nemo itaque omnis.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(41, 4, 'Quibusdam beatae nisi quae eligendi dicta ut esse distinctio dolore ut aut.', '2021-08-21 21:47:34', '2021-08-21 21:47:34'),
(42, 4, 'Ea expedita sequi occaecati iusto est mollitia magnam omnis id ut harum autem ea.', '2021-08-21 21:47:34', '2021-08-21 21:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `toast_files`
--

DROP TABLE IF EXISTS `toast_files`;
CREATE TABLE IF NOT EXISTS `toast_files` (
  `id` varchar(255) NOT NULL,
  `toast_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toast_files_toast_id_foreign` (`toast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `toast_files`
--

INSERT INTO `toast_files` (`id`, `toast_id`, `name`, `url`, `type`, `created_at`, `updated_at`) VALUES
('1AXeTUeOBBF3MjuFgxSpTmEh-n4dXCHZe', 1, 'Logo_STU.png', 'https://drive.google.com/uc?id=1AXeTUeOBBF3MjuFgxSpTmEh-n4dXCHZe&export=media', 'image', '2021-08-21 07:57:38', '2021-08-21 07:57:38'),
('1yWlmSGKvlaGVG1e8yGDj_vsHDKWqGdmI', 1, 'DATN_2021_HD5_VongQuyenMinh.pdf', 'https://drive.google.com/uc?id=1yWlmSGKvlaGVG1e8yGDj_vsHDKWqGdmI&export=media', 'file', '2021-08-21 07:57:34', '2021-08-21 07:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'minhvong', 'minh.vongquyen@student.stu.edu.vn', '2021-08-21 07:44:15', '$2y$10$88Zuu4DutTZYpYg9cnIGJuVEsKt0FHQgResUExtUCufh02gq88ChW', NULL, NULL, NULL),
(2, 'hieule', 'hieu.levan@student.stu.edu.vn', '2021-08-21 07:44:15', '$2y$10$oq.68NExohTBxV8HyNrZc.WvsZAXIdJ4kkinzZwBKhMC/54QoPOqO', NULL, NULL, NULL),
(3, 'anhnguyen', 'anh.chaunguyenquoc@student.stu.edu.vn', '2021-08-21 07:44:15', '$2y$10$zPzlrefNB09p8dh.GgulKOT9HCvZw1M1Q7qfn27FR1uwOHzTDACtK', NULL, NULL, NULL),
(4, 'aaronvong', 'dh51703728@student.stu.edu.vn', '2021-08-21 21:37:44', '$2y$10$wn4soeMcCrt.noS598ileOWkDUjKa1uopsgZJiYvrMcaU4e/Wgiv2', NULL, '2021-08-21 21:37:18', '2021-08-21 21:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_follows`
--

DROP TABLE IF EXISTS `user_follows`;
CREATE TABLE IF NOT EXISTS `user_follows` (
  `follower_id` bigint UNSIGNED NOT NULL COMMENT 'user_id',
  `following_id` bigint UNSIGNED NOT NULL COMMENT 'profile_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`follower_id`,`following_id`),
  KEY `user_follows_following_id_foreign` (`following_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_follows`
--

INSERT INTO `user_follows` (`follower_id`, `following_id`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL),
(2, 3, NULL, NULL),
(3, 2, NULL, NULL),
(4, 1, NULL, NULL),
(4, 2, NULL, NULL),
(4, 3, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `setting_user`
--
ALTER TABLE `setting_user`
  ADD CONSTRAINT `setting_user_setting_id_foreign` FOREIGN KEY (`setting_id`) REFERENCES `settings` (`id`),
  ADD CONSTRAINT `setting_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `toasts`
--
ALTER TABLE `toasts`
  ADD CONSTRAINT `toasts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `toast_files`
--
ALTER TABLE `toast_files`
  ADD CONSTRAINT `toast_files_toast_id_foreign` FOREIGN KEY (`toast_id`) REFERENCES `toasts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD CONSTRAINT `user_follows_follower_id_foreign` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_follows_following_id_foreign` FOREIGN KEY (`following_id`) REFERENCES `profiles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
