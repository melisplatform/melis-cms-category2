-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 19, 2018 at 03:27 PM
-- Server version: 5.7.18-log
-- PHP Version: 7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `melisplatform`
--

-- --------------------------------------------------------

--
-- Table structure for table `melis_cms_category2`
--

CREATE TABLE `melis_cms_category2` (
  `cat2_id` int(11) NOT NULL COMMENT 'Category ID',
  `cat2_father_cat_id` int(11) NOT NULL DEFAULT '-1' COMMENT 'The id of the parent''s category. If first category, -1',
  `cat2_order` int(11) NOT NULL DEFAULT '1' COMMENT 'Displaying order',
  `cat2_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Active / not active',
  `cat2_reference` varchar(45) DEFAULT NULL COMMENT 'Reference of the category',
  `cat2_date_valid_start` datetime DEFAULT NULL COMMENT 'Starting datetime for showing the category',
  `cat2_date_valid_end` datetime DEFAULT NULL COMMENT 'Ending datetime for showing the category',
  `cat2_date_creation` datetime NOT NULL COMMENT 'Creation date of this category',
  `cat2_user_id_creation` int(11) NOT NULL COMMENT 'BO user who created this category',
  `cat2_date_edit` datetime DEFAULT NULL COMMENT 'Last edition date',
  `cat2_user_id_edit` int(11) DEFAULT NULL COMMENT 'Last BO user who created this category'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table stores the categories of the ecommerce database';

--
-- Dumping data for table `melis_cms_category2`
--

INSERT INTO `melis_cms_category2` (`cat2_id`, `cat2_father_cat_id`, `cat2_order`, `cat2_status`, `cat2_reference`, `cat2_date_valid_start`, `cat2_date_valid_end`, `cat2_date_creation`, `cat2_user_id_creation`, `cat2_date_edit`, `cat2_user_id_edit`) VALUES
(1, -1, 1, 1, NULL, NULL, NULL, '2016-12-01 00:00:01', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `melis_ecom_category_trans`
--

CREATE TABLE `melis_cms_category2_trans` (
  `catt2_id` int(11) NOT NULL COMMENT 'Category translation Id',
  `catt2_category_id` int(11) NOT NULL COMMENT 'Category Id',
  `catt2_lang_id` int(11) NOT NULL COMMENT 'Lang Id',
  `catt2_name` varchar(100) NOT NULL COMMENT 'Name of the category in the given lang',
  `catt2_description` text COMMENT 'Description of the category in the given lang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table stores the translations of categories';

--
-- Dumping data for table `melis_ecom_category_trans`
--

INSERT INTO `melis_cms_category2_trans` (`catt2_id`, `catt2_category_id`, `catt2_lang_id`, `catt2_name`, `catt2_description`) VALUES
(1, 1, 1, 'My catalog', NULL),
(2, 1, 2, 'Mon catalogue', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_category`
--
ALTER TABLE `melis_cms_category2`
  ADD PRIMARY KEY (`cat2_id`),
  ADD KEY `father_idx` (`cat2_father_cat_id`);

--
-- Indexes for table `melis_ecom_category_trans`
--
ALTER TABLE `melis_cms_category2_trans`
  ADD PRIMARY KEY (`catt2_id`),
  ADD KEY `category_idx` (`catt2_category_id`),
  ADD KEY `lang_idx` (`catt2_lang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_category`
--
ALTER TABLE `melis_cms_category2`
  MODIFY `cat2_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Category ID', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `melis_ecom_category_trans`
--
ALTER TABLE `melis_cms_category2_trans`
  MODIFY `catt2_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Category translation Id', AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE melis_cms_category2_sites`(
`cats2_id` INT NOT NULL AUTO_INCREMENT ,
`cats2_site_id` INT NOT NULL ,
`cats2_cat2_id` INT NOT NULL ,
 PRIMARY KEY (`cats2_id`)) ENGINE = InnoDB;

CREATE TABLE `melis_cms_category2_media` (
`catm2_id` INT NOT NULL AUTO_INCREMENT ,
`catm2_type` VARCHAR(10) NOT NULL ,
`catm2_path` VARCHAR(200) NOT NULL ,
`catm2_cat_id` INT NOT NULL , PRIMARY KEY (`catm2_id`)) ENGINE = InnoDB;