ALTER TABLE `reference_links` ADD `group_code` VARCHAR(20) NULL DEFAULT NULL AFTER `product_id`;


CREATE TABLE IF NOT EXISTS `ref_link_checkpoint_configs` (
`id` int(11) NOT NULL,
  `reference_link` varchar(100) NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `checkpoints_nos` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ref_link_checkpoint_configs`
--
ALTER TABLE `ref_link_checkpoint_configs`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ref_link_checkpoint_configs`
--
ALTER TABLE `ref_link_checkpoint_configs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;



ALTER TABLE `audit_checkpoints`  ADD `org_checkpoint_id` INT(11) NULL DEFAULT NULL  AFTER `id`