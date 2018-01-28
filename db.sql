SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Schema triphatch
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `triphatch` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ;
USE `triphatch` ;

-- -----------------------------------------------------
-- Table `triphatch`.`images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(50) NOT NULL,
  `description` VARCHAR(100) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `display_name` VARCHAR(30) NULL,
  `is_admin` TINYINT(1) NOT NULL,
  `image_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_users_1_idx` (`image_id` ASC),
  CONSTRAINT `fk_users_1`
    FOREIGN KEY (`image_id`)
    REFERENCES `triphatch`.`images` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`trips`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`trips` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `public_url` VARCHAR(32) NULL,
  `start_date` DATETIME NULL,
  `end_date` DATETIME NULL,
  `image_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_trips_1_idx` (`image_id` ASC),
  UNIQUE INDEX `public_url_UNIQUE` (`public_url` ASC),
  CONSTRAINT `fk_trips_1`
    FOREIGN KEY (`image_id`)
    REFERENCES `triphatch`.`images` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`days` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `order` INT UNSIGNED NOT NULL,
  `image_id` INT UNSIGNED NOT NULL,
  `trip_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_days_1_idx` (`image_id` ASC),
  INDEX `fk_days_2_idx` (`trip_id` ASC),
  CONSTRAINT `fk_days_1`
    FOREIGN KEY (`image_id`)
    REFERENCES `triphatch`.`images` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_days_2`
    FOREIGN KEY (`trip_id`)
    REFERENCES `triphatch`.`trips` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`action_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`action_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `icon_class` VARCHAR(50) NOT NULL,
  `color_class` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`actions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`actions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `content` VARCHAR(1000) NULL,
  `order` INT UNSIGNED NOT NULL,
  `day_id` INT UNSIGNED NOT NULL,
  `action_type_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_actions_1_idx` (`day_id` ASC),
  INDEX `fk_actions_2_idx` (`action_type_id` ASC),
  CONSTRAINT `fk_actions_1`
    FOREIGN KEY (`day_id`)
    REFERENCES `triphatch`.`days` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_actions_2`
    FOREIGN KEY (`action_type_id`)
    REFERENCES `triphatch`.`action_types` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`user_trip_xref`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`user_trip_xref` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `trip_id` INT UNSIGNED NOT NULL,
  `role` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_user_trip_xref_1_idx` (`user_id` ASC),
  INDEX `fk_user_trip_xref_2_idx` (`trip_id` ASC),
  CONSTRAINT `fk_user_trip_xref_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `triphatch`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_trip_xref_2`
    FOREIGN KEY (`trip_id`)
    REFERENCES `triphatch`.`trips` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`trip_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`trip_comments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(500) NOT NULL,
  `user_trip_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_trip_comments_1_idx` (`user_trip_id` ASC),
  CONSTRAINT `fk_trip_comments_1`
    FOREIGN KEY (`user_trip_id`)
    REFERENCES `triphatch`.`user_trip_xref` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`invites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`invites` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `message` VARCHAR(255) NULL,
  `token` VARCHAR(32) NOT NULL,
  `trip_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_invites_1_idx` (`trip_id` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  CONSTRAINT `fk_invites_1`
  FOREIGN KEY (`trip_id`)
  REFERENCES `triphatch`.`trips` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `triphatch`.`trip_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `triphatch`.`trip_files` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `path` VARCHAR(50) NOT NULL,
  `trip_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `path_UNIQUE` (`path` ASC),
  INDEX `fk_trip_files_1_idx` (`trip_id` ASC),
  CONSTRAINT `fk_trip_files_1`
  FOREIGN KEY (`trip_id`)
  REFERENCES `triphatch`.`trips` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
