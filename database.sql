-- SQL dump generated using DBML (dbml-lang.org)
-- Database: MySQL
-- Generated at: 2023-07-05T17:58:26.942Z

CREATE TABLE `categories` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(225) UNIQUE NOT NULL,
  `parent_id` int DEFAULT null
);

ALTER TABLE `categories` ADD CONSTRAINT `categories_fk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
