# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.4.8-MariaDB-1:10.4.8+maria~bionic)
# Database: eehmage
# Generation Time: 2020-08-25 01:20:20 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;


# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups`
(
    `id`        char(36)     NOT NULL DEFAULT '',
    `is_active` tinyint(1)   NOT NULL DEFAULT 1,
    `name`      varchar(100) NOT NULL DEFAULT '',
    `dir`       tinytext     NOT NULL,
    `created`   datetime     NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified`  datetime     NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
    `deleted`   datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `is_active` (`is_active`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;



# Dump of table images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images`
(
    `id`            char(36)     NOT NULL DEFAULT '',
    `group_id`      char(36)     NOT NULL DEFAULT '',
    `is_active`     tinyint(1)   NOT NULL DEFAULT 1,
    `name`          varchar(100) NOT NULL DEFAULT '',
    `original_name` varchar(100) NOT NULL DEFAULT '',
    `path`          tinytext     NOT NULL,
    `size`          int(11)               DEFAULT NULL,
    `type`          varchar(100)          DEFAULT NULL,
    `width`         int(11)               DEFAULT NULL,
    `height`        int(11)               DEFAULT NULL,
    `mime`          varchar(100)          DEFAULT NULL,
    `created`       datetime     NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified`      datetime     NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
    `deleted`       datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `image_name` (`group_id`, `name`),
    KEY `is_active` (`is_active`),
    KEY `group_id` (`group_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;



/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
