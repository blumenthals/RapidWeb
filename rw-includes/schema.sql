-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 29, 2016 at 11:02 AM
-- Server version: 5.5.48-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `marvinj_rwdev`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE IF NOT EXISTS `archive` (
  `pagename` varchar(100) NOT NULL DEFAULT '',
  `version` int(11) NOT NULL DEFAULT '1',
  `flags` int(11) NOT NULL DEFAULT '0',
  `author` varchar(100) DEFAULT NULL,
  `lastmodified` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `refs` text,
  `meta` text,
  `title` text,
  `keywords` text,
  `variables` text,
  `template` text,
  PRIMARY KEY (`pagename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hitcount`
--

CREATE TABLE IF NOT EXISTS `hitcount` (
  `pagename` varchar(100) NOT NULL DEFAULT '',
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pagename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hottopics`
--

CREATE TABLE IF NOT EXISTS `hottopics` (
  `pagename` varchar(100) NOT NULL DEFAULT '',
  `lastmodified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pagename`,`lastmodified`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `MODYLLIC`
--

CREATE TABLE IF NOT EXISTS `MODYLLIC` (
  `kind` char(9) NOT NULL,
  `which` char(90) NOT NULL,
  `value` varchar(20000) NOT NULL,
  PRIMARY KEY (`kind`,`which`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `MODYLLIC`
--

INSERT INTO `MODYLLIC` (`kind`, `which`, `value`) VALUES
('TABLE', 'MODYLLIC', '{"static":true}');

-- --------------------------------------------------------

--
-- Table structure for table `rapidwebinfo`
--

CREATE TABLE IF NOT EXISTS `rapidwebinfo` (
  `name` varchar(32) NOT NULL,
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wiki`
--

CREATE TABLE IF NOT EXISTS `wiki` (
  `pagename` varchar(100) NOT NULL DEFAULT '',
  `version` int(11) NOT NULL DEFAULT '1',
  `flags` int(11) NOT NULL DEFAULT '0',
  `author` varchar(100) DEFAULT NULL,
  `lastmodified` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `refs` text,
  `title` text,
  `keywords` text,
  `meta` text,
  `variables` text,
  `template` varchar(100) DEFAULT NULL,
  `noindex` tinyint(1) DEFAULT NULL,
  `gallery` text,
  `page_type` varchar(32) NOT NULL DEFAULT 'page',
  `plugins` text,
  `head` text,
  `foot` text,
  PRIMARY KEY (`pagename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wiki`
--

INSERT INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`, `noindex`, `gallery`, `page_type`, `plugins`, `head`, `foot`) VALUES
('nav', 2, 0, NULL, 1398261877, 1398180493, '*[Gallery]\n*[Locations]\n*[Contact Us]\n*[About Us]\n*[Services]\n*[Home]', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Home', 14, 0, NULL, 1459262655, 1398180606, '!!!Lorem ipsum dolor sit amet.\n!!consectetuer adipiscing elit\nNulam [Contact Us] erat ut turpis. Suspendise urna nibh, vivera non, semper suscipit, posuere a, pede. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, comodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagitis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phaselus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.\n\n!!Sed egestas, ante et vulputate\nMorbi interdum molis sapien. Sed ac risus. Phaselus lacinia, magna a ulamcorper laoret, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, telus. Suspendise ac urna. Etiam pelentesque mauris ut lectus. Nunc telus ante, matis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.\n\n\n!Nunc telus ante, matis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Services', 6, 0, NULL, 1398270531, 1398180612, '!!!Services\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat matis eros. Nulam malesuada erat ut turpis. Suspendise urna nibh, vivera non, semper suscipit, posuere a, pede.\n\nSed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, comodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagitis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phaselus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('About Us', 2, 0, NULL, 1398270548, 1398180617, '!!!About\n\nMorbi interdum molis sapien. Sed ac risus. Phaselus lacinia, magna a ulamcorper laoret, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, telus. Suspendise ac urna. Etiam pelentesque mauris ut lectus. Nunc telus ante, matis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.\n\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat matis eros. Nulam malesuada erat ut turpis. Suspendise urna nibh, vivera non, semper suscipit, posuere a, pede.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Contact Us', 2, 0, NULL, 1459262680, 1398180625, '!!!Contact Us\n\nSed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, comodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagitis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phaselus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Locations', 2, 0, NULL, 1459262695, 1398180630, '!!!Locations\n\nMorbi interdum molis sapien. Sed ac risus. Phaselus lacinia, magna a ulamcorper laoret, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, telus. Suspendise ac urna. Etiam pelentesque mauris ut lectus. Nunc telus ante, matis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.\n\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat matis eros. Nulam malesuada erat ut turpis. Suspendise urna nibh, vivera non, semper suscipit, posuere a, pede.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('left_footer', 1, 0, NULL, 1398180681, 1398180681, '!!!Blumenthals.com\n201 N. Union St. #307%%%\nOlean, NY 14760 US\n!!716-372-4008 | 716-372-4008', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('call_to_action', 1, 0, NULL, 1398180699, 1398180699, '(555) 555-5555', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('top_sidebar', 1, 0, NULL, 1398180713, 1398180713, '!!Morbi interdum molis\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat matis eros. Nulam malesuada erat ut turpis. Suspendise urna nibh, vivera non, semper suscipit, posuere a, pede.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('middle_sidebar', 1, 0, NULL, 1398180735, 1398180735, '!!Contact Us!\nSed egestas, ante et vulputate volutpat, eros pede semper.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('bottom_sidebar', 1, 0, NULL, 1398180810, 1398180810, '!!Sed egestas, ante et vulputate\nVolutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, comodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagitis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phaselus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('slideshow', 7, 0, NULL, 1459262163, 1398181877, 'STARTHTML\n<li><img src="images/upload/slide01.jpg" /></li>\n<li><img src="images/upload/slide02.jpg" /></li>\n<li><img src="images/upload/slide03.jpg" /></li>\nENDHTML', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Gallery', 9, 0, NULL, 1459262905, 1398261897, '', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[{"image":"\\/images\\/upload\\/Gallery\\/bigstock-African-american-Single-parent-8325980.jpg","thumbnail":"\\/images\\/upload\\/Gallery\\/bigstock-African-american-Single-parent-8325980.jpg.150x150.jpg","caption":"Family","description":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit."},{"image":"\\/images\\/upload\\/Gallery\\/bigstock-Coffee-Cup-And-Coffee-Beans-84244856.jpg","thumbnail":"\\/images\\/upload\\/Gallery\\/bigstock-Coffee-Cup-And-Coffee-Beans-84244856.jpg.150x150.jpg","caption":"Pick-Me-Up","description":"Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue."},{"image":"\\/images\\/upload\\/Gallery\\/bigstock-Glowing-Old-Light-Bulbs-84917747.jpg","thumbnail":"\\/images\\/upload\\/Gallery\\/bigstock-Glowing-Old-Light-Bulbs-84917747.jpg.150x150.jpg","caption":"Ideas","description":"Quisque volutpat matis eros. Nulam malesuada erat ut turpis."},{"image":"\\/images\\/upload\\/Gallery\\/bigstock-Hand-above-green-fresh-grass-o-21512303.jpg","thumbnail":"\\/images\\/upload\\/Gallery\\/bigstock-Hand-above-green-fresh-grass-o-21512303.jpg.150x150.jpg","caption":"Just Right","description":"Suspendise urna nibh, vivera non, semper suscipit, posuere a, pede."},{"image":"\\/images\\/upload\\/Gallery\\/bigstock-Under-The-Boardwalk-61845371.jpg","thumbnail":"\\/images\\/upload\\/Gallery\\/bigstock-Under-The-Boardwalk-61845371.jpg.150x150.jpg","caption":"Beach Pier","description":"Phaselus pede arcu, dapibus eu, fermentum et, dapibus sed, urna."}]', 'rwgallery', '{}', NULL, NULL),
('404-FileNotFound', 6, 0, NULL, 1398273672, 1398271710, '|<div class="error_image"><img src="rw-global/images/edit/404b.png" alt="404 - File Not Found" /></div>\n!!!Oops, Page Not Found.\nWe apologize. The page you are looking for cannot be found.\n\nPlease try searching our site using the search box below.\n%%Fullsearch%%\n\nYou may also want to try looking through our [Sitemap], start over from the [home page | home], or select from the navigational menus above. We hope you find just what you were looking for.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('403-Restricted', 3, 0, NULL, 1398274025, 1398273750, '|<div class="error_image"><img src="rw-global/images/edit/403b.png" alt="403 - Restricted" /></div>\n!!!This is a restricted area.\nWe apologize. The page you are looking for is in a restricted area and is not available to the public.\n\nPlease try  searching our site using the search box below.\n%%Fullsearch%%\n\nYou may also want to try looking through our [Sitemap], start over from the [home page | home], or select from the navigational menus above. We hope you find just what you were looking for.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('500-ServerError', 1, 0, NULL, 1398273882, 1398273882, '|<div class="error_image"><img src="rw-global/images/edit/500b.png" alt="500 - Server Error" /></div>\n!!!Whoops, Internal Server Error.\nWe apologize. The page you are looking for is inaccessible due to a little server hiccup.\n\nPlease try your request again or try searching our site using the search box below.\n%%Fullsearch%%\n\nYou may also want to try looking through our [Sitemap], start over from the [home page | home], or select from the navigational menus above. We hope you find just what you were looking for.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('401-AuthorizationRequired', 1, 0, NULL, 1398273950, 1398273950, '|<div class="error_image"><img src="rw-global/images/edit/401b.png" alt="401- AuthorizationRequired" /></div>\n!!!This is a restricted area.\nWe apologize. The page you are looking for requires the proper authorization.\n\nPlease try your request again or try searching our site using the search box below.\n%%Fullsearch%%\n\nYou may also want to try looking through our [Sitemap], start over from the [home page | home], or select from the navigational menus above. We hope you find just what you were looking for.', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Sitemap', 5, 0, NULL, 1459262930, 1398274363, '!!!SiteMap\n!![Home]\n*[slideshow]\n\n!![Primary Navigation | nav]\n*[Services]\n*[About Us]\n*[Contact Us]\n*[Locations]\n*[Gallery]\n\n!!Template Elements\n*[Call To Action Button | call_to_action]\n*[Top Sidebar | top_sidebar]\n*[Middle Sidebar | middle_sidebar]\n*[Bottom Sidebar | bottom_sidebar]\n*[Left Footer | left_footer]\n\n!!Extra Pages\n*[404-FileNotFound]\n*[403-Restricted]\n*[500-ServerError]\n*[401-AuthorizationRequired]\n*[Search]\n*[Support]', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Search', 1, 0, NULL, 1398274810, 1398274810, '!!!Search Our Site\n\nView the [SiteMap], or use the following for a full text search. It will search any page within the website. This takes a few seconds. The results will show all lines on a given page that contain a match.\n\n%%Fullsearch%%\n\n------\n\nSeparate words with a space. All words have to match. To exclude words prepend a ''-''. Example: ''services -internet'' looks for all pages containing the words ''services'' but not containing the word ''internet''', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL),
('Support', 4, 0, NULL, 1398274938, 1398274890, '!!!Blumenthals  Olean NY Web Hosting Support Options\n\n!For the quickest response post on our [Ticket Reporting System|http://tickets.blumenthals.com].\n\n!!Web Hosting, Web Design, Email Support:\n[Blumenthals  Web Hosting, Web Design - Olean Office|http://www.blumenthals.com]%%%\n201 N Union St. Suite 317%%%\nOlean, NY 14760 %%%\n716-372-4008\n\n!!Billing & Invoicing Questions:\n[Blumenthals  Web Hosting, Web Design - Bradford Office|http://www.blumenthals.com]%%%\n6 Valleybrook Drive%%%\nBradford PA 16701%%%\n814-368-4057', 'a:0:{}', NULL, NULL, NULL, NULL, NULL, 0, '[]', 'page', '{}', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wikilinks`
--

CREATE TABLE IF NOT EXISTS `wikilinks` (
  `frompage` varchar(100) NOT NULL DEFAULT '',
  `topage` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`frompage`,`topage`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wikiscore`
--

CREATE TABLE IF NOT EXISTS `wikiscore` (
  `pagename` varchar(100) NOT NULL DEFAULT '',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pagename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wp_commentmeta`
--

CREATE TABLE IF NOT EXISTS `wp_commentmeta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_swedish_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_comments`
--

CREATE TABLE IF NOT EXISTS `wp_comments` (
  `comment_ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8_swedish_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8_swedish_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_post_ID` (`comment_post_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_links`
--

CREATE TABLE IF NOT EXISTS `wp_links` (
  `link_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8_swedish_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_options`
--

CREATE TABLE IF NOT EXISTS `wp_options` (
  `option_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL DEFAULT '0',
  `option_name` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8_swedish_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_postmeta`
--

CREATE TABLE IF NOT EXISTS `wp_postmeta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_swedish_ci,
  PRIMARY KEY (`meta_id`),
  KEY `meta_key` (`meta_key`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_posts`
--

CREATE TABLE IF NOT EXISTS `wp_posts` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8_swedish_ci NOT NULL,
  `post_title` text COLLATE utf8_swedish_ci NOT NULL,
  `post_excerpt` text COLLATE utf8_swedish_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8_swedish_ci NOT NULL,
  `pinged` text COLLATE utf8_swedish_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` text COLLATE utf8_swedish_ci NOT NULL,
  `post_parent` bigint(20) NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_author` (`post_author`),
  KEY `post_name` (`post_name`),
  KEY `post_parent` (`post_parent`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_terms`
--

CREATE TABLE IF NOT EXISTS `wp_terms` (
  `term_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_relationships`
--

CREATE TABLE IF NOT EXISTS `wp_term_relationships` (
  `object_id` bigint(20) NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_taxonomy`
--

CREATE TABLE IF NOT EXISTS `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8_swedish_ci NOT NULL,
  `parent` bigint(20) NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_usermeta`
--

CREATE TABLE IF NOT EXISTS `wp_usermeta` (
  `umeta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_swedish_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `meta_key` (`meta_key`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

CREATE TABLE IF NOT EXISTS `wp_users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `user_pass` varchar(64) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
