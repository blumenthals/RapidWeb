CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`name`)
 );
INSERT INTO settings VALUES('default_title', 'Test Rapidweb');
INSERT INTO settings VALUES('default_meta_keywords', 'Rapidweb, Olean, Content Management');
INSERT INTO settings VALUES('default_meta_description', 'An easy content management system'); 