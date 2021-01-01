-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2019 at 09:18 AM
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
-- Table structure for table `h_type_bed`
--

CREATE TABLE IF NOT EXISTS `h_type_bed` (
  `h_type_bed_id` int(11) NOT NULL,
  `h_type_bed_name` varchar(50) NOT NULL,
  `h_type_bed_desc` varchar(128) NOT NULL,
  `h_type_bed_image` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_type_bed`
--

INSERT INTO `h_type_bed` (`h_type_bed_id`, `h_type_bed_name`, `h_type_bed_desc`, `h_type_bed_image`) VALUES
(1, 'Single', 'เตียงเดี่ยว', '../system/upload_icon/5d5bb1f2b8104-bed_single.png'),
(2, 'Twin', '2เตียงเดี่ยว', '../system/upload_icon/5d649d33d9c78-bed_twin.png'),
(3, 'Double', 'เตียงคู่', '../system/upload_icon/5d5e6d1fd5d42-bed.png'),
(4, 'Triple', '1เตียงคู่ 1 เตียงเดี่ยว', '../system/upload_icon/5d649e6dd0c2f-bed_triple.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `h_type_bed`
--
ALTER TABLE `h_type_bed`
  ADD PRIMARY KEY (`h_type_bed_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `h_type_bed`
--
ALTER TABLE `h_type_bed`
  MODIFY `h_type_bed_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
