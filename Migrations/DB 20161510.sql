ALTER TABLE `inspections` ADD `sort_index` SMALLINT(4) NULL DEFAULT NULL AFTER `is_active`;

ALTER TABLE `products` ADD `checked_by` INT(11) NULL DEFAULT NULL AFTER `dir_path`;
ALTER TABLE `products` ADD `approved_by` INT(11) NULL DEFAULT NULL AFTER `checked_by`;