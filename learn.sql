-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2021 at 03:46 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `learn`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `id` int(11) NOT NULL,
  `batchcode` varchar(256) NOT NULL,
  `class` int(11) NOT NULL,
  `school` varchar(256) NOT NULL,
  `students` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cheatlog`
--

CREATE TABLE `cheatlog` (
  `id` int(11) NOT NULL,
  `cheatid` varchar(256) NOT NULL,
  `userid` varchar(256) NOT NULL,
  `examid` varchar(256) NOT NULL,
  `submissionid` varchar(256) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `examid` varchar(256) NOT NULL,
  `batchcode` varchar(256) NOT NULL,
  `examname` varchar(256) NOT NULL,
  `questionpaper` varchar(256) NOT NULL,
  `fullmarks` int(11) NOT NULL,
  `examduration` int(11) NOT NULL COMMENT 'in minutes',
  `submissionduration` int(11) NOT NULL COMMENT 'in minutes',
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 for old, 1 for scheduled, 2 for ongoing, 3 for checking, 4 for deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `passwordresetreq`
--

CREATE TABLE `passwordresetreq` (
  `id` int(11) NOT NULL,
  `userid` varchar(256) NOT NULL,
  `requested_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `accepted_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 for pending, 1 for accepted, 2 for drop'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `submissionid` varchar(256) NOT NULL,
  `userid` varchar(256) NOT NULL,
  `examname` varchar(256) NOT NULL,
  `examid` varchar(256) NOT NULL,
  `fullmarks` int(11) NOT NULL,
  `timeleft` int(11) DEFAULT NULL,
  `submissiontimeleft` int(11) DEFAULT NULL,
  `answerscript` varchar(256) DEFAULT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 for not attempted, 1 for submitted, 2 for ongoing, 3 for not appearing, 4 for upload pending, 5 for not uploaded',
  `resultDivision` varchar(256) DEFAULT NULL,
  `result` varchar(256) DEFAULT NULL,
  `resulttext` varchar(4000) DEFAULT NULL,
  `resultviewallowed` int(11) NOT NULL DEFAULT 0 COMMENT '0 for not allowed, 1 for allowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `userbase`
--

CREATE TABLE `userbase` (
  `id` int(11) NOT NULL,
  `userid` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` varchar(256) NOT NULL DEFAULT 'student',
  `batch` varchar(256) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 for inactive, 1 for active, 2 for pw change accepted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userbase`
--

INSERT INTO `userbase` (`id`, `userid`, `email`, `name`, `phone`, `password`, `role`, `batch`, `status`) VALUES
(1, 'admin', 'admin@soumit.in', 'Admin', '8927876631', 'f83ef041dff59b0ef0e5fb883ef23ca6', 'admin', 'admin', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheatlog`
--
ALTER TABLE `cheatlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwordresetreq`
--
ALTER TABLE `passwordresetreq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userbase`
--
ALTER TABLE `userbase`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheatlog`
--
ALTER TABLE `cheatlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passwordresetreq`
--
ALTER TABLE `passwordresetreq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userbase`
--
ALTER TABLE `userbase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
