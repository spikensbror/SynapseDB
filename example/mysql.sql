SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `example_tbl`
-- ----------------------------
DROP TABLE IF EXISTS `example_tbl`;
CREATE TABLE `example_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `email` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of example_tbl
-- ----------------------------
