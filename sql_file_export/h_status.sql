-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2019 at 09:19 AM
-- Server version: 5.6.37
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myport`
--

-- --------------------------------------------------------

--
-- Table structure for table `h_status`
--

CREATE TABLE IF NOT EXISTS `h_status` (
  `h_status_id` int(11) NOT NULL,
  `h_status_name` varchar(128) DEFAULT NULL,
  `h_status_statustouser` int(11) NOT NULL DEFAULT '1',
  `h_status_color` varchar(10) NOT NULL DEFAULT '#FFFFFF'
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_status`
--

INSERT INTO `h_status` (`h_status_id`, `h_status_name`, `h_status_statustouser`, `h_status_color`) VALUES
(1, 'Open', 1, '#FFFFFF'),
(2, 'Maintenance', 2, '#F8D7DA'),
(14, 'VIP', 2, '#F1C40F'),
(15, 'Lucky room', 1, '#2ECC71');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `h_status`
--
ALTER TABLE `h_status`
  ADD PRIMARY KEY (`h_status_id`),
  ADD KEY `h_status_statustouser` (`h_status_statustouser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `h_status`
--
ALTER TABLE `h_status`
  MODIFY `h_status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `h_status`
--
ALTER TABLE `h_status`
  ADD CONSTRAINT `h_status_ibfk_1` FOREIGN KEY (`h_status_statustouser`) REFERENCES `h_statustouser` (`h_statustouser_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
