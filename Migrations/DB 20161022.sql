DROP TABLE inspection_checkpoint_history;

--
-- Table structure for table `inspection_checkpoint_history`
--

CREATE TABLE IF NOT EXISTS `inspection_checkpoint_history` (
`id` int(11) NOT NULL,
  `version` smallint(4) NOT NULL,
  `type` enum('Before','After') NOT NULL,
  `gmes_code` varchar(50) NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `checkpoint_no` smallint(4) NOT NULL,
  `insp_item` varchar(100) NOT NULL,
  `insp_item2` text CHARACTER SET utf8,
  `insp_item3` text CHARACTER SET utf8 NOT NULL,
  `insp_item4` text,
  `spec` text CHARACTER SET utf8 NOT NULL,
  `lsl` varchar(10) DEFAULT NULL,
  `usl` varchar(10) DEFAULT NULL,
  `tgt` varchar(10) DEFAULT NULL,
  `unit` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `guideline_image` varchar(150) NOT NULL,
  `automate_result_row` varchar(4) NOT NULL,
  `automate_result_col` varchar(4) NOT NULL,
  `change_type` enum('Added','Deleted','Updated') NOT NULL,
  `changed_on` datetime NOT NULL,
  `remark` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT