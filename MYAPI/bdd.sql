CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;
-- ----------------------------------------------------- -- Table `mydb`.`user` -- ----------------------------------------------------- 
CREATE TABLE IF NOT EXISTS `mydb`.`user` 
(   `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(45) NOT NULL,
    `email` VARCHAR(45) NOT NULL, 
    `pseudo` VARCHAR(45) NULL,
    `password` VARCHAR(45) NOT NULL,
    `created_at` DATETIME NOT NULL, 
    PRIMARY KEY (`id`),
     UNIQUE INDEX `email_UNIQUE` (`email` ASC), 
     UNIQUE INDEX `username_UNIQUE` (`username` ASC)) ENGINE = InnoDB;
-- ----------------------------------------------------- -- Table `mydb`.`video` -- ----------------------------------------------------- 
CREATE TABLE IF NOT EXISTS `mydb`.`video`
( `id` INT NOT NULL AUTO_INCREMENT,
 `name` VARCHAR(45) NOT NULL,
 `duration` INT NULL,
  `user_id` INT NOT NULL, 
 `source` VARCHAR(45) NOT NULL, 
 `created_at` DATETIME NOT NULL,
`view` INT NULL,
`enabled` TINYINT(1) NULL,
    PRIMARY KEY (`id`),
     INDEX `fk_video_user_idx` (`user_id` ASC), 
     CONSTRAINT `fk_video_user` FOREIGN KEY (`user_id`) REFERENCES `mydb`.`user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE = InnoDB;
-- ----------------------------------------------------- -- Table `mydb`.`video_format` -- ----------------------------------------------------- 
CREATE TABLE IF NOT EXISTS `mydb`.`video_format`
 ( `id` INT NOT NULL AUTO_INCREMENT,
 `code` VARCHAR(45) NOT NULL,
  `uri` VARCHAR(45) NOT NULL,
   `video_id` INT NOT NULL,
    PRIMARY KEY (`id`),
     INDEX `fk_video_format_video1_idx` (`video_id` ASC),
     CONSTRAINT `fk_video_format_video1` FOREIGN KEY (`video_id`) REFERENCES `mydb`.`video` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE = InnoDB;
-- ----------------------------------------------------- -- Table `mydb`.`token` -- ----------------------------------------------------- 
CREATE TABLE IF NOT EXISTS `mydb`.`token`
 ( `id` INT NOT NULL AUTO_INCREMENT,
 `code` VARCHAR(45) NOT NULL,
  `expired_at` DATETIME NOT NULL,
   `user_id` INT NOT NULL, 
  PRIMARY KEY (`id`), INDEX `fk_token_user1_idx` (`user_id` ASC), 
  UNIQUE INDEX `code_UNIQUE` (`code` ASC),
   CONSTRAINT `fk_token_user1` FOREIGN KEY (`user_id`) REFERENCES `mydb`.`user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE = InnoDB; 
-- ----------------------------------------------------- -- Table `mydb`.`comment` -- ----------------------------------------------------- 
CREATE TABLE IF NOT EXISTS `mydb`.`comment`
 ( `id` INT NOT NULL AUTO_INCREMENT,
 `body` LONGTEXT NULL,
 `user_id` INT NOT NULL,
  `video_id` INT NOT NULL,
  PRIMARY KEY (`id`),
   INDEX `fk_comment_user1_idx` (`user_id` ASC),
    INDEX `fk_comment_video1_idx` (`video_id` ASC),
    CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `mydb`.`user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
    CONSTRAINT `fk_comment_video1` FOREIGN KEY (`video_id`) REFERENCES `mydb`.`video` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE = InnoDB;
     SET SQL_MODE=@OLD_SQL_MODE; SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS; SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

ALTER TABLE video ADD encoded TINYINT(1) NOT NULL;