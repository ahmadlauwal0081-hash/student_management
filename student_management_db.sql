-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2026 at 02:59 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `registration_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) CHARACTER SET ascii DEFAULT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'student',
  `profile_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scanned_document` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `registration_number`, `full_name`, `email`, `department`, `level`, `created_at`, `password`, `role`, `profile_image`, `scanned_document`) VALUES
(10, '', '', 'ahmadlauwal0081@gmail.com', '', 0, '2026-11-09 12:05:47', '$2y$10$NLH8Biqt9g7uMfv8ZWu75u1b8IB9K5ZAt/isPqJW45/ojyoO4A0oC', 'admin', NULL, NULL),
(15, 'UG/2610204060', 'Aisha Marafa', 'aishamarafabello@gmail.com', 'COMPUTER SCIENCE', 100, '2026-11-09 16:08:32', '$2y$10$ICqmJgjHvQ/BP82I13CTcubIG80v17.qu/e6/ssrCslEpinbNalCW', 'student', 'profile_6af3a85502e20.png', NULL),
(16, '', 'Ibrahim Musa', 'ibrahimmusa@gmail.com', '', 0, '2026-11-09 16:47:57', '$2y$10$v90QU8.7P3ee52CgS1mMzOVgNYrbG2/cQYSGzKKdcS0KDsnd4aT/K', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `id` int(11) NOT NULL,
  `student_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `session_year` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `document_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'Pending',
  `admin_remark` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `student_documents`
--

INSERT INTO `student_documents` (`id`, `student_email`, `session_year`, `document_type`, `file_name`, `uploaded_at`, `status`, `admin_remark`) VALUES
(14, 'aishamarafabello@gmail.com', '2024/2025 (Year 1)', 'JAMB Original Result', 'doc_6afddb55820d1.png', '2026-11-18 17:07:33', 'Pending', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
