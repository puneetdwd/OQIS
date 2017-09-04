ALTER TABLE `inspections` ADD `is_deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `automate_settings`;

ALTER TABLE `reference_links` ADD `inspection_id` INT(11) NULL DEFAULT NULL AFTER `product_id`,
 ADD `tool` VARCHAR(50) NULL DEFAULT NULL AFTER `inspection_id`;
 
ALTER TABLE `reference_links` CHANGE `model_suffix` `model_suffix` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL; 

ALTER TABLE `audits` ADD `tool` VARCHAR(50) NULL DEFAULT NULL AFTER `line_id`;

ALTER TABLE `production_plans` ADD `original_lot_size` MEDIUMINT(8) NOT NULL AFTER `lot_size`;
ALTER TABLE `sampling_plans` ADD `original_lot_size` MEDIUMINT(8) NOT NULL AFTER `lot_size`;

ALTER TABLE `users` CHANGE `product_id` `product_id` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `audits` ADD `on_hold` TINYINT(1) NOT NULL DEFAULT '0' AFTER `state`;

ALTER TABLE `production_plans` ADD `is_user_defined` TINYINT(1) NOT NULL DEFAULT '0' AFTER `original_lot_size`;


--
-- Table structure for table `inspection_checkpoint_history`
--

CREATE TABLE IF NOT EXISTS `inspection_checkpoint_history` (
`id` int(11) NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `checkpoint_id` int(11) NOT NULL,
  `change_type` enum('Added','Deleted','Updated') NOT NULL,
  `changed_on` datetime NOT NULL,
  `remark` text,
  `created` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inspection_checkpoint_history`
--
ALTER TABLE `inspection_checkpoint_history`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inspection_checkpoint_history`
--
ALTER TABLE `inspection_checkpoint_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;