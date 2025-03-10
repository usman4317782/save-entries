-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 05:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `save_entries`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `description`, `created_at`) VALUES
(7, 'Brand G', 'Description for Brand G', '2025-02-24 11:08:40'),
(8, 'Brand H', 'Description for Brand H', '2025-02-24 11:08:40'),
(9, 'Brand I', 'Description for Brand I', '2025-02-24 11:08:40'),
(10, 'Brand J', 'Description for Brand J', '2025-02-24 11:08:40'),
(11, 'Brand K', 'Description for Brand K', '2025-02-24 11:08:40'),
(12, 'Brand L', 'Description for Brand L', '2025-02-24 11:08:40'),
(13, 'Brand M', 'Description for Brand M', '2025-02-24 11:08:40'),
(14, 'Brand N', 'Description for Brand N', '2025-02-24 11:08:40'),
(15, 'Brand O', 'Description for Brand O', '2025-02-24 11:08:40'),
(16, 'Brand P', 'Description for Brand P', '2025-02-24 11:08:40'),
(17, 'Brand Q', 'Description for Brand Q', '2025-02-24 11:08:40'),
(18, 'Brand R', 'Description for Brand R', '2025-02-24 11:08:40'),
(19, 'Brand S', 'Description for Brand S', '2025-02-24 11:08:40'),
(20, 'Brand T', 'Description for Brand T', '2025-02-24 11:08:40'),
(21, 'Brand U', 'Description for Brand U', '2025-02-24 11:08:40'),
(22, 'Brand V', 'Description for Brand V', '2025-02-24 11:08:40'),
(23, 'Brand W', 'Description for Brand W', '2025-02-24 11:08:40'),
(24, 'Brand X', 'Description for Brand X', '2025-02-24 11:08:40'),
(25, 'Brand Y', 'Description for Brand Y', '2025-02-24 11:08:40'),
(26, 'Brand Z', 'Description for Brand Z', '2025-02-24 11:08:40'),
(27, 'Brand AA', 'Description for Brand AA', '2025-02-24 11:08:40'),
(28, 'Brand AB', 'Description for Brand AB', '2025-02-24 11:08:40'),
(29, 'Brand AC', 'Description for Brand AC', '2025-02-24 11:08:40'),
(30, 'Brand AD', 'Description for Brand AD', '2025-02-24 11:08:40'),
(31, 'Brand AE', 'Description for Brand AE', '2025-02-24 11:08:40'),
(32, 'Brand AF', 'Description for Brand AF', '2025-02-24 11:08:40'),
(33, 'Brand AG', 'Description for Brand AG', '2025-02-24 11:08:40'),
(34, 'Brand AH', 'Description for Brand AH', '2025-02-24 11:08:40'),
(35, 'Brand AI', 'Description for Brand AI', '2025-02-24 11:08:40'),
(36, 'Brand AJ', 'Description for Brand AJ', '2025-02-24 11:08:40'),
(37, 'Brand AK', 'Description for Brand AK', '2025-02-24 11:08:40'),
(38, 'Brand AL', 'Description for Brand AL', '2025-02-24 11:08:40'),
(39, 'Brand AM', 'Description for Brand AM', '2025-02-24 11:08:40'),
(40, 'Brand AN', 'Description for Brand AN', '2025-02-24 11:08:40'),
(41, 'Brand AO', 'Description for Brand AO', '2025-02-24 11:08:40'),
(42, 'Brand AP', 'Description for Brand AP', '2025-02-24 11:08:40'),
(43, 'Brand AQ', 'Description for Brand AQ', '2025-02-24 11:08:40'),
(44, 'Brand AR', 'Description for Brand AR', '2025-02-24 11:08:40'),
(45, 'Brand AS', 'Description for Brand AS', '2025-02-24 11:08:40'),
(46, 'Brand AT', 'Description for Brand AT', '2025-02-24 11:08:40'),
(47, 'Brand AU', 'Description for Brand AU', '2025-02-24 11:08:40'),
(48, 'Brand AV', 'Description for Brand AV', '2025-02-24 11:08:40'),
(49, 'Brand AW', 'Description for Brand AW', '2025-02-24 11:08:40'),
(50, 'Brand AX', 'Description for Brand AX', '2025-02-24 11:08:40'),
(51, 'Brand AY', 'Description for Brand AY', '2025-02-24 11:08:40'),
(52, 'Brand AZ', 'Description for Brand AZ', '2025-02-24 11:08:40'),
(53, 'Brand BA', 'Description for Brand BA', '2025-02-24 11:08:40'),
(54, 'Brand BB', 'Description for Brand BB', '2025-02-24 11:08:40'),
(55, 'Brand BC', 'Description for Brand BC', '2025-02-24 11:08:40'),
(56, 'Brand BD', 'Description for Brand BD', '2025-02-24 11:08:40'),
(57, 'Brand BE', 'Description for Brand BE', '2025-02-24 11:08:40'),
(58, 'Brand BF', 'Description for Brand BF', '2025-02-24 11:08:40'),
(59, 'Brand BG', 'Description for Brand BG', '2025-02-24 11:08:40'),
(60, 'Brand BH', 'Description for Brand BH', '2025-02-24 11:08:40'),
(61, 'Brand BI', 'Description for Brand BI', '2025-02-24 11:08:40'),
(62, 'Brand BJ', 'Description for Brand BJ', '2025-02-24 11:08:40'),
(63, 'Brand BK', 'Description for Brand BK', '2025-02-24 11:08:40'),
(64, 'Brand BL', 'Description for Brand BL', '2025-02-24 11:08:40'),
(65, 'Brand BM', 'Description for Brand BM', '2025-02-24 11:08:40'),
(66, 'Brand BN', 'Description for Brand BN', '2025-02-24 11:08:40'),
(67, 'Brand BO', 'Description for Brand BO', '2025-02-24 11:08:40'),
(68, 'Brand BP', 'Description for Brand BP', '2025-02-24 11:08:40'),
(69, 'Brand BQ', 'Description for Brand BQ', '2025-02-24 11:08:40'),
(70, 'Brand BR', 'Description for Brand BR', '2025-02-24 11:08:40'),
(71, 'Brand BS', 'Description for Brand BS', '2025-02-24 11:08:40'),
(72, 'Brand BT', 'Description for Brand BT', '2025-02-24 11:08:40'),
(73, 'Brand BU', 'Description for Brand BU', '2025-02-24 11:08:40'),
(74, 'Brand BV', 'Description for Brand BV', '2025-02-24 11:08:40'),
(75, 'Brand BW', 'Description for Brand BW', '2025-02-24 11:08:40'),
(76, 'Brand BX', 'Description for Brand BX', '2025-02-24 11:08:40'),
(77, 'Brand BY', 'Description for Brand BY', '2025-02-24 11:08:40'),
(78, 'Brand BZ', 'Description for Brand BZ', '2025-02-24 11:08:40'),
(79, 'Brand CA', 'Description for Brand CA', '2025-02-24 11:08:40'),
(80, 'Brand CB', 'Description for Brand CB', '2025-02-24 11:08:40'),
(81, 'Brand CC', 'Description for Brand CC', '2025-02-24 11:08:40'),
(82, 'Brand CD', 'Description for Brand CD', '2025-02-24 11:08:40'),
(83, 'Brand CE', 'Description for Brand CE', '2025-02-24 11:08:40'),
(84, 'Brand CF', 'Description for Brand CF', '2025-02-24 11:08:40'),
(85, 'Brand CG', 'Description for Brand CG', '2025-02-24 11:08:40'),
(86, 'Brand CH', 'Description for Brand CH', '2025-02-24 11:08:40'),
(87, 'Brand CI', 'Description for Brand CI', '2025-02-24 11:08:40'),
(88, 'Brand CJ', 'Description for Brand CJ', '2025-02-24 11:08:40'),
(89, 'Brand CK', 'Description for Brand CK', '2025-02-24 11:08:40'),
(90, 'Brand CL', 'Description for Brand CL', '2025-02-24 11:08:40'),
(91, 'Brand CM', 'Description for Brand CM', '2025-02-24 11:08:40'),
(92, 'Brand CN', 'Description for Brand CN', '2025-02-24 11:08:40'),
(93, 'Brand CO', 'Description for Brand CO', '2025-02-24 11:08:40'),
(94, 'Brand CP', 'Description for Brand CP', '2025-02-24 11:08:40'),
(95, 'Brand CQ', 'Description for Brand CQ', '2025-02-24 11:08:40'),
(96, 'Brand CR', 'Description for Brand CR', '2025-02-24 11:08:40'),
(97, 'Brand CS', 'Description for Brand CS', '2025-02-24 11:08:40'),
(98, 'Brand CT', 'Description for Brand CT', '2025-02-24 11:08:40'),
(99, 'Brand CU', 'Description for Brand CU', '2025-02-24 11:08:40'),
(100, 'Brand CV', 'Description for Brand CV', '2025-02-24 11:08:40'),
(101, 'Brand CW', 'Description for Brand CW', '2025-02-24 11:08:40'),
(102, 'Brand CX', 'Description for Brand CX', '2025-02-24 11:08:40'),
(103, 'Brand CY', 'Description for Brand CY', '2025-02-24 11:08:40'),
(104, 'Brand CZ', 'Description for Brand CZ', '2025-02-24 11:08:40'),
(105, 'Brand DA', 'Description for Brand DA', '2025-02-24 11:08:40'),
(106, 'Brand DB', 'Description for Brand DB', '2025-02-24 11:08:40'),
(107, 'Brand DC', 'Description for Brand DC', '2025-02-24 11:08:40'),
(108, 'Brand DD', 'Description for Brand DD', '2025-02-24 11:08:40'),
(109, 'Brand DE', 'Description for Brand DE', '2025-02-24 11:08:40'),
(110, 'Brand DF', 'Description for Brand DF', '2025-02-24 11:08:40'),
(111, 'Brand DG', 'Description for Brand DG', '2025-02-24 11:08:40'),
(112, 'Brand DH', 'Description for Brand DH', '2025-02-24 11:08:40'),
(113, 'Brand DI', 'Description for Brand DI', '2025-02-24 11:08:40'),
(114, 'Brand DJ', 'Description for Brand DJ', '2025-02-24 11:08:40'),
(115, 'Brand DK', 'Description for Brand DK', '2025-02-24 11:08:40'),
(116, 'Brand DL', 'Description for Brand DL', '2025-02-24 11:08:40'),
(117, 'Brand DM', 'Description for Brand DM', '2025-02-24 11:08:40'),
(118, 'Brand DN', 'Description for Brand DN', '2025-02-24 11:08:40'),
(119, 'Brand DO', 'Description for Brand DO', '2025-02-24 11:08:40'),
(120, 'Brand DP', 'Description for Brand DP', '2025-02-24 11:08:40'),
(121, 'Brand DQ', 'Description for Brand DQ', '2025-02-24 11:08:40'),
(122, 'Brand DR', 'Description for Brand DR', '2025-02-24 11:08:40'),
(123, 'Brand DS', 'Description for Brand DS', '2025-02-24 11:08:40'),
(124, 'Brand DT', 'Description for Brand DT', '2025-02-24 11:08:40'),
(125, 'Brand DU', 'Description for Brand DU', '2025-02-24 11:08:40'),
(126, 'Brand DV', 'Description for Brand DV', '2025-02-24 11:08:40'),
(127, 'Brand DW', 'Description for Brand DW', '2025-02-24 11:08:40'),
(128, 'Brand DX', 'Description for Brand DX', '2025-02-24 11:08:40'),
(129, 'Brand DY', 'Description for Brand DY', '2025-02-24 11:08:40'),
(130, 'Brand DZ', 'Description for Brand DZ', '2025-02-24 11:08:40'),
(131, 'Brand EA', 'Description for Brand EA', '2025-02-24 11:08:40'),
(132, 'Brand EB', 'Description for Brand EB', '2025-02-24 11:08:40'),
(133, 'Brand EC', 'Description for Brand EC', '2025-02-24 11:08:40'),
(134, 'Brand ED', 'Description for Brand ED', '2025-02-24 11:08:40'),
(135, 'Brand EE', 'Description for Brand EE', '2025-02-24 11:08:40'),
(136, 'Brand EF', 'Description for Brand EF', '2025-02-24 11:08:40'),
(137, 'Brand EG', 'Description for Brand EG', '2025-02-24 11:08:40'),
(138, 'Brand EH', 'Description for Brand EH', '2025-02-24 11:08:40'),
(139, 'Brand EI', 'Description for Brand EI', '2025-02-24 11:08:40'),
(140, 'Brand EJ', 'Description for Brand EJ', '2025-02-24 11:08:40'),
(141, 'Brand EK', 'Description for Brand EK', '2025-02-24 11:08:40'),
(142, 'Brand EL', 'Description for Brand EL', '2025-02-24 11:08:40'),
(143, 'Brand EM', 'Description for Brand EM', '2025-02-24 11:08:40'),
(144, 'Brand EN', 'Description for Brand EN', '2025-02-24 11:08:40'),
(145, 'Brand EO', 'Description for Brand EO', '2025-02-24 11:08:40'),
(146, 'Brand EP', 'Description for Brand EP', '2025-02-24 11:08:40'),
(147, 'Brand EQ', 'Description for Brand EQ', '2025-02-24 11:08:40'),
(148, 'Brand ER', 'Description for Brand ER', '2025-02-24 11:08:40'),
(149, 'Brand ES', 'Description for Brand ES', '2025-02-24 11:08:40'),
(150, 'Brand ET', 'Description for Brand ET', '2025-02-24 11:08:40'),
(151, 'Brand EU', 'Description for Brand EU', '2025-02-24 11:08:40'),
(152, 'Brand EV', 'Description for Brand EV', '2025-02-24 11:08:40'),
(153, 'Brand EW', 'Description for Brand EW', '2025-02-24 11:08:40'),
(154, 'Brand EX', 'Description for Brand EX', '2025-02-24 11:08:40'),
(155, 'Brand EY', 'Description for Brand EY', '2025-02-24 11:08:40'),
(156, 'Brand EZ', 'Description for Brand EZ', '2025-02-24 11:08:40'),
(157, 'Brand FA', 'Description for Brand FA', '2025-02-24 11:08:40'),
(158, 'Brand FB', 'Description for Brand FB', '2025-02-24 11:08:40'),
(159, 'Brand FC', 'Description for Brand FC', '2025-02-24 11:08:40'),
(160, 'Brand FD', 'Description for Brand FD', '2025-02-24 11:08:40'),
(161, 'Brand FE', 'Description for Brand FE', '2025-02-24 11:08:40'),
(162, 'Brand FF', 'Description for Brand FF', '2025-02-24 11:08:40'),
(163, 'Brand FG', 'Description for Brand FG', '2025-02-24 11:08:40'),
(164, 'Brand FH', 'Description for Brand FH', '2025-02-24 11:08:40'),
(165, 'Brand FI', 'Description for Brand FI', '2025-02-24 11:08:40'),
(166, 'Brand FJ', 'Description for Brand FJ', '2025-02-24 11:08:40'),
(167, 'Brand FK', 'Description for Brand FK', '2025-02-24 11:08:40'),
(168, 'Brand FL', 'Description for Brand FL', '2025-02-24 11:08:40'),
(169, 'Brand FM', 'Description for Brand FM', '2025-02-24 11:08:40'),
(170, 'Brand FN', 'Description for Brand FN', '2025-02-24 11:08:40'),
(171, 'Brand FO', 'Description for Brand FO', '2025-02-24 11:08:40'),
(172, 'Brand FP', 'Description for Brand FP', '2025-02-24 11:08:40'),
(173, 'Brand FQ', 'Description for Brand FQ', '2025-02-24 11:08:40'),
(174, 'Brand FR', 'Description for Brand FR', '2025-02-24 11:08:40'),
(175, 'Brand FS', 'Description for Brand FS', '2025-02-24 11:08:40'),
(176, 'Brand FT', 'Description for Brand FT', '2025-02-24 11:08:40'),
(177, 'Brand FU', 'Description for Brand FU', '2025-02-24 11:08:40'),
(178, 'Brand FV', 'Description for Brand FV', '2025-02-24 11:08:40'),
(179, 'Brand FW', 'Description for Brand FW', '2025-02-24 11:08:40'),
(180, 'Brand FX', 'Description for Brand FX', '2025-02-24 11:08:40'),
(181, 'Brand FY', 'Description for Brand FY', '2025-02-24 11:08:40'),
(182, 'Brand FZ', 'Description for Brand FZ', '2025-02-24 11:08:40'),
(183, 'Brand GA', 'Description for Brand GA', '2025-02-24 11:08:40'),
(184, 'Brand GB', 'Description for Brand GB', '2025-02-24 11:08:40'),
(185, 'Brand GC', 'Description for Brand GC', '2025-02-24 11:08:40'),
(186, 'Brand GD', 'Description for Brand GD', '2025-02-24 11:08:40'),
(187, 'Brand GE', 'Description for Brand GE', '2025-02-24 11:08:40'),
(188, 'Brand GF', 'Description for Brand GF', '2025-02-24 11:08:40'),
(189, 'Brand GG', 'Description for Brand GG', '2025-02-24 11:08:40'),
(190, 'Brand GH', 'Description for Brand GH', '2025-02-24 11:08:40'),
(191, 'Brand GI', 'Description for Brand GI', '2025-02-24 11:08:40'),
(192, 'Brand GJ', 'Description for Brand GJ', '2025-02-24 11:08:40'),
(193, 'Brand GK', 'Description for Brand GK', '2025-02-24 11:08:40'),
(194, 'Brand GL', 'Description for Brand GL', '2025-02-24 11:08:40'),
(195, 'Brand GM', 'Description for Brand GM', '2025-02-24 11:08:40'),
(196, 'Brand GN', 'Description for Brand GN', '2025-02-24 11:08:40'),
(197, 'Brand GO', 'Description for Brand GO', '2025-02-24 11:08:40'),
(198, 'Brand GP', 'Description for Brand GP', '2025-02-24 11:08:40'),
(199, 'Brand GQ', 'Description for Brand GQ', '2025-02-24 11:08:40'),
(200, 'Brand GR', 'Description for Brand GR', '2025-02-24 11:08:40'),
(201, 'Brand GS', 'Description for Brand GS', '2025-02-24 11:08:40'),
(202, 'Brand GT', 'Description for Brand GT', '2025-02-24 11:08:40'),
(203, 'Brand GU', 'Description for Brand GU', '2025-02-24 11:08:40'),
(204, 'Brand GV', 'Description for Brand GV', '2025-02-24 11:08:40'),
(205, 'Brand GW', 'Description for Brand GW', '2025-02-24 11:08:40'),
(206, 'Brand GX', 'Description for Brand GX', '2025-02-24 11:08:40'),
(207, 'Brand GY', 'Description for Brand GY', '2025-02-24 11:08:40'),
(208, 'Brand GZ', 'Description for Brand GZ', '2025-02-24 11:08:40'),
(209, 'Brand HA', 'Description for Brand HA', '2025-02-24 11:08:40'),
(210, 'Brand HB', 'Description for Brand HB', '2025-02-24 11:08:40'),
(211, 'Brand HC', 'Description for Brand HC', '2025-02-24 11:08:40'),
(212, 'Brand HD', 'Description for Brand HD', '2025-02-24 11:08:40'),
(213, 'Brand HE', 'Description for Brand HE', '2025-02-24 11:08:40'),
(214, 'Brand HF', 'Description for Brand HF', '2025-02-24 11:08:40'),
(215, 'Brand HG', 'Description for Brand HG', '2025-02-24 11:08:40'),
(216, 'Brand HH', 'Description for Brand HH', '2025-02-24 11:08:40'),
(217, 'Brand HI', 'Description for Brand HI', '2025-02-24 11:08:40'),
(218, 'Brand HJ', 'Description for Brand HJ', '2025-02-24 11:08:40'),
(219, 'Brand HK', 'Description for Brand HK', '2025-02-24 11:08:40'),
(220, 'Brand HL', 'Description for Brand HL', '2025-02-24 11:08:40'),
(221, 'Brand HM', 'Description for Brand HM', '2025-02-24 11:08:40'),
(222, 'Brand HN', 'Description for Brand HN', '2025-02-24 11:08:40'),
(223, 'Brand HO', 'Description for Brand HO', '2025-02-24 11:08:40'),
(224, 'Brand HP', 'Description for Brand HP', '2025-02-24 11:08:40'),
(225, 'Brand HQ', 'Description for Brand HQ', '2025-02-24 11:08:40'),
(226, 'Brand HR', 'Description for Brand HR', '2025-02-24 11:08:40'),
(227, 'Brand HS', 'Description for Brand HS', '2025-02-24 11:08:40'),
(228, 'Brand HT', 'Description for Brand HT', '2025-02-24 11:08:40'),
(229, 'Brand HU', 'Description for Brand HU', '2025-02-24 11:08:40'),
(230, 'Brand HV', 'Description for Brand HV', '2025-02-24 11:08:40'),
(231, 'Brand HW', 'Description for Brand HW', '2025-02-24 11:08:40'),
(232, 'Brand HX', 'Description for Brand HX', '2025-02-24 11:08:40'),
(233, 'Brand HY', 'Description for Brand HY', '2025-02-24 11:08:40'),
(234, 'Brand HZ', 'Description for Brand HZ', '2025-02-24 11:08:40'),
(235, 'Brand IA', 'Description for Brand IA', '2025-02-24 11:08:40'),
(236, 'Brand IB', 'Description for Brand IB', '2025-02-24 11:08:40'),
(237, 'Brand IC', 'Description for Brand IC', '2025-02-24 11:08:40'),
(238, 'Brand ID', 'Description for Brand ID', '2025-02-24 11:08:40'),
(239, 'Brand IE', 'Description for Brand IE', '2025-02-24 11:08:40'),
(240, 'Brand IF', 'Description for Brand IF', '2025-02-24 11:08:40'),
(241, 'Brand IG', 'Description for Brand IG', '2025-02-24 11:08:40'),
(242, 'Brand IH', 'Description for Brand IH', '2025-02-24 11:08:40'),
(243, 'Brand II', 'Description for Brand II', '2025-02-24 11:08:40'),
(244, 'Brand IJ', 'Description for Brand IJ', '2025-02-24 11:08:40'),
(245, 'Brand IK', 'Description for Brand IK', '2025-02-24 11:08:40'),
(246, 'Brand IL', 'Description for Brand IL', '2025-02-24 11:08:40'),
(247, 'Brand IM', 'Description for Brand IM', '2025-02-24 11:08:40'),
(248, 'Brand IN', 'Description for Brand IN', '2025-02-24 11:08:40'),
(249, 'Brand IO', 'Description for Brand IO', '2025-02-24 11:08:40'),
(250, 'Brand IP', 'Description for Brand IP', '2025-02-24 11:08:40'),
(251, 'Brand IQ', 'Description for Brand IQ', '2025-02-24 11:08:40'),
(252, 'Brand IR', 'Description for Brand IR', '2025-02-24 11:08:40'),
(253, 'Brand IS', 'Description for Brand IS', '2025-02-24 11:08:40'),
(254, 'Brand IT', 'Description for Brand IT', '2025-02-24 11:08:40'),
(255, 'Brand IU', 'Description for Brand IU', '2025-02-24 11:08:40'),
(263, 'Brand JC', 'Description for Brand JC', '2025-02-24 11:08:40'),
(264, 'Brand JD', 'Description for Brand JD', '2025-02-24 11:08:40'),
(265, 'Brand JE', 'Description for Brand JE', '2025-02-24 11:08:40'),
(266, 'Brand JF', 'Description for Brand JF', '2025-02-24 11:08:40'),
(267, 'Brand JG', 'Description for Brand JG', '2025-02-24 11:08:40'),
(268, 'Brand JH', 'Description for Brand JH', '2025-02-24 11:08:40'),
(269, 'Brand JI', 'Description for Brand JI', '2025-02-24 11:08:40'),
(270, 'Brand JJ', 'Description for Brand JJ', '2025-02-24 11:08:40'),
(271, 'Brand JK', 'Description for Brand JK', '2025-02-24 11:08:40'),
(272, 'Brand JL', 'Description for Brand JL', '2025-02-24 11:08:40'),
(273, 'Brand JM', 'Description for Brand JM', '2025-02-24 11:08:40'),
(274, 'Brand JN', 'Description for Brand JN', '2025-02-24 11:08:40'),
(275, 'Brand JO', 'Description for Brand JO', '2025-02-24 11:08:40'),
(276, 'Brand JP', 'Description for Brand JP', '2025-02-24 11:08:40'),
(277, 'Brand JQ', 'Description for Brand JQ', '2025-02-24 11:08:40'),
(278, 'Brand JR', 'Description for Brand JR', '2025-02-24 11:08:40'),
(279, 'Brand JS', 'Description for Brand JS', '2025-02-24 11:08:40'),
(280, 'Brand JT', 'Description for Brand JT', '2025-02-24 11:08:40'),
(281, 'Brand JU', 'Description for Brand JU', '2025-02-24 11:08:40'),
(282, 'Brand JV', 'Description for Brand JV', '2025-02-24 11:08:40'),
(283, 'Brand JW', 'Description for Brand JW', '2025-02-24 11:08:40'),
(284, 'Brand JX', 'Description for Brand JX', '2025-02-24 11:08:40'),
(285, 'Brand JY', 'Description for Brand JY', '2025-02-24 11:08:40'),
(286, 'Brand JZ', 'Description for Brand JZ', '2025-02-24 11:08:40'),
(287, 'Brand KA', 'Description for Brand KA', '2025-02-24 11:08:40'),
(288, 'Brand KB', 'Description for Brand KB', '2025-02-24 11:08:40'),
(289, 'Brand KC', 'Description for Brand KC', '2025-02-24 11:08:40'),
(290, 'Brand KD', 'Description for Brand KD', '2025-02-24 11:08:40'),
(291, 'Brand KE', 'Description for Brand KE', '2025-02-24 11:08:40'),
(292, 'Brand KF', 'Description for Brand KF', '2025-02-24 11:08:40'),
(293, 'Brand KG', 'Description for Brand KG', '2025-02-24 11:08:40'),
(294, 'Brand KH', 'Description for Brand KH', '2025-02-24 11:08:40'),
(295, 'Brand KI', 'Description for Brand KI', '2025-02-24 11:08:40'),
(296, 'Brand KJ', 'Description for Brand KJ', '2025-02-24 11:08:40'),
(297, 'Brand KK', 'Description for Brand KK', '2025-02-24 11:08:40'),
(298, 'Brand KL', 'Description for Brand KL', '2025-02-24 11:08:40'),
(299, 'Brand KM', 'Description for Brand KM', '2025-02-24 11:08:40'),
(300, 'Brand KN', 'Description for Brand KN', '2025-02-24 11:08:40'),
(301, 'Brand KO', 'Description for Brand KO', '2025-02-24 11:08:40'),
(302, 'Brand KP', 'Description for Brand KP', '2025-02-24 11:08:40'),
(303, 'Brand KQ', 'Description for Brand KQ', '2025-02-24 11:08:40'),
(304, 'Brand KR', 'Description for Brand KR', '2025-02-24 11:08:40'),
(305, 'Brand KS', 'Description for Brand KS', '2025-02-24 11:08:40'),
(306, 'Brand KT', 'Description for Brand KT', '2025-02-24 11:08:40'),
(307, 'Brand KU', 'Description for Brand KU', '2025-02-24 11:08:40'),
(308, 'Brand KV', 'Description for Brand KV', '2025-02-24 11:08:40'),
(309, 'Brand KW', 'Description for Brand KW', '2025-02-24 11:08:40'),
(310, 'Brand KX', 'Description for Brand KX', '2025-02-24 11:08:40'),
(311, 'Brand KY', 'Description for Brand KY', '2025-02-24 11:08:40'),
(312, 'Brand KZ', 'Description for Brand KZ', '2025-02-24 11:08:40'),
(313, 'Brand LA', 'Description for Brand LA', '2025-02-24 11:08:40'),
(314, 'Brand LB', 'Description for Brand LB', '2025-02-24 11:08:40'),
(315, 'Brand LC', 'Description for Brand LC', '2025-02-24 11:08:40'),
(316, 'Brand LD', 'Description for Brand LD', '2025-02-24 11:08:40'),
(317, 'Brand LE', 'Description for Brand LE', '2025-02-24 11:08:40'),
(318, 'Brand LF', 'Description for Brand LF', '2025-02-24 11:08:40'),
(319, 'Brand LG', 'Description for Brand LG', '2025-02-24 11:08:40'),
(320, 'Brand LH', 'Description for Brand LH', '2025-02-24 11:08:40'),
(321, 'Brand LI', 'Description for Brand LI', '2025-02-24 11:08:40'),
(322, 'Brand LJ', 'Description for Brand LJ', '2025-02-24 11:08:40'),
(323, 'Brand LK', 'Description for Brand LK', '2025-02-24 11:08:40'),
(324, 'Brand LL', 'Description for Brand LL', '2025-02-24 11:08:40'),
(325, 'Brand LM', 'Description for Brand LM', '2025-02-24 11:08:40'),
(326, 'Brand LN', 'Description for Brand LN', '2025-02-24 11:08:40'),
(327, 'Brand LO', 'Description for Brand LO', '2025-02-24 11:08:40'),
(328, 'Brand LP', 'Description for Brand LP', '2025-02-24 11:08:40'),
(329, 'Brand LQ', 'Description for Brand LQ', '2025-02-24 11:08:40'),
(330, 'Brand LR', 'Description for Brand LR', '2025-02-24 11:08:40'),
(331, 'Brand LS', 'Description for Brand LS', '2025-02-24 11:08:40'),
(332, 'Brand LT', 'Description for Brand LT', '2025-02-24 11:08:40'),
(333, 'Brand LU', 'Description for Brand LU', '2025-02-24 11:08:40'),
(334, 'Brand LV', 'Description for Brand LV', '2025-02-24 11:08:40'),
(335, 'Brand LW', 'Description for Brand LW', '2025-02-24 11:08:40'),
(336, 'Brand LX', 'Description for Brand LX', '2025-02-24 11:08:40'),
(337, 'Brand LY', 'Description for Brand LY', '2025-02-24 11:08:40'),
(338, 'Brand LZ', 'Description for Brand LZ', '2025-02-24 11:08:40'),
(339, 'Brand MA', 'Description for Brand MA', '2025-02-24 11:08:40'),
(340, 'Brand MB', 'Description for Brand MB', '2025-02-24 11:08:40'),
(341, 'Brand MC', 'Description for Brand MC', '2025-02-24 11:08:40'),
(342, 'Brand MD', 'Description for Brand MD', '2025-02-24 11:08:40'),
(343, 'Brand ME', 'Description for Brand ME', '2025-02-24 11:08:40'),
(344, 'Brand MF', 'Description for Brand MF', '2025-02-24 11:08:40'),
(345, 'Brand MG', 'Description for Brand MG', '2025-02-24 11:08:40'),
(346, 'Brand MH', 'Description for Brand MH', '2025-02-24 11:08:40'),
(347, 'Brand MI', 'Description for Brand MI', '2025-02-24 11:08:40'),
(348, 'Brand MJ', 'Description for Brand MJ', '2025-02-24 11:08:40'),
(349, 'Brand MK', 'Description for Brand MK', '2025-02-24 11:08:40'),
(350, 'Brand ML', 'Description for Brand ML', '2025-02-24 11:08:40'),
(351, 'Brand MM', 'Description for Brand MM', '2025-02-24 11:08:40'),
(352, 'Brand MN', 'Description for Brand MN', '2025-02-24 11:08:40'),
(353, 'Brand MO', 'Description for Brand MO', '2025-02-24 11:08:40'),
(354, 'Brand MP', 'Description for Brand MP', '2025-02-24 11:08:40'),
(355, 'Brand MQ', 'Description for Brand MQ', '2025-02-24 11:08:40'),
(356, 'Brand MR', 'Description for Brand MR', '2025-02-24 11:08:40'),
(357, 'Brand MS', 'Description for Brand MS', '2025-02-24 11:08:40'),
(358, 'Brand MT', 'Description for Brand MT', '2025-02-24 11:08:40'),
(359, 'Brand MU', 'Description for Brand MU', '2025-02-24 11:08:40'),
(360, 'Brand MV', 'Description for Brand MV', '2025-02-24 11:08:40'),
(361, 'Brand MW', 'Description for Brand MW', '2025-02-24 11:08:40'),
(362, 'Brand MX', 'Description for Brand MX', '2025-02-24 11:08:40'),
(363, 'Brand MY', 'Description for Brand MY', '2025-02-24 11:08:40'),
(364, 'Brand MZ', 'Description for Brand MZ', '2025-02-24 11:08:40'),
(365, 'Brand NA', 'Description for Brand NA', '2025-02-24 11:08:40'),
(366, 'Brand NB', 'Description for Brand NB', '2025-02-24 11:08:40'),
(367, 'Brand NC', 'Description for Brand NC', '2025-02-24 11:08:40'),
(368, 'Brand ND', 'Description for Brand ND', '2025-02-24 11:08:40'),
(369, 'Brand NE', 'Description for Brand NE', '2025-02-24 11:08:40'),
(370, 'Brand NF', 'Description for Brand NF', '2025-02-24 11:08:40'),
(371, 'Brand NG', 'Description for Brand NG', '2025-02-24 11:08:40'),
(372, 'Brand NH', 'Description for Brand NH', '2025-02-24 11:08:40'),
(373, 'Brand NI', 'Description for Brand NI', '2025-02-24 11:08:40'),
(374, 'Brand NJ', 'Description for Brand NJ', '2025-02-24 11:08:40'),
(375, 'Brand NK', 'Description for Brand NK', '2025-02-24 11:08:40'),
(376, 'Brand NL', 'Description for Brand NL', '2025-02-24 11:08:40'),
(377, 'Brand NM', 'Description for Brand NM', '2025-02-24 11:08:40'),
(378, 'Brand NN', 'Description for Brand NN', '2025-02-24 11:08:40'),
(379, 'Brand NO', 'Description for Brand NO', '2025-02-24 11:08:40'),
(380, 'Brand NP', 'Description for Brand NP', '2025-02-24 11:08:40'),
(381, 'Brand NQ', 'Description for Brand NQ', '2025-02-24 11:08:40'),
(382, 'Brand NR', 'Description for Brand NR', '2025-02-24 11:08:40'),
(383, 'Brand NS', 'Description for Brand NS', '2025-02-24 11:08:40'),
(384, 'Brand NT', 'Description for Brand NT', '2025-02-24 11:08:40'),
(385, 'Brand NU', 'Description for Brand NU', '2025-02-24 11:08:40'),
(386, 'Brand NV', 'Description for Brand NV', '2025-02-24 11:08:40'),
(387, 'Brand NW', 'Description for Brand NW', '2025-02-24 11:08:40'),
(388, 'Brand NX', 'Description for Brand NX', '2025-02-24 11:08:40'),
(389, 'Brand NY', 'Description for Brand NY', '2025-02-24 11:08:40'),
(390, 'Brand NZ', 'Description for Brand NZ', '2025-02-24 11:08:40'),
(391, 'Brand OA', 'Description for Brand OA', '2025-02-24 11:08:40'),
(392, 'Brand OB', 'Description for Brand OB', '2025-02-24 11:08:40'),
(393, 'Brand OC', 'Description for Brand OC', '2025-02-24 11:08:40'),
(394, 'Brand OD', 'Description for Brand OD', '2025-02-24 11:08:40'),
(395, 'Brand OE', 'Description for Brand OE', '2025-02-24 11:08:40'),
(396, 'Brand OF', 'Description for Brand OF', '2025-02-24 11:08:40'),
(397, 'Brand OG', 'Description for Brand OG', '2025-02-24 11:08:40'),
(398, 'Brand OH', 'Description for Brand OH', '2025-02-24 11:08:40'),
(399, 'Brand OI', 'Description for Brand OI', '2025-02-24 11:08:40'),
(400, 'Brand OJ', 'Description for Brand OJ', '2025-02-24 11:08:40'),
(401, 'Brand OK', 'Description for Brand OK', '2025-02-24 11:08:40'),
(402, 'Brand OL', 'Description for Brand OL', '2025-02-24 11:08:40'),
(403, 'Brand OM', 'Description for Brand OM', '2025-02-24 11:08:40'),
(404, 'Brand ON', 'Description for Brand ON', '2025-02-24 11:08:40'),
(405, 'Brand OO', 'Description for Brand OO', '2025-02-24 11:08:40'),
(406, 'Brand OP', 'Description for Brand OP', '2025-02-24 11:08:40'),
(407, 'Brand OQ', 'Description for Brand OQ', '2025-02-24 11:08:40'),
(408, 'Brand OR', 'Description for Brand OR', '2025-02-24 11:08:40'),
(409, 'Brand OS', 'Description for Brand OS', '2025-02-24 11:08:40'),
(410, 'Brand OT', 'Description for Brand OT', '2025-02-24 11:08:40'),
(411, 'Brand OU', 'Description for Brand OU', '2025-02-24 11:08:40'),
(412, 'Brand OV', 'Description for Brand OV', '2025-02-24 11:08:40'),
(413, 'Brand OW', 'Description for Brand OW', '2025-02-24 11:08:40'),
(414, 'Brand OX', 'Description for Brand OX', '2025-02-24 11:08:40'),
(415, 'Brand OY', 'Description for Brand OY', '2025-02-24 11:08:40'),
(416, 'Brand OZ', 'Description for Brand OZ', '2025-02-24 11:08:40'),
(417, 'Brand PA', 'Description for Brand PA', '2025-02-24 11:08:40'),
(418, 'Brand PB', 'Description for Brand PB', '2025-02-24 11:08:40'),
(419, 'Brand PC', 'Description for Brand PC', '2025-02-24 11:08:40'),
(420, 'Brand PD', 'Description for Brand PD', '2025-02-24 11:08:40'),
(421, 'Brand PE', 'Description for Brand PE', '2025-02-24 11:08:40'),
(422, 'Brand PF', 'Description for Brand PF', '2025-02-24 11:08:40'),
(423, 'Brand PG', 'Description for Brand PG', '2025-02-24 11:08:40'),
(424, 'Brand PH', 'Description for Brand PH', '2025-02-24 11:08:40'),
(425, 'Brand PI', 'Description for Brand PI', '2025-02-24 11:08:40'),
(426, 'Brand PJ', 'Description for Brand PJ', '2025-02-24 11:08:40'),
(427, 'Brand PK', 'Description for Brand PK', '2025-02-24 11:08:40'),
(428, 'Brand PL', 'Description for Brand PL', '2025-02-24 11:08:40'),
(429, 'Brand PM', 'Description for Brand PM', '2025-02-24 11:08:40'),
(430, 'Brand PN', 'Description for Brand PN', '2025-02-24 11:08:40'),
(431, 'Brand PO', 'Description for Brand PO', '2025-02-24 11:08:40'),
(432, 'Brand PP', 'Description for Brand PP', '2025-02-24 11:08:40'),
(433, 'Brand PQ', 'Description for Brand PQ', '2025-02-24 11:08:40'),
(434, 'Brand PR', 'Description for Brand PR', '2025-02-24 11:08:40'),
(435, 'Brand PS', 'Description for Brand PS', '2025-02-24 11:08:40'),
(436, 'Brand PT', 'Description for Brand PT', '2025-02-24 11:08:40'),
(437, 'Brand PU', 'Description for Brand PU', '2025-02-24 11:08:40'),
(438, 'Brand PV', 'Description for Brand PV', '2025-02-24 11:08:40'),
(439, 'Brand PW', 'Description for Brand PW', '2025-02-24 11:08:40'),
(440, 'Brand PX', 'Description for Brand PX', '2025-02-24 11:08:40'),
(441, 'Brand PY', 'Description for Brand PY', '2025-02-24 11:08:40'),
(442, 'Brand PZ', 'Description for Brand PZ', '2025-02-24 11:08:40'),
(443, 'Brand QA', 'Description for Brand QA', '2025-02-24 11:08:40'),
(444, 'Brand QB', 'Description for Brand QB', '2025-02-24 11:08:40'),
(445, 'Brand QC', 'Description for Brand QC', '2025-02-24 11:08:40'),
(446, 'Brand QD', 'Description for Brand QD', '2025-02-24 11:08:40'),
(447, 'Brand QE', 'Description for Brand QE', '2025-02-24 11:08:40'),
(448, 'Brand QF', 'Description for Brand QF', '2025-02-24 11:08:40'),
(449, 'Brand QG', 'Description for Brand QG', '2025-02-24 11:08:40'),
(450, 'Brand QH', 'Description for Brand QH', '2025-02-24 11:08:40'),
(451, 'Brand QI', 'Description for Brand QI', '2025-02-24 11:08:40'),
(452, 'Brand QJ', 'Description for Brand QJ', '2025-02-24 11:08:40'),
(453, 'Brand QK', 'Description for Brand QK', '2025-02-24 11:08:40'),
(454, 'Brand QL', 'Description for Brand QL', '2025-02-24 11:08:40'),
(455, 'Brand QM', 'Description for Brand QM', '2025-02-24 11:08:40'),
(456, 'Brand QN', 'Description for Brand QN', '2025-02-24 11:08:40'),
(457, 'Brand QO', 'Description for Brand QO', '2025-02-24 11:08:40'),
(458, 'Brand QP', 'Description for Brand QP', '2025-02-24 11:08:40'),
(459, 'Brand QQ', 'Description for Brand QQ', '2025-02-24 11:08:40'),
(460, 'Brand QR', 'Description for Brand QR', '2025-02-24 11:08:40'),
(461, 'Brand QS', 'Description for Brand QS', '2025-02-24 11:08:40'),
(462, 'Brand QT', 'Description for Brand QT', '2025-02-24 11:08:40'),
(463, 'Brand QU', 'Description for Brand QU', '2025-02-24 11:08:40'),
(464, 'Brand QV', 'Description for Brand QV', '2025-02-24 11:08:40'),
(465, 'Brand QW', 'Description for Brand QW', '2025-02-24 11:08:40'),
(466, 'Brand QX', 'Description for Brand QX', '2025-02-24 11:08:40'),
(467, 'Brand QY', 'Description for Brand QY', '2025-02-24 11:08:40'),
(468, 'Brand QZ', 'Description for Brand QZ', '2025-02-24 11:08:40'),
(469, 'Brand RA', 'Description for Brand RA', '2025-02-24 11:08:40'),
(470, 'Brand RB', 'Description for Brand RB', '2025-02-24 11:08:40'),
(471, 'Brand RC', 'Description for Brand RC', '2025-02-24 11:08:40'),
(472, 'Brand RD', 'Description for Brand RD', '2025-02-24 11:08:40'),
(473, 'Brand RE', 'Description for Brand RE', '2025-02-24 11:08:40'),
(474, 'Brand RF', 'Description for Brand RF', '2025-02-24 11:08:40'),
(475, 'Brand RG', 'Description for Brand RG', '2025-02-24 11:08:40'),
(476, 'Brand RH', 'Description for Brand RH', '2025-02-24 11:08:40'),
(477, 'Brand RI', 'Description for Brand RI', '2025-02-24 11:08:40'),
(478, 'Brand RJ', 'Description for Brand RJ', '2025-02-24 11:08:40'),
(479, 'Brand RK', 'Description for Brand RK', '2025-02-24 11:08:40'),
(480, 'Brand RL', 'Description for Brand RL', '2025-02-24 11:08:40'),
(481, 'Brand RM', 'Description for Brand RM', '2025-02-24 11:08:40'),
(482, 'Brand RN', 'Description for Brand RN', '2025-02-24 11:08:40'),
(483, 'Brand RO', 'Description for Brand RO', '2025-02-24 11:08:40'),
(484, 'Brand RP', 'Description for Brand RP', '2025-02-24 11:08:40'),
(485, 'Brand RQ', 'Description for Brand RQ', '2025-02-24 11:08:40'),
(486, 'Brand RR', 'Description for Brand RR', '2025-02-24 11:08:40'),
(487, 'Brand RS', 'Description for Brand RS', '2025-02-24 11:08:40'),
(488, 'Brand RT', 'Description for Brand RT', '2025-02-24 11:08:40'),
(489, 'Brand RU', 'Description for Brand RU', '2025-02-24 11:08:40'),
(490, 'Brand RV', 'Description for Brand RV', '2025-02-24 11:08:40'),
(491, 'Brand RW', 'Description for Brand RW', '2025-02-24 11:08:40'),
(492, 'Brand RX', 'Description for Brand RX', '2025-02-24 11:08:40'),
(493, 'Brand RY', 'Description for Brand RY', '2025-02-24 11:08:40'),
(494, 'Brand RZ', 'Description for Brand RZ', '2025-02-24 11:08:40'),
(495, 'Brand SA', 'Description for Brand SA', '2025-02-24 11:08:40'),
(496, 'Brand SB', 'Description for Brand SB', '2025-02-24 11:08:40'),
(497, 'Brand SC', 'Description for Brand SC', '2025-02-24 11:08:40'),
(498, 'Brand SD', 'Description for Brand SD', '2025-02-24 11:08:40'),
(499, 'Brand SE', 'Description for Brand SE', '2025-02-24 11:08:40'),
(500, 'Brand SF', 'Description for Brand SF', '2025-02-24 11:08:40'),
(501, 'Brand SG', 'Description for Brand SG', '2025-02-24 11:08:40'),
(502, 'Brand SH', 'Description for Brand SH', '2025-02-24 11:08:40'),
(503, 'Brand SI', 'Description for Brand SI', '2025-02-24 11:08:40'),
(504, 'Brand SJ', 'Description for Brand SJ', '2025-02-24 11:08:40'),
(505, 'Brand SK', 'Description for Brand SK', '2025-02-24 11:08:40'),
(506, 'Brand SL', 'Description for Brand SL', '2025-02-24 11:08:40'),
(507, 'Brand SM', 'Description for Brand SM', '2025-02-24 11:08:40'),
(508, 'Brand SN', 'Description for Brand SN', '2025-02-24 11:08:40'),
(509, 'Brand SO', 'Description for Brand SO', '2025-02-24 11:08:40'),
(510, 'Brand SP', 'Description for Brand SP', '2025-02-24 11:08:40'),
(511, 'Brand SQ', 'Description for Brand SQ', '2025-02-24 11:08:40'),
(519, 'Brand SY', 'Description for Brand SY', '2025-02-24 11:08:40'),
(520, 'Brand SZ', 'Description for Brand SZ', '2025-02-24 11:08:40'),
(521, 'Brand TA', 'Description for Brand TA', '2025-02-24 11:08:40'),
(522, 'Brand TB', 'Description for Brand TB', '2025-02-24 11:08:40'),
(523, 'Brand TC', 'Description for Brand TC', '2025-02-24 11:08:40'),
(524, 'Brand TD', 'Description for Brand TD', '2025-02-24 11:08:40'),
(525, 'Brand TE', 'Description for Brand TE', '2025-02-24 11:08:40'),
(526, 'Brand TF', 'Description for Brand TF', '2025-02-24 11:08:40'),
(527, 'Brand TG', 'Description for Brand TG', '2025-02-24 11:08:40'),
(528, 'Brand TH', 'Description for Brand TH', '2025-02-24 11:08:40'),
(529, 'Brand TI', 'Description for Brand TI', '2025-02-24 11:08:40'),
(530, 'Brand TJ', 'Description for Brand TJ', '2025-02-24 11:08:40'),
(531, 'Brand TK', 'Description for Brand TK', '2025-02-24 11:08:40'),
(532, 'Brand TL', 'Description for Brand TL', '2025-02-24 11:08:40'),
(533, 'Brand TM', 'Description for Brand TM', '2025-02-24 11:08:40'),
(534, 'Brand TN', 'Description for Brand TN', '2025-02-24 11:08:40'),
(535, 'Brand TO', 'Description for Brand TO', '2025-02-24 11:08:40'),
(536, 'Brand TP', 'Description for Brand TP', '2025-02-24 11:08:40'),
(537, 'Brand TQ', 'Description for Brand TQ', '2025-02-24 11:08:40'),
(538, 'Brand TR', 'Description for Brand TR', '2025-02-24 11:08:40'),
(539, 'Brand TS', 'Description for Brand TS', '2025-02-24 11:08:40'),
(540, 'Brand TT', 'Description for Brand TT', '2025-02-24 11:08:40'),
(541, 'Brand TU', 'Description for Brand TU', '2025-02-24 11:08:40'),
(542, 'Brand TV', 'Description for Brand TV', '2025-02-24 11:08:40'),
(543, 'Brand TW', 'Description for Brand TW', '2025-02-24 11:08:40'),
(544, 'Brand TX', 'Description for Brand TX', '2025-02-24 11:08:40'),
(545, 'Brand TY', 'Description for Brand TY', '2025-02-24 11:08:40'),
(546, 'Brand TZ', 'Description for Brand TZ', '2025-02-24 11:08:40'),
(547, 'Brand UA', 'Description for Brand UA', '2025-02-24 11:08:40'),
(548, 'Brand UB', 'Description for Brand UB', '2025-02-24 11:08:40'),
(549, 'Brand UC', 'Description for Brand UC', '2025-02-24 11:08:40'),
(550, 'Brand UD', 'Description for Brand UD', '2025-02-24 11:08:40'),
(551, 'Brand UE', 'Description for Brand UE', '2025-02-24 11:08:40'),
(552, 'Brand UF', 'Description for Brand UF', '2025-02-24 11:08:40'),
(553, 'Brand UG', 'Description for Brand UG', '2025-02-24 11:08:40'),
(554, 'Brand UH', 'Description for Brand UH', '2025-02-24 11:08:40'),
(555, 'Brand UI', 'Description for Brand UI', '2025-02-24 11:08:40'),
(556, 'Brand UJ', 'Description for Brand UJ', '2025-02-24 11:08:40'),
(557, 'Brand UK', 'Description for Brand UK', '2025-02-24 11:08:40'),
(558, 'Brand UL', 'Description for Brand UL', '2025-02-24 11:08:40'),
(559, 'Brand UM', 'Description for Brand UM', '2025-02-24 11:08:40'),
(560, 'Brand UN', 'Description for Brand UN', '2025-02-24 11:08:40'),
(561, 'Brand UO', 'Description for Brand UO', '2025-02-24 11:08:40'),
(562, 'Brand UP', 'Description for Brand UP', '2025-02-24 11:08:40'),
(563, 'Brand UQ', 'Description for Brand UQ', '2025-02-24 11:08:40'),
(564, 'Brand UR', 'Description for Brand UR', '2025-02-24 11:08:40'),
(565, 'Brand US', 'Description for Brand US', '2025-02-24 11:08:40'),
(566, 'Brand UT', 'Description for Brand UT', '2025-02-24 11:08:40'),
(567, 'Brand UU', 'Description for Brand UU', '2025-02-24 11:08:40'),
(568, 'Brand UV', 'Description for Brand UV', '2025-02-24 11:08:40'),
(569, 'Brand UW', 'Description for Brand UW', '2025-02-24 11:08:40'),
(570, 'Brand UX', 'Description for Brand UX', '2025-02-24 11:08:40'),
(571, 'Brand UY', 'Description for Brand UY', '2025-02-24 11:08:40'),
(572, 'Brand UZ', 'Description for Brand UZ', '2025-02-24 11:08:40'),
(573, 'Brand VA', 'Description for Brand VA', '2025-02-24 11:08:40'),
(574, 'Brand VB', 'Description for Brand VB', '2025-02-24 11:08:40'),
(575, 'Brand VC', 'Description for Brand VC', '2025-02-24 11:08:40'),
(576, 'Brand VD', 'Description for Brand VD', '2025-02-24 11:08:40'),
(577, 'Brand VE', 'Description for Brand VE', '2025-02-24 11:08:40'),
(578, 'Brand VF', 'Description for Brand VF', '2025-02-24 11:08:40'),
(579, 'Brand VG', 'Description for Brand VG', '2025-02-24 11:08:40'),
(580, 'Brand VH', 'Description for Brand VH', '2025-02-24 11:08:40'),
(581, 'Brand VI', 'Description for Brand VI', '2025-02-24 11:08:40'),
(582, 'Brand VJ', 'Description for Brand VJ', '2025-02-24 11:08:40'),
(583, 'Brand VK', 'Description for Brand VK', '2025-02-24 11:08:40'),
(584, 'Brand VL', 'Description for Brand VL', '2025-02-24 11:08:40'),
(585, 'Brand VM', 'Description for Brand VM', '2025-02-24 11:08:40'),
(586, 'Brand VN', 'Description for Brand VN', '2025-02-24 11:08:40'),
(587, 'Brand VO', 'Description for Brand VO', '2025-02-24 11:08:40'),
(588, 'Brand VP', 'Description for Brand VP', '2025-02-24 11:08:40'),
(589, 'Brand VQ', 'Description for Brand VQ', '2025-02-24 11:08:40'),
(590, 'Brand VR', 'Description for Brand VR', '2025-02-24 11:08:40'),
(591, 'Brand VS', 'Description for Brand VS', '2025-02-24 11:08:40'),
(592, 'Brand VT', 'Description for Brand VT', '2025-02-24 11:08:40'),
(593, 'Brand VU', 'Description for Brand VU', '2025-02-24 11:08:40'),
(594, 'Brand VV', 'Description for Brand VV', '2025-02-24 11:08:40'),
(595, 'Brand VW', 'Description for Brand VW', '2025-02-24 11:08:40'),
(596, 'Brand VX', 'Description for Brand VX', '2025-02-24 11:08:40'),
(597, 'Brand VY', 'Description for Brand VY', '2025-02-24 11:08:40'),
(598, 'Brand VZ', 'Description for Brand VZ', '2025-02-24 11:08:40'),
(599, 'Brand WA', 'Description for Brand WA', '2025-02-24 11:08:40'),
(600, 'Brand WB', 'Description for Brand WB', '2025-02-24 11:08:40'),
(601, 'Brand WC', 'Description for Brand WC', '2025-02-24 11:08:40'),
(602, 'Brand WD', 'Description for Brand WD', '2025-02-24 11:08:40'),
(603, 'Brand WE', 'Description for Brand WE', '2025-02-24 11:08:40'),
(604, 'Brand WF', 'Description for Brand WF', '2025-02-24 11:08:40'),
(605, 'Brand WG', 'Description for Brand WG', '2025-02-24 11:08:40'),
(606, 'Brand WH', 'Description for Brand WH', '2025-02-24 11:08:40'),
(607, 'Brand WI', 'Description for Brand WI', '2025-02-24 11:08:40'),
(608, 'Brand WJ', 'Description for Brand WJ', '2025-02-24 11:08:40'),
(609, 'Brand WK', 'Description for Brand WK', '2025-02-24 11:08:40'),
(610, 'Brand WL', 'Description for Brand WL', '2025-02-24 11:08:40'),
(611, 'Brand WM', 'Description for Brand WM', '2025-02-24 11:08:40'),
(612, 'Brand WN', 'Description for Brand WN', '2025-02-24 11:08:40'),
(613, 'Brand WO', 'Description for Brand WO', '2025-02-24 11:08:40'),
(614, 'Brand WP', 'Description for Brand WP', '2025-02-24 11:08:40'),
(615, 'Brand WQ', 'Description for Brand WQ', '2025-02-24 11:08:40'),
(616, 'Brand WR', 'Description for Brand WR', '2025-02-24 11:08:40'),
(617, 'Brand WS', 'Description for Brand WS', '2025-02-24 11:08:40'),
(618, 'Brand WT', 'Description for Brand WT', '2025-02-24 11:08:40'),
(619, 'Brand WU', 'Description for Brand WU', '2025-02-24 11:08:40'),
(620, 'Brand WV', 'Description for Brand WV', '2025-02-24 11:08:40'),
(621, 'Brand WW', 'Description for Brand WW', '2025-02-24 11:08:40'),
(622, 'Brand WX', 'Description for Brand WX', '2025-02-24 11:08:40'),
(623, 'Brand WY', 'Description for Brand WY', '2025-02-24 11:08:40'),
(624, 'Brand WZ', 'Description for Brand WZ', '2025-02-24 11:08:40'),
(625, 'Brand XA', 'Description for Brand XA', '2025-02-24 11:08:40'),
(626, 'Brand XB', 'Description for Brand XB', '2025-02-24 11:08:40'),
(627, 'Brand XC', 'Description for Brand XC', '2025-02-24 11:08:40'),
(628, 'Brand XD', 'Description for Brand XD', '2025-02-24 11:08:40'),
(629, 'Brand XE', 'Description for Brand XE', '2025-02-24 11:08:40'),
(630, 'Brand XF', 'Description for Brand XF', '2025-02-24 11:08:40'),
(631, 'Brand XG', 'Description for Brand XG', '2025-02-24 11:08:40'),
(632, 'Brand XH', 'Description for Brand XH', '2025-02-24 11:08:40'),
(633, 'Brand XI', 'Description for Brand XI', '2025-02-24 11:08:40'),
(634, 'Brand XJ', 'Description for Brand XJ', '2025-02-24 11:08:40'),
(635, 'Brand XK', 'Description for Brand XK', '2025-02-24 11:08:40'),
(636, 'Brand XL', 'Description for Brand XL', '2025-02-24 11:08:40'),
(637, 'Brand XM', 'Description for Brand XM', '2025-02-24 11:08:40'),
(638, 'Brand XN', 'Description for Brand XN', '2025-02-24 11:08:40'),
(639, 'Brand XO', 'Description for Brand XO', '2025-02-24 11:08:40'),
(640, 'Brand XP', 'Description for Brand XP', '2025-02-24 11:08:40'),
(641, 'Brand XQ', 'Description for Brand XQ', '2025-02-24 11:08:40'),
(642, 'Brand XR', 'Description for Brand XR', '2025-02-24 11:08:40'),
(643, 'Brand XS', 'Description for Brand XS', '2025-02-24 11:08:40'),
(644, 'Brand XT', 'Description for Brand XT', '2025-02-24 11:08:40'),
(645, 'Brand XU', 'Description for Brand XU', '2025-02-24 11:08:40'),
(646, 'Brand XV', 'Description for Brand XV', '2025-02-24 11:08:40'),
(647, 'Brand XW', 'Description for Brand XW', '2025-02-24 11:08:40'),
(648, 'Brand XX', 'Description for Brand XX', '2025-02-24 11:08:40'),
(649, 'Brand XY', 'Description for Brand XY', '2025-02-24 11:08:40'),
(650, 'Brand XZ', 'Description for Brand XZ', '2025-02-24 11:08:40'),
(651, 'Brand YA', 'Description for Brand YA', '2025-02-24 11:08:40'),
(652, 'Brand YB', 'Description for Brand YB', '2025-02-24 11:08:40'),
(653, 'Brand YC', 'Description for Brand YC', '2025-02-24 11:08:40'),
(654, 'Brand YD', 'Description for Brand YD', '2025-02-24 11:08:40'),
(655, 'Brand YE', 'Description for Brand YE', '2025-02-24 11:08:40'),
(656, 'Brand YF', 'Description for Brand YF', '2025-02-24 11:08:40'),
(657, 'Brand YG', 'Description for Brand YG', '2025-02-24 11:08:40'),
(658, 'Brand YH', 'Description for Brand YH', '2025-02-24 11:08:40'),
(659, 'Brand YI', 'Description for Brand YI', '2025-02-24 11:08:40'),
(660, 'Brand YJ', 'Description for Brand YJ', '2025-02-24 11:08:40'),
(661, 'Brand YK', 'Description for Brand YK', '2025-02-24 11:08:40'),
(662, 'Brand YL', 'Description for Brand YL', '2025-02-24 11:08:40'),
(663, 'Brand YM', 'Description for Brand YM', '2025-02-24 11:08:40'),
(664, 'Brand YN', 'Description for Brand YN', '2025-02-24 11:08:40'),
(665, 'Brand YO', 'Description for Brand YO', '2025-02-24 11:08:40'),
(666, 'Brand YP', 'Description for Brand YP', '2025-02-24 11:08:40'),
(667, 'Brand YQ', 'Description for Brand YQ', '2025-02-24 11:08:40'),
(668, 'Brand YR', 'Description for Brand YR', '2025-02-24 11:08:40'),
(669, 'Brand YS', 'Description for Brand YS', '2025-02-24 11:08:40'),
(670, 'Brand YT', 'Description for Brand YT', '2025-02-24 11:08:40'),
(671, 'Brand YU', 'Description for Brand YU', '2025-02-24 11:08:40'),
(672, 'Brand YV', 'Description for Brand YV', '2025-02-24 11:08:40'),
(673, 'Brand YW', 'Description for Brand YW', '2025-02-24 11:08:40'),
(674, 'Brand YX', 'Description for Brand YX', '2025-02-24 11:08:40'),
(675, 'Brand YY', 'Description for Brand YY', '2025-02-24 11:08:40'),
(676, 'Brand YZ', 'Description for Brand YZ', '2025-02-24 11:08:40'),
(677, 'Brand ZA', 'Description for Brand ZA', '2025-02-24 11:08:40'),
(678, 'Brand ZB', 'Description for Brand ZB', '2025-02-24 11:08:40'),
(679, 'Brand ZC', 'Description for Brand ZC', '2025-02-24 11:08:40'),
(680, 'Brand ZD', 'Description for Brand ZD', '2025-02-24 11:08:40'),
(681, 'Brand ZE', 'Description for Brand ZE', '2025-02-24 11:08:40'),
(682, 'Brand ZF', 'Description for Brand ZF', '2025-02-24 11:08:40'),
(683, 'Brand ZG', 'Description for Brand ZG', '2025-02-24 11:08:40'),
(684, 'Brand ZH', 'Description for Brand ZH', '2025-02-24 11:08:40'),
(685, 'Brand ZI', 'Description for Brand ZI', '2025-02-24 11:08:40'),
(686, 'Brand ZJ', 'Description for Brand ZJ', '2025-02-24 11:08:40'),
(687, 'Brand ZK', 'Description for Brand ZK', '2025-02-24 11:08:40'),
(688, 'Brand ZL', 'Description for Brand ZL', '2025-02-24 11:08:40'),
(689, 'Brand ZM', 'Description for Brand ZM', '2025-02-24 11:08:40'),
(690, 'Brand ZN', 'Description for Brand ZN', '2025-02-24 11:08:40'),
(691, 'Brand ZO', 'Description for Brand ZO', '2025-02-24 11:08:40'),
(692, 'Brand ZP', 'Description for Brand ZP', '2025-02-24 11:08:40'),
(693, 'Brand ZQ', 'Description for Brand ZQ', '2025-02-24 11:08:40'),
(694, 'Brand ZR', 'Description for Brand ZR', '2025-02-24 11:08:40'),
(695, 'Brand ZS', 'Description for Brand ZS', '2025-02-24 11:08:40'),
(696, 'Brand ZT', 'Description for Brand ZT', '2025-02-24 11:08:40'),
(697, 'Brand ZU', 'Description for Brand ZU', '2025-02-24 11:08:40'),
(698, 'Brand ZV', 'Description for Brand ZV', '2025-02-24 11:08:40'),
(699, 'Brand ZW', 'Description for Brand ZW', '2025-02-24 11:08:40'),
(700, 'Brand ZX', 'Description for Brand ZX', '2025-02-24 11:08:40'),
(701, 'Brand ZY', 'Description for Brand ZY', '2025-02-24 11:08:40'),
(702, 'Brand ZZ', 'Description for Brand ZZ', '2025-02-24 11:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` text NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('stock','non-stock') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `type`, `created_at`) VALUES
(904, 'Luggage', 'Travel luggage and bags', 'stock', '2025-02-24 10:53:11'),
(905, 'Sunglasses', 'Sunglasses and eyewear', 'non-stock', '2025-02-24 10:53:11'),
(906, 'Bicycles', 'Bicycles and cycling gear', 'stock', '2025-02-24 10:53:11'),
(907, 'Motorcycles', 'Motorcycles and accessories', 'stock', '2025-02-24 10:53:11'),
(908, 'Scooters', 'Scooters and accessories', 'stock', '2025-02-24 10:53:11'),
(909, 'Camping Gear', 'Camping tents and supplies', 'stock', '2025-02-24 10:53:11'),
(910, 'Hiking Boots', 'Boots for hiking', 'stock', '2025-02-24 10:53:11'),
(911, 'Fishing Rods', 'Fishing rods and reels', 'stock', '2025-02-24 10:53:11'),
(912, 'Tents', 'Camping tents', 'stock', '2025-02-24 10:53:11'),
(913, 'Coolers', 'Coolers and ice chests', 'stock', '2025-02-24 10:53:11'),
(914, 'Backpacks', 'Backpacks and bags', 'stock', '2025-02-24 10:53:11'),
(915, 'Skiing', 'Skiing equipment and accessories', 'stock', '2025-02-24 10:53:11'),
(916, 'Snowboarding', 'Snowboarding gear and accessories', 'stock', '2025-02-24 10:53:11'),
(917, 'Fitness Trackers', 'Wearable fitness trackers', 'stock', '2025-02-24 10:53:11'),
(918, 'Yoga', 'Yoga mats and accessories', 'stock', '2025-02-24 10:53:11'),
(919, 'Pilates', 'Pilates equipment and accessories', 'stock', '2025-02-24 10:53:11'),
(920, 'Martial Arts', 'Martial arts gear and uniforms', 'stock', '2025-02-24 10:53:11'),
(921, 'Dance', 'Dance shoes and accessories', 'stock', '2025-02-24 10:53:11'),
(922, 'Swimming', 'Swimming gear and accessories', 'stock', '2025-02-24 10:53:11'),
(923, 'Golf', 'Golf clubs and accessories', 'stock', '2025-02-24 10:53:11'),
(924, 'Tennis', 'Tennis rackets and accessories', 'stock', '2025-02-24 10:53:11'),
(925, 'Baseball', 'Baseball equipment and accessories', 'stock', '2025-02-24 10:53:11'),
(926, 'Basketball', 'Basketball gear and accessories', 'stock', '2025-02-24 10:53:11'),
(927, 'Soccer', 'Soccer balls and gear', 'stock', '2025-02-24 10:53:11'),
(928, 'Rugby', 'Rugby equipment and accessories', 'stock', '2025-02-24 10:53:11'),
(929, 'Cricket', 'Cricket gear and accessories', 'stock', '2025-02-24 10:53:11'),
(930, 'Hockey', 'Hockey equipment and accessories', 'stock', '2025-02-24 10:53:11'),
(931, 'Volleyball', 'Volleyball gear and accessories', 'stock', '2025-02-24 10:53:11'),
(932, 'Running', 'Running shoes and gear', 'stock', '2025-02-24 10:53:11'),
(933, 'Cycling Apparel', 'Clothing for cycling', 'stock', '2025-02-24 10:53:11'),
(934, 'Winter Wear', 'Winter clothing and accessories', 'stock', '2025-02-24 10:53:11'),
(935, 'Summer Wear', 'Summer clothing and accessories', 'stock', '2025-02-24 10:53:11'),
(936, 'Formal Wear', 'Formal clothing and accessories', 'non-stock', '2025-02-24 10:53:11'),
(937, 'Casual Wear', 'Casual clothing and accessories', 'non-stock', '2025-02-24 10:53:11'),
(938, 'Swimwear', 'Swimwear and beachwear', 'non-stock', '2025-02-24 10:53:11'),
(939, 'Lingerie', 'Lingerie and intimate apparel', 'non-stock', '2025-02-24 10:53:11'),
(940, 'Activewear', 'Clothing for active lifestyles', 'stock', '2025-02-24 10:53:11'),
(941, 'Maternity', 'Maternity clothing and accessories', 'non-stock', '2025-02-24 10:53:11'),
(942, 'Plus Size', 'Plus size clothing and accessories', 'non-stock', '2025-02-24 10:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(512) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `address`, `contact_number`, `created_at`, `updated_at`) VALUES
(5, 'muhammad javed', 'karachi north karachi', '03224487854', '2025-02-27 04:03:56', '2025-02-27 04:04:46'),
(6, 'qasim', 'lahore cantt lahore', '03255998745', '2025-02-27 04:04:08', '2025-02-27 04:04:08'),
(7, 'ibrahim', 'multan main road multan', '03225569874', '2025-02-27 04:04:33', '2025-02-27 04:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL CHECK (`price` >= 0),
  `stock_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 CHECK (`stock_quantity` >= 0),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `product_name`, `description`, `price`, `stock_quantity`, `created_at`, `updated_at`) VALUES
(179, 918, 524, 'first product', ' new product added', 12547.00, 12, '2025-02-26 18:59:17', NULL),
(180, 917, 275, 'second product', 'second product description added', 23232.00, 429496, '2025-02-26 19:05:40', '2025-02-26 19:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_verified` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `is_active`, `is_verified`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin123', 'admin', 'admin@gmail.com', '$2y$10$67WtxFJjkDuDPtRsYJ81feeChEykm58Hdt.JkkTGjkhP/bV1NDLam', 'admin', 'admin', 1, 1, '2025-02-22 03:37:42', '2025-02-22 03:37:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_metadata`
--

CREATE TABLE `user_metadata` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(50) NOT NULL,
  `meta_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(512) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `name`, `address`, `contact_number`, `created_at`, `updated_at`) VALUES
(1, 'imran', 'lahore', '03224487854', '2025-02-27 04:11:08', '2025-02-27 04:11:08'),
(2, 'ibrahim', 'Bahawalpur Division Bahawalpur\'s ', '0325-4597874', '2025-02-27 04:11:34', '2025-02-27 04:11:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand_name` (`brand_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_category_name` (`category_name`) USING HASH,
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx_customer_name` (`name`),
  ADD KEY `idx_customer_contact` (`contact_number`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product` (`category_id`,`brand_id`,`product_name`),
  ADD KEY `idx_products_filter` (`category_id`,`brand_id`,`price`),
  ADD KEY `fk_product_brand` (`brand_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_products_search` (`product_name`,`description`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_metadata`
--
ALTER TABLE `user_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `idx_vendor_name` (`name`),
  ADD KEY `idx_vendor_contact` (`contact_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=705;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=945;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_metadata`
--
ALTER TABLE `user_metadata`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_metadata`
--
ALTER TABLE `user_metadata`
  ADD CONSTRAINT `user_metadata_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
