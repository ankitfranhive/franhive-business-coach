-- Run once. Adds payment arrangement customization and stored submission JSON.

ALTER TABLE `payment_agreement_requests`
ADD COLUMN `payment_arrangement_intro_override` LONGTEXT NULL DEFAULT NULL
COMMENT 'Optional HTML replacing default intro from form settings for this link'
AFTER `total_inc_gst`;

ALTER TABLE `ENROLL_AGREEMENT_DATA`
ADD COLUMN `payment_arrangement_json` LONGTEXT NULL DEFAULT NULL
COMMENT 'JSON: payment arrangement type, dates, plan fields';
