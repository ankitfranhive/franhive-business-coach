-- Optional manual migration; schema is also ensured in PHP (test_sections_helper).
CREATE TABLE IF NOT EXISTS `TEST_SECTIONS` (
  `SECTION_ID` int unsigned NOT NULL AUTO_INCREMENT,
  `TEST_ID` int unsigned NOT NULL,
  `SECTION_NAME` varchar(255) NOT NULL,
  `SORT_ORDER` int unsigned NOT NULL DEFAULT 1,
  `CREATED_ON` datetime DEFAULT NULL,
  PRIMARY KEY (`SECTION_ID`),
  KEY `idx_test_sections_test` (`TEST_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Skip a statement if the column or index already exists.
ALTER TABLE `TEST_SERIES` ADD COLUMN `USE_SECTIONS` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `SECTION_ID` int unsigned NULL DEFAULT NULL;
ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `QUESTION_SORT` int unsigned NULL DEFAULT NULL;
ALTER TABLE `TEST_QUESTION_MAPPING` ADD INDEX `idx_tqm_test_section` (`TEST_ID`, `SECTION_ID`);
