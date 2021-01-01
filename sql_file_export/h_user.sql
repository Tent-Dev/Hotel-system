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
-- Table structure for table `h_user`
--

CREATE TABLE IF NOT EXISTS `h_user` (
  `h_user_id` int(11) NOT NULL,
  `h_user_username` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `h_user_password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `h_user_rank` int(11) NOT NULL DEFAULT '0',
  `h_user_firstname` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `h_user_lastname` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `h_user_image` text COLLATE utf16_unicode_ci NOT NULL,
  `h_user_dateregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `h_user`
--

INSERT INTO `h_user` (`h_user_id`, `h_user_username`, `h_user_password`, `h_user_rank`, `h_user_firstname`, `h_user_lastname`, `h_user_image`, `h_user_dateregister`) VALUES
(83, 'admin', '$2y$12$lT3.un9SzkVHbkt7OgDvdOW5Km1PL0Ztj2z52YDk47P16bpLRlDZe', 1, 'Chutipas', 'Borsub', '../system/upload_profile/5d5f5fbbb0730-mypic.jpg', '2019-08-08 07:20:47'),
(108, 'Clerk01', '$2y$12$idT.PfpJ1qJxkjSBIHkOl.DXXVY2kM8MTRmOwgs2az5VoC7LxYo56', 6, 'Namjai', 'Maitee', '', '2019-08-27 02:54:23'),
(109, 'Operator01', '$2y$12$i9Al8YfOJuKTCHvh9dHj8uCi4RrU680kX/dztGboPJo5X.yTt6PyG', 7, 'Manee', 'Aroina', '', '2019-08-27 02:55:01'),
(110, 'Operator02', '$2y$12$f29f1quneCxPLQ.utK7ebe4QMyZ2hPI9CsWkWN3lkckBs0naVqSlu', 7, 'Namthip', 'Cola', '', '2019-08-28 09:04:47'),
(111, 'Operator03', '$2y$12$pyw5JOfNaZkfQw2NqTvmFe47aVzhlIZfhmMQdW4DNS3bPqe5DUfgm', 7, 'Kailey', 'Feest', '', '2019-08-28 09:08:04'),
(112, 'Accounting01', '$2y$12$mwFqcKmAolcF9pvGqWF9he..g09UtX1w4ld3YCxsyA.5uyqPng.F6', 4, 'Dorris', 'Anissa', '', '2019-08-28 09:09:01'),
(113, 'Accounting02', '$2y$12$ZtPsI6LZRIIAPXW74/6gY.QMo8T/SCoNxIJLTAp6mMwfk6DgLPZCy', 4, 'Maybell', 'Schuster', '', '2019-08-28 09:09:22'),
(114, 'Cashier01', '$2y$12$81K7gALC5B7BhPoLq/GHbePN7qWTvBElawIwUoBG7F0/Da2nPF.YS', 3, 'Amaya', 'Sarah', '', '2019-08-28 09:09:51'),
(115, 'Cashier02', '$2y$12$nJUODbelbFnFTsC9Mz.02OXoGI5DEozv9rbFlz25NsX.eZjD8ngKG', 3, 'Flavie', 'Crist', '', '2019-08-28 09:10:13'),
(116, 'Assistant01', '$2y$12$9Hcv4T5x6XhBiO7nvlOySO2p8ISI4iVFLvpVj7oXiTpX/oYIhXa5y', 5, 'Tate', 'Marks', '', '2019-08-28 09:17:02'),
(117, 'Assistant02', '$2y$12$cdVSwxuTjhTVYvuOeIw9zuIzn4BST..Pxw0ZhzLghrGLTp4fhs.CG', 5, 'Mazie', 'Lehner', '', '2019-08-28 09:17:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `h_user`
--
ALTER TABLE `h_user`
  ADD PRIMARY KEY (`h_user_id`),
  ADD KEY `fk_rank` (`h_user_rank`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `h_user`
--
ALTER TABLE `h_user`
  MODIFY `h_user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=118;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `h_user`
--
ALTER TABLE `h_user`
  ADD CONSTRAINT `fk_rank` FOREIGN KEY (`h_user_rank`) REFERENCES `h_userrank` (`h_userrank_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
