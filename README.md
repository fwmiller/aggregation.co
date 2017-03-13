aggregation.co
==============
This code implements an RSS aggregator website using PHP.  It requires the use of a database to hold information about RSS feeds.
This code is currently used by the site http://aggregation.co

Database tables
---------------

CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `password` char(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `salt` char(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8

CREATE TABLE `feeds` (
 `id` int(11) NOT NULL,
 `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE `items` (
 `id` int(11) NOT NULL,
 `feedTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `feedLink` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `itemTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `itemPubDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `itemLink` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `itemDesc` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8

