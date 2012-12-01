/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : hackathon_peer

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2012-12-01 12:52:21
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `problems`
-- ----------------------------
DROP TABLE IF EXISTS `problems`;
CREATE TABLE `problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `uploader_id` varchar(50) NOT NULL,
  `uploader_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `filename` varchar(100) NOT NULL,
  `entry_date` datetime NOT NULL,
  `like_count` int(11) unsigned zerofill NOT NULL,
  `last_liked` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `stars` int(30) unsigned zerofill NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of problems
-- ----------------------------
INSERT INTO `problems` VALUES ('1', 'à¦¤à§‡à¦œà¦—à¦¾à¦“ à¦à¦²à¦¾à¦•à¦¾à§Ÿ à¦…à¦ªà¦°à¦¿à¦šà§à¦›à¦¾à¦¨à§à¦¨à¦¤à¦¾!', '1', 'me', 'email@email.com', 'à¦›à§‹à¦Ÿ à¦°à§‹à¦¡ à¦—à§à¦²à§‹ à¦¤à§‡ à¦ªà§à¦°à¦šà§à¦° à¦®à§Ÿà¦²à¦¾ à¦†à¦¬à¦°à§à¦œà¦¨à¦¾ à¥¤', '1', 'fn', '2012-12-01 11:48:53', '00000000001', '2012-12-01 12:04:04', 'Active', '000000000000000000000000000001', '23.764828415739313', '90.39773941040039');
INSERT INTO `problems` VALUES ('2', 'à¦¬à¦—à§à§œà¦¾à§Ÿ à¦Ÿà§Ÿà¦²à§‡à¦Ÿà§‡ à¦¹à¦¾à¦¤ à¦§à§‹à§Ÿà¦¾à¦° à¦ªà¦¾à¦¨à¦¿ à¦¨à¦¾à¦‡', '1', 'me', 'email@email.com', 'à¦ªà¦¾à¦¬à¦²à¦¿à¦• à¦Ÿà§Ÿà¦²à§‡à¦Ÿà§‡ à¦¹à¦¾à¦¤ à¦§à§‹à§Ÿà¦¾à¦° à¦ªà¦¾à¦¨à¦¿ à¦¨à¦¾à¦‡, à¦¬à§‡à¦¶ à¦•à§Ÿà§‡à¦•à¦Ÿà¦¿ à¦à¦²à¦¾à¦•à¦¾à§Ÿ', '5', 'fn', '2012-12-01 12:03:14', '00000000001', '2012-12-01 12:03:49', 'Active', '000000000000000000000000000005', '24.84997670301076', '89.36777114868164');
INSERT INTO `problems` VALUES ('3', 'à¦¸à§à¦¯à¦¾à¦¨à¦¿à¦Ÿà§à¦¯à¦¾à¦¶à¦¨ à¦¸à¦®à¦¸à§à¦¯à¦¾', '1', 'me', 'email@email.com', 'à¦®à¦¾à¦¨à§à¦· à¦¬à§‡à¦¶à§€! à¦¯à¦¤à§à¦¨ à¦¨à¦¾à¦‡! à¦…à¦ªà¦°à¦¿à¦¸à§à¦•à¦¾à¦°!', null, 'fn', '2012-12-01 12:29:36', '00000000000', '2012-12-01 12:29:36', 'Active', '000000000000000000000000000000', '23.740955729131407', '90.39656057953835');
INSERT INTO `problems` VALUES ('4', 'Khilgaon slums lack toilets', '1', 'me', 'email@email.com', 'We need to build toilets ASAP.', null, 'fn', '2012-12-01 12:39:14', '00000000000', '2012-12-01 12:39:14', 'Active', '000000000000000000000000000000', '23.75361058287537', '90.43739318847656');

