# Upgrade 1.11 - Server Error Pages & Support Page
# --------------------------------------------------------

REPLACE INTO `wiki` (`pagename`, `version`, `flags`, `author`, `lastmodified`, `created`, `content`, `refs`, `title`, `keywords`, `meta`, `variables`, `template`) VALUES
('401-AuthorizationRequired', 1, 0, 'admin', 1222318755, 1222318755, 'STARTHTML\n<style type="text/css">\n<!--\nbody, td, th {\n	font-family: Arial, Helvetica, sans-serif;\n	font-size: 12px;\n}\na:link, a:visited, a:active {\n	color: #C82127;\n}\na:hover {\n	color: #E62128;\n}\nh1 {\n	font-weight: bold;\n	font-size: 14px;\n	color: #C82127;\n}\n.pngimg {\n	behavior: url("../../../../rw-global/pngbehavior.htc");\n}\n-->\n</style>\n<center>\n<table border="0" cellspacing="0" cellpadding="0">\n<tr>\n<td><img src="../../../../rw-global/images/edit/401b.png" alt="401 - Authorization Required" width="271" height="178" class="pngimg" /></td>\n<td>\n\n<h1>This is a restricted area.</h1>\nWe apologize. The page you are looking<br>\nfor requires the proper authorization.<br><br>\n\nPlease try your request again or try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href="###SCRIPTURL###?Sitemap">sitemap</a>, start over from the <a href="index.php?home">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL),
('403-Restricted', 1, 0, 'admin', 1222318726, 1222318726, 'STARTHTML\n<style type="text/css">\n<!--\nbody, td, th {\n	font-family: Arial, Helvetica, sans-serif;\n	font-size: 12px;\n}\na:link, a:visited, a:active {\n	color: #C82127;\n}\na:hover {\n	color: #E62128;\n}\nh1 {\n	font-weight: bold;\n	font-size: 14px;\n	color: #C82127;\n}\n.pngimg {\n	behavior: url("../../../../rw-global/pngbehavior.htc");\n}\n-->\n</style>\n<center>\n<table border="0" cellspacing="0" cellpadding="0">\n<tr>\n<td><img src="../../../../rw-global/images/edit/403b.png" alt="403 - Forbidden" width="303" height="179" class="pngimg" /></td>\n<td>\n\n<h1>This is a restricted area.</h1>\nWe apologize. The page you are looking for is in<br>\na restricted area and is not available to the public.<br><br>\n\nIf you''re in denial and think this is a conspiracy<br>\nthat cannot be possibly true, please try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href="###SCRIPTURL###?Sitemap">sitemap</a>, start over from the <a href="index.php?home">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL),
('404-FileNotFound', 8, 1, 'admin', 1222290675, 1012391280, 'STARTHTML\n<style type="text/css">\n<!--\nbody, td, th {\n	font-family: Arial, Helvetica, sans-serif;\n	font-size: 12px;\n}\na:link, a:visited, a:active {\n	color: #F28B22;\n}\na:hover {\n	color: #F6B618;\n}\nh1 {\n	font-weight: bold;\n	font-size: 14px;\n	color: #F28B22;\n}\n.pngimg {\n	behavior: url("../../../../rw-global/pngbehavior.htc");\n}\n-->\n</style>\n<center>\n<table border="0" cellspacing="0" cellpadding="0">\n<tr>\n<td><img src="../../../../rw-global/images/edit/404b.png" alt="404 - File Not Found" width="248" height="171" class="pngimg" /></td>\n<td>\n\n<h1>Oops, Page Not Found.</h1>\nWe apologize. The page you<br>\nare looking for cannot be found.<br><br>\n\nIf you''re in denial and think this is a conspiracy<br>\nthat cannot be possibly true, please try searching<br>\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href="###SCRIPTURL###?Sitemap">sitemap</a>, start over from the <a href="index.php?home">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', NULL, NULL, NULL, NULL, NULL),
('500-ServerError', 1, 0, 'admin', 1222318742, 1222318742, 'STARTHTML\n<style type="text/css">\n<!--\nbody, td, th {\n	font-family: Arial, Helvetica, sans-serif;\n	font-size: 12px;\n}\na:link, a:visited, a:active {\n	color: #F28B22;\n}\na:hover {\n	color: #F6B618;\n}\nh1 {\n	font-weight: bold;\n	font-size: 14px;\n	color: #F28B22;\n}\n.pngimg {\n	behavior: url("../../../../rw-global/pngbehavior.htc");\n}\n-->\n</style>\n<center>\n<table border="0" cellspacing="0" cellpadding="0">\n<tr>\n<td><img src="../../../../rw-global/images/edit/500b.png" alt="500 - Internal Server Error" width="213" height="173" class="pngimg" /></td>\n<td>\n\n<h1>Whoops, Internal Server Error.</h1>\nWe apologize. The page you are looking for<br>\nis unaccessible due to a little server hiccup.<br><br>\n\nPlease try your request again or try searching\nour site using the search box below.<br><br>\nENDHTML\n%%Fullsearch%%\nSTARTHTML\n<br>\nYou may also want to try looking through our<br />\n<a href="###SCRIPTURL###?Sitemap">sitemap</a>, start over from the <a href="index.php?home">home page</a>, or select<br>\nfrom the navigational menus. We hope you find<br>\njust what you were looking for.\n</td>\n</tr>\n</table>\n</center>\nENDHTML', 'a:0:{}', '', '', '', NULL, NULL),
('BlumenthalsSupport', 1, 0, 'admin', 1222318661, 1222318661, '!!Blumenthals  Olean NY Web Hosting Support Options\n\nFor the quickest response post on our [Ticket Reporting System|http://tickets.blumenthals.com].\n\nWeb Hosting, Web Design, Email Support:%%%\n[Blumenthals  Web Hosting, Web Design - Olean Office|http://www.blumenthals.com]%%%\n201 N Union St. Suite 317%%%\nOlean, NY 14760 %%%\n716-372-4008\n\nBilling & Invoicing Questions:%%%\nBlumenthals.com%%%\n6 Valleybrook Drive%%%\nBradford PA 16701%%%\n814-368-4057', 'a:0:{}', '', '', '', NULL, NULL);
