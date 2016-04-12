SET NAMES 'utf8';
DELIMITER ;;

CREATE TABLE `archive` (
    pagename VARCHAR(100) NOT NULL DEFAULT '',
    version INT NOT NULL DEFAULT 1,
    flags INT NOT NULL DEFAULT 0,
    author VARCHAR(100),
    lastmodified INT NOT NULL DEFAULT 0,
    created INT NOT NULL DEFAULT 0,
    content MEDIUMTEXT NOT NULL,
    refs TEXT,
    meta TEXT,
    title TEXT,
    keywords TEXT,
    variables TEXT,
    template TEXT,
    PRIMARY KEY (pagename)
) ENGINE=MyISAM
;;
CREATE TABLE hitcount (
    pagename VARCHAR(100) NOT NULL DEFAULT '',
    hits INT NOT NULL DEFAULT 0,
    PRIMARY KEY (pagename)
) ENGINE=MyISAM
;;
CREATE TABLE hottopics (
    pagename VARCHAR(100) NOT NULL DEFAULT '',
    lastmodified INT NOT NULL DEFAULT 0,
    PRIMARY KEY (pagename,lastmodified)
) ENGINE=MyISAM
;;
CREATE TABLE rapidwebinfo (
    name VARCHAR(32) NOT NULL,
    value TEXT,
    PRIMARY KEY (name)
) ENGINE=MyISAM
;;
CREATE TABLE settings (
    name VARCHAR(100) NOT NULL,
    value VARCHAR(255),
    PRIMARY KEY (name)
) ENGINE=MyISAM
;;
CREATE TABLE wiki (
    pagename VARCHAR(100) NOT NULL DEFAULT '',
    version INT NOT NULL DEFAULT 1,
    flags INT NOT NULL DEFAULT 0,
    author VARCHAR(100),
    lastmodified INT NOT NULL DEFAULT 0,
    created INT NOT NULL DEFAULT 0,
    content MEDIUMTEXT NOT NULL,
    refs TEXT,
    title TEXT,
    keywords TEXT,
    meta TEXT,
    variables TEXT,
    template VARCHAR(100),
    noindex TINYINT(1),
    gallery TEXT,
    page_type VARCHAR(32) NOT NULL DEFAULT 'page',
    plugins TEXT,
    head TEXT,
    foot TEXT,
    PRIMARY KEY (pagename)
) ENGINE=MyISAM
;;
CREATE TABLE wikilinks (
    frompage VARCHAR(100) NOT NULL DEFAULT '',
    topage VARCHAR(100) NOT NULL DEFAULT '',
    PRIMARY KEY (frompage,topage)
) ENGINE=MyISAM
;;
CREATE TABLE wikiscore (
    pagename VARCHAR(100) NOT NULL DEFAULT '',
    score INT NOT NULL DEFAULT 0,
    PRIMARY KEY (pagename)
) ENGINE=MyISAM
;;
CREATE TABLE wp_commentmeta (
    meta_id BIGINT NOT NULL auto_increment,
    comment_id BIGINT NOT NULL DEFAULT 0,
    meta_key VARCHAR(255),
    meta_value LONGTEXT,
    PRIMARY KEY (meta_id),
    KEY comment_id (comment_id),
    KEY meta_key (meta_key)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_comments (
    comment_ID BIGINT NOT NULL auto_increment,
    comment_post_ID BIGINT NOT NULL DEFAULT 0,
    comment_author TINYTEXT NOT NULL,
    comment_author_email VARCHAR(100) NOT NULL DEFAULT '',
    comment_author_url VARCHAR(200) NOT NULL DEFAULT '',
    comment_author_IP VARCHAR(100) NOT NULL DEFAULT '',
    comment_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    comment_date_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    comment_content TEXT NOT NULL,
    comment_karma INT NOT NULL DEFAULT 0,
    comment_approved VARCHAR(20) NOT NULL DEFAULT '1',
    comment_agent VARCHAR(255) NOT NULL DEFAULT '',
    comment_type VARCHAR(20) NOT NULL DEFAULT '',
    comment_parent BIGINT NOT NULL DEFAULT 0,
    user_id BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (comment_ID),
    KEY comment_approved (comment_approved),
    KEY comment_approved_date_gmt (comment_approved,comment_date_gmt),
    KEY comment_date_gmt (comment_date_gmt),
    KEY comment_parent (comment_parent),
    KEY comment_post_ID (comment_post_ID)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_links (
    link_id BIGINT NOT NULL auto_increment,
    link_url VARCHAR(255) NOT NULL DEFAULT '',
    link_name VARCHAR(255) NOT NULL DEFAULT '',
    link_image VARCHAR(255) NOT NULL DEFAULT '',
    link_target VARCHAR(25) NOT NULL DEFAULT '',
    link_description VARCHAR(255) NOT NULL DEFAULT '',
    link_visible VARCHAR(20) NOT NULL DEFAULT 'Y',
    link_owner BIGINT NOT NULL DEFAULT 1,
    link_rating INT NOT NULL DEFAULT 0,
    link_updated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    link_rel VARCHAR(255) NOT NULL DEFAULT '',
    link_notes MEDIUMTEXT NOT NULL,
    link_rss VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (link_id),
    KEY link_visible (link_visible)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_options (
    option_id BIGINT NOT NULL auto_increment,
    blog_id INT NOT NULL DEFAULT 0,
    option_name VARCHAR(64) NOT NULL DEFAULT '',
    option_value LONGTEXT NOT NULL,
    autoload VARCHAR(20) NOT NULL DEFAULT 'yes',
    PRIMARY KEY (option_id),
    UNIQUE KEY option_name (option_name)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_postmeta (
    meta_id BIGINT NOT NULL auto_increment,
    post_id BIGINT NOT NULL DEFAULT 0,
    meta_key VARCHAR(255),
    meta_value LONGTEXT,
    PRIMARY KEY (meta_id),
    KEY meta_key (meta_key),
    KEY post_id (post_id)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_posts (
    ID BIGINT NOT NULL auto_increment,
    post_author BIGINT NOT NULL DEFAULT 0,
    post_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    post_date_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    post_content LONGTEXT NOT NULL,
    post_title TEXT NOT NULL,
    post_excerpt TEXT NOT NULL,
    post_status VARCHAR(20) NOT NULL DEFAULT 'publish',
    comment_status VARCHAR(20) NOT NULL DEFAULT 'open',
    ping_status VARCHAR(20) NOT NULL DEFAULT 'open',
    post_password VARCHAR(20) NOT NULL DEFAULT '',
    post_name VARCHAR(200) NOT NULL DEFAULT '',
    to_ping TEXT NOT NULL,
    pinged TEXT NOT NULL,
    post_modified DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    post_modified_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    post_content_filtered TEXT NOT NULL,
    post_parent BIGINT NOT NULL DEFAULT 0,
    guid VARCHAR(255) NOT NULL DEFAULT '',
    menu_order INT NOT NULL DEFAULT 0,
    post_type VARCHAR(20) NOT NULL DEFAULT 'post',
    post_mime_type VARCHAR(100) NOT NULL DEFAULT '',
    comment_count BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID),
    KEY post_author (post_author),
    KEY post_name (post_name),
    KEY post_parent (post_parent),
    KEY type_status_date (post_type,post_status,post_date,ID)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_term_relationships (
    object_id BIGINT NOT NULL DEFAULT 0,
    term_taxonomy_id BIGINT NOT NULL DEFAULT 0,
    term_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (object_id,term_taxonomy_id),
    KEY term_taxonomy_id (term_taxonomy_id)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_term_taxonomy (
    term_taxonomy_id BIGINT NOT NULL auto_increment,
    term_id BIGINT NOT NULL DEFAULT 0,
    taxonomy VARCHAR(32) NOT NULL DEFAULT '',
    description LONGTEXT NOT NULL,
    parent BIGINT NOT NULL DEFAULT 0,
    count BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (term_taxonomy_id),
    KEY taxonomy (taxonomy),
    UNIQUE KEY term_id_taxonomy (term_id,taxonomy)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_terms (
    term_id BIGINT NOT NULL auto_increment,
    name VARCHAR(200) NOT NULL DEFAULT '',
    slug VARCHAR(200) NOT NULL DEFAULT '',
    term_group BIGINT(10) NOT NULL DEFAULT 0,
    PRIMARY KEY (term_id),
    KEY name (name),
    UNIQUE KEY slug (slug)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_usermeta (
    umeta_id BIGINT NOT NULL auto_increment,
    user_id BIGINT NOT NULL DEFAULT 0,
    meta_key VARCHAR(255),
    meta_value LONGTEXT,
    PRIMARY KEY (umeta_id),
    KEY meta_key (meta_key),
    KEY user_id (user_id)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;
CREATE TABLE wp_users (
    ID BIGINT NOT NULL auto_increment,
    user_login VARCHAR(60) NOT NULL DEFAULT '',
    user_pass VARCHAR(64) NOT NULL DEFAULT '',
    user_nicename VARCHAR(50) NOT NULL DEFAULT '',
    user_email VARCHAR(100) NOT NULL DEFAULT '',
    user_url VARCHAR(100) NOT NULL DEFAULT '',
    user_registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    user_activation_key VARCHAR(60) NOT NULL DEFAULT '',
    user_status INT NOT NULL DEFAULT 0,
    display_name VARCHAR(250) NOT NULL DEFAULT '',
    PRIMARY KEY (ID),
    KEY user_login_key (user_login),
    KEY user_nicename (user_nicename)
) ENGINE=MyISAM
  DEFAULT CHARACTER SET=utf8
  DEFAULT COLLATE=utf8_swedish_ci
;;

DELIMITER ;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
