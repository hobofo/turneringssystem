-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Vært: localhost:3306
-- Genereringstid: 12. 07 2015 kl. 19:13:16
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

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_brugere`
--

CREATE TABLE `hbf_brugere` (
`bruger_id` int(11) NOT NULL,
  `navn` varchar(128) NOT NULL DEFAULT '',
  `telefon` varchar(32) NOT NULL DEFAULT '',
  `rangliste` int(11) NOT NULL DEFAULT '0',
  `deaktiv` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=1062 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_indstillinger`
--

CREATE TABLE `hbf_indstillinger` (
`setting_id` int(11) NOT NULL,
  `short` varchar(64) NOT NULL DEFAULT '',
  `setting` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_kampe`
--

CREATE TABLE `hbf_kampe` (
`kamp_id` int(11) NOT NULL,
  `turnerings_id` int(11) NOT NULL DEFAULT '0',
  `parameter` int(11) NOT NULL DEFAULT '0',
  `hold1` int(11) NOT NULL DEFAULT '0',
  `hold2` int(11) NOT NULL DEFAULT '0',
  `rang1` int(11) NOT NULL DEFAULT '0',
  `rang2` int(11) NOT NULL DEFAULT '0',
  `resultat1` int(11) NOT NULL DEFAULT '0',
  `resultat2` int(11) NOT NULL DEFAULT '0',
  `kampnr` int(11) NOT NULL DEFAULT '0',
  `bord` char(1) NOT NULL DEFAULT '',
  `vinder` varchar(11) NOT NULL DEFAULT '',
  `type` char(2) NOT NULL DEFAULT '',
  `pulje` int(11) NOT NULL DEFAULT '0',
  `startet` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15959 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_puljer`
--

CREATE TABLE `hbf_puljer` (
`pulje_id` int(11) NOT NULL,
  `turnerings_id` int(11) NOT NULL DEFAULT '0',
  `pulje_nr` int(11) NOT NULL DEFAULT '0',
  `spiller_id` int(11) NOT NULL DEFAULT '0',
  `maal_scoret` int(11) NOT NULL DEFAULT '0',
  `maal_gaaetind` int(11) NOT NULL DEFAULT '0',
  `point` int(11) NOT NULL DEFAULT '0',
  `kampe` int(11) NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `rangering` varchar(10) NOT NULL DEFAULT '',
  `rangering_konflikt` int(1) NOT NULL DEFAULT '0',
  `rangering_total` int(11) NOT NULL DEFAULT '0',
  `kvartfinale` int(11) NOT NULL DEFAULT '0',
  `initial_placering` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4861 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_rangliste`
--

CREATE TABLE `hbf_rangliste` (
`rangliste_id` int(11) NOT NULL,
  `bruger_id` int(11) NOT NULL DEFAULT '0',
  `turnerings_id` int(11) NOT NULL DEFAULT '0',
  `text` varchar(200) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `point` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=9732 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_spillere`
--

CREATE TABLE `hbf_spillere` (
`spiller_id` int(11) NOT NULL,
  `turnering_id` int(11) NOT NULL DEFAULT '0',
  `spiller` int(11) NOT NULL DEFAULT '0',
  `medspiller` int(11) NOT NULL DEFAULT '0',
  `primaer` varchar(12) NOT NULL DEFAULT '',
  `rang` int(11) NOT NULL,
  `betalt` int(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10897 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `hbf_turnering`
--

CREATE TABLE `hbf_turnering` (
`turnering_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slut_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `puljer` text NOT NULL,
  `point` text NOT NULL,
  `borde` text NOT NULL,
  `kvartfinaler` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=431 DEFAULT CHARSET=latin1;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `hbf_brugere`
--
ALTER TABLE `hbf_brugere`
 ADD PRIMARY KEY (`bruger_id`), ADD UNIQUE KEY `telefon` (`telefon`), ADD KEY `deaktiv` (`deaktiv`), ADD KEY `bruger_id` (`bruger_id`);

--
-- Indeks for tabel `hbf_indstillinger`
--
ALTER TABLE `hbf_indstillinger`
 ADD PRIMARY KEY (`setting_id`), ADD UNIQUE KEY `short` (`short`);

--
-- Indeks for tabel `hbf_kampe`
--
ALTER TABLE `hbf_kampe`
 ADD PRIMARY KEY (`kamp_id`), ADD KEY `turnerings_id` (`turnerings_id`), ADD KEY `type` (`type`);

--
-- Indeks for tabel `hbf_puljer`
--
ALTER TABLE `hbf_puljer`
 ADD PRIMARY KEY (`pulje_id`), ADD KEY `turnerings_id` (`turnerings_id`), ADD KEY `spiller_id` (`spiller_id`);

--
-- Indeks for tabel `hbf_rangliste`
--
ALTER TABLE `hbf_rangliste`
 ADD PRIMARY KEY (`rangliste_id`), ADD KEY `bruger_id` (`bruger_id`), ADD KEY `turnerings_id` (`turnerings_id`);

--
-- Indeks for tabel `hbf_spillere`
--
ALTER TABLE `hbf_spillere`
 ADD PRIMARY KEY (`spiller_id`), ADD KEY `turnering_id` (`turnering_id`);

--
-- Indeks for tabel `hbf_turnering`
--
ALTER TABLE `hbf_turnering`
 ADD PRIMARY KEY (`turnering_id`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `hbf_brugere`
--
ALTER TABLE `hbf_brugere`
MODIFY `bruger_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1062;
--
-- Tilføj AUTO_INCREMENT i tabel `hbf_indstillinger`
--
ALTER TABLE `hbf_indstillinger`
MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Tilføj AUTO_INCREMENT i tabel `hbf_kampe`
--
ALTER TABLE `hbf_kampe`
MODIFY `kamp_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15959;
--
-- Tilføj AUTO_INCREMENT i tabel `hbf_puljer`
--
ALTER TABLE `hbf_puljer`
MODIFY `pulje_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4861;
--
-- Tilføj AUTO_INCREMENT i tabel `hbf_rangliste`
--
ALTER TABLE `hbf_rangliste`
MODIFY `rangliste_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9732;
--
-- Tilføj AUTO_INCREMENT i tabel `hbf_spillere`
--
ALTER TABLE `hbf_spillere`
MODIFY `spiller_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10897;
--
-- Tilføj AUTO_INCREMENT i tabel `hbf_turnering`
--
ALTER TABLE `hbf_turnering`
MODIFY `turnering_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=431;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
