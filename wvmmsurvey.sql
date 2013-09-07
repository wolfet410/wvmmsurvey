-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 06, 2013 at 08:43 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wvmmsurvey`
--

-- --------------------------------------------------------

--
-- Table structure for table `Output`
--

CREATE TABLE IF NOT EXISTS `Output` (
  `ouid` mediumint(9) NOT NULL AUTO_INCREMENT,
  `suid` mediumint(9) NOT NULL,
  `quid` mediumint(9) NOT NULL,
  `store` mediumint(9) NOT NULL,
  `rating` tinytext NOT NULL,
  `maxrating` tinytext NOT NULL,
  `qtext` text NOT NULL,
  `notestext` mediumtext NOT NULL,
  `radio` mediumtext NOT NULL,
  `textarea` text NOT NULL,
  `response` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sync` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `ouid` (`ouid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `Output`
--

INSERT INTO `Output` (`ouid`, `suid`, `quid`, `store`, `rating`, `maxrating`, `qtext`, `notestext`, `radio`, `textarea`, `response`, `sync`) VALUES
(1, 1, 6, 109, '', '', 'Associates Working During Visit:', '', '', 'Associates working hard', '2013-08-27 11:34:53', '2013-09-05 13:32:49'),
(2, 1, 2, 109, '3', '6', 'All Reps and Manager dressed sharply in the proper uniform with a name tag and current lanyard', 'Comments/Notes:', 'Needs Improvement', 'Needs improvement', '2013-08-27 11:34:57', '2013-09-05 13:32:49'),
(3, 1, 3, 109, '6', '6', 'Merchandising is in accordance with Store Plan o Gram including Demo Phones (if missing collateral please notate in comments)', 'Comments/Notes:', 'Meets Expectations', 'Meeting some expectations', '2013-08-27 11:35:06', '2013-09-05 13:32:49'),
(4, 1, 8, 109, '0', '1', 'Do the PASSION board daily goals equal to the 10% Conversion Revenue goal? Review Conversion Calculator on WV Connect to calculate revenue goals. Ensure reps can speak to their daily goals.', '', 'Needs Improvement', '', '2013-08-27 14:10:26', '2013-09-05 13:32:49'),
(5, 1, 9, 109, '', '', 'What are the team''s Total Box and New Act Conversion results? Review WOW change based on the Conversion Corner reporting in WV Connect - are they improving or declining week over week?', 'Comment:', 'Meets Expectations', 'More meets', '2013-08-27 14:08:11', '2013-09-05 13:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE IF NOT EXISTS `Questions` (
  `quid` smallint(6) NOT NULL AUTO_INCREMENT,
  `active` enum('true','false') NOT NULL,
  `sort` smallint(6) NOT NULL,
  `table` enum('true','false') NOT NULL DEFAULT 'false',
  `rated` enum('true','false') NOT NULL DEFAULT 'false',
  `type` enum('heading','radio','textbox') NOT NULL,
  `text` text NOT NULL,
  `answers` text NOT NULL,
  `notes` enum('true','false') NOT NULL DEFAULT 'false',
  `notestext` text NOT NULL,
  UNIQUE KEY `uid` (`quid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=370 ;

--
-- Dumping data for table `Questions`
--

INSERT INTO `Questions` (`quid`, `active`, `sort`, `table`, `rated`, `type`, `text`, `answers`, `notes`, `notestext`) VALUES
(1, 'true', 3, 'false', 'false', 'heading', 'Is the Store meeting the following Visual Guidelines?', '', 'false', ''),
(2, 'true', 4, 'true', 'true', 'radio', 'All Reps and Manager dressed sharply in the proper uniform with a name tag and current lanyard', 'Needs Improvement~3,Meets Expectations~6', 'true', 'Comments/Notes:'),
(3, 'true', 5, 'true', 'true', 'radio', 'Merchandising is in accordance with Store Plan o Gram including Demo Phones (if missing collateral please notate in comments)', 'Needs Improvement~3,Meets Expectations~6', 'true', 'Comments/Notes:'),
(5, 'true', 1, 'false', 'false', 'heading', 'Staff', '', 'false', ''),
(6, 'true', 2, 'false', 'false', 'textbox', 'Associates Working During Visit:', '', 'false', ''),
(7, 'true', 6, 'true', 'false', 'radio', 'Check the Store Exterior (Store Sign, Landscape, Parking Lot) & Check Interior (Proper Music, Windows, Bathrooms, Carpets, Fixtures and Lights)', 'Needs Improvement,Meets Expectations', 'true', 'Comments/Notes:'),
(8, 'true', 7, 'false', 'true', 'radio', 'Do the PASSION board daily goals equal to the 10% Conversion Revenue goal? Review Conversion Calculator on WV Connect to calculate revenue goals. Ensure reps can speak to their daily goals.', 'Needs Improvement~0,Meets Expectations~1', 'false', ''),
(9, 'true', 8, 'false', 'false', 'radio', 'What are the team''s Total Box and New Act Conversion results? Review WOW change based on the Conversion Corner reporting in WV Connect - are they improving or declining week over week?', 'Needs Improvement,Meets Expectations', 'true', 'Comment:'),
(10, 'true', 9, 'false', 'false', 'radio', 'Is the team executing on the 2-3 identified "Plays/Tactics" the manager selected from the Conversion Playbook for this month? Note the MTD performance in each tactic area selected.', 'Needs Improvement,Meets Expectations', 'true', 'Comment:'),
(11, 'true', 10, 'false', 'false', 'radio', 'Is the manager addressing gaps in rep performance to drive Total Box Conversion? Review bottom 2 performing reps, actions taken, rep RFG''s and performance reviews.', 'Needs Improvement,Meets Expectations', 'false', ''),
(12, 'true', 11, 'false', 'false', 'radio', 'Is the PASSION and Key Information board current and updated with the new headers and metrics?', 'Needs Improvement,Meets Expectations', 'false', ''),
(13, 'true', 12, 'false', 'false', 'heading', 'Commissions Knowledge', '', 'false', ''),
(14, 'true', 13, 'true', 'false', 'radio', 'Do all associates completely understand the commission program?', 'Needs Improvement,Meets Expectations', 'true', 'Comments/Notes:'),
(15, 'true', 14, 'true', 'false', 'radio', 'Have all associates completed the commission quiz?', 'Needs Improvement,Meets Expectations', 'true', 'Comments/Notes:'),
(16, 'true', 15, 'true', 'false', 'radio', 'Can all associates confidently explain all SPIFFs?', 'Needs Improvement,Meets Expectations', 'true', 'Comments/Notes:');

-- --------------------------------------------------------

--
-- Table structure for table `Results`
--

CREATE TABLE IF NOT EXISTS `Results` (
  `ruid` smallint(11) NOT NULL AUTO_INCREMENT,
  `suid` smallint(6) NOT NULL,
  `quid` smallint(11) NOT NULL,
  `radio` text NOT NULL,
  `textarea` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ruid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `Results`
--

INSERT INTO `Results` (`ruid`, `suid`, `quid`, `radio`, `textarea`, `updated`) VALUES
(1, 1, 6, '', 'Associates working hard', '2013-08-27 11:34:53'),
(2, 1, 2, 'Needs Improvement~3', '', '2013-08-27 11:34:53'),
(3, 1, 2, '', 'Needs improvement', '2013-08-27 11:34:57'),
(4, 1, 3, 'Meets Expectations~6', '', '2013-08-27 11:34:58'),
(5, 1, 3, '', 'Meeting some expectations', '2013-08-27 11:35:06'),
(6, 1, 8, 'Needs Improvement~0', '', '2013-08-27 14:10:26'),
(7, 1, 9, 'Meets Expectations', '', '2013-08-27 14:08:06'),
(8, 1, 9, '', 'More meets', '2013-08-27 14:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `Stores`
--

CREATE TABLE IF NOT EXISTS `Stores` (
  `sap` smallint(6) NOT NULL,
  `desc` mediumtext NOT NULL,
  `market` tinytext NOT NULL,
  `region` tinytext NOT NULL,
  UNIQUE KEY `sap` (`sap`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Stores`
--

INSERT INTO `Stores` (`sap`, `desc`, `market`, `region`) VALUES
(331, '35th & Thunderbird', 'Arizona', 'West Division'),
(310, '59th and Thomas', 'Arizona', 'West Division'),
(174, '59th Ave and Loop 101/Arrowhead', 'Arizona', 'West Division'),
(171, '67th and Bethany Home', 'Arizona', 'West Division'),
(328, '75th & Thunderbird', 'Arizona', 'West Division'),
(173, 'Fountain Plaza', 'Arizona', 'West Division'),
(172, 'McClintock', 'Arizona', 'West Division'),
(170, 'Power and Broadway', 'Arizona', 'West Division'),
(1214, 'Belmont', 'Chicago Central', 'Northern Division'),
(223, 'Brighton Park', 'Chicago Central', 'Northern Division'),
(230, 'Gage Park', 'Chicago Central', 'Northern Division'),
(311, 'Little Village', 'Chicago Central', 'Northern Division'),
(2656, 'S Western Ave & W Cermak Rd', 'Chicago Central', 'Northern Division'),
(1213, 'Hermosa', 'Chicago Central', 'Northern Division'),
(1212, 'Westtown', 'Chicago Central', 'Northern Division'),
(1321, 'Archer and Ashland', 'Chicago Central', 'Northern Division'),
(239, 'Archer Heights', 'Chicago Central', 'Northern Division'),
(227, 'Albany Park', 'Chicago North', 'Northern Division'),
(231, 'Irving Park', 'Chicago North', 'Northern Division'),
(240, 'Montrose', 'Chicago North', 'Northern Division'),
(2657, 'E Dundee Rd & Lynda Dr (Palatine)', 'Chicago North', 'Northern Division'),
(238, 'Lawrence', 'Chicago North', 'Northern Division'),
(2659, 'Milwaukee Ave & Oakton (Niles)', 'Chicago North', 'Northern Division'),
(229, 'North Lake', 'Chicago North', 'Northern Division'),
(2589, 'Rand Rd & Arlington Heights Rd', 'Chicago North', 'Northern Division'),
(236, 'River Grove', 'Chicago North', 'Northern Division'),
(1330, 'Western & Ainslie', 'Chicago North', 'Northern Division'),
(1322, 'Harlem & Pershing', 'Chicago South', 'Northern Division'),
(226, 'Hawthorne', 'Chicago South', 'Northern Division'),
(1720, 'E. Boughton & I-355 (Bolingbrook)', 'Chicago South', 'Northern Division'),
(232, 'Evergreen Park', 'Chicago South', 'Northern Division'),
(1242, 'Halsted & 31st', 'Chicago South', 'Northern Division'),
(1332, 'Joliet & Norma', 'Chicago South', 'Northern Division'),
(225, 'Midlothian', 'Chicago South', 'Northern Division'),
(1243, 'Oaklawn North', 'Chicago South', 'Northern Division'),
(235, 'Oaklawn South', 'Chicago South', 'Northern Division'),
(234, 'River Oaks', 'Chicago South', 'Northern Division'),
(224, 'Austin Bluffs', 'Colorado', 'West Division'),
(312, 'Boulder', 'Colorado', 'West Division'),
(241, 'Broadway & Mineral / Littleton Store', 'Colorado', 'West Division'),
(228, 'Centerra Marketplace', 'Colorado', 'West Division'),
(1244, 'Chapel Hills Mall', 'Colorado', 'West Division'),
(1245, 'Cherry Creek', 'Colorado', 'West Division'),
(1264, 'Citadel 2', 'Colorado', 'West Division'),
(1978, 'Colfax & Gaylord', 'Colorado', 'West Division'),
(237, 'Front Range / Harmony & Ziegler', 'Colorado', 'West Division'),
(222, 'Greeley', 'Colorado', 'West Division'),
(3469, 'Easton Town Center', 'Columbus 1', 'East Division'),
(192, 'Harrisburg Pike', 'Columbus 1', 'East Division'),
(199, 'Hebron & 30th/Heath Plaza', 'Columbus 1', 'East Division'),
(1871, 'Morse Crossing & Easton Market', 'Columbus 1', 'East Division'),
(1547, 'S High St & Obetz (Great Southern)', 'Columbus 1', 'East Division'),
(3475, 'Sawmill', 'Columbus 1', 'East Division'),
(200, 'Stringtown/Grove City', 'Columbus 1', 'East Division'),
(2337, 'Worthington/Wilson Bridge', 'Columbus 1', 'East Division'),
(9492, 'Campus (N High & 11th Street)', 'Columbus 1', 'East Division'),
(9491, 'North Hamilton/Hamilton & Morse', 'Columbus 1', 'East Division'),
(9490, 'East Main', 'Columbus 2', 'East Division'),
(2070, 'Northern Lights (Cleveland & Innis)', 'Columbus 2', 'East Division'),
(7704, 'Polaris Fashion Mall', 'Columbus 2', 'East Division'),
(9493, 'River Valley Blvd (Circle/Outlet)', 'Columbus 2', 'East Division'),
(2087, 'W 5th Ave & Northwest Blvd', 'Columbus 2', 'East Division'),
(9686, 'West Broad Street', 'Columbus 2', 'East Division'),
(3480, 'Westpointe', 'Columbus 2', 'East Division'),
(9006, 'East Broad Street', 'Columbus 2', 'East Division'),
(3471, 'Eastland Plaza', 'Columbus 2', 'East Division'),
(8990, 'I-70 & SR256 (Slate Ridge Plaza)', 'Columbus 2', 'East Division'),
(2483, '9 Mile & John R', 'Detroit East', 'Northern Division'),
(180, 'Fraser', 'Detroit East', 'Northern Division'),
(183, 'Lakeside', 'Detroit East', 'Northern Division'),
(2445, 'Gratiot & 12 Mile', 'Detroit East', 'Northern Division'),
(2206, 'Highland Rd & Crescent Lake Rd', 'Detroit East', 'Northern Division'),
(190, 'Lathrup', 'Detroit East', 'Northern Division'),
(181, 'Sterling Heights', 'Detroit East', 'Northern Division'),
(2746, 'Woodward Ave & West Blvd', 'Detroit East', 'Northern Division'),
(2205, 'Telegraph Rd & Ecorse Rd', 'Detroit West', 'Northern Division'),
(1241, 'Fairlane Mall', 'Detroit West', 'Northern Division'),
(7712, 'Ford & Beech', 'Detroit West', 'Northern Division'),
(187, 'Livonia', 'Detroit West', 'Northern Division'),
(186, 'Plymouth', 'Detroit West', 'Northern Division'),
(182, 'Redford', 'Detroit West', 'Northern Division'),
(184, 'Westland', 'Detroit West', 'Northern Division'),
(188, 'Wyandotte', 'Detroit West', 'Northern Division'),
(1925, '37th & Flagler', 'Florida', 'East Division'),
(1971, '8th & SW 57th', 'Florida', 'East Division'),
(1501, 'Bird & Ludlum', 'Florida', 'East Division'),
(2099, 'Boca Raton', 'Florida', 'East Division'),
(1970, 'Broward', 'Florida', 'East Division'),
(2275, 'Coral Springs', 'Florida', 'East Division'),
(2183, 'DelRay Beach', 'Florida', 'East Division'),
(1854, 'Fort Lauderdale', 'Florida', 'East Division'),
(2168, 'Palm Beach', 'Florida', 'East Division'),
(1855, 'Pompano Beach', 'Florida', 'East Division'),
(2244, 'West Palm Beach', 'Florida', 'East Division'),
(2314, 'Acworth', 'Atlanta', 'East Division'),
(2096, 'Johns Creek', 'Atlanta', 'East Division'),
(2164, 'Lawrenceville', 'Atlanta', 'East Division'),
(2100, 'Mableton', 'Atlanta', 'East Division'),
(2023, 'Peachtree City', 'Atlanta', 'East Division'),
(2097, 'Tucker', 'Atlanta', 'East Division'),
(2022, 'Woodstock', 'Atlanta', 'East Division'),
(1162, 'Blue Springs', 'Kansas City', 'Central Division'),
(1463, 'Hwy 50 & Chipman / Lee Summit', 'Kansas City', 'Central Division'),
(1164, 'Noland', 'Kansas City', 'Central Division'),
(1741, 'Oak Park Mall II', 'Kansas City', 'Central Division'),
(1165, 'Overland Park/Hemlock', 'Kansas City', 'Central Division'),
(1163, 'Roe', 'Kansas City', 'Central Division'),
(1561, 'RT 350 & Gregory / Raytown', 'Kansas City', 'Central Division'),
(2312, '108th & Lapham Street (West Allis)', 'Milwaukee', 'Northern Division'),
(8690, 'Bayshore Town Center', 'Milwaukee', 'Northern Division'),
(2543, 'Brookfield Square', 'Milwaukee', 'Northern Division'),
(1635, 'East Capital (Capital Dr. & 1st St)', 'Milwaukee', 'Northern Division'),
(5007, 'Grand Avenue', 'Milwaukee', 'Northern Division'),
(2345, 'Greenfield & 70th', 'Milwaukee', 'Northern Division'),
(4907, 'Mayfair Kiosk', 'Milwaukee', 'Northern Division'),
(2346, 'Miller Park Way & Greenfield', 'Milwaukee', 'Northern Division'),
(2591, 'Regency Mall', 'Milwaukee', 'Northern Division'),
(2433, 'S 27th & West Loomis', 'Milwaukee', 'Northern Division'),
(2484, 'S. 76th & Barnard', 'Milwaukee', 'Northern Division'),
(5006, 'Southridge Mall', 'Milwaukee', 'Northern Division'),
(7698, 'West Capital (56th & Capitol)', 'Milwaukee', 'Northern Division'),
(2263, '66th & Nicolette', 'Minneapolis 1', 'West Division'),
(2313, 'Brooklyn Blvd & Hwy 81', 'Minneapolis 1', 'West Division'),
(1870, 'Central & 44th', 'Minneapolis 1', 'West Division'),
(2239, 'Eagen (Cliff Road & Park Center)', 'Minneapolis 1', 'West Division'),
(2248, 'Hwy 7 & 101', 'Minneapolis 1', 'West Division'),
(1333, 'Lake & Chicago', 'Minneapolis 1', 'West Division'),
(1013, 'Oakwood Mall', 'Minneapolis 1', 'West Division'),
(544, 'Woodbury (Bielenberg & Valley Creek)', 'Minneapolis 1', 'West Division'),
(2655, 'York & 69th (Yorkdale Shoppes)', 'Minneapolis 1', 'West Division'),
(1714, 'Arcade & Neid', 'Minneapolis 2', 'West Division'),
(1685, 'Hudson & McKnight', 'Minneapolis 2', 'West Division'),
(543, 'Rochester (12th St & Crossroads)', 'Minneapolis 2', 'West Division'),
(2297, 'Rosedale Center 4', 'Minneapolis 2', 'West Division'),
(1514, 'White Bear & Larpenteur', 'Minneapolis 2', 'West Division'),
(9949, '101st & Memorial', 'Oklahoma', 'West Division'),
(9938, '21st & Lewis', 'Oklahoma', 'West Division'),
(8161, '21st & Yale', 'Oklahoma', 'West Division'),
(9924, '31st & Garnett', 'Oklahoma', 'West Division'),
(8896, '71st & 169', 'Oklahoma', 'West Division'),
(8897, '71st & Yale', 'Oklahoma', 'West Division'),
(8887, 'Muskogee', 'Oklahoma', 'West Division'),
(665, 'Promenade Mall', 'Oklahoma', 'West Division'),
(9861, 'Sand Springs', 'Oklahoma', 'West Division'),
(489, 'Woodland Hills Mall', 'Oklahoma', 'West Division'),
(8167, 'Battlefield', 'Springfield', 'West Division'),
(2628, 'Kansas & Kearney', 'Springfield', 'West Division'),
(2405, 'Northpark Mall', 'Springfield', 'West Division'),
(2444, 'South Campbell', 'Springfield', 'West Division'),
(109, 'Gravois Bluffs', 'St. Louis 2', 'Central Division'),
(9691, 'Kirkwood/Lindbergh & I-44', 'St. Louis 2', 'Central Division'),
(2335, 'Lindell & Whittier', 'St. Louis 2', 'Central Division'),
(9762, 'Manchester Rd & Breshire Dr', 'St. Louis 2', 'Central Division'),
(7906, 'New Halls Ferry & Lindberg', 'St. Louis 2', 'Central Division'),
(9696, 'Arnold/Michigan Ave & I-61', 'St. Louis 2', 'Central Division'),
(9798, 'Collinsville', 'St. Louis 2', 'Central Division'),
(9942, 'Edwardsville', 'St. Louis 2', 'Central Division'),
(9943, 'Fairview Heights', 'St. Louis 2', 'Central Division'),
(9678, 'Highway 50 & I-64', 'St. Louis 2', 'Central Division'),
(9095, 'I-55 & Loughborough Ave.', 'St. Louis 2', 'Central Division'),
(110, 'Lindbergh & Union', 'St. Louis 2', 'Central Division'),
(4936, 'South County Mall', 'St. Louis 2', 'Central Division'),
(9808, 'Telegraph Rd & Barracksview', 'St. Louis 2', 'Central Division'),
(111, 'Hampton Village', 'St. Louis 2', 'Central Division'),
(1427, 'LaClede & Watson', 'St. Louis 2', 'Central Division'),
(2153, 'St Charles Rock Rd & McKelvey (Orchard Bend)', 'St. Louis 2', 'Central Division'),
(9977, 'Broadway Crossing', 'St. Louis 3', 'Central Division'),
(9846, 'Florissant/N Linbergh & Washington', 'St. Louis 3', 'Central Division'),
(8434, 'Hanley & Folk / Maplewood', 'St. Louis 3', 'Central Division'),
(9697, 'Overland/Hurstgreen Ave & Page Ave.', 'St. Louis 3', 'Central Division'),
(1478, 'St Charles Rock & Ashby', 'St. Louis 3', 'Central Division'),
(8226, 'Zumbehl Rd. & I-70 / Regency Square', 'St. Louis 3', 'Central Division'),
(8101, 'Delmar & Kingsland', 'St. Louis 3', 'Central Division'),
(9752, 'Jennings/W. Florissant & Lucas and Hunt', 'St. Louis 3', 'Central Division'),
(2152, 'N Florissant & Airport Rd (Airport Plaza)', 'St. Louis 3', 'Central Division'),
(4939, 'St. Louis Galleria Mall', 'St. Louis 3', 'Central Division'),
(7872, 'St. Louis Mills Mall', 'St. Louis 3', 'Central Division'),
(1980, 'W Florissant & Festival Dr (Ferguson)', 'St. Louis 3', 'Central Division'),
(9941, '94 Crossing / Mid Rivers', 'St. Louis 3', 'Central Division'),
(9108, 'Chesterfield Airport Rd. & Boones Crossing St. 2', 'St. Louis 3', 'Central Division'),
(7599, 'Des Peres Mall / West County', 'St. Louis 3', 'Central Division'),
(8435, 'O''Fallon Walk/Hwy K & Feise', 'St. Louis 3', 'Central Division'),
(2410, '21st & Amidon', 'Wichita', 'Central Division'),
(8316, '29th & Rock Rd.', 'Wichita', 'Central Division'),
(494, 'East (Eastgate Plaza)', 'Wichita', 'Central Division'),
(2443, 'Maple & Ridge', 'Wichita', 'Central Division'),
(6509, 'New Market Square', 'Wichita', 'Central Division'),
(329, '35th & Glendale', 'Arizona', 'West Division');

-- --------------------------------------------------------

--
-- Table structure for table `Surveys`
--

CREATE TABLE IF NOT EXISTS `Surveys` (
  `suid` smallint(6) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `store` smallint(6) NOT NULL,
  `quids` text NOT NULL,
  `systemLastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userCreated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uid` (`suid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Surveys`
--

INSERT INTO `Surveys` (`suid`, `email`, `store`, `quids`, `systemLastModified`, `userCreated`) VALUES
(1, 'twolfe@datatechcafe.com', 109, '5,6,1,2,3,7,8,9,10,11,12,13,14,15,16', '2013-09-05 12:37:22', '2013-08-27 11:34:00'),
(2, 'twolfe@datatechcafe.com', 110, '5,6,1,2,3,7,8,9,10,11,12,13,14,15,16', '2013-09-05 12:37:15', '2013-09-05 10:18:00'),
(3, 'dtcadmin@wirelessvision.com', 110, '5,6,1,2,3,7,8,9,10,11,12,13,14,15,16', '2013-09-05 10:35:57', '2013-09-05 10:35:00');
