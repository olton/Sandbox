-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema metro_sandbox
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema metro_sandbox
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `metro_sandbox` DEFAULT CHARACTER SET utf8 ;
USE `metro_sandbox` ;

-- -----------------------------------------------------
-- Table `metro_sandbox`.`code`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `metro_sandbox`.`code` ;

CREATE TABLE IF NOT EXISTS `metro_sandbox`.`code` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  `user` BIGINT(20) UNSIGNED NOT NULL,
  `html` TEXT NULL DEFAULT NULL,
  `css` TEXT NULL DEFAULT NULL,
  `js` TEXT NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `template` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `hash` VARCHAR(20) NULL DEFAULT NULL,
  `html_head` TEXT NULL DEFAULT NULL,
  `html_processor` ENUM('none', 'halm', 'markdown', 'slim', 'pug') NULL DEFAULT 'none',
  `html_classes` VARCHAR(255) NULL DEFAULT NULL,
  `css_processor` ENUM('none', 'less', 'scss', 'sass', 'stylus') NULL DEFAULT 'none',
  `desc` VARCHAR(255) NULL DEFAULT NULL,
  `tags` VARCHAR(255) NULL DEFAULT NULL,
  `code_type` ENUM('code', 'template') NULL DEFAULT 'code',
  `js_processor` ENUM('none', 'Babel', 'TypeScript', 'CoffeeScript', 'LiveScript') NULL DEFAULT 'none',
  `css_external` TEXT NULL DEFAULT NULL,
  `js_external` TEXT NULL DEFAULT NULL,
  `body_classes` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `i_code_user` (`user` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 80
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `metro_sandbox`.`libs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `metro_sandbox`.`libs` ;

CREATE TABLE IF NOT EXISTS `metro_sandbox`.`libs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `css` VARCHAR(255) NULL DEFAULT NULL,
  `js` VARCHAR(255) NULL DEFAULT NULL,
  `var` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `metro_sandbox`.`temp_files`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `metro_sandbox`.`temp_files` ;

CREATE TABLE IF NOT EXISTS `metro_sandbox`.`temp_files` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 499
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `metro_sandbox`.`templates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `metro_sandbox`.`templates` ;

CREATE TABLE IF NOT EXISTS `metro_sandbox`.`templates` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NULL DEFAULT NULL,
  `css` TEXT NULL DEFAULT NULL,
  `html` TEXT NULL DEFAULT NULL,
  `js` TEXT NULL DEFAULT NULL,
  `libs` TEXT NULL DEFAULT NULL,
  `icon` VARCHAR(255) NULL DEFAULT NULL,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `ui_name` (`name` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `metro_sandbox`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `metro_sandbox`.`user` ;

CREATE TABLE IF NOT EXISTS `metro_sandbox`.`user` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NULL DEFAULT NULL,
  `email` VARCHAR(50) NULL DEFAULT NULL,
  `password` VARCHAR(50) NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` DATETIME NULL DEFAULT NULL,
  `oauth` ENUM('none', 'github', 'facebook', 'twitter') NOT NULL DEFAULT 'none',
  `access_token` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
