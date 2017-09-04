--
-- Table structure for table `approved_checked`
--

CREATE TABLE IF NOT EXISTS `approved_checked` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_datetime` datetime DEFAULT NULL,
  `checked_by` int(11) DEFAULT NULL,
  `checked_datetime` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approved_checked`
--
ALTER TABLE `approved_checked`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approved_checked`
--
ALTER TABLE `approved_checked`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;