ALTER TABLE `removal` ADD `is_international` VARCHAR(16) NOT NULL DEFAULT 'no' AFTER `notes`;
ALTER TABLE `removal` ADD `from_country` VARCHAR(255) NOT NULL AFTER `is_international`, ADD `to_country` VARCHAR(255) NOT NULL AFTER `from_country`;
ALTER TABLE `removal` ADD `from_country_id` INT NOT NULL AFTER `to_country`, ADD `to_country_id` INT NOT NULL AFTER `from_country_id`;