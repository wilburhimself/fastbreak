-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2009 at 10:11 PM
-- Server version: 5.1.36
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `famous`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(1, 'La Romana'),
(2, 'Santo Domingo');

-- --------------------------------------------------------

--
-- Table structure for table `negocios`
--

CREATE TABLE IF NOT EXISTS `negocios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `negocios`
--

INSERT INTO `negocios` (`id`, `name`, `address`, `phone`) VALUES
(1, 'Agencia enriquillo', 'Avenida santa Rosa #100, La Romana', '809-556-3610');

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(75) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `nombre`, `email`) VALUES
(2, 'Wilbur', 'wilbur.himself@gmail.com'),
(3, 'Rose', 'roselin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE IF NOT EXISTS `places` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`, `address`, `phone`, `city_id`) VALUES
(11, 'Agencia Enriquillo', 'calle santa rosa #100, La Romana', '809-556-3610', 1),
(2, 'Restaurant Patepalo', 'Plaza del alacazr #3', '809-567-3940', 0),
(5, 'Reposteria Francia', 'Los mejores panes que venden en toda la comarca, ya tu sabe, me quite', '809-7845-59', 2),
(8, 'Rico Hot dog', 'esq. bolivar con nuñez', '809-561-2752', 2);