-- ----------------------------
-- Table structure for `problems_votes`
-- ----------------------------
DROP TABLE IF EXISTS `problems_votes`;
CREATE TABLE `problems_votes` (
  `fbid` varchar(40) NOT NULL,
  `voted` int(10) NOT NULL,
  `liking_time` datetime NOT NULL,
  `item_id` int(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of problems_votes
-- ----------------------------
INSERT INTO `problems_votes` VALUES ('5', '5', '2012-12-01 12:03:49', '2');
INSERT INTO `problems_votes` VALUES ('2', '1', '2012-12-01 12:04:04', '1');

-- ----------------------------
-- Table structure for `solutions`
-- ----------------------------
DROP TABLE IF EXISTS `solutions`;
CREATE TABLE `solutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `uploader_id` varchar(50) NOT NULL,
  `uploader_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `filename` varchar(100) NOT NULL,
  `entry_date` datetime NOT NULL,
  `stars` int(11) DEFAULT NULL,
  `like_count` int(11) unsigned zerofill NOT NULL,
  `last_liked` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `cost` int(11) DEFAULT NULL,
  `man_power` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of solutions
-- ----------------------------
INSERT INTO `solutions` VALUES ('1', 'à¦à¦²à¦¾à¦•à¦¾à¦° à¦¸à¦•à¦²à§‡à¦° à¦¸à¦¹à¦¯à§‹à¦—à¦¿à¦¤à¦¾à§Ÿ', '3', 'sdf', 'email@email.com', ' à¦¸à§à¦•à§à¦², à¦•à¦²à§‡à¦œ à¦“ à¦†à¦¶à§‡à¦ªà¦¾à¦¶à§‡à¦° à¦¬à¦¾à§œà¦¿ à¦¤à§‡ à¦²à§‹à¦• à¦—à¦¿à§Ÿà§‡ à¦—à¦¿à§Ÿà§‡, à¦…à¦¥à¦¬à¦¾ à¦…à¦¨à§à¦¯ à¦‰à¦ªà¦¾à§Ÿà§‡ à¦à¦° à¦–à¦¾à¦°à¦¾à¦ª à¦¦à¦¿à¦• à¦¬à§à¦à¦¾à¦¤à§‡ à¦¹à¦¬à§‡à¥¤', '2', 'fn', '2012-12-01 12:12:07', '3', '00000000002', '2012-12-01 12:20:20', 'Active', '1', '20000', '15', '20');
INSERT INTO `solutions` VALUES ('2', 'We Must Change the behavior of ours', '2', 'sdf', 'email@email.com', 'We need to tell each one, that how it can cause harm to our health, so we need to advertise, as well as run different campaign. ', '5', 'fn', '2012-12-01 12:16:31', '5', '00000000001', '2012-12-01 12:20:30', 'Active', '1', '100000', '30', '10');
INSERT INTO `solutions` VALUES ('3', 'à¦Ÿà§Ÿà¦²à§‡à¦Ÿ à¦—à§à¦²à§‹à¦° à¦œà¦¨à§à¦¯ à¦¦à§à¦°à§à¦¤ à¦¸à¦°à¦•à¦¾à¦°à¦¿ à¦¸à¦¾à¦¹à¦¾à¦¯à§à¦¯ à¦ªà§à¦°à§Ÿà§‹à¦œà¦¨', '4', 'sdf', 'email@email.com', 'à¦¦à§à¦°à§à¦¤ à¦¸à¦°à¦•à¦¾à¦° à¦¥à§‡à¦•à§‡ à¦Ÿà¦¾à¦•à¦¾ à¦¬à¦°à¦¾à¦¦à§à¦¦ à¦•à¦°à¦¤à§‡, à¦œà§‡à¦²à¦¾ à¦…à¦«à¦¿à¦¸ à¦ à¦†à¦¬à§‡à¦¦à¦¨ à¦•à¦°à§à¦¨à¥¤', '3', 'fn', '2012-12-01 12:28:52', '3', '00000000001', '2012-12-01 12:29:02', 'Active', '2', '0', '0', '0');

-- ----------------------------
-- Table structure for `solutions_votes`
-- ----------------------------
DROP TABLE IF EXISTS `solutions_votes`;
CREATE TABLE `solutions_votes` (
  `fbid` varchar(50) NOT NULL,
  `voted` int(11) DEFAULT NULL,
  `liking_time` datetime NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of solutions_votes
-- ----------------------------
INSERT INTO `solutions_votes` VALUES ('7', '1', '2012-12-01 12:12:32', '1');
INSERT INTO `solutions_votes` VALUES ('3', '3', '2012-12-01 12:20:20', '1');
INSERT INTO `solutions_votes` VALUES ('6', '5', '2012-12-01 12:20:30', '2');
INSERT INTO `solutions_votes` VALUES ('4', '3', '2012-12-01 12:29:02', '3');
