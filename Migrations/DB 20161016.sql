
DELETE FROM `sampling_plans` WHERE `line` = '';

--
-- Table structure for table `audits_completed`
--

CREATE TABLE IF NOT EXISTS `audits_completed` (
`id` int(11) NOT NULL,
  `audit_id` int(11) NOT NULL,
  `audit_date` date NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `line` varchar(50) NOT NULL,
  `model_suffix` varchar(50) NOT NULL,
  `workorder` varchar(20) NOT NULL,
  `serial_no` varchar(50) NOT NULL,
  `checkpoint_count` smallint(4) NOT NULL,
  `ok_count` smallint(4) NOT NULL,
  `ng_count` smallint(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16384 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits_completed`
--
ALTER TABLE `audits_completed`
 ADD PRIMARY KEY (`id`), ADD KEY `audit_date` (`audit_date`,`inspection_id`,`line`,`model_suffix`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits_completed`
--
ALTER TABLE `audits_completed`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `audits_completed`(`audit_id`, `audit_date`, `inspection_id`, `line`, `model_suffix`, `workorder`, `serial_no`, `checkpoint_count`, `ok_count`, `ng_count`, `created`)
SELECT a.id, a.audit_date, a.inspection_id, l.name as line, a.model_suffix, a.workorder, a.serial_no, COUNT(ac.id) as checkpoint_count,
SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
FROM audits a
INNER JOIN product_lines l
ON a.line_id = l.id
LEFT JOIN audit_checkpoints ac
ON a.id = ac.audit_id
WHERE state = 'completed'
GROUP BY a.id;