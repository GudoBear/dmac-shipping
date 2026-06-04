-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 05:34 AM
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
-- Database: `dmac_shipping_optimized`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_type`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 14:46:14'),
(2, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 14:46:23'),
(3, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 14:53:54'),
(4, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 14:54:02'),
(5, 'employee', 1, 'Added expense', 'Added expense amount ₱100.00 on 2026-06-05', '::1', '2026-06-03 17:16:20'),
(6, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 17:43:44'),
(7, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 17:43:52'),
(8, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 17:46:25'),
(9, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 17:46:34'),
(10, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 18:11:44'),
(11, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 18:11:54'),
(12, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 18:14:17'),
(13, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 18:14:28'),
(14, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 18:20:37'),
(15, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 18:20:53'),
(16, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 18:21:50'),
(17, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 18:21:54'),
(18, 'employee', 1, 'Updated profile', 'Employee/Admin updated account settings.', '::1', '2026-06-03 18:59:47'),
(19, 'employee', 1, 'Updated profile', 'Employee/Admin updated account settings.', '::1', '2026-06-03 19:18:16'),
(20, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 19:24:45'),
(21, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 19:24:58'),
(22, 'client', 4, 'Updated profile', 'Client updated account settings.', '::1', '2026-06-03 19:25:43'),
(23, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 19:25:47'),
(24, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 19:32:01'),
(25, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 19:32:46'),
(26, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 19:32:56'),
(27, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 23:50:11'),
(28, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-03 23:50:21'),
(29, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-03 23:57:04'),
(30, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 23:57:20'),
(31, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-03 23:58:00'),
(32, 'employee', 12, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-03 23:59:02'),
(33, 'employee', 12, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-04 00:00:46'),
(34, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:00:53'),
(35, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 00:01:50'),
(36, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-04 00:02:04'),
(37, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-04 00:05:17'),
(38, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:05:39'),
(39, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 00:07:13'),
(40, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-04 00:23:42'),
(41, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-04 00:23:55'),
(42, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:24:03'),
(43, 'client', 8, 'Logged out', 'Client logged out.', '::1', '2026-06-04 00:26:33'),
(44, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:27:24'),
(45, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 00:31:57'),
(46, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:33:35'),
(47, 'client', 4, 'Updated profile', 'Client updated account settings.', '::1', '2026-06-04 00:33:45'),
(48, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 00:34:00'),
(49, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-04 00:34:19'),
(50, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:41:09'),
(51, 'client', 4, 'Updated profile', 'Client updated account settings.', '::1', '2026-06-04 00:41:26'),
(52, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 00:41:39'),
(53, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:46:58'),
(54, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 00:56:18'),
(55, 'client', 4, 'Updated profile', 'Client updated account settings.', '::1', '2026-06-04 01:02:05'),
(56, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 01:29:56'),
(57, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-04 01:30:31'),
(58, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 03:25:32'),
(59, 'client', 4, 'Logged out', 'Client logged out.', '::1', '2026-06-04 03:26:57'),
(60, 'employee', 1, 'Logged in', 'Employee/Admin logged in successfully.', '::1', '2026-06-04 03:27:10'),
(61, 'employee', 1, 'Logged out', 'Employee/Admin logged out.', '::1', '2026-06-04 03:29:59'),
(62, 'client', 4, 'Logged in', 'Client logged in successfully.', '::1', '2026-06-04 03:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `airdetails`
--

CREATE TABLE `airdetails` (
  `airdetails_ID` int(11) NOT NULL,
  `booking_ID` int(11) DEFAULT NULL,
  `transport_ID` int(11) NOT NULL,
  `emp_ID` int(11) NOT NULL,
  `airlines` varchar(255) NOT NULL,
  `airdetails_reference` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airdetails`
--

INSERT INTO `airdetails` (`airdetails_ID`, `booking_ID`, `transport_ID`, `emp_ID`, `airlines`, `airdetails_reference`) VALUES
(1, 11, 1, 2, 'TestAir', 'TEST123'),
(2, 5, 1, 8, 'Philippine Airlines', 'PR-1234'),
(3, 2, 1, 16, 'Cebu Pacific', 'PR-1234'),
(4, 13, 1, 16, 'Philippine Airlines', 'TEST123'),
(5, 15, 1, 18, 'Cebu Pacific', 'TEST123');

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE `animal` (
  `animal_ID` int(11) NOT NULL,
  `animal_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animal`
--

INSERT INTO `animal` (`animal_ID`, `animal_type`) VALUES
(2, 'chicks'),
(3, 'dog'),
(1, 'gamefowl');

-- --------------------------------------------------------

--
-- Table structure for table `animalbatch`
--

CREATE TABLE `animalbatch` (
  `animalbatch_ID` int(11) NOT NULL,
  `booking_ID` int(11) NOT NULL,
  `animal_ID` int(11) NOT NULL,
  `animalbatch_quantity` int(11) NOT NULL DEFAULT 1,
  `animalbatch_fee` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animalbatch`
--

INSERT INTO `animalbatch` (`animalbatch_ID`, `booking_ID`, `animal_ID`, `animalbatch_quantity`, `animalbatch_fee`) VALUES
(1, 1, 2, 20, 0.00),
(2, 1, 3, 20, 0.00),
(3, 2, 3, 25, 0.00),
(4, 2, 1, 125, 0.00),
(5, 3, 2, 11, 0.00),
(6, 4, 1, 12, 0.00),
(7, 5, 2, 100, 0.00),
(8, 5, 3, 3, 0.00),
(9, 5, 1, 1, 0.00),
(10, 11, 2, 13, 0.00),
(11, 12, 1, 23, 0.00),
(12, 13, 1, 1, 0.00),
(13, 14, 2, 67, 0.00),
(14, 15, 1, 67, 0.00),
(15, 15, 2, 12, 0.00),
(16, 16, 1, 1, 0.00),
(17, 17, 1, 1, 0.00),
(18, 18, 1, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_ID` int(11) NOT NULL,
  `client_ID` int(11) NOT NULL,
  `pickup_ID` int(11) NOT NULL,
  `receiver_ID` int(11) NOT NULL,
  `transport_ID` int(11) DEFAULT NULL,
  `booking_status` enum('PENDING REVIEW','PROCESSING','FOR PICK-UP','PREPARING FOR TRANSIT','IN TRANSIT','DELIVERED/SHIPPED','CANCELLED') NOT NULL DEFAULT 'PENDING REVIEW',
  `booking_startdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_requestdate` date DEFAULT NULL,
  `booking_enddate` date DEFAULT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `insurance_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `agreement_accepted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_ID`, `client_ID`, `pickup_ID`, `receiver_ID`, `transport_ID`, `booking_status`, `booking_startdate`, `booking_requestdate`, `booking_enddate`, `terms_accepted`, `insurance_accepted`, `agreement_accepted_at`) VALUES
(1, 1, 1, 1, 2, 'DELIVERED/SHIPPED', '2026-06-02 07:15:30', '2026-06-03', '2026-06-04', 0, 0, NULL),
(2, 1, 2, 2, 1, 'DELIVERED/SHIPPED', '2026-06-02 07:53:57', '2026-06-03', '2026-06-04', 0, 0, NULL),
(3, 2, 3, 3, 2, 'DELIVERED/SHIPPED', '2026-06-02 15:55:15', '2026-06-12', '2026-06-04', 0, 0, NULL),
(4, 4, 4, 4, NULL, 'DELIVERED/SHIPPED', '2026-06-02 17:47:22', '2026-06-12', '2026-06-04', 0, 0, NULL),
(5, 4, 5, 5, 1, 'DELIVERED/SHIPPED', '2026-06-02 17:51:27', '2026-06-10', '2026-06-04', 0, 0, NULL),
(11, 4, 11, 11, 1, 'DELIVERED/SHIPPED', '2026-06-02 20:50:34', '2026-06-03', '2026-06-04', 1, 1, '2026-06-02 20:50:34'),
(12, 4, 12, 12, NULL, 'DELIVERED/SHIPPED', '2026-06-02 23:21:04', '2026-06-19', '2026-06-04', 1, 1, '2026-06-02 23:21:04'),
(13, 4, 13, 13, 1, 'DELIVERED/SHIPPED', '2026-06-03 11:17:12', '2026-06-10', '2026-06-04', 1, 1, '2026-06-03 11:17:12'),
(14, 4, 14, 14, 2, 'DELIVERED/SHIPPED', '2026-06-03 17:46:14', '2026-06-17', '2026-06-04', 1, 1, '2026-06-03 17:46:14'),
(15, 4, 15, 15, 1, 'DELIVERED/SHIPPED', '2026-06-03 18:13:44', '2026-06-11', '2026-06-04', 1, 1, '2026-06-03 18:13:44'),
(16, 4, 16, 16, 2, 'FOR PICK-UP', '2026-06-03 18:21:47', '2026-06-06', NULL, 1, 1, '2026-06-03 18:21:47'),
(17, 4, 17, 17, NULL, 'FOR PICK-UP', '2026-06-04 03:26:03', '2026-06-10', NULL, 1, 1, '2026-06-04 03:26:03'),
(18, 4, 18, 18, NULL, 'PENDING REVIEW', '2026-06-04 03:30:53', '2026-06-18', NULL, 1, 1, '2026-06-04 03:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `bookingassignment`
--

CREATE TABLE `bookingassignment` (
  `bookingassignment_ID` int(11) NOT NULL,
  `booking_ID` int(11) NOT NULL,
  `emp_ID` int(11) NOT NULL,
  `assigned_by_emp_ID` int(11) DEFAULT NULL,
  `process_stage` enum('PICKUP','PROCESSING','IN_TRANSIT','ARRIVAL','DELIVERED') NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookingassignment`
--

INSERT INTO `bookingassignment` (`bookingassignment_ID`, `booking_ID`, `emp_ID`, `assigned_by_emp_ID`, `process_stage`, `assigned_at`, `notes`) VALUES
(1, 1, 2, NULL, 'PICKUP', '2026-06-02 07:48:09', NULL),
(2, 2, 2, NULL, 'PICKUP', '2026-06-02 07:54:35', NULL),
(3, 3, 2, NULL, 'PICKUP', '2026-06-02 15:56:03', NULL),
(4, 5, 2, NULL, 'PICKUP', '2026-06-02 18:00:22', NULL),
(5, 12, 2, 1, 'DELIVERED', '2026-06-03 03:12:05', NULL),
(6, 13, 10, 1, 'ARRIVAL', '2026-06-03 11:56:29', NULL),
(7, 13, 10, 1, 'PICKUP', '2026-06-03 11:56:34', NULL),
(8, 13, 9, 1, 'PROCESSING', '2026-06-03 11:56:38', NULL),
(9, 13, 9, 1, 'IN_TRANSIT', '2026-06-03 11:56:49', 'dapat nasa lipa ka na'),
(10, 15, 10, 1, 'IN_TRANSIT', '2026-06-03 18:16:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client_ID` int(11) NOT NULL,
  `client_firstname` varchar(255) NOT NULL,
  `client_lastname` varchar(255) NOT NULL,
  `client_contact` varchar(20) NOT NULL,
  `client_email` varchar(255) NOT NULL,
  `client_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `failed_login_count` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_ID`, `client_firstname`, `client_lastname`, `client_contact`, `client_email`, `client_password`, `created_at`, `deleted_at`, `last_login_at`, `failed_login_count`, `locked_until`, `profile_image`) VALUES
(1, 'baniga', 'palicpic', '094552232323', 'jay@gmail.com', '$2y$10$eTpIzD8BfgkbWXZxfVaF0ub87Crkb0dsS5r8ZFh0B5KFayvHg6z3a', '2026-06-02 06:59:32', NULL, NULL, 0, NULL, NULL),
(2, 'ryzza', 'Try', '09455332247', 'ryzza@gmail.com', '$2y$10$Mox1boTzTkGpQYiYYYgrhOir7xCMrr6aU1TpRyo5oiiK1q2XLbnle', '2026-06-02 14:37:28', NULL, NULL, 0, NULL, NULL),
(3, 'bebang', 'babo', '09455332247', 'bebang@gmail.com', '$2y$10$jUZLRqBGs.kjg9B69xjoGeHK0pxp1BF1BHrYTk6RZtBrFTQ5bht1O', '2026-06-02 17:28:13', NULL, NULL, 0, NULL, NULL),
(4, 'client', 'try', '09345678901', 'client@gmail.com', '$2y$10$wSrTYzQcDM9ZLyftdQQ/1OJQZPQnXwbM8gi1YAkhOxFJ20kBFnRAy', '2026-06-02 17:44:59', NULL, '2026-06-04 11:30:14', 0, NULL, 'public/uploads/profile/client_1780534925_bf138da8893e.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_ID` int(11) NOT NULL,
  `dept_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_ID`, `dept_name`) VALUES
(1, 'Team Girls'),
(2, 'Team Boys');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_ID` int(11) NOT NULL,
  `dept_ID` int(11) NOT NULL,
  `emp_firstname` varchar(255) NOT NULL,
  `emp_lastname` varchar(255) NOT NULL,
  `emp_contact` varchar(20) NOT NULL,
  `emp_email` varchar(255) NOT NULL,
  `emp_password` varchar(255) NOT NULL,
  `registered_since` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `account_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` datetime DEFAULT NULL,
  `failed_login_count` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_ID`, `dept_ID`, `emp_firstname`, `emp_lastname`, `emp_contact`, `emp_email`, `emp_password`, `registered_since`, `deleted_at`, `account_status`, `is_super_admin`, `last_login_at`, `failed_login_count`, `locked_until`, `force_password_change`, `profile_image`) VALUES
(1, 2, 'Gedde', 'Palicpic', '09090909090', 'admin@gmail.com', '$2y$10$Omr3BPSqTh8cPBqN1KwGr.5ow3MF1/IqgfzDJDogWn.E0UG69I1Ja', '2026-06-02 07:34:11', NULL, 'approved', 1, '2026-06-04 11:27:10', 0, NULL, 0, 'public/uploads/profile/employee_1780513187_5df15ab82217.jpg'),
(2, 2, 'Jamba', 'Juice', '09123412348', 'jamba.j@gmail.com', '$2y$10$5ychQIjfkdri597Po5qmOOJ.V6AMbfsE8e.QQqs5mEhUuXbHWgVZC', '2026-06-02 07:45:21', '2026-06-03 14:06:46', 'rejected', 0, NULL, 0, NULL, 0, NULL),
(4, 2, 'Gedde', 'Palicpic', '09455332247', 'geddeahrenzp@gmail.com', '$2y$10$Carx4uw5y6Jo92ve2a3/8.MNrts0BDen99osnGfiA63SQHqbjwPMS', '2026-06-02 13:54:16', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(5, 1, 'Ryzza', 'Dimaano', '09455332247', 'ryzzzzaa@gmail.com', '$2y$10$r4C7iG.pQBbUjZahwdJ4FevWU5XMHnBuZ1UgY58q0yOP50XRUGGIe', '2026-06-02 16:58:21', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(6, 2, 'Gedde', 'Dimaano', '09455332247', 'ad@gmail.com', '$2y$10$5kjUnoYA2v6LfZ2qywHQbu40Z2fboqvRiNaq5na3Jboi7VolgHPAS', '2026-06-02 17:39:28', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(7, 1, 'asdsds', 'ssssss', '09455332247', 'rytaray@gmail.com', '$2y$10$dZwlBGgn1HTpn1i/tFRaQuQB9eDIyBRbbSmlUkb7AzR4BXcO4/9kG', '2026-06-02 18:11:12', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(8, 1, 'Baby Guenn', 'Bituin', '09455327895', 'guenn@gmail.com', '$2y$10$nP5lelthgRCDhstVe7y2E.33p6DFociCWOOTKYD09JYAhhuHInYzq', '2026-06-03 04:16:58', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(9, 1, 'Edna Dimaculangan', 'Briones', '09455327895', 'edna@gmail.com', '$2y$10$6xpou5xaYbmGbHpZP1NJOuPqZ8pYl4hiamwCD2BFcxfjjxYdVmrMG', '2026-06-03 04:18:32', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(10, 1, 'Glory Dimaculangan', 'Dela Cruz', '09455327895', 'glory@gmail.com', '$2y$10$V0Yo52rFD/4jxAk0EcLN5.hjlTSaW7/lbFsrs0j5pvhiC40uTjAq2', '2026-06-03 04:20:24', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(11, 1, 'Mayeth De Rama', 'Gomez', '09455327895', 'gomez@gmail.com', '$2y$10$KQ2g5O7gwdOasXnSGf9bA.LUTVdNh70lNPt/2Yi4ewGy4HftvKwPC', '2026-06-03 04:21:32', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(12, 1, 'Ma. Carmela Acopra', 'Llarena', '09455332247', 'carmela@gmail.com', '$2y$10$DZSAZ7KiZcVMIQClNHy4FulWDksJxJbvRvqDJSEkKYW1LU1EqlMyu', '2026-06-03 04:22:39', NULL, 'approved', 0, '2026-06-04 07:59:02', 0, NULL, 0, NULL),
(13, 1, 'John', 'Arnellie', '09170000001', 'john.arnellie@dmac.test', '$2y$12$GNSe7aq6wty1gQHkCb3D4uTwWq2.8ai/gMmPTGPw.uYv4WquvBxp6', '2026-06-03 17:14:55', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(14, 1, 'Marvin', 'Zoran', '09170000002', 'marvin.zoran@dmac.test', '$2y$12$GNSe7aq6wty1gQHkCb3D4uTwWq2.8ai/gMmPTGPw.uYv4WquvBxp6', '2026-06-03 17:14:55', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(15, 1, 'Mario', 'Bagusa', '09170000003', 'mario.bagusa@dmac.test', '$2y$12$GNSe7aq6wty1gQHkCb3D4uTwWq2.8ai/gMmPTGPw.uYv4WquvBxp6', '2026-06-03 17:14:55', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(16, 1, 'AeroSwift', 'Global Cargo', '09000000001', 'aeroswift@dmac.test', '$2y$10$wH6Q6wYFv7K1MzW7Zf6xQOyUCF8WpH9S1AvlL6qD9qMfUo7v5nX7e', '2026-06-03 17:36:35', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(17, 1, 'StratoFreight', 'Logistics', '09000000002', 'stratofreight@dmac.test', '$2y$10$wH6Q6wYFv7K1MzW7Zf6xQOyUCF8WpH9S1AvlL6qD9qMfUo7v5nX7e', '2026-06-03 17:36:35', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL),
(18, 1, 'JetStream', 'Courier Corp', '09000000003', 'jetstream@dmac.test', '$2y$10$wH6Q6wYFv7K1MzW7Zf6xQOyUCF8WpH9S1AvlL6qD9qMfUo7v5nX7e', '2026-06-03 17:36:35', NULL, 'approved', 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_permissions`
--

CREATE TABLE `employee_permissions` (
  `employee_permission_ID` int(11) NOT NULL,
  `emp_ID` int(11) NOT NULL,
  `permission_ID` int(11) NOT NULL,
  `granted_by_emp_ID` int(11) DEFAULT NULL,
  `granted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_permissions`
--

INSERT INTO `employee_permissions` (`employee_permission_ID`, `emp_ID`, `permission_ID`, `granted_by_emp_ID`, `granted_at`) VALUES
(1, 1, 8, 1, '2026-06-02 16:48:29'),
(2, 1, 7, 1, '2026-06-02 16:48:29'),
(3, 1, 11, 1, '2026-06-02 16:48:29'),
(4, 1, 3, 1, '2026-06-02 16:48:29'),
(5, 1, 4, 1, '2026-06-02 16:48:29'),
(6, 1, 2, 1, '2026-06-02 16:48:29'),
(7, 1, 1, 1, '2026-06-02 16:48:29'),
(8, 1, 13, 1, '2026-06-02 16:48:29'),
(9, 1, 16, 1, '2026-06-02 16:48:29'),
(10, 1, 14, 1, '2026-06-02 16:48:29'),
(11, 1, 15, 1, '2026-06-02 16:48:29'),
(12, 1, 12, 1, '2026-06-02 16:48:29'),
(13, 1, 9, 1, '2026-06-02 16:48:29'),
(14, 1, 10, 1, '2026-06-02 16:48:29'),
(15, 1, 19, 1, '2026-06-02 16:48:29'),
(16, 1, 18, 1, '2026-06-02 16:48:29'),
(17, 1, 6, 1, '2026-06-02 16:48:29'),
(18, 1, 5, 1, '2026-06-02 16:48:29'),
(81, 8, 7, NULL, '2026-06-03 04:16:58'),
(82, 8, 8, NULL, '2026-06-03 04:16:58'),
(83, 8, 11, NULL, '2026-06-03 04:16:58'),
(84, 8, 2, NULL, '2026-06-03 04:16:58'),
(85, 8, 4, NULL, '2026-06-03 04:16:58'),
(86, 9, 11, NULL, '2026-06-03 04:18:32'),
(87, 9, 2, NULL, '2026-06-03 04:18:32'),
(88, 9, 4, NULL, '2026-06-03 04:18:32'),
(89, 9, 12, NULL, '2026-06-03 04:18:32'),
(90, 9, 14, NULL, '2026-06-03 04:18:32'),
(91, 9, 9, NULL, '2026-06-03 04:18:32'),
(92, 9, 10, NULL, '2026-06-03 04:18:32'),
(93, 9, 5, NULL, '2026-06-03 04:18:32'),
(94, 9, 6, NULL, '2026-06-03 04:18:32'),
(95, 10, 2, NULL, '2026-06-03 04:20:24'),
(96, 10, 3, NULL, '2026-06-03 04:20:24'),
(97, 10, 4, NULL, '2026-06-03 04:20:24'),
(98, 10, 1, NULL, '2026-06-03 04:20:24'),
(99, 10, 12, NULL, '2026-06-03 04:20:24'),
(100, 10, 14, NULL, '2026-06-03 04:20:24'),
(101, 10, 5, NULL, '2026-06-03 04:20:24'),
(102, 10, 6, NULL, '2026-06-03 04:20:24'),
(103, 11, 7, NULL, '2026-06-03 04:21:32'),
(104, 11, 8, NULL, '2026-06-03 04:21:32'),
(105, 11, 11, NULL, '2026-06-03 04:21:32'),
(106, 11, 2, NULL, '2026-06-03 04:21:32'),
(107, 11, 4, NULL, '2026-06-03 04:21:32'),
(108, 11, 1, NULL, '2026-06-03 04:21:32'),
(109, 11, 12, NULL, '2026-06-03 04:21:32'),
(110, 11, 14, NULL, '2026-06-03 04:21:32'),
(111, 11, 5, NULL, '2026-06-03 04:21:32'),
(139, 7, 1, NULL, '2026-06-03 07:42:30'),
(140, 5, 8, NULL, '2026-06-03 14:32:21'),
(141, 5, 11, NULL, '2026-06-03 14:32:21'),
(142, 5, 3, NULL, '2026-06-03 14:32:21'),
(143, 5, 4, NULL, '2026-06-03 14:32:21'),
(144, 5, 1, NULL, '2026-06-03 14:32:21'),
(145, 5, 12, NULL, '2026-06-03 14:32:21'),
(146, 5, 9, NULL, '2026-06-03 14:32:21'),
(147, 5, 10, NULL, '2026-06-03 14:32:21'),
(148, 5, 18, NULL, '2026-06-03 14:32:21'),
(149, 5, 5, NULL, '2026-06-03 14:32:21'),
(150, 5, 6, NULL, '2026-06-03 14:32:21'),
(151, 12, 7, NULL, '2026-06-03 14:34:36'),
(152, 12, 8, NULL, '2026-06-03 14:34:36'),
(153, 12, 11, NULL, '2026-06-03 14:34:36'),
(154, 12, 2, NULL, '2026-06-03 14:34:36'),
(155, 12, 3, NULL, '2026-06-03 14:34:36'),
(156, 12, 4, NULL, '2026-06-03 14:34:36'),
(157, 12, 1, NULL, '2026-06-03 14:34:36'),
(158, 12, 12, NULL, '2026-06-03 14:34:36'),
(159, 12, 14, NULL, '2026-06-03 14:34:36'),
(160, 12, 16, NULL, '2026-06-03 14:34:36'),
(161, 12, 9, NULL, '2026-06-03 14:34:36'),
(162, 12, 10, NULL, '2026-06-03 14:34:36'),
(163, 12, 19, NULL, '2026-06-03 14:34:36'),
(164, 12, 18, NULL, '2026-06-03 14:34:36'),
(165, 12, 5, NULL, '2026-06-03 14:34:36'),
(166, 12, 6, NULL, '2026-06-03 14:34:36');

-- --------------------------------------------------------

--
-- Table structure for table `emprole`
--

CREATE TABLE `emprole` (
  `emprole_ID` int(11) NOT NULL,
  `emp_ID` int(11) NOT NULL,
  `role_ID` int(11) NOT NULL,
  `is_coordinator` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emprole`
--

INSERT INTO `emprole` (`emprole_ID`, `emp_ID`, `role_ID`, `is_coordinator`) VALUES
(1, 1, 1, 0),
(3, 4, 2, 0),
(5, 6, 2, 0),
(6, 7, 4, 1),
(7, 8, 4, 1),
(8, 9, 4, 1),
(9, 10, 4, 1),
(10, 11, 4, 1),
(12, 2, 4, 1),
(13, 2, 5, 0),
(14, 2, 2, 0),
(15, 5, 4, 1),
(16, 5, 3, 0),
(17, 12, 4, 1),
(18, 12, 3, 0),
(19, 12, 2, 0),
(20, 13, 5, 0),
(21, 15, 5, 0),
(22, 14, 5, 0),
(23, 16, 11, 0),
(24, 18, 11, 0),
(25, 17, 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_ID` int(11) NOT NULL,
  `expensecategory_ID` int(11) NOT NULL,
  `processed_by_emp_ID` int(11) NOT NULL,
  `expense_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `expense_description` text DEFAULT NULL,
  `expense_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`expense_ID`, `expensecategory_ID`, `processed_by_emp_ID`, `expense_amount`, `expense_description`, `expense_date`, `created_at`) VALUES
(1, 11, 1, 100.00, NULL, '2026-06-05', '2026-06-03 17:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `expensecategory`
--

CREATE TABLE `expensecategory` (
  `expensecategory_ID` int(11) NOT NULL,
  `categoryname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expensecategory`
--

INSERT INTO `expensecategory` (`expensecategory_ID`, `categoryname`) VALUES
(8, 'Airline Expenses'),
(14, 'Cebu Pac Expenses'),
(9, 'Consignee Per Destination'),
(2, 'Driver Allowance'),
(17, 'Employee Salary'),
(10, 'Farm Expenses'),
(1, 'Fuel'),
(12, 'Fuel Expenses'),
(18, 'Insurance Fee'),
(11, 'Land Trip Expenses'),
(13, 'Meal Expenses'),
(15, 'Miscellaneous Fee'),
(7, 'Other'),
(4, 'Packaging'),
(6, 'Permit / Document Fee'),
(16, 'Salary'),
(5, 'Vehicle Maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_ID` int(11) NOT NULL,
  `booking_ID` int(11) NOT NULL,
  `feed_rate` decimal(2,1) NOT NULL,
  `feed_comment` varchar(255) DEFAULT NULL,
  `feed_submitted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_ID`, `booking_ID`, `feed_rate`, `feed_comment`, `feed_submitted`) VALUES
(1, 12, 5.0, 'good', '2026-06-03 19:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `landdetails`
--

CREATE TABLE `landdetails` (
  `landdetails_ID` int(11) NOT NULL,
  `booking_ID` int(11) DEFAULT NULL,
  `transport_ID` int(11) NOT NULL,
  `vehicle_ID` int(11) NOT NULL,
  `emp_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landdetails`
--

INSERT INTO `landdetails` (`landdetails_ID`, `booking_ID`, `transport_ID`, `vehicle_ID`, `emp_ID`) VALUES
(1, 1, 2, 1, 2),
(2, 3, 2, 1, 2),
(3, 14, 2, 3, 14),
(4, 16, 2, 3, 15);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_ID` int(11) NOT NULL,
  `user_type` enum('client','employee','unknown') NOT NULL DEFAULT 'unknown',
  `email` varchar(255) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`attempt_ID`, `user_type`, `email`, `success`, `ip_address`, `user_agent`, `attempted_at`) VALUES
(1, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 14:46:23'),
(2, 'employee', 'gedde@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 14:54:02'),
(3, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 17:43:52'),
(4, 'employee', 'gedde@gmail.com', 0, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 17:46:30'),
(5, 'employee', 'gedde@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 17:46:34'),
(6, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 18:11:54'),
(7, 'employee', 'gedde@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 18:14:28'),
(8, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 18:20:53'),
(9, 'employee', 'gedde@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 18:21:54'),
(10, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 19:24:58'),
(11, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 19:32:01'),
(12, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 19:32:56'),
(13, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 23:50:21'),
(14, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 23:57:20'),
(15, 'employee', 'carmela@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-03 23:59:02'),
(16, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:00:53'),
(17, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:02:04'),
(18, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:05:39'),
(19, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:23:42'),
(20, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:24:03'),
(21, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:27:24'),
(22, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:33:35'),
(23, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:34:19'),
(24, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:41:09'),
(25, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:46:58'),
(26, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 00:56:18'),
(27, 'employee', 'admin@gmail.com', 0, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 01:30:20'),
(28, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 01:30:31'),
(29, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 03:25:32'),
(30, 'employee', 'admin@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 03:27:10'),
(31, 'client', 'client@gmail.com', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', '2026-06-04 03:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_ID` int(11) NOT NULL,
  `booking_ID` int(11) NOT NULL,
  `paymethod_ID` int(11) DEFAULT NULL,
  `pay_amount` decimal(10,2) DEFAULT NULL,
  `box_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pickup_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `head_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `number_of_heads` int(11) NOT NULL DEFAULT 0,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('COD','ONLINE') DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `payment_status` enum('PENDING','PAID','OVERDUE') NOT NULL DEFAULT 'PENDING',
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_reference` varchar(100) DEFAULT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_ID`, `booking_ID`, `paymethod_ID`, `pay_amount`, `box_fee`, `pickup_fee`, `shipping_fee`, `head_price`, `number_of_heads`, `total_amount`, `payment_method`, `pay_date`, `payment_status`, `amount_paid`, `payment_reference`, `proof_of_payment`, `receipt_file`, `created_at`, `updated_at`) VALUES
(1, 11, 2, 636.00, 1.00, 23.00, 300.00, 24.00, 13, 636.00, NULL, '2026-06-03', 'PAID', 0.00, '', NULL, NULL, '2026-06-02 22:49:39', '2026-06-03 00:50:46'),
(2, 5, 1, 6080.00, 200.00, 3500.00, 300.00, 20.00, 104, 6080.00, NULL, '2026-06-03', 'PAID', 0.00, '', NULL, NULL, '2026-06-02 23:06:29', '2026-06-03 07:23:05'),
(3, 12, 2, 2600.00, 100.00, 100.00, 100.00, 100.00, 23, 2600.00, NULL, NULL, 'OVERDUE', 0.00, '', NULL, NULL, '2026-06-02 23:42:47', '2026-06-03 01:42:47'),
(4, 4, 1, 150.00, 10.00, 10.00, 10.00, 10.00, 12, 150.00, NULL, NULL, 'PENDING', 0.00, '', NULL, NULL, '2026-06-02 23:46:35', '2026-06-03 01:46:35'),
(5, 15, 2, 1191.00, 100.00, 111.00, 111.00, 11.00, 79, 1191.00, NULL, '2026-06-03', 'PAID', 0.00, '', NULL, NULL, '2026-06-03 18:19:23', '2026-06-03 20:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `paymethod`
--

CREATE TABLE `paymethod` (
  `paymethod_ID` int(11) NOT NULL,
  `pay_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymethod`
--

INSERT INTO `paymethod` (`paymethod_ID`, `pay_method`) VALUES
(5, 'Bank Transfer'),
(3, 'Cash'),
(1, 'Cash on Delivery'),
(4, 'GCash'),
(6, 'ONLINE'),
(2, 'Online Payment');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_ID` int(11) NOT NULL,
  `permission_key` varchar(100) NOT NULL,
  `permission_name` varchar(150) NOT NULL,
  `permission_group` varchar(100) NOT NULL DEFAULT 'General',
  `permission_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_ID`, `permission_key`, `permission_name`, `permission_group`, `permission_description`, `created_at`) VALUES
(1, 'dashboard_view', 'View Dashboard', 'Dashboard', 'Can open staff dashboard.', '2026-06-02 16:48:29'),
(2, 'bookings_view', 'View Bookings', 'Bookings', 'Can view booking records.', '2026-06-02 16:48:29'),
(3, 'bookings_approve', 'Approve/Reject Bookings', 'Bookings', 'Can approve or reject client bookings.', '2026-06-02 16:48:29'),
(4, 'bookings_assign', 'Assign Booking/Driver', 'Bookings', 'Can assign bookings to employees/drivers.', '2026-06-02 16:48:29'),
(5, 'shipments_view', 'View Shipments', 'Shipments', 'Can view shipment records.', '2026-06-02 16:48:29'),
(6, 'shipments_update', 'Update Shipment Status', 'Shipments', 'Can update shipment process/status.', '2026-06-02 16:48:29'),
(7, 'accounting_view', 'View Accounting', 'Accounting', 'Can view payment/accounting records.', '2026-06-02 16:48:29'),
(8, 'accounting_manage', 'Manage Payments', 'Accounting', 'Can update payment status, method, and proof.', '2026-06-02 16:48:29'),
(9, 'feedback_view', 'View Feedback', 'Feedback', 'Can view client feedback.', '2026-06-02 16:48:29'),
(10, 'notifications_manage', 'Manage Notifications', 'Notifications', 'Can create/read system notifications.', '2026-06-02 16:48:29'),
(11, 'activity_logs_view', 'View Activity Logs', 'Audit Trail', 'Can view activity logs.', '2026-06-02 16:48:29'),
(12, 'employees_view', 'View Employees', 'Employees', 'Can view employee accounts.', '2026-06-02 16:48:29'),
(13, 'employees_create', 'Create Employee/Admin', 'Employees', 'Can create employee/admin accounts inside admin.', '2026-06-02 16:48:29'),
(14, 'employees_edit', 'Edit Employees', 'Employees', 'Can edit employee details and roles.', '2026-06-02 16:48:29'),
(15, 'employees_permissions', 'Assign Employee Access', 'Employees', 'Can assign permissions to employees.', '2026-06-02 16:48:29'),
(16, 'employees_deactivate', 'Deactivate Employees', 'Employees', 'Can deactivate or soft-delete employee accounts.', '2026-06-02 16:48:29'),
(18, 'settings_view', 'View Settings', 'Settings', 'Can view own settings/profile.', '2026-06-02 16:48:29'),
(19, 'reports_export', 'Export Reports', 'Reports', 'Can export reports as CSV/PDF.', '2026-06-02 16:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `pickup`
--

CREATE TABLE `pickup` (
  `pickup_ID` int(11) NOT NULL,
  `contact_firstname` varchar(255) NOT NULL,
  `contact_lastname` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `pickup_street` varchar(255) NOT NULL,
  `pickup_municipality` varchar(255) NOT NULL,
  `pickup_province` varchar(255) NOT NULL,
  `pickup_fee` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup`
--

INSERT INTO `pickup` (`pickup_ID`, `contact_firstname`, `contact_lastname`, `contact_number`, `pickup_street`, `pickup_municipality`, `pickup_province`, `pickup_fee`) VALUES
(1, 'Jean', 'Valenzuela', '09123412341', 'Lipa City', 'Batangas', 'Lipa', 0.00),
(2, 'Gedde', 'Panget', '09123412341', 'Tite', 'ko', 'what?', 0.00),
(3, 'chimbi', 'dibi', '0932323222', 'Lipa', 'Cirty', 'batangas', 0.00),
(4, 'client', 'try', '123456789101', 'Lipa', 'Cirty', 'batangas', 0.00),
(5, 'asdasd', 'dasda', '121212121', 'assdad', 'ddddd', 'dsdsd', 0.00),
(11, 'chimbi', 'dibi', '09455332247', 'Santor', 'Batangas', 'batangas', 0.00),
(12, 'chimbi', 'dibi', '09455332247', 'Lipa', 'ddddd', 'batangas', 0.00),
(13, 'chimbi', 'try', '09455332247', 'Lipa', 'Cirty', 'batangas', 0.00),
(14, 'Jean', 'valnezuela', '09455332247', 'Lipa', 'ddddd', 'batangas', 0.00),
(15, 'Ryzza', 'Dimanno', '09455330003', 'Bagong Pook', 'Lipa', 'Batangas', 0.00),
(16, 'Sean', 'Palicpic', '09455332247', 'Lipa', 'City', 'Batangas', 0.00),
(17, 'chimbi', 'dibi', '09455332247', 'Lipa', 'Batangas', 'batangas', 0.00),
(18, 'gedde', 'ahrtenz', '09434312231', 'santor', 'Batangas', 'batangas', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `receiver`
--

CREATE TABLE `receiver` (
  `receiver_ID` int(11) NOT NULL,
  `receiver_firstname` varchar(255) NOT NULL,
  `receiver_lastname` varchar(255) NOT NULL,
  `receiver_contact` varchar(20) NOT NULL,
  `receiver_street` varchar(255) NOT NULL,
  `receiver_municipality` varchar(255) NOT NULL,
  `receiver_province` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receiver`
--

INSERT INTO `receiver` (`receiver_ID`, `receiver_firstname`, `receiver_lastname`, `receiver_contact`, `receiver_street`, `receiver_municipality`, `receiver_province`) VALUES
(1, 'Rheeze', 'Zara', '09123412341', 'Dikoalam Street', 'Batangas', 'Lipa City'),
(2, 'Jean', 'Ayet', '09123412343', 'idk', 'what', 'wow'),
(3, 'Me', 'And', '09434423233', 'lipa', 'lipa', 'batangas'),
(4, 'Me', 'And', '232323232222', 'inos', 'lipa', 'manila'),
(5, 'sdadasd', 'asdasdasd', '345345343', 'asdasda', 'asdasd', 'asdasd'),
(11, 'Me', 'And', '09434423233', 'lipa', 'Batangas City', 'Batangas'),
(12, 'Me', 'And', '09434423233', 'inos', 'Batangas City', 'Batangas'),
(13, 'Me', 'And', '09434423233', 'lipa', 'Lemery', 'Batangas'),
(14, 'Tita', 'gladys', '09434423233', 'padre garcia', 'Rosario', 'Batangas'),
(15, 'Gedde', 'palicpic', '09434423233', 'Inosloban', 'Lipa City', 'Batangas'),
(16, 'Gedde', 'Palicpic', '09434423233', 'lipa', 'Los Baños', 'Laguna'),
(17, 'Me', 'And', '09434423233', 'lipa', 'Baao', 'Camsur'),
(18, 'Gedde', 'palicpic', '09345432424', 'inos', 'Sorsogon City', 'Albay');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_ID` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_ID`, `role`) VALUES
(1, 'ADMIN'),
(4, 'Coordinator'),
(5, 'Driver'),
(3, 'GENERAL MANAGER'),
(2, 'IT'),
(11, 'Shipper Agent');

-- --------------------------------------------------------

--
-- Table structure for table `transport`
--

CREATE TABLE `transport` (
  `transport_ID` int(11) NOT NULL,
  `transport_mode` enum('AIR','LAND') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport`
--

INSERT INTO `transport` (`transport_ID`, `transport_mode`) VALUES
(1, 'AIR'),
(2, 'LAND');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_ID` int(11) NOT NULL,
  `vehicle_type` varchar(100) NOT NULL,
  `vehicle_platenumber` varchar(50) NOT NULL,
  `vehicle_licensepermit` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_ID`, `vehicle_type`, `vehicle_platenumber`, `vehicle_licensepermit`) VALUES
(1, 'Land Transport Vehicle', 'DAE 3456', 'BAI-LSP-2026-88301'),
(2, 'Land Transport Vehicle', 'NDZ 1234', 'BAI-LSP-2026-88331'),
(3, 'Land Transport Vehicle', 'DBS 0785', 'BAI-LSP-2026-88351');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_booking_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_booking_summary` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_employee_access`
-- (See below for the actual view)
--
CREATE TABLE `vw_employee_access` (
`emp_ID` int(11)
,`emp_firstname` varchar(255)
,`emp_lastname` varchar(255)
,`emp_email` varchar(255)
,`emp_contact` varchar(20)
,`account_status` enum('pending','approved','rejected')
,`is_super_admin` tinyint(1)
,`deleted_at` timestamp
,`dept_name` varchar(255)
,`roles` mediumtext
,`permission_keys` mediumtext
);

-- --------------------------------------------------------

--
-- Structure for view `vw_booking_summary`
--
DROP TABLE IF EXISTS `vw_booking_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_booking_summary`  AS SELECT `b`.`booking_ID` AS `booking_ID`, `b`.`client_ID` AS `client_ID`, concat(`c`.`client_firstname`,' ',`c`.`client_lastname`) AS `client_name`, `c`.`client_email` AS `client_email`, `b`.`booking_status` AS `booking_status`, `b`.`booking_startdate` AS `booking_startdate`, `b`.`booking_requestdate` AS `booking_requestdate`, `b`.`booking_enddate` AS `booking_enddate`, concat(`pu`.`contact_firstname`,' ',`pu`.`contact_lastname`) AS `pickup_contact`, concat(`pu`.`pickup_street`,', ',`pu`.`pickup_municipality`,', ',`pu`.`pickup_province`) AS `pickup_address`, concat(`r`.`receiver_firstname`,' ',`r`.`receiver_lastname`) AS `receiver_name`, concat(`r`.`receiver_street`,', ',`r`.`receiver_municipality`,', ',`r`.`receiver_province`) AS `receiver_address`, `pay`.`payment_status` AS `payment_status`, `pay`.`pay_amount` AS `pay_amount`, `pay`.`amount_paid` AS `amount_paid`, `pay`.`pay_date` AS `pay_date`, `pay`.`paid_at` AS `paid_at` FROM ((((`booking` `b` join `client` `c` on(`b`.`client_ID` = `c`.`client_ID`)) join `pickup` `pu` on(`b`.`pickup_ID` = `pu`.`pickup_ID`)) join `receiver` `r` on(`b`.`receiver_ID` = `r`.`receiver_ID`)) left join `payment` `pay` on(`b`.`booking_ID` = `pay`.`booking_ID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_employee_access`
--
DROP TABLE IF EXISTS `vw_employee_access`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_employee_access`  AS SELECT `e`.`emp_ID` AS `emp_ID`, `e`.`emp_firstname` AS `emp_firstname`, `e`.`emp_lastname` AS `emp_lastname`, `e`.`emp_email` AS `emp_email`, `e`.`emp_contact` AS `emp_contact`, `e`.`account_status` AS `account_status`, `e`.`is_super_admin` AS `is_super_admin`, `e`.`deleted_at` AS `deleted_at`, `d`.`dept_name` AS `dept_name`, group_concat(distinct `r`.`role` order by `r`.`role` ASC separator ', ') AS `roles`, group_concat(distinct `p`.`permission_key` order by `p`.`permission_key` ASC separator ',') AS `permission_keys` FROM (((((`employee` `e` left join `department` `d` on(`e`.`dept_ID` = `d`.`dept_ID`)) left join `emprole` `er` on(`e`.`emp_ID` = `er`.`emp_ID`)) left join `roles` `r` on(`er`.`role_ID` = `r`.`role_ID`)) left join `employee_permissions` `ep` on(`e`.`emp_ID` = `ep`.`emp_ID`)) left join `permissions` `p` on(`ep`.`permission_ID` = `p`.`permission_ID`)) GROUP BY `e`.`emp_ID`, `e`.`emp_firstname`, `e`.`emp_lastname`, `e`.`emp_email`, `e`.`emp_contact`, `e`.`account_status`, `e`.`is_super_admin`, `e`.`deleted_at`, `d`.`dept_name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_activity_user` (`user_type`,`user_id`),
  ADD KEY `idx_activity_created` (`created_at`);

--
-- Indexes for table `airdetails`
--
ALTER TABLE `airdetails`
  ADD PRIMARY KEY (`airdetails_ID`),
  ADD KEY `fk_air_transport` (`transport_ID`),
  ADD KEY `fk_air_employee` (`emp_ID`),
  ADD KEY `idx_airdetails_booking` (`booking_ID`);

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`animal_ID`),
  ADD UNIQUE KEY `animal_type` (`animal_type`);

--
-- Indexes for table `animalbatch`
--
ALTER TABLE `animalbatch`
  ADD PRIMARY KEY (`animalbatch_ID`),
  ADD KEY `fk_batch_booking` (`booking_ID`),
  ADD KEY `fk_batch_animal` (`animal_ID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_ID`),
  ADD KEY `fk_booking_client` (`client_ID`),
  ADD KEY `fk_booking_pickup` (`pickup_ID`),
  ADD KEY `fk_booking_receiver` (`receiver_ID`),
  ADD KEY `fk_booking_transport` (`transport_ID`),
  ADD KEY `idx_booking_status` (`booking_status`),
  ADD KEY `idx_booking_client` (`client_ID`),
  ADD KEY `idx_booking_dates` (`booking_startdate`,`booking_requestdate`,`booking_enddate`);

--
-- Indexes for table `bookingassignment`
--
ALTER TABLE `bookingassignment`
  ADD PRIMARY KEY (`bookingassignment_ID`),
  ADD KEY `fk_assignment_booking` (`booking_ID`),
  ADD KEY `fk_assignment_employee` (`emp_ID`),
  ADD KEY `idx_bookingassignment_booking` (`booking_ID`),
  ADD KEY `idx_bookingassignment_employee` (`emp_ID`),
  ADD KEY `idx_bookingassignment_stage` (`process_stage`),
  ADD KEY `idx_bookingassignment_assigned_by` (`assigned_by_emp_ID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_ID`),
  ADD UNIQUE KEY `client_email` (`client_email`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_ID`),
  ADD UNIQUE KEY `emp_email` (`emp_email`),
  ADD KEY `fk_employee_department` (`dept_ID`),
  ADD KEY `idx_employee_status` (`account_status`),
  ADD KEY `idx_employee_super_admin` (`is_super_admin`);

--
-- Indexes for table `employee_permissions`
--
ALTER TABLE `employee_permissions`
  ADD PRIMARY KEY (`employee_permission_ID`),
  ADD UNIQUE KEY `unique_emp_permission` (`emp_ID`,`permission_ID`),
  ADD KEY `idx_employee_permissions_emp` (`emp_ID`),
  ADD KEY `idx_employee_permissions_permission` (`permission_ID`),
  ADD KEY `fk_emp_permissions_granted_by` (`granted_by_emp_ID`);

--
-- Indexes for table `emprole`
--
ALTER TABLE `emprole`
  ADD PRIMARY KEY (`emprole_ID`),
  ADD UNIQUE KEY `idx_emp_role` (`emp_ID`,`role_ID`),
  ADD KEY `fk_emprole_roles` (`role_ID`),
  ADD KEY `idx_emprole_is_coordinator` (`is_coordinator`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_ID`),
  ADD KEY `fk_expense_category` (`expensecategory_ID`),
  ADD KEY `fk_expense_employee` (`processed_by_emp_ID`),
  ADD KEY `idx_expense_date` (`expense_date`),
  ADD KEY `idx_expense_category` (`expensecategory_ID`),
  ADD KEY `idx_expense_processed_by` (`processed_by_emp_ID`);

--
-- Indexes for table `expensecategory`
--
ALTER TABLE `expensecategory`
  ADD PRIMARY KEY (`expensecategory_ID`),
  ADD UNIQUE KEY `categoryname` (`categoryname`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_ID`),
  ADD KEY `fk_feedback_booking` (`booking_ID`),
  ADD KEY `idx_feedback_booking` (`booking_ID`),
  ADD KEY `idx_feedback_submitted` (`feed_submitted`);

--
-- Indexes for table `landdetails`
--
ALTER TABLE `landdetails`
  ADD PRIMARY KEY (`landdetails_ID`),
  ADD KEY `fk_land_transport` (`transport_ID`),
  ADD KEY `fk_land_vehicle` (`vehicle_ID`),
  ADD KEY `fk_land_employee` (`emp_ID`),
  ADD KEY `idx_landdetails_booking` (`booking_ID`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_ID`),
  ADD KEY `idx_login_attempt_email` (`email`),
  ADD KEY `idx_login_attempt_time` (`attempted_at`),
  ADD KEY `idx_login_attempt_user_type` (`user_type`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_ID`),
  ADD KEY `fk_payment_booking` (`booking_ID`),
  ADD KEY `fk_payment_method` (`paymethod_ID`),
  ADD KEY `idx_payment_booking` (`booking_ID`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_payment_date` (`pay_date`);

--
-- Indexes for table `paymethod`
--
ALTER TABLE `paymethod`
  ADD PRIMARY KEY (`paymethod_ID`),
  ADD UNIQUE KEY `pay_method` (`pay_method`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_ID`),
  ADD UNIQUE KEY `unique_permission_key` (`permission_key`);

--
-- Indexes for table `pickup`
--
ALTER TABLE `pickup`
  ADD PRIMARY KEY (`pickup_ID`);

--
-- Indexes for table `receiver`
--
ALTER TABLE `receiver`
  ADD PRIMARY KEY (`receiver_ID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_ID`),
  ADD UNIQUE KEY `role` (`role`);

--
-- Indexes for table `transport`
--
ALTER TABLE `transport`
  ADD PRIMARY KEY (`transport_ID`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_ID`),
  ADD UNIQUE KEY `vehicle_platenumber` (`vehicle_platenumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `airdetails`
--
ALTER TABLE `airdetails`
  MODIFY `airdetails_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `animal_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `animalbatch`
--
ALTER TABLE `animalbatch`
  MODIFY `animalbatch_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bookingassignment`
--
ALTER TABLE `bookingassignment`
  MODIFY `bookingassignment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `client_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employee_permissions`
--
ALTER TABLE `employee_permissions`
  MODIFY `employee_permission_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `emprole`
--
ALTER TABLE `emprole`
  MODIFY `emprole_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expensecategory`
--
ALTER TABLE `expensecategory`
  MODIFY `expensecategory_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `landdetails`
--
ALTER TABLE `landdetails`
  MODIFY `landdetails_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `paymethod`
--
ALTER TABLE `paymethod`
  MODIFY `paymethod_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pickup`
--
ALTER TABLE `pickup`
  MODIFY `pickup_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `receiver`
--
ALTER TABLE `receiver`
  MODIFY `receiver_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transport`
--
ALTER TABLE `transport`
  MODIFY `transport_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `airdetails`
--
ALTER TABLE `airdetails`
  ADD CONSTRAINT `fk_air_employee` FOREIGN KEY (`emp_ID`) REFERENCES `employee` (`emp_ID`),
  ADD CONSTRAINT `fk_air_transport` FOREIGN KEY (`transport_ID`) REFERENCES `transport` (`transport_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_airdetails_booking` FOREIGN KEY (`booking_ID`) REFERENCES `booking` (`booking_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `animalbatch`
--
ALTER TABLE `animalbatch`
  ADD CONSTRAINT `fk_batch_animal` FOREIGN KEY (`animal_ID`) REFERENCES `animal` (`animal_ID`),
  ADD CONSTRAINT `fk_batch_booking` FOREIGN KEY (`booking_ID`) REFERENCES `booking` (`booking_ID`) ON DELETE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_client` FOREIGN KEY (`client_ID`) REFERENCES `client` (`client_ID`),
  ADD CONSTRAINT `fk_booking_pickup` FOREIGN KEY (`pickup_ID`) REFERENCES `pickup` (`pickup_ID`),
  ADD CONSTRAINT `fk_booking_receiver` FOREIGN KEY (`receiver_ID`) REFERENCES `receiver` (`receiver_ID`),
  ADD CONSTRAINT `fk_booking_transport` FOREIGN KEY (`transport_ID`) REFERENCES `transport` (`transport_ID`);

--
-- Constraints for table `bookingassignment`
--
ALTER TABLE `bookingassignment`
  ADD CONSTRAINT `fk_assignment_booking` FOREIGN KEY (`booking_ID`) REFERENCES `booking` (`booking_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assignment_employee` FOREIGN KEY (`emp_ID`) REFERENCES `employee` (`emp_ID`),
  ADD CONSTRAINT `fk_bookingassignment_assigned_by` FOREIGN KEY (`assigned_by_emp_ID`) REFERENCES `employee` (`emp_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `fk_employee_department` FOREIGN KEY (`dept_ID`) REFERENCES `department` (`dept_ID`);

--
-- Constraints for table `employee_permissions`
--
ALTER TABLE `employee_permissions`
  ADD CONSTRAINT `fk_emp_permissions_employee` FOREIGN KEY (`emp_ID`) REFERENCES `employee` (`emp_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_emp_permissions_granted_by` FOREIGN KEY (`granted_by_emp_ID`) REFERENCES `employee` (`emp_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_emp_permissions_permission` FOREIGN KEY (`permission_ID`) REFERENCES `permissions` (`permission_ID`) ON DELETE CASCADE;

--
-- Constraints for table `emprole`
--
ALTER TABLE `emprole`
  ADD CONSTRAINT `fk_emprole_employee` FOREIGN KEY (`emp_ID`) REFERENCES `employee` (`emp_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_emprole_roles` FOREIGN KEY (`role_ID`) REFERENCES `roles` (`role_ID`);

--
-- Constraints for table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `fk_expense_category` FOREIGN KEY (`expensecategory_ID`) REFERENCES `expensecategory` (`expensecategory_ID`),
  ADD CONSTRAINT `fk_expense_employee` FOREIGN KEY (`processed_by_emp_ID`) REFERENCES `employee` (`emp_ID`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_booking` FOREIGN KEY (`booking_ID`) REFERENCES `booking` (`booking_ID`) ON DELETE CASCADE;

--
-- Constraints for table `landdetails`
--
ALTER TABLE `landdetails`
  ADD CONSTRAINT `fk_land_employee` FOREIGN KEY (`emp_ID`) REFERENCES `employee` (`emp_ID`),
  ADD CONSTRAINT `fk_land_transport` FOREIGN KEY (`transport_ID`) REFERENCES `transport` (`transport_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_land_vehicle` FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicle` (`vehicle_ID`),
  ADD CONSTRAINT `fk_landdetails_booking` FOREIGN KEY (`booking_ID`) REFERENCES `booking` (`booking_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_ID`) REFERENCES `booking` (`booking_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_payment_method` FOREIGN KEY (`paymethod_ID`) REFERENCES `paymethod` (`paymethod_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
