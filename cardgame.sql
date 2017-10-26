# Host: 127.0.0.1  (Version 5.5.5-10.1.26-MariaDB)
# Date: 2017-10-08 02:44:10
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "games"
#

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `startTime` datetime DEFAULT NULL,
  `masterName` varchar(255) DEFAULT NULL,
  `clientName` varchar(255) DEFAULT NULL,
  `cardContents` varchar(512) DEFAULT NULL,
  `clientAcceptState` tinyint(3) DEFAULT '0',
  `curUserName` varchar(255) DEFAULT NULL,
  `clickedCards` varchar(255) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "games"
#

INSERT INTO `games` VALUES (11,'2017-10-08 02:29:42','aaa@hotmail.com','abc','15,06,33,42,06,08,34,08,42,15,35,33,34,41,47,47,35,41',1,'','aaa@hotmail.com:'),(12,'2017-10-08 02:40:37','aaa@hotmail.com','abc','37,44,35,01,01,16,44,35,37,16,34,07,25,07,46,34,25,46',1,'abc','aaa@hotmail.com:0,1'),(13,'2017-10-08 02:41:35','aaa@hotmail.com','abc','44,04,50,31,21,21,50,31,22,28,28,48,22,33,04,48,44,33',1,'','aaa@hotmail.com:');

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `lastTime` datetime DEFAULT NULL,
  `role` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (9,'aaa@hotmail.com','123','2017-10-08 00:52:14',1),(10,'abc','123',NULL,1);
