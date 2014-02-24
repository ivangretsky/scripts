CREATE TABLE IF NOT EXISTS `groupvk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idvk` int(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=829 ;