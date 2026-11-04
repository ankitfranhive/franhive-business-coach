-- Run on MySQL/MariaDB to speed up test admin & reports. Safe to re-run (duplicate index names will error — skip those statements).

-- Question bank
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD INDEX `idx_tsq_group` (`QUESTION_GROUP`(64));
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD INDEX `idx_tsq_type` (`QUESTION_TYPE`);
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD INDEX `idx_tsq_id_desc` (`QUESTION_ID`);

-- Test ↔ question mapping
ALTER TABLE `TEST_QUESTION_MAPPING` ADD INDEX `idx_tqm_test_question` (`TEST_ID`, `QUESTION_ID`);
ALTER TABLE `TEST_QUESTION_MAPPING` ADD INDEX `idx_tqm_test_section` (`TEST_ID`, `SECTION_ID`);

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
