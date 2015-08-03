-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2013 at 02:50 AM
-- Server version: 5.5.28
-- PHP Version: 5.3.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bebibo`
--

-- --------------------------------------------------------

--
-- Table structure for table `unactive_user`
--

CREATE TABLE IF NOT EXISTS `unactive_user` (
  `code_confirm` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `regdate` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `unactive_user`
--

INSERT INTO `unactive_user` (`code_confirm`, `ID`, `fullname`, `username`, `password`, `email`, `regdate`) VALUES
('', 9, 'hoangquocdong', 'donghq', '202cb962ac59075b964b07152d234b70', 'dangky_user@yahoo.com', 1932013),
('', 10, 'hoangquocdong', 'donghq1', '202cb962ac59075b964b07152d234b70', 'dangky_user1@yahoo.com', 1932013),
('', 11, 'hoangquocdong', 'donghq12', '202cb962ac59075b964b07152d234b70', 'dangky_user21@yahoo.com', 0),
('', 12, 'hoangquocdong', 'donghq122', '202cb962ac59075b964b07152d234b70', 'dangky_user221@yahoo.com', 19032013),
('', 13, 'hoangquocdong', 'donghq1221', '202cb962ac59075b964b07152d234b70', 'dangky_user2221@yahoo.com', 19032013),
('', 14, 'hoangquocdong', 'donghq122121', '202cb962ac59075b964b07152d234b70', 'dangky_user22211@yahoo.com', 19032013),
('50efd95bfc8e63f166c815455ad8579f', 15, 'hoangquocdong', 'donghq1221213', '202cb962ac59075b964b07152d234b70', 'dangky_user222211@yahoo.com', 19032013),
('894a813e4fed3adffd314f3cf7cc7525', 16, 'hoangquocdong', 'donghq12212133', '202cb962ac59075b964b07152d234b70', 'dangky_user2322211@yahoo.com', 19032013),
('df5bab77e4762df6c23e77e0a95d57be', 17, 'h', 'Ã¡', '202cb962ac59075b964b07152d234b70', 'dangky_use1r@yahoo.com', 19032013),
('48d30751d70316a823693998809b658c', 18, 'h', 'Ã¡Æ°', '202cb962ac59075b964b07152d234b70', 'dangky_useÆ°1r@yahoo.com', 19032013),
('01a56d57379eab93e086cacde99a0b43', 19, 'zxc', 'Ã¡d', '202cb962ac59075b964b07152d234b70', 'dangky_us1er@yahoo.com', 19032013),
('c9aee54995cde7193f2c49678dd5ac85', 20, 'zxc', 'zxc', '202cb962ac59075b964b07152d234b70', 'dangky_us21er@yahoo.com', 20032013);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
