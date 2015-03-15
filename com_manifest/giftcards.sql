CREATE TABLE jos_giftcards (
  num int(11) NOT NULL AUTO_INCREMENT,
  jumped tinyint(1) DEFAULT '0',
  expire date NOT NULL,
  person varchar(255) NOT NULL,
  email varchar(255) DEFAULT NULL,
  contact varchar(255) DEFAULT NULL,
  phone varchar(255) DEFAULT NULL,
  mail text DEFAULT NULL,
  note text DEFAULT NULL,
  product_jump tinyint(1) DEFAULT '0',
  product_photo tinyint(1) DEFAULT '0',
  product_video tinyint(1) DEFAULT '0',
  product_credit int(11) DEFAULT '0',
  PRIMARY KEY (num),
  KEY num (num)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
