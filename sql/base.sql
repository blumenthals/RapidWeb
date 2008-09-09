# phpMyAdmin SQL Dump
# version 2.5.3-rc1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Apr 12, 2004 at 01:54 AM
# Server version: 4.0.15
# PHP Version: 4.3.2
# 
# Database : `EMPTYRW`
# 

# --------------------------------------------------------

#
# Table structure for table `archive`
#

CREATE TABLE `archive` (
  `pagename` varchar(100) NOT NULL default '',
  `version` int(11) NOT NULL default '1',
  `flags` int(11) NOT NULL default '0',
  `author` varchar(100) default NULL,
  `lastmodified` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `refs` text,
  PRIMARY KEY  (`pagename`)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table `archive`
#


# --------------------------------------------------------

#
# Table structure for table `hitcount`
#

CREATE TABLE `hitcount` (
  `pagename` varchar(100) NOT NULL default '',
  `hits` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pagename`)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table `hitcount`
#

INSERT INTO `hitcount` VALUES ('home', 39);
INSERT INTO `hitcount` VALUES ('RecentChanges', 3);
INSERT INTO `hitcount` VALUES ('CompanyInformation', 1);
INSERT INTO `hitcount` VALUES ('OnlineBanking', 1);
INSERT INTO `hitcount` VALUES ('WhatsNew', 1);
INSERT INTO `hitcount` VALUES ('ContactUs', 6);
INSERT INTO `hitcount` VALUES ('FindPage', 17);
INSERT INTO `hitcount` VALUES ('SiteMap', 11);
INSERT INTO `hitcount` VALUES ('BackUp', 10);
INSERT INTO `hitcount` VALUES ('404-FileNotFound', 10);
INSERT INTO `hitcount` VALUES ('OfficeLocations', 1);
INSERT INTO `hitcount` VALUES ('BankingHours', 1);
INSERT INTO `hitcount` VALUES ('Services', 1);
INSERT INTO `hitcount` VALUES ('Products', 1);
INSERT INTO `hitcount` VALUES ('CommunityReinvestment', 1);
INSERT INTO `hitcount` VALUES ('Officers', 1);
INSERT INTO `hitcount` VALUES ('PrivacyNotice', 1);
INSERT INTO `hitcount` VALUES ('LocalLinks', 1);

# --------------------------------------------------------

#
# Table structure for table `hottopics`
#

CREATE TABLE `hottopics` (
  `pagename` varchar(100) NOT NULL default '',
  `lastmodified` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pagename`,`lastmodified`)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table `hottopics`
#


# --------------------------------------------------------

#
# Table structure for table `wiki`
#

CREATE TABLE `wiki` (
  `pagename` varchar(100) NOT NULL default '',
  `version` int(11) NOT NULL default '1',
  `flags` int(11) NOT NULL default '0',
  `author` varchar(100) default NULL,
  `lastmodified` int(11) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `refs` text,
  PRIMARY KEY  (`pagename`)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table `wiki`
#

INSERT INTO `wiki` VALUES ('home', 7, 1, 'admin', 1019004589, 1012377328, '!Welcome\n\nStart with your SiteMap', 'a:0:{}');
INSERT INTO `wiki` VALUES ('RecentChanges', 2, 1, 'admin', 1019004720, 1012377346, '!Recent Changes\n\n____April 16, 2002\n* [FindPage] ([diff|phpwiki:?diff=FindPage]) ..... admin\r\n* [BackUp] ([diff|phpwiki:?diff=BackUp]) ..... admin\r\n* [RecentChanges] ([diff|phpwiki:?diff=RecentChanges]) ..... admin\r\n* [SiteMap] ([diff|phpwiki:?diff=SiteMap]) ..... admin\n* [home] ([diff|phpwiki:?diff=home]) ..... admin', 'a:0:{}');
INSERT INTO `wiki` VALUES ('ContactUs', 1, 1, 'admin', 1012377736, 1012377736, '!Contact Us', 'a:0:{}');
INSERT INTO `wiki` VALUES ('FindPage', 3, 1, 'admin', 1019004925, 1012377865, '!Search Our Site\n\nView the SiteMap, RecentChanges, or use the following for a full text search. It will search any page within the website. This takes a few seconds. The results will show all lines on a given page that contain a match.\n\n%%Fullsearch%%\n\n------\n\nSeparate words with a space. All words have to match. To exclude words prepend a \'-\'. Example: \'services -internet\' looks for all pages containing the words \'services\' but not containing the word \'internet\'', 'a:0:{}');
INSERT INTO `wiki` VALUES ('SiteMap', 7, 1, 'admin', 1019004673, 1012377970, '!Site Map\n\n* [Home|home]\n\n* [Services]\n\n* [Products]\n\n* [Contact Us|ContactUs]\n\n* [Privacy Notice|PrivacyNotice]\n\n* [Links|Links]\n\n* [Search|FindPage]\n\n* [Custom Error Page|404-FileNotFound]', 'a:0:{}');
INSERT INTO `wiki` VALUES ('BackUp', 7, 1, 'admin', 1019004866, 1012380713, '!!RapidAdminPage\r\n\r\n__\'\'This works only if you are logged in as ADMIN\'\'__\r\n-----------\r\n\r\n! ZIP files of database\r\n\r\n__[ZIP Snapshot | phpwiki:?zip=snapshot]__ : contains only the latest versions\r\n\r\n__[ZIP Dump | phpwiki:?zip=all]__ : contains all archived versions\r\n\r\nThese links lead to zip files*, generated on the fly, which contain the most recent versions of all pages in your !RapidWeb. You will be prompted to save the zip file to your local computer.\r\n\r\nIf you want to view/restore pages from the zip file, you will need [WinZip|http://www.winzip.com] (PC) or [StuffIt Expander|http://www.aladdinsys.com/expander] (MAC) to extract and view pages from the zip file. (*The pages are stored, one per file, as MIME (RFC2045) e-mail (RFC822) messages.)\r\n\r\n-----------\r\n\r\n! Load / Dump Serialized Pages\r\n\r\nHere you can load or dump pages of your site into a server directory of your choice. (Note: The pages are not located on your local computer, as in the Zip option.)\r\n\r\n__Dump__\r\n\r\n%%ADMIN-INPUT-dumpserial-Dump_serialized_pages%%\r\n\r\nYou must use the server path when Dumping (backup) and Loading (restoring) data:\r\n\r\nIn this example, "yourdomain" would be your domain name without the last part (.com, .net, .org, etc...)\r\n\r\n%%%__/home/username/data/MM-DD-YY__\r\n\r\nAppend the date to the string like this:\r\n%%%__/home/username/data/06-07-01__\r\n\r\nThen you\'ll have a backup folder created for that date. The backup data will be stored in a secure area of the site (at the root level above public_html). Pages will be written out as "serialized" strings of a PHP associative array, meaning they will not be human readable.\r\n\r\nIf the directory does not exist the !RapidWeb will try to create one for you. Ensure that your server has write permissions to the directory!\r\n\r\n__Load__\r\n\r\n%%ADMIN-INPUT-loadserial-Load_serialized_pages%%\r\n\r\nIf you have dumped a set of pages from !RapidWeb, you can reload them here. Note that pages in your database will be overwritten; thus, if you dumped your ContactUs page when you load it from this form it will overwrite the one in your database now.\r\n\r\nIf you want to be selective just delete the pages from the directory you don\'t want to load. (You\'ll need to do this via FTP.)\r\n\r\n-----------', 'a:0:{}');
INSERT INTO `wiki` VALUES ('404-FileNotFound', 12, 1, 'admin', 1065210393, 1012391280, '|<center><img src=http://www.rapidweb.info/404.jpg>\n%%Fullsearch%%', 'a:0:{}');

# --------------------------------------------------------

#
# Table structure for table `wikilinks`
#

CREATE TABLE `wikilinks` (
  `frompage` varchar(100) NOT NULL default '',
  `topage` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`frompage`,`topage`)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table `wikilinks`
#

INSERT INTO `wikilinks` VALUES ('home', 'SiteMap');
INSERT INTO `wikilinks` VALUES ('FindPage', 'SiteMap');
INSERT INTO `wikilinks` VALUES ('FindPage', 'RecentChanges');
INSERT INTO `wikilinks` VALUES ('SiteMap', '404-FileNotFound');
INSERT INTO `wikilinks` VALUES ('404-FileNotFound', 'FindPage');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'FindPage');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'Links');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'PrivacyNotice');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'ContactUs');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'Products');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'Services');
INSERT INTO `wikilinks` VALUES ('SiteMap', 'home');
INSERT INTO `wikilinks` VALUES ('BackUp', 'ContactUs');
INSERT INTO `wikilinks` VALUES ('RecentChanges', 'BackUp');
INSERT INTO `wikilinks` VALUES ('RecentChanges', 'home');
INSERT INTO `wikilinks` VALUES ('RecentChanges', 'FindPage');
INSERT INTO `wikilinks` VALUES ('RecentChanges', 'SiteMap');

# --------------------------------------------------------

#
# Table structure for table `wikiscore`
#

CREATE TABLE `wikiscore` (
  `pagename` varchar(100) NOT NULL default '',
  `score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pagename`)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table `wikiscore`
#

INSERT INTO `wikiscore` VALUES ('404-FileNotFound', 3);
INSERT INTO `wikiscore` VALUES ('BackUp', 1);
INSERT INTO `wikiscore` VALUES ('ContactUs', 4);
INSERT INTO `wikiscore` VALUES ('FindPage', 5);
INSERT INTO `wikiscore` VALUES ('home', 4);
INSERT INTO `wikiscore` VALUES ('Links', 3);
INSERT INTO `wikiscore` VALUES ('PrivacyNotice', 3);
INSERT INTO `wikiscore` VALUES ('Products', 3);
INSERT INTO `wikiscore` VALUES ('RecentChanges', 3);
INSERT INTO `wikiscore` VALUES ('Services', 3);
INSERT INTO `wikiscore` VALUES ('SiteMap', 6);
