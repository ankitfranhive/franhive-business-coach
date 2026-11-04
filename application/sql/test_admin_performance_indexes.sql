-- Run on MySQL/MariaDB to speed up test admin & reports.
-- If a statement fails with "Duplicate key name", skip it (index already exists).

-- Question bank
-- QUESTION_GROUP: full-column index (no prefix). Works for INT/VARCHAR; skip if your column is TEXT/BLOB and this errors.
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD INDEX `idx_tsq_group` (`QUESTION_GROUP`);
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD INDEX `idx_tsq_type` (`QUESTION_TYPE`);
-- QUESTION_ID is usually PRIMARY KEY already — skip idx_tsq_id if you get duplicate key on PK.

-- Test ↔ question mapping
ALTER TABLE `TEST_QUESTION_MAPPING` ADD INDEX `idx_tqm_test_question` (`TEST_ID`, `QUESTION_ID`);

-- Section index (only after SECTION_ID exists — see block at end of this file or test_sections_schema.sql)

-- Allocations
ALTER TABLE `TEST_USER_ALLOCATION` ADD INDEX `idx_tua_user_test` (`USER_ID`, `TEST_ID`);
ALTER TABLE `TEST_USER_ALLOCATION` ADD INDEX `idx_tua_user_removed` (`USER_ID`, `IS_REMOVED`);

-- Responses (reports, latest attempt, counts)
ALTER TABLE `TEST_USER_RESPONSE` ADD INDEX `idx_tur_user_test` (`USER_ID`, `TEST_ID`);
ALTER TABLE `TEST_USER_RESPONSE` ADD INDEX `idx_tur_user_test_id` (`USER_ID`, `TEST_ID`, `ID`);
ALTER TABLE `TEST_USER_RESPONSE` ADD INDEX `idx_tur_test_id` (`TEST_ID`, `ID`);

-- Test series listing
ALTER TABLE `TEST_SERIES` ADD INDEX `idx_ts_del_id` (`IS_DEL`, `TEST_ID`);

-- Options lookup by question
ALTER TABLE `TEST_SERIES_QUESTION_OPTIONS` ADD INDEX `idx_tsqo_question_sort` (`QUESTION_ID`, `SORT_ORDER`);

-- =============================================================================
-- OPTIONAL: Test sections (run once if you use section-wise tests)
-- Skip any line that errors with "Duplicate column name" or "Duplicate key name".
-- =============================================================================
-- ALTER TABLE `TEST_SERIES` ADD COLUMN `USE_SECTIONS` TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `SECTION_ID` int unsigned NULL DEFAULT NULL;
-- ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `QUESTION_SORT` int unsigned NULL DEFAULT NULL;
-- ALTER TABLE `TEST_QUESTION_MAPPING` ADD INDEX `idx_tqm_test_section` (`TEST_ID`, `SECTION_ID`);
