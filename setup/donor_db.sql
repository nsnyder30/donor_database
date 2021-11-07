-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2021 at 11:25 PM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `donor_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

CREATE TABLE `access_log` (
  `acc_id` int(11) NOT NULL,
  `acc_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `acc_ip` varchar(15) NOT NULL,
  `acc_proxy_ip` varchar(15) DEFAULT NULL,
  `acc_username` varchar(20) NOT NULL,
  `acc_success` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_log`
--

INSERT INTO `access_log` (`acc_id`, `acc_timestamp`, `acc_ip`, `acc_proxy_ip`, `acc_username`, `acc_success`) VALUES
(1, '2021-03-14 04:06:58', '127.0.0.1', '', 'mydude', 'N'),
(2, '2021-03-14 04:08:08', '127.0.0.1', '', 'mydude', 'N'),
(3, '2021-03-14 04:08:13', '127.0.0.1', '', 'mydude', 'N'),
(4, '2021-03-14 04:18:00', '127.0.0.1', '', 'test_user', 'N'),
(5, '2021-03-14 04:18:29', '127.0.0.1', '', 'test_user', 'N'),
(6, '2021-03-14 04:18:54', '127.0.0.1', '', 'test_user', 'N'),
(7, '2021-03-14 04:19:46', '127.0.0.1', '', 'test_user', 'Y'),
(8, '2021-03-14 04:20:22', '127.0.0.1', '', 'test_user', 'Y'),
(9, '2021-03-14 04:23:26', '127.0.0.1', '', 'test_user', 'Y'),
(10, '2021-03-14 04:27:16', '127.0.0.1', '', 'john.smith@america.c', 'N'),
(11, '2021-03-14 04:40:30', '127.0.0.1', '', 'test_user', 'N'),
(12, '2021-08-07 18:32:17', '24.217.136.112', '24.217.136.112', 'test_user', 'N'),
(13, '2021-08-07 18:32:22', '24.217.136.112', '24.217.136.112', 'test_user', 'N'),
(14, '2021-08-24 14:57:23', '24.21.197.76', '24.21.197.76', 'test_usesr', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `cnt_id` int(11) NOT NULL,
  `cnt_first` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_last` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_fb_profile` text COLLATE utf8_unicode_ci,
  `cnt_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_street_addr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_state` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_zip` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_country` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_org` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_volunteer` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `cnt_guest_blogger` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `cnt_mailchimp` date DEFAULT NULL,
  `cnt_religion` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_email_sub` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt_email_sub_date` date DEFAULT '2018-01-01',
  `cnt_personal_details` text COLLATE utf8_unicode_ci,
  `cnt_notes` text COLLATE utf8_unicode_ci,
  `cnt_deleted` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`cnt_id`, `cnt_first`, `cnt_last`, `cnt_email`, `cnt_fb_profile`, `cnt_phone`, `cnt_street_addr`, `cnt_city`, `cnt_state`, `cnt_zip`, `cnt_country`, `cnt_type`, `cnt_org`, `cnt_volunteer`, `cnt_guest_blogger`, `cnt_mailchimp`, `cnt_religion`, `cnt_email_sub`, `cnt_email_sub_date`, `cnt_personal_details`, `cnt_notes`, `cnt_deleted`) VALUES
(1, 'Porfirio', 'Hersey', 'porfirio.hersey@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(2, 'Ben', 'Going', 'ben.going@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(3, 'Treena', 'Mcnellis', 'treena.mcnellis@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(13, 'Patti', 'Krueger', 'patti.krueger@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(15, 'Calista', 'Daugherty', 'calista.daugherty@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(16, 'Marlin', 'Maples', 'marlin.maples@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(17, 'Luther', 'Wire', 'luther.wire@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(22, 'Phuong', 'Triggs', 'phuong.triggs@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(24, 'Norbert', 'Mcaleer', 'norbert.mcaleer@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(28, 'Kelly', 'Coddington', 'kelly.coddington@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(45, 'Jamaal', 'Macrae', 'jamaal.macrae@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N'),
(52, 'Angel', 'Mahoney', 'angel.mahoney@nowhere.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, '2018-01-01', NULL, NULL, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `db_users`
--

CREATE TABLE `db_users` (
  `usr_username` varchar(20) NOT NULL,
  `usr_password` text NOT NULL,
  `usr_email` varchar(50) DEFAULT NULL,
  `usr_create_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usr_permissions` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `db_users`
--

INSERT INTO `db_users` (`usr_username`, `usr_password`, `usr_email`, `usr_create_dt`, `usr_permissions`) VALUES
('test_user', '8b4528ec116f2b9689c14ff56bf116a3', NULL, '2021-03-14 04:17:40', 'ALL');

-- --------------------------------------------------------

--
-- Table structure for table `donation_history`
--

CREATE TABLE `donation_history` (
  `don_id` int(11) NOT NULL,
  `don_imp_id` int(11) DEFAULT NULL,
  `don_date` date NOT NULL,
  `don_amount` decimal(11,2) NOT NULL,
  `don_thankyou` date DEFAULT NULL,
  `don_source_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `don_source_id` int(11) NOT NULL,
  `don_transaction_method` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `don_notes` text COLLATE utf8_unicode_ci,
  `don_deleted` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `donation_history`
