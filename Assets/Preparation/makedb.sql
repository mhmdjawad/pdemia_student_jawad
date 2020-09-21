CREATE DATABASE pdemia_student_projectmgt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use pdemia_student_projectmgt;


DROP TABLE IF EXISTS `account_role`;
DROP TABLE IF EXISTS `account`;

CREATE TABLE `account_role` (`id` int unsigned AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `account_role` ADD `name` varchar(100) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `account_role` ADD `active` varchar(1) default 1;
INSERT INTO account_role(id,name,active) VALUES("1","SuperAdmin","0");
INSERT INTO account_role(id,name) VALUES("2","Admin");
INSERT INTO account_role(id,name) VALUES("3","Moderator");
INSERT INTO account_role(id,name) VALUES("4","User");

-- for accounts and users
CREATE TABLE `account` (`id` int unsigned AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `account` ADD `name` varchar(100) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `account` ADD `email` varchar(100) NOT NULL UNIQUE;
ALTER TABLE `account` ADD `password` varchar(100) NOT NULL;
ALTER TABLE `account` ADD `salt` varchar(100) NOT NULL;
ALTER TABLE `account` ADD `timestamp_create` timestamp DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `account` ADD `active` varchar(1) default '1';
ALTER TABLE `account` ADD `active_enabled` varchar(1) default '1';
ALTER TABLE `account` ADD `active_verified` varchar(1) default '0';
ALTER TABLE `account` ADD `account_role_fk` int unsigned default 4;
-- ALTER TABLE `account` ADD constraint foreign key (`account_role_fk`) references `account_role`.`id` ON UPDATE CASCADE ON DELETE CASCADE;
INSERT INTO account(name,email,password) VALUES("test","test","test");





