# Upgrade 1.7 - Settings
# --------------------------------------------------------

CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`name`)
 );
INSERT INTO settings VALUES('default_title', 'Blumenthals.com Rapidweb Website');
INSERT INTO settings VALUES('default_meta_keywords', '');
INSERT INTO settings VALUES('default_meta_description', '');
