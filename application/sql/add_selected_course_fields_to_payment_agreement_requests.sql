-- Run once on MySQL/MariaDB.
-- Stores per-request course assignment done at send-time.

ALTER TABLE `payment_agreement_requests`
ADD COLUMN `selected_course_id` VARCHAR(64) NULL DEFAULT NULL
COMMENT 'Course selected by admin for this specific request'
AFTER `total_inc_gst`,
ADD COLUMN `selected_course_start_date` DATE NULL DEFAULT NULL
COMMENT 'Assigned course start date for this request'
AFTER `selected_course_id`,
ADD COLUMN `selected_course_end_date` DATE NULL DEFAULT NULL
COMMENT 'Assigned course end date for this request'
AFTER `selected_course_start_date`;
