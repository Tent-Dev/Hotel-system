-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2019 at 11:38 AM
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
-- Table structure for table `h_bill`
--

CREATE TABLE IF NOT EXISTS `h_bill` (
  `h_bill_id` int(11) NOT NULL,
  `h_bill_customerid` int(11) DEFAULT '0',
  `h_bill_paymenttypeid` int(11) DEFAULT '0',
  `h_bill_rateid` int(11) DEFAULT '0',
  `h_bill_session` varchar(128) DEFAULT NULL,
  `h_bill_checkin` date DEFAULT NULL,
  `h_bill_checkout` date DEFAULT NULL,
  `h_bill_guests` int(11) DEFAULT '0',
  `h_bill_rooms` int(11) DEFAULT '0',
  `h_bill_price` float DEFAULT '0',
  `h_bill_otherprice` float DEFAULT NULL,
  `h_bill_copy` longtext,
  `h_bill_special` varchar(255) DEFAULT NULL,
  `h_bill_status` int(11) NOT NULL DEFAULT '1',
  `h_bill_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_bill`
--

INSERT INTO `h_bill` (`h_bill_id`, `h_bill_customerid`, `h_bill_paymenttypeid`, `h_bill_rateid`, `h_bill_session`, `h_bill_checkin`, `h_bill_checkout`, `h_bill_guests`, `h_bill_rooms`, `h_bill_price`, `h_bill_otherprice`, `h_bill_copy`, `h_bill_special`, `h_bill_status`, `h_bill_timestamp`) VALUES
(1, 2, 5, 11, 'W2HLV7ZA58IUTY9', '2019-08-28', '2019-08-29', 3, 3, 11235, 0, '{"product_list":[{"product":"Single Room","qty":"1","price":"1500","total":"1500"},{"product":"Medium Room","qty":"2","price":"4500","total":"9000"}],"company_info":[{"company_tax_payer_no":"0901108123442","company_name":"Tent''s Hotel reservation System Co.,Ltd.","company_address":"235/233 Pattanakarn Rd. Bangkok 10250","company_tel":"(+66)830884161","company_email":"Tent.Reservation@tni.ac.th"}],"date_print":"Wed 28-Aug-2019 | 10:03:18 AM UTC(+07:00)","sub_total":"10500","discount":"0","discount_type":"None","total":"10500","vat":"7","vat_amount":"735","grand_total":"11235","rate_select":"Best Flexible Rate","session":"W2HLV7ZA58IUTY9","checkin":"28-Aug-2019","checkout":"29-Aug-2019","firstname":"E-TENT","lastname":"Studio","id":2}', '-', 3, '2019-08-28 03:02:35'),
(2, 3, 5, 11, 'RSAV73DW8ML19UQ', '2019-08-28', '2019-08-29', 2, 2, 7490, 0, '{"product_list":[{"product":"Small Room","qty":"1","price":"2500","total":"2500"},{"product":"Medium Room","qty":"1","price":"4500","total":"4500"}],"company_info":[{"company_tax_payer_no":"0901108123442","company_name":"Tent''s Hotel reservation System Co.,Ltd.","company_address":"235/233 Pattanakarn Rd. Bangkok 10250","company_tel":"(+66)830884161","company_email":"Tent.Reservation@tni.ac.th"}],"date_print":"Wed 28-Aug-2019 | 10:07:36 AM UTC(+07:00)","sub_total":"7000","discount":"0","discount_type":"None","total":"7000","vat":"7","vat_amount":"490","grand_total":"7490","rate_select":"Best Flexible Rate","session":"RSAV73DW8ML19UQ","checkin":"28-Aug-2019","checkout":"29-Aug-2019","firstname":"E-TENT","lastname":"Studio","id":3}', '-', 3, '2019-08-28 03:06:50'),
(3, 4, 5, 11, '4QGFC1OIXVUYARB', '2019-08-28', '2019-08-29', 2, 2, 7490, 0, '{"date_print":"Wed 28-Aug-2019 | 10:18:39 AM UTC(+07:00)","sub_total":"7000","discount":"0","discount_type":"None","total":"7000","vat":"7","vat_amount":"490","grand_total":"7490","rate_select":"Best Flexible Rate","session":"4QGFC1OIXVUYARB","checkin":"28-Aug-2019","checkout":"29-Aug-2019","firstname":"E-TENT","lastname":"Studio","id":4,"product_list":[{"product":"Small Room","qty":"1","price":"2500","total":"2500"},{"product":"Medium Room","qty":"1","price":"4500","total":"4500"}],"company_info":[{"company_tax_payer_no":"0901108123442","company_name":"Tent''s Hotel reservation System Co.,Ltd.","company_address":"235/233 Pattanakarn Rd. Bangkok 10250","company_tel":"(+66)830884161","company_email":"Tent.Reservation@tni.ac.th"}]}', '-', 3, '2019-08-28 03:16:00'),
(4, 5, 5, 11, 'Z9NPGH87DRVYCIX', '2019-09-04', '2019-09-05', 1, 1, 2675, NULL, NULL, '-', 4, '2019-09-04 11:48:06'),
(5, 6, 5, 11, 'GYW1ZR4LHB2N8D9', '2019-09-04', '2019-09-05', 1, 1, 2675, NULL, NULL, '-', 1, '2019-09-04 11:52:44'),
(6, 7, 1, 12, 'FUEWXHI6YSGPVJ2', '2019-09-05', '2019-09-06', 1, 1, 535, NULL, NULL, '-', 4, '2019-09-05 03:19:52'),
(7, 8, 5, 16, 'NR3LTVZ2BQWAXPO', '2019-09-05', '2019-09-06', 1, 1, 1337.5, NULL, NULL, '-', 1, '2019-09-05 03:41:37'),
(8, 9, 2, 12, 'NPX1GYZQHC0KWF4', '2019-09-09', '2019-09-10', 1, 1, 1605, NULL, NULL, '-', 1, '2019-09-09 04:37:31'),
(9, 10, 1, 12, '69BVK72Y4J3ACGF', '2019-09-09', '2019-09-10', 1, 1, 1605, NULL, NULL, '-', 1, '2019-09-09 05:24:44'),
(10, 11, 1, 12, '8ER543ZTABQOY12', '2019-09-09', '2019-09-10', 1, 1, 1605, NULL, NULL, 'f', 1, '2019-09-09 10:21:44'),
(11, 12, 1, 12, '7UK94ION6LTAZJE', '2019-09-09', '2019-09-10', 1, 1, 1605, NULL, NULL, 'f', 2, '2019-09-09 10:21:53'),
(12, 13, 1, 12, '38FVEQUMO2JB1X7', '2019-09-09', '2019-09-10', 1, 1, 3745, NULL, NULL, 'f', 1, '2019-09-09 10:22:01'),
(13, 14, 5, 16, 'WKBY0MZPC4FQTGN', '2019-09-11', '2019-09-14', 5, 3, 7222.5, 0, '{"date_print":"Thu 19-Sep-2019 | 10:29:40 AM UTC(+07:00)","sub_total":"13500","discount":"50","discount_type":"Percent","total":"6750","vat":"7","vat_amount":"472.5","grand_total":"7222.5","rate_select":"Summer sale 50%","session":"WKBY0MZPC4FQTGN","checkin":"11-Sep-2019","checkout":"14-Sep-2019","firstname":"Chutipas","lastname":"Borsub","id":14,"product_list":[{"product":"Medium Room","qty":"3","price":"4500","total":"13500"}],"company_info":[{"company_tax_payer_no":"0901108123442","company_name":"Tent''s Hotel reservation System Co.,Ltd.","company_address":"235/233 Pattanakarn Rd. Bangkok 10250","company_tel":"(+66)830884161","company_email":"bo.chutipas_st@tni.ac.th"}]}', 'I need cake.', 3, '2019-09-11 02:46:48'),
(14, 15, 5, 11, 'DLOIU38WYNXPEZ4', '2019-09-24', '2019-09-25', 1, 1, 3103, 428, '{"date_print":"Thu 26-Sep-2019 | 12:20:51 PM UTC(+07:00)","sub_total":"2900","discount":"0","discount_type":"None","total":"2900","vat":"7","vat_amount":"203","grand_total":"3103","rate_select":"Best Flexible Rate","session":"DLOIU38WYNXPEZ4","checkin":"24-Sep-2019","checkout":"25-Sep-2019","firstname":"E-TENT","lastname":"Studio","id":15,"product_list":[{"product":"Small Room","qty":"1","price":"2500","total":"2500"},{"product":"แก้วแตก","qty":"1","price":"400","total":"400"}],"company_info":[{"company_tax_payer_no":"0901108123442","company_name":"Tent''s Hotel reservation System Co.,Ltd.","company_address":"235/233 Pattanakarn Rd. Bangkok 10250","company_tel":"(+66)830884161","company_email":"bo.chutipas_st@tni.ac.th"}]}', '-', 3, '2019-09-24 11:02:01'),
(15, 16, 5, 11, 'GT4EB76W0DCAXSM', '2019-09-25', '2019-10-01', 3, 3, 73830, NULL, NULL, '-', 2, '2019-09-25 04:55:28'),
(16, 17, 5, 11, '1ROMT2GU3NJP0YB', '2019-09-25', '2019-09-26', 1, 2, 7490, NULL, NULL, '-', 1, '2019-09-25 04:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `h_bill_status`
--

CREATE TABLE IF NOT EXISTS `h_bill_status` (
  `h_bill_status_id` int(11) NOT NULL,
  `h_bill_status_name` varchar(180) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_bill_status`
--

INSERT INTO `h_bill_status` (`h_bill_status_id`, `h_bill_status_name`) VALUES
(1, 'Waiting'),
(2, 'Checked in'),
(3, 'Checked out'),
(4, 'Canceled');

-- --------------------------------------------------------

--
-- Table structure for table `h_customer`
--

CREATE TABLE IF NOT EXISTS `h_customer` (
  `h_customer_id` int(11) NOT NULL,
  `h_customer_firstname` varchar(255) NOT NULL DEFAULT '',
  `h_customer_lastname` varchar(255) NOT NULL DEFAULT '',
  `h_customer_email` varchar(255) DEFAULT NULL,
  `h_customer_phone` varchar(128) DEFAULT NULL,
  `h_customer_region` varchar(128) DEFAULT NULL,
  `h_customer_address` varchar(255) DEFAULT NULL,
  `h_customer_city` varchar(128) DEFAULT NULL,
  `h_customer_postal` varchar(128) DEFAULT NULL,
  `h_customer_codesession` varchar(128) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_customer`
--

INSERT INTO `h_customer` (`h_customer_id`, `h_customer_firstname`, `h_customer_lastname`, `h_customer_email`, `h_customer_phone`, `h_customer_region`, `h_customer_address`, `h_customer_city`, `h_customer_postal`, `h_customer_codesession`) VALUES
(1, 'SYSTEM', 'TEMP', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'E-TENT', 'Studio', 'bo.chutipas_st@tni.ac.th', '830884161', 'Bangkok', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'W2HLV7ZA58IUTY9'),
(3, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', '1', '1', '1', '1', 'RSAV73DW8ML19UQ'),
(4, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', '1', '1', '1', '1', '4QGFC1OIXVUYARB'),
(5, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'Z9NPGH87DRVYCIX'),
(6, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'GYW1ZR4LHB2N8D9'),
(7, 'E-TENT', 'Studio', 'test@gmail.co0', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'FUEWXHI6YSGPVJ2'),
(8, 'test', 'Studio', '1@gmail.com1', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'NR3LTVZ2BQWAXPO'),
(9, 'Chutipas', 'Borsub', 'qqqAngel_flood@hotmail.com', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'NPX1GYZQHC0KWF4'),
(10, 'Chutipas', 'Borsub', 'Angel_floodwww@hotmail.com', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', '69BVK72Y4J3ACGF'),
(11, 'test', 'f', 'f', 'f', 'f', 'f', 'f', 'f', '8ER543ZTABQOY12'),
(12, 'f', 'f', 'f', 'f', 'f', 'f', 'f', 'f', '7UK94ION6LTAZJE'),
(13, 'f', 'f', 'f', 'f', 'f', 'f', 'f', 'f', '38FVEQUMO2JB1X7'),
(14, 'Chutipas', 'Borsub', 'bo.chutipas_st@tni.ac.th', '0830884161', 'Thailand', '234/666 Sukumvit Rd.', 'Bangkok', '10160', 'WKBY0MZPC4FQTGN'),
(15, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', 'กรุงเทพมหานคร', '12/100 ถนนมาเจริญ', 'หนองแขม', '10160', 'DLOIU38WYNXPEZ4'),
(16, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', '', '', '', '', 'GT4EB76W0DCAXSM'),
(17, 'E-TENT', 'Studio', 'chutipas27316@gmail.com', '830884161', '', '', '', '', '1ROMT2GU3NJP0YB');

-- --------------------------------------------------------

--
-- Table structure for table `h_gallery`
--

CREATE TABLE IF NOT EXISTS `h_gallery` (
  `h_gallery_id` int(11) NOT NULL,
  `h_gallery_roomtypeid` int(11) DEFAULT '0',
  `h_gallery_filename` text,
  `h_gallery_filenameorigin` varchar(255) NOT NULL,
  `h_gallery_cover` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=312 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_gallery`
--

INSERT INTO `h_gallery` (`h_gallery_id`, `h_gallery_roomtypeid`, `h_gallery_filename`, `h_gallery_filenameorigin`, `h_gallery_cover`) VALUES
(282, 2, '../system/upload_hotel/5d28442f7d863-hotel4_1.jpeg', '5d28442f7d863-hotel4_1.jpeg', 0),
(283, 2, '../system/upload_hotel/5d28442f7dab8-hotel4_2.jpeg', '5d28442f7dab8-hotel4_2.jpeg', 0),
(284, 4, '../system/upload_hotel/5d284452d3ab5-hotel5_1.jpeg', '5d284452d3ab5-hotel5_1.jpeg', 0),
(285, 4, '../system/upload_hotel/5d284452d3d05-hotel5_2.jpeg', '5d284452d3d05-hotel5_2.jpeg', 0),
(286, 4, '../system/upload_hotel/5d284452d3f07-hotel5_3.jpeg', '5d284452d3f07-hotel5_3.jpeg', 0),
(287, 4, '../system/upload_hotel/5d284452dca11-hotel5_4.jpeg', '5d284452dca11-hotel5_4.jpeg', 0),
(288, 2, '../system/upload_hotel/5d2844600a5e3-hotel4_3.jpeg', '5d2844600a5e3-hotel4_3.jpeg', 0),
(289, 2, '../system/upload_hotel/5d2844600aba4-hotel4_4.jpeg', '5d2844600aba4-hotel4_4.jpeg', 0),
(290, 1, '../system/upload_hotel/5d28446fe085f-hotel1_1.jpeg', '5d28446fe085f-hotel1_1.jpeg', 0),
(292, 2, '../system/upload_hotel/5d28448e14abb-hotel4_1.jpeg', '5d28448e14abb-hotel4_1.jpeg', 1),
(301, 1, '../system/upload_hotel/5d2861617a04f-hotel1_2.jpeg', '5d2861617a04f-hotel1_2.jpeg', 0),
(306, 4, '../system/upload_hotel/5d368af50378b-hotel5_1.jpeg', '5d368af50378b-hotel5_1.jpeg', 1),
(307, 1, '../system/upload_hotel/5d368b0a29534-hotel1_1.jpeg', '5d368b0a29534-hotel1_1.jpeg', 1),
(308, 3, '../system/upload_hotel/5d5f6bd7b2f05-hotel2_1.jpeg', '5d5f6bd7b2f05-hotel2_1.jpeg', 0),
(309, 3, '../system/upload_hotel/5d5f6bd7b3234-hotel2_2.jpeg', '5d5f6bd7b3234-hotel2_2.jpeg', 0),
(310, 3, '../system/upload_hotel/5d5f6bd7b3441-hotel2_3.jpg', '5d5f6bd7b3441-hotel2_3.jpg', 0),
(311, 3, '../system/upload_hotel/5d5f6be943a9b-hotel2_2.jpeg', '5d5f6be943a9b-hotel2_2.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `h_mix_ratetype`
--

CREATE TABLE IF NOT EXISTS `h_mix_ratetype` (
  `h_mix_ratetype_id` int(11) NOT NULL,
  `h_mix_ratetype_rateid` int(11) NOT NULL,
  `h_mix_ratetype_typeid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=478 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_mix_ratetype`
--

INSERT INTO `h_mix_ratetype` (`h_mix_ratetype_id`, `h_mix_ratetype_rateid`, `h_mix_ratetype_typeid`) VALUES
(256, 11, 1),
(257, 11, 2),
(258, 11, 3),
(259, 11, 4),
(260, 11, 5),
(460, 12, 1),
(461, 12, 2),
(462, 12, 3),
(463, 12, 4),
(464, 12, 5),
(465, 12, 7),
(466, 16, 1),
(467, 16, 2),
(468, 16, 3),
(469, 16, 4),
(470, 16, 5),
(471, 16, 7),
(472, 17, 7),
(473, 17, 3),
(474, 17, 2),
(475, 17, 4),
(476, 17, 1),
(477, 17, 5);

-- --------------------------------------------------------

--
-- Table structure for table `h_paymenttype`
--

CREATE TABLE IF NOT EXISTS `h_paymenttype` (
  `h_paymenttype_id` int(11) NOT NULL,
  `h_paymenttype_type` varchar(128) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_paymenttype`
--

INSERT INTO `h_paymenttype` (`h_paymenttype_id`, `h_paymenttype_type`) VALUES
(1, 'VISA'),
(2, 'JCB'),
(3, 'MASTERCARD'),
(4, 'AMERICAN EXPRESS'),
(5, 'Walk in');

-- --------------------------------------------------------

--
-- Table structure for table `h_rate`
--

CREATE TABLE IF NOT EXISTS `h_rate` (
  `h_rate_id` int(11) NOT NULL,
  `h_rate_name` varchar(128) NOT NULL,
  `h_rate_discountset` varchar(20) NOT NULL DEFAULT 'None',
  `h_rate_discount` float DEFAULT '0',
  `h_rate_desc` text,
  `h_rate_dateset` tinyint(1) NOT NULL DEFAULT '0',
  `h_rate_datestart` date DEFAULT NULL,
  `h_rate_dateend` date DEFAULT NULL,
  `h_rate_deposit` tinyint(1) NOT NULL DEFAULT '1',
  `h_rate_statustouser` int(11) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_rate`
--

INSERT INTO `h_rate` (`h_rate_id`, `h_rate_name`, `h_rate_discountset`, `h_rate_discount`, `h_rate_desc`, `h_rate_dateset`, `h_rate_datestart`, `h_rate_dateend`, `h_rate_deposit`, `h_rate_statustouser`) VALUES
(11, 'Best Flexible Rate', 'None', 0, '<ul>\n	<li>Breakfast included</li>\n	<li>Non Refundable</li>\n	<li>No deposit required</li>\n</ul>\n', 0, '0000-00-00', '0000-00-00', 0, 1),
(12, 'Stay Longer Pay Less', 'Bath', 1000, '<ul>\n	<li>Breakfast included</li>\n	<li>1 night deposit required</li>\n	<li>1 night&#39;s non-refundable deposit required</li>\n</ul>\n', 0, '0000-00-00', '0000-00-00', 1, 1),
(16, 'Summer sale 50%', 'Percent', 50, '<blockquote>\n<p>&quot;<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing :D&quot;</p>\n</blockquote>\n\n<p>&nbsp;</p>\n\n<ul>\n	<li>50% off</li>\n	<li>Breakfast included</li>\n	<li>No deposit required</li>\n	<li>Gift voucher 1,000 THB. For next time booking</li>\n</ul>\n\n<p><span style="color:#e74c3c">* For online booking only.</span></p>\n', 0, '0000-00-00', '0000-00-00', 0, 1),
(17, 'Test Time', 'None', 0, '<blockquote>\n<p>Test Time :D</p>\n</blockquote>\n', 1, '2019-09-10', '2019-09-30', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `h_room`
--

CREATE TABLE IF NOT EXISTS `h_room` (
  `h_room_id` int(11) NOT NULL,
  `h_room_name` varchar(255) NOT NULL,
  `h_room_type` int(11) NOT NULL,
  `h_room_status` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_room`
--

INSERT INTO `h_room` (`h_room_id`, `h_room_name`, `h_room_type`, `h_room_status`) VALUES
(1, '1000', 1, 1),
(2, '1001', 1, 1),
(14, '1002', 4, 2),
(15, '1003', 3, 14),
(17, '1005', 1, 1),
(18, '1006', 2, 15),
(19, '1007', 2, 1),
(20, '1008', 3, 2),
(24, '1012', 2, 1),
(25, '1013', 2, 1),
(26, '1014', 2, 1),
(27, '1015', 4, 1),
(28, '1016', 2, 1),
(32, '1017', 2, 1),
(34, '1018', 2, 1),
(35, '1019', 3, 15),
(36, '1020', 4, 1),
(37, '1011', 3, 1),
(38, '1009', 3, 1),
(39, '1010', 3, 1),
(51, '1021', 5, 1),
(56, '1004', 1, 1),
(57, '1022', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `h_status`
--

CREATE TABLE IF NOT EXISTS `h_status` (
  `h_status_id` int(11) NOT NULL,
  `h_status_name` varchar(128) DEFAULT NULL,
  `h_status_statustouser` int(11) NOT NULL DEFAULT '1',
  `h_status_color` varchar(10) NOT NULL DEFAULT '#FFFFFF'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_status`
--

INSERT INTO `h_status` (`h_status_id`, `h_status_name`, `h_status_statustouser`, `h_status_color`) VALUES
(1, 'Open', 1, '#FFFFFF'),
(2, 'Maintenance', 2, '#F8D7DA'),
(14, 'VIP', 2, '#F1C40F'),
(15, 'Lucky room', 1, '#2ECC71');

-- --------------------------------------------------------

--
-- Table structure for table `h_statustouser`
--

CREATE TABLE IF NOT EXISTS `h_statustouser` (
  `h_statustouser_id` int(11) NOT NULL,
  `h_statustouser_name` varchar(128) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_statustouser`
--

INSERT INTO `h_statustouser` (`h_statustouser_id`, `h_statustouser_name`) VALUES
(1, 'แสดงในหน้าเว็บ'),
(2, 'ซ่อนจากหน้าเว็บ');

-- --------------------------------------------------------

--
-- Table structure for table `h_transaction`
--

CREATE TABLE IF NOT EXISTS `h_transaction` (
  `h_trans_id` int(11) NOT NULL,
  `h_trans_roomid` int(11) DEFAULT '0',
  `h_trans_customerid` int(11) DEFAULT '0',
  `h_trans_checkindate` date DEFAULT NULL,
  `h_trans_checkoutdate` date DEFAULT NULL,
  `h_trans_session` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `h_trans_codesession` varchar(128) DEFAULT NULL,
  `h_trans_bill_status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_transaction`
--

INSERT INTO `h_transaction` (`h_trans_id`, `h_trans_roomid`, `h_trans_customerid`, `h_trans_checkindate`, `h_trans_checkoutdate`, `h_trans_session`, `h_trans_codesession`, `h_trans_bill_status`) VALUES
(4, 57, 2, '2019-08-28', '2019-08-29', '2019-08-28 03:02:17', '', 3),
(5, 19, 2, '2019-08-28', '2019-08-29', '2019-08-28 03:02:17', '', 3),
(6, 24, 2, '2019-08-28', '2019-08-29', '2019-08-28 03:02:17', '', 3),
(7, 38, 3, '2019-08-28', '2019-08-29', '2019-08-28 03:06:45', '', 3),
(8, 19, 3, '2019-08-28', '2019-08-29', '2019-08-28 03:06:45', '', 3),
(9, 39, 4, '2019-08-28', '2019-08-29', '2019-08-28 03:15:57', '', 3),
(10, 24, 4, '2019-08-28', '2019-08-29', '2019-08-28 03:15:57', '', 3),
(14, 38, 5, '2019-09-04', '2019-09-05', '2019-09-04 11:47:54', '', 4),
(15, 39, 6, '2019-09-04', '2019-09-05', '2019-09-04 11:49:13', '', 1),
(16, 57, 7, '0000-00-00', '0000-00-00', '2019-09-05 03:18:31', '', 4),
(17, 38, 8, '2019-09-05', '2019-09-06', '2019-09-05 03:20:30', '', 1),
(20, 39, 9, '2019-09-09', '2019-09-10', '2019-09-09 04:01:56', '', 1),
(23, 35, 10, '2019-09-09', '2019-09-10', '2019-09-09 05:11:21', '', 1),
(28, 38, 11, '2019-09-09', '2019-09-10', '2019-09-09 10:18:02', '', 1),
(29, 37, 12, '2019-09-09', '2019-09-10', '2019-09-09 10:18:03', '', 2),
(30, 18, 13, '2019-09-09', '2019-09-10', '2019-09-09 10:18:20', '', 1),
(31, 18, 14, '2019-09-11', '2019-09-14', '2019-09-11 02:45:39', '', 3),
(32, 19, 14, '2019-09-11', '2019-09-14', '2019-09-11 02:45:39', '', 3),
(33, 24, 14, '2019-09-11', '2019-09-14', '2019-09-11 02:45:39', '', 3),
(34, 38, 15, '2019-09-24', '2019-09-25', '2019-09-24 11:01:44', '', 3),
(35, 24, 16, '2019-09-25', '2019-10-01', '2019-09-25 04:55:23', '', 2),
(36, 25, 16, '2019-09-25', '2019-10-01', '2019-09-25 04:55:23', '', 2),
(37, 39, 16, '2019-09-25', '2019-10-01', '2019-09-25 04:55:23', '', 2),
(38, 51, 17, '2019-09-25', '2019-09-26', '2019-09-25 04:56:04', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `h_type`
--

CREATE TABLE IF NOT EXISTS `h_type` (
  `h_type_id` int(11) NOT NULL,
  `h_type_name` varchar(255) NOT NULL,
  `h_type_desc` text,
  `h_type_price` float DEFAULT '0',
  `h_type_capacity` int(11) DEFAULT '0',
  `h_type_bed` int(11) DEFAULT '0',
  `h_type_bedtotal` int(11) NOT NULL DEFAULT '0',
  `h_type_statustouser` int(11) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_type`
--

INSERT INTO `h_type` (`h_type_id`, `h_type_name`, `h_type_desc`, `h_type_price`, `h_type_capacity`, `h_type_bed`, `h_type_bedtotal`, `h_type_statustouser`) VALUES
(1, 'Big Room', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 6500, 6, 3, 1, 1),
(2, 'Medium Room', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 4500, 4, 2, 1, 1),
(3, 'Small Room', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 2500, 2, 3, 1, 1),
(4, 'Premier Room', 'Located2 in the beach wing the height of comfort and tranquility with 55 sq. M. Balcony with oversized day bed free internet wifi marble bathroom with separate shower and bathtub and loccitane amenities complimentary access to vana nava waterpark and true arena sport Club2', 5500, 2, 3, 1, 1),
(5, 'Superior Room', 'Located sin the bluport2 wing which is located opposite side of the existing resort on the city side and can be accessed via a pedestrian bridge leading over the road. The latest in colonial style 37 to 45sqm. For room rate with breakfast it is served at le colonial 1st floor bluport wing', 7000, 2, 4, 1, 1),
(7, 'Single Room', 'Located in the bluport wing which is located opposite side of the existing resort on the city side and can be accessed via a pedestrian bridge leading over the road. The latest in colonial style 37 to 45sqm. For room rate with breakfast it is served at le colonial 1st floor bluport wing.', 1500, 1, 1, 1, 1);

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
(83, 'admin', '$2y$12$ItodPcOUiT3hzGWrugqFVOBmsNxbbMIoKBF7t1.ZjYxFC2TGOCZ8q', 1, 'Chutipas', 'Borsub', '../system/upload_profile/5d5f5fbbb0730-mypic.jpg', '2019-08-08 07:20:47'),
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

-- --------------------------------------------------------

--
-- Table structure for table `h_userrank`
--

CREATE TABLE IF NOT EXISTS `h_userrank` (
  `h_userrank_id` int(11) NOT NULL,
  `h_userrank_name` varchar(128) DEFAULT NULL,
  `h_userrank_permission` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_userrank`
--

INSERT INTO `h_userrank` (`h_userrank_id`, `h_userrank_name`, `h_userrank_permission`) VALUES
(1, 'Office Manager', 2),
(2, 'Reception', 1),
(3, 'Cashier', 1),
(4, 'Accounting', 1),
(5, 'Assistant Manager', 2),
(6, 'Reservation Clerk', 1),
(7, 'Telephone Operator', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `h_bill`
--
ALTER TABLE `h_bill`
  ADD PRIMARY KEY (`h_bill_id`),
  ADD KEY `fk_paymenttype` (`h_bill_paymenttypeid`),
  ADD KEY `fk_customer` (`h_bill_customerid`),
  ADD KEY `fk_rate` (`h_bill_rateid`),
  ADD KEY `h_bill_status` (`h_bill_status`);

--
-- Indexes for table `h_bill_status`
--
ALTER TABLE `h_bill_status`
  ADD PRIMARY KEY (`h_bill_status_id`);

--
-- Indexes for table `h_customer`
--
ALTER TABLE `h_customer`
  ADD PRIMARY KEY (`h_customer_id`);

--
-- Indexes for table `h_gallery`
--
ALTER TABLE `h_gallery`
  ADD PRIMARY KEY (`h_gallery_id`),
  ADD KEY `fk_roomtypeid` (`h_gallery_roomtypeid`);

--
-- Indexes for table `h_mix_ratetype`
--
ALTER TABLE `h_mix_ratetype`
  ADD PRIMARY KEY (`h_mix_ratetype_id`),
  ADD KEY `fk_typeid` (`h_mix_ratetype_typeid`),
  ADD KEY `fk_rateid` (`h_mix_ratetype_rateid`);

--
-- Indexes for table `h_paymenttype`
--
ALTER TABLE `h_paymenttype`
  ADD PRIMARY KEY (`h_paymenttype_id`);

--
-- Indexes for table `h_rate`
--
ALTER TABLE `h_rate`
  ADD PRIMARY KEY (`h_rate_id`),
  ADD KEY `h_rate_statustouser` (`h_rate_statustouser`);

--
-- Indexes for table `h_room`
--
ALTER TABLE `h_room`
  ADD PRIMARY KEY (`h_room_id`),
  ADD KEY `fk_h_type` (`h_room_type`),
  ADD KEY `fk_h_status` (`h_room_status`);

--
-- Indexes for table `h_status`
--
ALTER TABLE `h_status`
  ADD PRIMARY KEY (`h_status_id`),
  ADD KEY `h_status_statustouser` (`h_status_statustouser`);

--
-- Indexes for table `h_statustouser`
--
ALTER TABLE `h_statustouser`
  ADD PRIMARY KEY (`h_statustouser_id`);

--
-- Indexes for table `h_transaction`
--
ALTER TABLE `h_transaction`
  ADD PRIMARY KEY (`h_trans_id`),
  ADD KEY `fk_customerid` (`h_trans_customerid`),
  ADD KEY `fk_roomid` (`h_trans_roomid`),
  ADD KEY `h_trans__bill_status` (`h_trans_bill_status`);

--
-- Indexes for table `h_type`
--
ALTER TABLE `h_type`
  ADD PRIMARY KEY (`h_type_id`),
  ADD KEY `fk_statustouser` (`h_type_statustouser`),
  ADD KEY `h_type_bed` (`h_type_bed`);

--
-- Indexes for table `h_type_bed`
--
ALTER TABLE `h_type_bed`
  ADD PRIMARY KEY (`h_type_bed_id`);

--
-- Indexes for table `h_user`
--
ALTER TABLE `h_user`
  ADD PRIMARY KEY (`h_user_id`),
  ADD KEY `fk_rank` (`h_user_rank`);

--
-- Indexes for table `h_userrank`
--
ALTER TABLE `h_userrank`
  ADD PRIMARY KEY (`h_userrank_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `h_bill`
--
ALTER TABLE `h_bill`
  MODIFY `h_bill_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `h_bill_status`
--
ALTER TABLE `h_bill_status`
  MODIFY `h_bill_status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `h_customer`
--
ALTER TABLE `h_customer`
  MODIFY `h_customer_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `h_gallery`
--
ALTER TABLE `h_gallery`
  MODIFY `h_gallery_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=312;
--
-- AUTO_INCREMENT for table `h_mix_ratetype`
--
ALTER TABLE `h_mix_ratetype`
  MODIFY `h_mix_ratetype_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=478;
--
-- AUTO_INCREMENT for table `h_paymenttype`
--
ALTER TABLE `h_paymenttype`
  MODIFY `h_paymenttype_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `h_rate`
--
ALTER TABLE `h_rate`
  MODIFY `h_rate_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `h_room`
--
ALTER TABLE `h_room`
  MODIFY `h_room_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `h_status`
--
ALTER TABLE `h_status`
  MODIFY `h_status_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `h_statustouser`
--
ALTER TABLE `h_statustouser`
  MODIFY `h_statustouser_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `h_transaction`
--
ALTER TABLE `h_transaction`
  MODIFY `h_trans_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `h_type`
--
ALTER TABLE `h_type`
  MODIFY `h_type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `h_type_bed`
--
ALTER TABLE `h_type_bed`
  MODIFY `h_type_bed_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `h_user`
--
ALTER TABLE `h_user`
  MODIFY `h_user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT for table `h_userrank`
--
ALTER TABLE `h_userrank`
  MODIFY `h_userrank_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `h_bill`
--
ALTER TABLE `h_bill`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`h_bill_customerid`) REFERENCES `h_customer` (`h_customer_id`),
  ADD CONSTRAINT `fk_paymenttype` FOREIGN KEY (`h_bill_paymenttypeid`) REFERENCES `h_paymenttype` (`h_paymenttype_id`),
  ADD CONSTRAINT `fk_rate` FOREIGN KEY (`h_bill_rateid`) REFERENCES `h_rate` (`h_rate_id`),
  ADD CONSTRAINT `h_bill_ibfk_1` FOREIGN KEY (`h_bill_status`) REFERENCES `h_bill_status` (`h_bill_status_id`);

--
-- Constraints for table `h_gallery`
--
ALTER TABLE `h_gallery`
  ADD CONSTRAINT `fk_roomtypeid` FOREIGN KEY (`h_gallery_roomtypeid`) REFERENCES `h_type` (`h_type_id`);

--
-- Constraints for table `h_mix_ratetype`
--
ALTER TABLE `h_mix_ratetype`
  ADD CONSTRAINT `fk_rateid` FOREIGN KEY (`h_mix_ratetype_rateid`) REFERENCES `h_rate` (`h_rate_id`),
  ADD CONSTRAINT `fk_typeid` FOREIGN KEY (`h_mix_ratetype_typeid`) REFERENCES `h_type` (`h_type_id`);

--
-- Constraints for table `h_rate`
--
ALTER TABLE `h_rate`
  ADD CONSTRAINT `h_rate_ibfk_1` FOREIGN KEY (`h_rate_statustouser`) REFERENCES `h_statustouser` (`h_statustouser_id`);

--
-- Constraints for table `h_room`
--
ALTER TABLE `h_room`
  ADD CONSTRAINT `fk_h_status` FOREIGN KEY (`h_room_status`) REFERENCES `h_status` (`h_status_id`),
  ADD CONSTRAINT `fk_h_type` FOREIGN KEY (`h_room_type`) REFERENCES `h_type` (`h_type_id`);

--
-- Constraints for table `h_status`
--
ALTER TABLE `h_status`
  ADD CONSTRAINT `h_status_ibfk_1` FOREIGN KEY (`h_status_statustouser`) REFERENCES `h_statustouser` (`h_statustouser_id`);

--
-- Constraints for table `h_transaction`
--
ALTER TABLE `h_transaction`
  ADD CONSTRAINT `fk_customerid` FOREIGN KEY (`h_trans_customerid`) REFERENCES `h_customer` (`h_customer_id`),
  ADD CONSTRAINT `fk_roomid` FOREIGN KEY (`h_trans_roomid`) REFERENCES `h_room` (`h_room_id`),
  ADD CONSTRAINT `h_transaction_ibfk_1` FOREIGN KEY (`h_trans_bill_status`) REFERENCES `h_bill_status` (`h_bill_status_id`);

--
-- Constraints for table `h_type`
--
ALTER TABLE `h_type`
  ADD CONSTRAINT `fk_statustouser` FOREIGN KEY (`h_type_statustouser`) REFERENCES `h_statustouser` (`h_statustouser_id`),
  ADD CONSTRAINT `h_type_ibfk_1` FOREIGN KEY (`h_type_bed`) REFERENCES `h_type_bed` (`h_type_bed_id`);

--
-- Constraints for table `h_user`
--
ALTER TABLE `h_user`
  ADD CONSTRAINT `fk_rank` FOREIGN KEY (`h_user_rank`) REFERENCES `h_userrank` (`h_userrank_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
