ALTER TABLE `socialProfiles` ADD `status` INT NOT NULL DEFAULT '1' ;

DROP TABLE `license`;

ALTER TABLE `mediaSettings` ADD `categoriesLimit` INT NOT NULL AFTER `id`;

UPDATE `mediaSettings` SET `categoriesLimit` = '7';