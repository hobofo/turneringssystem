-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Vært: localhost:3306
-- Genereringstid: 12. 07 2015 kl. 19:14:28
-- Serverversion: 5.5.38
-- PHP-version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hobofo_dk`
--

--
-- Data dump for tabellen `hbf_indstillinger`
--

INSERT INTO `hbf_indstillinger` (`setting_id`, `short`, `setting`) VALUES
(1, 'borde', '{Bord 1},{Bord 2},{Bord 3},{Bord 4},{Bord 5}'),
(2, 'rangliste0', '{Onsdag},{128},{64},{32},{16},{8},{4},{2},{1}'),
(3, 'rangliste1', '{Mandag},{640},{320},{160},{80},{40},{20},{10},{5}'),
(4, 'rangliste3', '{Tirsdag},{16000},{8000},{4000},{2000},{1000},{500},{250},{125}'),
(5, 'rangliste4', '{Ingen point},{0},{0},{0},{0},{0},{0},{0},{0}'),
(6, 'rangliste5', ''),
(7, 'rangliste6', '{},{},{},{},{},{},{},{},{}'),
(8, 'rangliste2', '{Torsdag},{3200},{1600},{800},{400},{200},{100},{50},{25}'),
(9, 'brugernavn', 'hbf'),
(10, 'password', 'hbf'),
(11, 'final_10', '2013-05-01');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
