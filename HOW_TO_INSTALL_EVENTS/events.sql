/*
MySQL Data Transfer
Source Host: localhost
Source Database: holodb
Target Host: localhost
Target Database: holodb
Date: 8/8/2008 10:36:40 PM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for events
-- ----------------------------
CREATE TABLE `events` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `description` varchar(100) default NULL,
  `userid` int(10) default NULL,
  `roomid` int(10) default NULL,
  `category` int(10) default NULL,
  `date` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