--

INSERT INTO `donation_history` (`don_id`, `don_imp_id`, `don_date`, `don_amount`, `don_thankyou`, `don_source_type`, `don_source_id`, `don_transaction_method`, `don_notes`, `don_deleted`) VALUES
(29, 5, '2021-07-18', '40.00', NULL, 'Contact', 13, NULL, NULL, 'N'),
(30, 5, '2021-09-21', '45.00', NULL, 'Contact', 1, NULL, NULL, 'N'),
(31, 5, '2020-11-29', '100.00', NULL, 'Contact', 15, NULL, NULL, 'N'),
(32, 5, '2020-11-02', '30.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(33, 5, '2020-01-02', '15.00', NULL, 'Contact', 17, NULL, NULL, 'N'),
(34, 5, '2021-10-02', '95.00', NULL, 'Contact', 2, NULL, NULL, 'N'),
(35, 5, '2020-02-26', '80.00', NULL, 'Contact', 17, NULL, NULL, 'N'),
(36, 5, '2021-03-04', '80.00', NULL, 'Contact', 2, NULL, NULL, 'N'),
(37, 5, '2021-06-30', '40.00', NULL, 'Contact', 3, NULL, NULL, 'N'),
(38, 5, '2020-05-21', '50.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(39, 5, '2020-10-27', '50.00', NULL, 'Contact', 3, NULL, NULL, 'N'),
(40, 5, '2021-01-26', '60.00', NULL, 'Contact', 24, NULL, NULL, 'N'),
(41, 5, '2020-09-20', '35.00', NULL, 'Contact', 13, NULL, NULL, 'N'),
(42, 5, '2021-09-20', '90.00', NULL, 'Contact', 2, NULL, NULL, 'N'),
(43, 5, '2020-04-21', '10.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(44, 5, '2021-10-03', '60.00', NULL, 'Contact', 28, NULL, NULL, 'N'),
(97, 8, '2020-05-03', '90.00', NULL, 'Contact', 1, NULL, NULL, 'N'),
(98, 8, '2021-12-24', '5.00', NULL, 'Contact', 52, NULL, NULL, 'N'),
(99, 8, '2021-04-30', '60.00', NULL, 'Contact', 17, NULL, NULL, 'N'),
(100, 8, '2020-05-27', '90.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(101, 8, '2020-04-30', '95.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(102, 8, '2021-02-27', '15.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(103, 8, '2021-10-29', '80.00', NULL, 'Contact', 24, NULL, NULL, 'N'),
(104, 8, '2021-07-09', '40.00', NULL, 'Contact', 13, NULL, NULL, 'N'),
(105, 8, '2021-03-11', '30.00', NULL, 'Contact', 28, NULL, NULL, 'N'),
(106, 8, '2021-06-15', '85.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(107, 8, '2020-05-03', '90.00', NULL, 'Contact', 1, NULL, NULL, 'N'),
(108, 8, '2021-12-24', '5.00', NULL, 'Contact', 52, NULL, NULL, 'N'),
(109, 8, '2021-04-30', '60.00', NULL, 'Contact', 17, NULL, NULL, 'N'),
(110, 8, '2020-05-27', '90.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(111, 8, '2020-04-30', '95.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(112, 8, '2021-02-27', '15.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(113, 8, '2021-10-29', '80.00', NULL, 'Contact', 24, NULL, NULL, 'N'),
(114, 8, '2021-07-09', '40.00', NULL, 'Contact', 13, NULL, NULL, 'N'),
(115, 8, '2021-03-11', '30.00', NULL, 'Contact', 28, NULL, NULL, 'N'),
(116, 8, '2021-06-15', '85.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(117, 9, '2021-07-18', '80.00', NULL, 'Contact', 45, NULL, NULL, 'N'),
(118, 9, '2021-05-07', '25.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(119, 9, '2021-09-21', '40.00', NULL, 'Contact', 28, NULL, NULL, 'N'),
(120, 9, '2020-08-15', '55.00', NULL, 'Contact', 24, NULL, NULL, 'N'),
(121, 9, '2020-11-29', '95.00', NULL, 'Contact', 24, NULL, NULL, 'N'),
(122, 9, '2020-10-15', '5.00', NULL, 'Contact', 2, NULL, NULL, 'N'),
(123, 9, '2020-11-02', '85.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(124, 9, '2020-11-27', '50.00', NULL, 'Contact', 52, NULL, NULL, 'N'),
(125, 9, '2020-01-02', '100.00', NULL, 'Contact', 15, NULL, NULL, 'N'),
(126, 9, '2021-09-23', '60.00', NULL, 'Contact', 24, NULL, NULL, 'N'),
(127, 9, '2021-10-02', '45.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(128, 9, '2021-06-11', '100.00', NULL, 'Contact', 17, NULL, NULL, 'N'),
(129, 9, '2020-02-26', '10.00', NULL, 'Contact', 1, NULL, NULL, 'N'),
(130, 9, '2020-07-28', '85.00', NULL, 'Contact', 2, NULL, NULL, 'N'),
(131, 9, '2021-03-04', '60.00', NULL, 'Contact', 2, NULL, NULL, 'N'),
(132, 9, '2021-06-30', '100.00', NULL, 'Contact', 15, NULL, NULL, 'N'),
(133, 9, '2020-05-21', '15.00', NULL, 'Contact', 3, NULL, NULL, 'N'),
(134, 9, '2021-04-20', '25.00', NULL, 'Contact', 17, NULL, NULL, 'N'),
(135, 9, '2020-10-27', '55.00', NULL, 'Contact', 45, NULL, NULL, 'N'),
(136, 9, '2020-04-10', '65.00', NULL, 'Contact', 16, NULL, NULL, 'N'),
(137, 9, '2021-01-26', '80.00', NULL, 'Contact', 15, NULL, NULL, 'N'),
(138, 9, '2020-08-04', '35.00', NULL, 'Contact', 28, NULL, NULL, 'N'),
(139, 9, '2020-09-20', '10.00', NULL, 'Contact', 52, NULL, NULL, 'N'),
(140, 9, '2021-09-20', '10.00', NULL, 'Contact', 3, NULL, NULL, 'N'),
(141, 9, '2020-04-21', '30.00', NULL, 'Contact', 22, NULL, NULL, 'N'),
(142, 9, '2021-10-03', '40.00', NULL, 'Contact', 1, NULL, NULL, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `email_subscriptions`
--

CREATE TABLE `email_subscriptions` (
  `sub_cnt_id` int(11) NOT NULL,
  `sub_dist_list` varchar(30) NOT NULL,
  `sub_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

CREATE TABLE `imports` (
  `imp_id` int(11) NOT NULL,
  `imp_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imp_filename` varchar(50) NOT NULL,
  `imp_source` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `imports`
--

INSERT INTO `imports` (`imp_id`, `imp_ts`, `imp_filename`, `imp_source`) VALUES
(5, '2021-09-29 05:23:41', 'sample_paypal_transactions.csv', NULL),
(8, '2021-09-29 05:26:13', 'sample_fb_upload_02.csv', NULL),
(9, '2021-09-29 05:26:13', 'sample_fb_upload.csv', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `nte_entity_type` varchar(15) NOT NULL,
  `nte_entity_id` int(11) NOT NULL,
  `nte_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nte_category` varchar(20) DEFAULT NULL,
  `nte_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `org2cnt`
--

CREATE TABLE `org2cnt` (
  `o2c_org` int(11) NOT NULL,
  `o2c_cnt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `org_id` int(11) NOT NULL,
  `org_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `org_prim_contact` int(11) DEFAULT NULL,
  `org_website` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `org_address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `org_deleted` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`org_id`, `org_name`, `org_prim_contact`, `org_website`, `org_address`, `org_deleted`) VALUES
(1, 'Hosh Posh Bagosh', NULL, 'hpb.org', '', 'N'),
(2, 'Sator Group', NULL, 'sgroup.com', '', 'N'),
(3, 'Market Research Associates', NULL, 'mresearch.net', '', 'N'),
(4, 'Steve\'s Sleeves', NULL, 'sleevytime.org', '', 'N'),
(5, 'Mike\'s Bikes', NULL, 'rollin.com', '', 'N');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_log`
--
ALTER TABLE `access_log`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`cnt_id`),
  ADD UNIQUE KEY `idx_first_last` (`cnt_first`,`cnt_last`);

--
-- Indexes for table `db_users`
--
ALTER TABLE `db_users`
  ADD PRIMARY KEY (`usr_username`);

--
-- Indexes for table `donation_history`
--
ALTER TABLE `donation_history`
  ADD PRIMARY KEY (`don_id`);

--
-- Indexes for table `email_subscriptions`
--
ALTER TABLE `email_subscriptions`
  ADD PRIMARY KEY (`sub_cnt_id`,`sub_dist_list`);

--
-- Indexes for table `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`imp_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`nte_entity_type`,`nte_entity_id`,`nte_dt`),
  ADD UNIQUE KEY `ind_dt_type_id` (`nte_dt`,`nte_entity_type`,`nte_entity_id`);

--
-- Indexes for table `org2cnt`
--
ALTER TABLE `org2cnt`
  ADD PRIMARY KEY (`o2c_org`,`o2c_cnt`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`org_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `cnt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `donation_history`
--
ALTER TABLE `donation_history`
  MODIFY `don_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `imports`
--
ALTER TABLE `imports`
  MODIFY `imp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
