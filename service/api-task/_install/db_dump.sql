CREATE DATABASE IF NOT EXISTS `task_db`;

CREATE TABLE IF NOT EXISTS `task_db`.`products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `created_ts` timestamp DEFAULT CURRENT_TIMESTAMP ,
  `updated_ts` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,

  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  INDEX (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `task_db`.`products` (`name`, `price`) VALUES
  ('pc_1', 799.99),
  ('notebook_1', 579.90),
  ('router', 89.95),
  ('phone', 450.99),
  ('pc_2', 1790.99);