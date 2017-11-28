CREATE TABLE IF NOT EXISTS `color_setting` (
  `id` int(11) NOT NULL,
  `is_color` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `color_setting` (`id`, `is_color`) VALUES
(1, 1);


CREATE TABLE IF NOT EXISTS `emails` (
`id` int(5) NOT NULL,
  `name` varchar(500) DEFAULT NULL,
  `email_id` varchar(500) DEFAULT NULL,
  `product_id` int(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

ALTER TABLE `emails`
 ADD PRIMARY KEY (`id`);
ALTER TABLE `emails`
MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
