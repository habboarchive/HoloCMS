-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2008 at 04:03 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `holodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_homes_catalouge`
--

CREATE TABLE IF NOT EXISTS `cms_homes_catalouge` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` varchar(1) NOT NULL,
  `subtype` varchar(1) NOT NULL,
  `data` text NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL default '1',
  `category` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

--
-- Dumping data for table `cms_homes_catalouge`
--

INSERT INTO `cms_homes_catalouge` (`id`, `name`, `type`, `subtype`, `data`, `price`, `amount`, `category`) VALUES
(1, 'Genie Fire Head', '1', '0', 'geniefirehead', 2, 1, '19'),
(2, 'Trax SFX', '1', '0', 'trax_sfx', 1, 1, '3'),
(3, 'Trax Disco', '1', '0', 'trax_disco', 1, 1, '3'),
(4, 'Trax 8 Bit', '1', '0', 'trax_8_bit', 1, 1, '3'),
(5, 'Trax Electro', '1', '0', 'trax_electro', 1, 1, '3'),
(6, 'Trax Reggae', '1', '0', 'trax_reggae', 1, 1, '3'),
(7, 'Trax Ambient', '1', '0', 'trax_ambient', 1, 1, '3'),
(8, 'Trax Bling', '1', '0', 'trax_bling', 1, 1, '3'),
(9, 'Trax Heavy', '1', '0', 'trax_heavy', 1, 1, '3'),
(10, 'Trax Latin', '1', '0', 'trax_latin', 1, 1, '3'),
(11, 'Trax Rock', '1', '0', 'trax_rock', 1, 1, '3'),
(12, 'Animated Falling Rain', '4', '0', 'bg_rain', 3, 1, '27'),
(13, 'Notes', '3', '0', 'stickienote', 2, 5, '29'),
(14, 'Animated Blue Serpentine', '4', '0', 'bg_serpentine_darkblue', 3, 1, '27'),
(15, 'Animated Red Serpentine', '4', '0', 'bg_serpntine_darkred', 3, 1, '27'),
(16, 'Animated Brown Serpentine', '4', '0', 'bg_serpentine_1', 3, 1, '27'),
(17, 'Animated Pink Serpentine', '4', '0', 'bg_serpentine_2', 3, 1, '27'),
(18, 'Denim', '4', '0', 'bg_denim', 3, 1, '27'),
(19, 'Lace', '4', '0', 'bg_lace', 3, 1, '27'),
(20, 'Stiched', '4', '0', 'bg_stitched', 3, 1, '27'),
(21, 'Wood', '4', '0', 'bg_wood', 3, 1, '27'),
(22, 'Cork', '4', '0', 'bg_cork', 3, 1, '27'),
(23, 'Stone', '4', '0', 'bg_stone', 3, 1, '27'),
(24, 'Bricks', '4', '0', 'bg_pattern_bricks', 3, 1, '27'),
(25, 'Ruled Paper', '4', '0', 'bg_ruled_paper', 3, 1, '27'),
(26, 'Grass', '4', '0', 'bg_grass', 3, 1, '27'),
(27, 'Hotel', '4', '0', 'bg_hotel', 3, 1, '27'),
(28, 'Bubble', '4', '0', 'bg_bubble', 3, 1, '27'),
(29, 'Bobba Skulls', '4', '0', 'bg_pattern_bobbaskulls1', 3, 1, '27'),
(30, 'Deep Space', '4', '0', 'bg_pattern_space', 3, 1, '27'),
(31, 'Submarine', '4', '0', 'bg_image_submarine', 3, 1, '27'),
(32, 'Metal II', '4', '0', 'bg_metal2', 3, 1, '27'),
(33, 'Broken Glass', '4', '0', 'bg_broken_glass', 3, 1, '27'),
(34, 'Clouds', '4', '0', 'bg_pattern_clouds', 3, 1, '27'),
(35, 'Comic', '4', '0', 'bg_comic2', 3, 1, '27'),
(36, 'Floral 1', '4', '0', 'bg_pattern_floral_01', 3, 1, '27'),
(37, 'A', '1', '0', 'a', 1, 1, '5'),
(38, 'B', '1', '0', 'b_2', 1, 1, '5'),
(39, 'C', '1', '0', 'c', 1, 1, '5'),
(40, 'D', '1', '0', 'd', 1, 1, '5'),
(41, 'E', '1', '0', 'e', 1, 1, '5'),
(42, 'F', '1', '0', 'f', 1, 1, '5'),
(43, 'G', '1', '0', 'g', 1, 1, '5'),
(44, 'H', '1', '0', 'h', 1, 1, '5'),
(45, 'I', '1', '0', 'i', 1, 1, '5'),
(46, 'J', '1', '0', 'j', 1, 1, '5'),
(47, 'K', '1', '0', 'k', 1, 1, '5'),
(48, 'L', '1', '0', 'l', 1, 1, '5'),
(49, 'M', '1', '0', 'm', 1, 1, '5'),
(50, 'N', '1', '0', 'n', 1, 1, '5'),
(51, 'O', '1', '0', 'o', 1, 1, '5'),
(52, 'P', '1', '0', 'p', 1, 1, '5'),
(53, 'Q', '1', '0', 'q', 1, 1, '5'),
(54, 'R', '1', '0', 'r', 1, 1, '5'),
(55, 'S', '1', '0', 's', 1, 1, '5'),
(56, 'T', '1', '0', 't', 1, 1, '5'),
(57, 'U', '1', '0', 'u', 1, 1, '5'),
(58, 'V', '1', '0', 'v', 1, 1, '5'),
(59, 'W', '1', '0', 'w', 1, 1, '5'),
(60, 'X', '1', '0', 'x', 1, 1, '5'),
(61, 'Y', '1', '0', 'y', 1, 1, '5'),
(62, 'Z', '1', '0', 'z', 1, 1, '5'),
(63, 'Bling Star', '1', '0', 'bling_star', 1, 1, '6'),
(64, 'Bling A', '1', '0', 'bling_a', 1, 1, '6'),
(65, 'Bling B', '1', '0', 'bling_b', 1, 1, '6'),
(66, 'Bling C', '1', '0', 'bling_c', 1, 1, '6'),
(67, 'Bling D', '1', '0', 'bling_d', 1, 1, '6'),
(68, 'Bling E', '1', '0', 'bling_e', 1, 1, '6'),
(69, 'Bling F', '1', '0', 'bling_f', 1, 1, '6'),
(70, 'Bling G', '1', '0', 'bling_g', 1, 1, '6'),
(71, 'Bling H', '1', '0', 'bling_h', 1, 1, '6'),
(72, 'Bling I', '1', '0', 'bling_i', 1, 1, '6'),
(73, 'Bling J', '1', '0', 'bling_j', 1, 1, '6'),
(74, 'Bling K', '1', '0', 'bling_k', 1, 1, '6'),
(75, 'Bling L', '1', '0', 'bling_l', 1, 1, '6'),
(76, 'Bling M', '1', '0', 'bling_m', 1, 1, '6'),
(77, 'Bling N', '1', '0', 'bling_n', 1, 1, '6'),
(78, 'Bling O', '1', '0', 'bling_o', 1, 1, '6'),
(79, 'Bling P', '1', '0', 'bling_p', 1, 1, '6'),
(80, 'Bling Q', '1', '0', 'bling_q', 1, 1, '6'),
(81, 'Bling R', '1', '0', 'bling_r', 1, 1, '6'),
(82, 'Bling S', '1', '0', 'bling_s', 1, 1, '6'),
(83, 'Bling T', '1', '0', 'bling_t', 1, 1, '6'),
(84, 'Bling U', '1', '0', 'bling_u', 1, 1, '6'),
(85, 'Bling V', '1', '0', 'bling_v', 1, 1, '6'),
(86, 'Bling W', '1', '0', 'bling_w', 1, 1, '6'),
(87, 'Bling X', '1', '0', 'bling_x', 1, 1, '6'),
(88, 'Bling Y', '1', '0', 'bling_y', 1, 1, '6'),
(89, 'Bling Z', '1', '0', 'bling_z', 1, 1, '6'),
(90, 'Bling Underscore', '1', '0', 'bling_underscore', 1, 1, '6'),
(91, 'Bling Comma', '1', '0', 'bling_comma', 1, 1, '6'),
(92, 'Bling Dot', '1', '0', 'bling_dot', 1, 1, '6'),
(93, 'Bling Exclamation', '1', '0', 'bling_exclamation', 1, 1, '6'),
(94, 'Bling Question', '1', '0', 'bling_question', 1, 1, '6'),
(95, 'European Letter 3', '1', '0', 'a_with_circle', 1, 1, '5'),
(96, 'European Letter 1', '1', '0', 'a_with_dots', 1, 1, '5'),
(97, 'European Letter 2', '1', '0', 'o_with_dots', 1, 1, '5'),
(98, 'Dot', '1', '0', 'dot', 1, 1, '5'),
(99, 'Acsent 1', '1', '0', 'acsent1', 1, 1, '5'),
(100, 'Acsent 2', '1', '0', 'acsent2', 1, 1, '5'),
(101, 'Underscore', '1', '0', 'underscore', 1, 1, '5'),
(102, 'Holograph Emulator', '1', '0', 'sticker_holograph', 5, 1, '19');
