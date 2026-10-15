-- Sync demo (franhive) schema toward eyd
-- Generated: 2026-10-07 15:58:09
SET NAMES utf8mb4;
SET SESSION sql_mode = '';
USE `franhive`;

-- Tables present on eyd but missing on demo (CREATE TABLE from eyd):
CREATE TABLE IF NOT EXISTS `CALENDAR_EVENTS` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) NOT NULL,
  `START_DATE` date NOT NULL,
  `END_DATE` date DEFAULT NULL,
  `CATEGORY` enum('INDIA_FESTIVAL','GLOBAL_HOLIDAY','AU_HOLIDAY') NOT NULL,
  `SOURCE` enum('MANUAL','NAGER') NOT NULL DEFAULT 'MANUAL',
  `IS_ACTIVE` tinyint(1) NOT NULL DEFAULT '1',
  `CREATED_AT` datetime DEFAULT CURRENT_TIMESTAMP,
  `UPDATED_AT` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `idx_category_start` (`CATEGORY`,`START_DATE`),
  KEY `idx_active` (`IS_ACTIVE`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `LANDINGPAGE_CONTACTS_DATA` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `platform` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_id` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `LEAD_EMAIL_LOGS` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `LEAD_ID` int NOT NULL,
  `TEMPLATE_ID` int DEFAULT NULL,
  `TO_EMAIL` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TO_NAME` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SUBJECT` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BODY` longtext COLLATE utf8mb4_unicode_ci,
  `STATUS` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `SENT_BY` int DEFAULT NULL,
  `SEND_DATE` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_lead_id` (`LEAD_ID`),
  KEY `idx_send_date` (`SEND_DATE`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `TEST_SERIES_QUESTION_OPTIONS` (
  `OPTION_ID` int NOT NULL AUTO_INCREMENT,
  `QUESTION_ID` int NOT NULL,
  `OPTION_TEXT` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `IS_CORRECT` tinyint(1) DEFAULT '0',
  `OPTION_MARKS` decimal(5,2) DEFAULT '0.00',
  `SORT_ORDER` int DEFAULT '1',
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OPTION_ID`),
  KEY `idx_question_id` (`QUESTION_ID`),
  CONSTRAINT `fk_qo_question` FOREIGN KEY (`QUESTION_ID`) REFERENCES `TEST_SERIES_QUESTIONS` (`QUESTION_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ef_form_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ef_form_type_fields` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `form_type_id` int unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `label` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'text',
  `options_json` text,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `placeholder` varchar(255) DEFAULT NULL,
  `help_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ft` (`form_type_id`),
  CONSTRAINT `ef_form_type_fields_ibfk_1` FOREIGN KEY (`form_type_id`) REFERENCES `ef_form_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1890 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ef_templates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `form_type_id` int unsigned NOT NULL,
  `slug` varchar(120) NOT NULL,
  `title` varchar(255) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `subheading` varchar(255) DEFAULT NULL,
  `body_html` mediumtext,
  `overrides_json` mediumtext,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_ft` (`form_type_id`),
  CONSTRAINT `ef_templates_ibfk_1` FOREIGN KEY (`form_type_id`) REFERENCES `ef_form_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ef_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int unsigned NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `prefill_json` mediumtext,
  `token` char(64) NOT NULL,
  `status` enum('sent','draft','submitted','expired') NOT NULL DEFAULT 'sent',
  `expires_at` datetime DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `static_form_slug` varchar(64) DEFAULT NULL COMMENT 'When set, form fields come from config instead of template',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_tpl` (`template_id`),
  CONSTRAINT `ef_requests_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `ef_templates` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=412 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ef_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `request_id` bigint unsigned NOT NULL,
  `template_id` int unsigned NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `http_referer` varchar(255) DEFAULT NULL,
  `accept_language` varchar(255) DEFAULT NULL,
  `client_timezone` varchar(80) DEFAULT NULL,
  `client_time_iso` varchar(40) DEFAULT NULL,
  `screen_resolution` varchar(30) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `signature_sha256` char(64) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `pdf_sha256` char(64) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `static_form_slug` varchar(64) DEFAULT NULL COMMENT 'When set, submission is for a static form (e.g. iict_enrolment_agreement)',
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `idx_req` (`request_id`),
  CONSTRAINT `ef_submissions_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `ef_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ef_submissions_ibfk_2` FOREIGN KEY (`template_id`) REFERENCES `ef_templates` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ef_submission_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submission_id` bigint unsigned NOT NULL,
  `field_name` varchar(80) NOT NULL,
  `field_label` text NOT NULL,
  `value_text` mediumtext,
  PRIMARY KEY (`id`),
  KEY `idx_sub` (`submission_id`),
  CONSTRAINT `ef_submission_values_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `ef_submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2796 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `franhive_data` (
  `LOG_ID` int DEFAULT NULL,
  `SUB_MODULE_NAME` mediumtext COLLATE utf8mb4_unicode_ci,
  `NODE_ID` mediumtext COLLATE utf8mb4_unicode_ci,
  `USER_ID` int DEFAULT NULL,
  `ACTIVITY_DATE` mediumtext COLLATE utf8mb4_unicode_ci,
  `ACTIVITY` mediumtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `module_fh` (
  `MODULE_ID` int DEFAULT NULL,
  `MODULE_NAME` mediumtext COLLATE utf8mb4_unicode_ci,
  `CREATED_ON` mediumtext COLLATE utf8mb4_unicode_ci,
  `MODIFIED_ON` mediumtext COLLATE utf8mb4_unicode_ci,
  `CREATED_BY` mediumtext COLLATE utf8mb4_unicode_ci,
  `MODIFIED_BY` mediumtext COLLATE utf8mb4_unicode_ci,
  `RECORD_STATUS` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `landing_page_id` int DEFAULT NULL,
  `session_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aud',
  `total_cents` int NOT NULL,
  `deposit_cents` int NOT NULL,
  `remaining_cents` int NOT NULL,
  `plan_mode` enum('auto','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto',
  `balance_due_date` date DEFAULT NULL,
  `status` enum('deposit_paid','balance_scheduled','balance_paid','invoice_sent','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deposit_paid',
  `promotion_code_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_due` (`balance_due_date`,`status`),
  KEY `idx_orders_customer` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_idx` (`email`),
  KEY `token_idx` (`token_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payment_agreement_form_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `payment_agreement_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `total_inc_gst` decimal(12,2) DEFAULT NULL COMMENT 'Total inc GST set when sending the form link',
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `deposit_paid_on` date DEFAULT NULL,
  `selected_course_id` varchar(64) DEFAULT NULL COMMENT 'Course selected by admin for this specific request',
  `selected_course_start_date` date DEFAULT NULL COMMENT 'Assigned course start date for this request',
  `selected_course_end_date` date DEFAULT NULL COMMENT 'Assigned course end date for this request',
  `payment_arrangement_intro_override` longtext COMMENT 'Optional HTML replacing default intro from form settings for this link',
  `token` varchar(255) NOT NULL,
  `token_expires_at` datetime NOT NULL,
  `status` enum('sent','opened','submitted','expired') NOT NULL DEFAULT 'sent',
  `agreement_id` int DEFAULT NULL,
  `sent_by` int DEFAULT NULL,
  `sent_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `opened_at` datetime DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `signature_first_name` varchar(100) DEFAULT NULL,
  `signature_last_name` varchar(100) DEFAULT NULL,
  `thank_you_email_template_id` int DEFAULT NULL,
  `thank_you_message` mediumtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_payment_agreement_token` (`token`),
  KEY `fk_payment_request_agreement` (`agreement_id`),
  CONSTRAINT `fk_payment_request_agreement` FOREIGN KEY (`agreement_id`) REFERENCES `ENROLL_AGREEMENT_DATA` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `permission_fh` (
  `PERMISSION_ID` int DEFAULT NULL,
  `CREATED_BY` int DEFAULT NULL,
  `MODIFIED_BY` int DEFAULT NULL,
  `DELETED_BY` mediumtext COLLATE utf8mb4_unicode_ci,
  `RECORD_STATUS` int DEFAULT NULL,
  `NODE_ID` mediumtext COLLATE utf8mb4_unicode_ci,
  `CREATED_ON` mediumtext COLLATE utf8mb4_unicode_ci,
  `MODIFIED_ON` mediumtext COLLATE utf8mb4_unicode_ci,
  `LAST_VIEWED_ON` mediumtext COLLATE utf8mb4_unicode_ci,
  `PERMISSION_NAME` mediumtext COLLATE utf8mb4_unicode_ci,
  `MODULE_ID` mediumtext COLLATE utf8mb4_unicode_ci,
  `USER_TYPE` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Missing columns on demo (add from eyd):
ALTER TABLE `CAMPAIGN` ADD COLUMN `WORKFLOW_STATUS` varchar(32) COLLATE utf8mb3_general_ci NULL DEFAULT 'draft' AFTER `CAMPAIGN_TYPE`;
ALTER TABLE `CAMPAIGN` ADD COLUMN `TOTAL_RECIPIENTS` int NOT NULL DEFAULT 0 AFTER `WORKFLOW_STATUS`;
ALTER TABLE `CAMPAIGN` ADD COLUMN `SENT_COUNT` int NOT NULL DEFAULT 0 AFTER `TOTAL_RECIPIENTS`;
ALTER TABLE `CAMPAIGN` ADD COLUMN `LAST_ACTIVITY_AT` datetime NULL AFTER `SENT_COUNT`;
ALTER TABLE `CAMPAIGN` ADD COLUMN `TIMEZONE` varchar(64) COLLATE utf8mb3_general_ci NULL DEFAULT 'Australia/Melbourne' AFTER `LAST_ACTIVITY_AT`;
ALTER TABLE `CAMPAIGN_TEMPLATES_MAPPING` ADD COLUMN `SEND_STATUS` varchar(32) COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending' AFTER `STATUS`;
ALTER TABLE `CAMPAIGN_TEMPLATES_MAPPING` ADD COLUMN `SENT_AT` datetime NULL AFTER `SEND_STATUS`;
ALTER TABLE `CAMPAIGN_TEMPLATES_MAPPING` ADD COLUMN `SEND_AT` datetime NULL AFTER `SENT_AT`;
ALTER TABLE `CAMPAIGN_TEMPLATES_MAPPING` ADD COLUMN `TIMEZONE` varchar(64) COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Australia/Melbourne' AFTER `SEND_AT`;
ALTER TABLE `CAMPAIGN_TEMPLATE_USER_MAPPING` ADD COLUMN `CONTACT_SOURCE` varchar(20) COLLATE utf8mb4_unicode_ci NULL DEFAULT 'entity' AFTER `STATUS`;
ALTER TABLE `COURSES` ADD COLUMN `REGISTRARTION_LINK` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `THUMNAIL_IMAGE`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `request_id` int NULL AFTER `id`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `client_id` int NULL AFTER `request_id`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `business_name` varchar(255) COLLATE utf8mb4_0900_ai_ci NULL AFTER `full_name`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `postal_address` varchar(255) COLLATE utf8mb4_0900_ai_ci NULL AFTER `business_name`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `city_suburb` varchar(150) COLLATE utf8mb4_0900_ai_ci NULL AFTER `postal_address`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `postcode` varchar(30) COLLATE utf8mb4_0900_ai_ci NULL AFTER `city_suburb`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `country` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL AFTER `postcode`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `contact_number_mobile` varchar(30) COLLATE utf8mb4_0900_ai_ci NULL AFTER `country`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `contact_number_work` varchar(30) COLLATE utf8mb4_0900_ai_ci NULL AFTER `contact_number_mobile`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `emergency_contact_number` varchar(30) COLLATE utf8mb4_0900_ai_ci NULL AFTER `emergency_contact_name`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `course_name` text COLLATE utf8mb4_0900_ai_ci NULL AFTER `program_id`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `training_date_text` text COLLATE utf8mb4_0900_ai_ci NULL AFTER `course_name`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `location_name` varchar(255) COLLATE utf8mb4_0900_ai_ci NULL AFTER `training_date_text`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `notes` text COLLATE utf8mb4_0900_ai_ci NULL AFTER `location_name`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `total_inc_gst` decimal(10,2) NULL AFTER `notes`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `deposit_paid_on` date NULL AFTER `deposit_paid_via`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `balance_amount` decimal(10,2) NULL AFTER `deposit_paid_on`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `holds_certification` tinyint(1) NOT NULL DEFAULT 0 AFTER `due_date`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `certification_details` text COLLATE utf8mb4_0900_ai_ci NULL AFTER `holds_certification`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `certification_attachment` varchar(255) COLLATE utf8mb4_0900_ai_ci NULL AFTER `certification_details`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `agree_terms_4` tinyint(1) NOT NULL DEFAULT 0 AFTER `agree_terms_3`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `agree_terms_5` tinyint(1) NOT NULL DEFAULT 0 AFTER `agree_terms_4`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `agree_terms_6` tinyint(1) NOT NULL DEFAULT 0 AFTER `agree_terms_5`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `stripe_customer_id` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL AFTER `payment_option`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `stripe_payment_method_id` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL AFTER `stripe_customer_id`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `card_brand` varchar(50) COLLATE utf8mb4_0900_ai_ci NULL AFTER `stripe_payment_method_id`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `card_last4` varchar(10) COLLATE utf8mb4_0900_ai_ci NULL AFTER `card_brand`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `card_expiry_month` varchar(10) COLLATE utf8mb4_0900_ai_ci NULL AFTER `card_last4`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `card_expiry_year` varchar(10) COLLATE utf8mb4_0900_ai_ci NULL AFTER `card_expiry_month`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `signature_image` longtext COLLATE utf8mb4_0900_ai_ci NULL AFTER `created_at`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `other_course_name` varchar(255) COLLATE utf8mb4_0900_ai_ci NULL AFTER `signature_image`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `ip_address` varchar(45) COLLATE utf8mb4_0900_ai_ci NULL AFTER `other_course_name`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `user_agent` text COLLATE utf8mb4_0900_ai_ci NULL AFTER `ip_address`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `client_timezone` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL AFTER `user_agent`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `client_time_iso` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL AFTER `client_timezone`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `screen_resolution` varchar(50) COLLATE utf8mb4_0900_ai_ci NULL AFTER `client_time_iso`;
ALTER TABLE `ENROLL_AGREEMENT_DATA` ADD COLUMN `payment_arrangement_json` longtext COLLATE utf8mb4_0900_ai_ci NULL AFTER `screen_resolution`;
ALTER TABLE `ENTITY` ADD COLUMN `LEAD_SENDING_ORDER` int NOT NULL DEFAULT 0 AFTER `ADDRESS2`;
ALTER TABLE `ENTITY` ADD COLUMN `CLIENT_SENDING_ORDER` int NOT NULL DEFAULT 0 AFTER `LEAD_SENDING_ORDER`;
ALTER TABLE `LANDING_PAGES` ADD COLUMN `PAYMENT_TEMPLATE` int NULL AFTER `REGISTERED_TEMPLATE`;
ALTER TABLE `LANDING_PAGES` ADD COLUMN `TESTIMONIALS_IMAGES` varchar(500) COLLATE utf8mb4_unicode_ci NULL AFTER `BANNER_IMAGE_WIDTH`;
ALTER TABLE `LANDING_PAGES` ADD COLUMN `PRICE_FIRST_TRAINING` decimal(10,2) NULL AFTER `CURRENCY`;
ALTER TABLE `LANDING_PAGES` ADD COLUMN `PRICE_SECOND_TRAINING` decimal(10,2) NULL AFTER `PRICE_FIRST_TRAINING`;
ALTER TABLE `LANDING_PAGES` ADD COLUMN `EMAIL_TEMPLATE` int NULL AFTER `PRICE_DEPOSIT`;
ALTER TABLE `LESSONS` ADD COLUMN `THUMBNAIL_IMAGE` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `OBJECTIVE`;
ALTER TABLE `LESSONS` ADD COLUMN `LESSON_VIDEO_LINK` varchar(255) COLLATE utf8mb4_unicode_ci NULL AFTER `THUMBNAIL_IMAGE`;
ALTER TABLE `LESSONS` ADD COLUMN `LESSON_FOR_COURSE` varchar(100) COLLATE utf8mb4_unicode_ci NULL AFTER `LESSON_VIDEO_LINK`;
ALTER TABLE `TEMPLATES` ADD COLUMN `MERGE_TAGS` text COLLATE utf8mb4_unicode_ci NULL AFTER `TEMPLATE_SIGN`;
ALTER TABLE `TEST_QUESTION_MAPPING` ADD COLUMN `MAP_STATUS` tinyint NOT NULL DEFAULT 0 AFTER `QUESTION_ID`;
ALTER TABLE `TEST_SERIES` ADD COLUMN `TEST_TYPE` int NULL AFTER `TOTAL_QUESTIONS`;
ALTER TABLE `TEST_SERIES` ADD COLUMN `TEST_INSTRUCTIONS` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `TEST_TYPE`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `option_1` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `MODIFIED_ON`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `is_correct_1` tinyint(1) NOT NULL DEFAULT 0 AFTER `option_1`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `option_2` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `is_correct_1`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `is_correct_2` tinyint(1) NOT NULL DEFAULT 0 AFTER `option_2`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `option_3` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `is_correct_2`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `is_correct_3` tinyint(1) NOT NULL DEFAULT 0 AFTER `option_3`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `option_4` mediumtext COLLATE utf8mb4_unicode_ci NULL AFTER `is_correct_3`;
ALTER TABLE `TEST_SERIES_QUESTIONS` ADD COLUMN `is_correct_4` tinyint(1) NOT NULL DEFAULT 0 AFTER `option_4`;

-- Columns that differ in type/default (modify demo to match eyd):
-- Review carefully before running on production demo.
ALTER TABLE `ACTIVITY_LOGS` MODIFY COLUMN `ACTIVITY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `AGREEMENT_DETAILS` MODIFY COLUMN `ENTITY_DATA_FROM_COMPANY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `AGREEMENT_DETAILS` MODIFY COLUMN `ENTITY_DATA_FROM_LEAD` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `AGREEMENT_DETAILS_REMARKS` MODIFY COLUMN `REMARKS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `CAMPAIGN_EMAIL_LOGS` MODIFY COLUMN `BODY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `CAMPAIGN_EMAIL_LOGS` MODIFY COLUMN `ATTACHMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `COURSES` MODIFY COLUMN `OBJECTIVE` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `COURSES` MODIFY COLUMN `THUMNAIL_IMAGE` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `DELETED_LOGS` MODIFY COLUMN `DATA_JSON` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `email_address` varchar(150) COLLATE utf8mb4_0900_ai_ci NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `deposit_amount` decimal(10,2) NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `deposit_paid_via` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `agree_terms_1` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `agree_terms_2` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `agree_terms_3` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `payment_option` varchar(100) COLLATE utf8mb4_0900_ai_ci NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `name_on_card` varchar(150) COLLATE utf8mb4_0900_ai_ci NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `signature_date` datetime NULL;
ALTER TABLE `ENROLL_AGREEMENT_DATA` MODIFY COLUMN `created_at` timestamp NULL;
ALTER TABLE `ENTITY` MODIFY COLUMN `COURSE_ID` text COLLATE utf8mb3_general_ci NULL;
ALTER TABLE `FD_AGREEMENTS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_AGREEMENTS` MODIFY COLUMN `STREET_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CALLS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT1` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT2` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT3` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT4` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT5` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT6` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CONTRACT_SIGNING` MODIFY COLUMN `UPLOADED_DOCUMENT7` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CUSTOMER_COMPLAINTS` MODIFY COLUMN `SUMMARY_OF_INCIDENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CUSTOMER_COMPLAINTS` MODIFY COLUMN `ACTION_TAKEN` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CUSTOMER_COMPLAINTS` MODIFY COLUMN `DOCUMENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_CUSTOMER_COMPLAINTS` MODIFY COLUMN `STREET_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_DEFAULT_TERMINATION` MODIFY COLUMN `DOCUMENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_DEFAULT_TERMINATION` MODIFY COLUMN `ADDRESS_1` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_DEFAULT_TERMINATION` MODIFY COLUMN `ADDRESS_2` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_DEFAULT_TERMINATION` MODIFY COLUMN `CA_ADDRESS_1` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_DEFAULT_TERMINATION` MODIFY COLUMN `CA_ADDRESS_2` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_DOCUMENTS` MODIFY COLUMN `FILE_NAME` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_EVENT` MODIFY COLUMN `SUMMARY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_GUARANTOR` MODIFY COLUMN `STREET_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_INSURANCE` MODIFY COLUMN `OTHER_1_COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_INSURANCE` MODIFY COLUMN `OTHER_2_COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_INSURANCE` MODIFY COLUMN `DOCUMENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_INSURANCE` MODIFY COLUMN `ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_INSURANCE` MODIFY COLUMN `AGENCY_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_LEGAL_VIOLATION` MODIFY COLUMN `SUMMARY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_LEGAL_VIOLATION` MODIFY COLUMN `ACTION_TAKEN` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_LENDERS` MODIFY COLUMN `ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_LENDERS` MODIFY COLUMN `DOCUMENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_MARKETING` MODIFY COLUMN `ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_MARKETING` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_MYSTERY_REVIEW` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_MYSTERY_REVIEW` MODIFY COLUMN `P_COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_MYSTERY_REVIEW` MODIFY COLUMN `S_COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_MYSTERY_REVIEW` MODIFY COLUMN `A_COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_REAL_ESTATE` MODIFY COLUMN `OPTION_TERMYEARS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_REAL_ESTATE` MODIFY COLUMN `GENERAL_CONTRACTOR_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_REAL_ESTATE` MODIFY COLUMN `L_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_REAL_ESTATE` MODIFY COLUMN `S_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_REAL_ESTATE` MODIFY COLUMN `T_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_RENEWAL` MODIFY COLUMN `MISSING_DOCS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_RENEWAL` MODIFY COLUMN `NOTES` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_TERRITORY` MODIFY COLUMN `DISPUTES_IF_ANY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_TERRITORY` MODIFY COLUMN `NOTES` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_TERRITORY` MODIFY COLUMN `ZIP_OWNED_EXCLUSIVELY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_TERRITORY` MODIFY COLUMN `ZIP_FOR_LOCATOR` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `FD_TERRITORY` MODIFY COLUMN `NATURAL_HAZARDS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `KICKOFF_CALLS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `KICKOFF_TASKS` MODIFY COLUMN `TASK_DESC` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `KNOWLEDGE_CENTER_COMMENTS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_TYPE` int NOT NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `SHARE_IMAGE` varchar(500) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_NAME` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `BANNER_IMAGE` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_MAIN_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_LEFT_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_RIGHT_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_THANKS_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_FEE` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_VIP_FEE` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_VIP_EXPERIENCE_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `BANNER_IMAGE_HEIGHT` int NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `BANNER_IMAGE_WIDTH` int NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `TESTIMONIALS_VIDEOS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `COACH_PHOTO` varchar(500) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `COACH_BIO_SHORT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `COACH_BIO_LONG` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `WHATSAPP_TEXT` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES` MODIFY COLUMN `VIP_CARD_TEXT` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LANDING_PAGES_VIDEOS` MODIFY COLUMN `VIDEO_TEXT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_AGENTS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_CALLS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `HEAR_ABOUT_US` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `EMP_ADDRESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `OTHER_INCOME` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `OPENING_ON` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `RESP_FOR_BUSINESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `SPOUSE_FEEL_BUSINESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `OTHER_FACTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `PERSONAL_PERSONALITY_STYLE` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `LEADS_BACKGROUNDBUSINESS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `GOALS_AND_ASPIRATIONS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `FRANCHISE_CONS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `TIME_FRAME_CHOICE` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `FRANCHISE_PROS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_PERSONAL_PROFILE` MODIFY COLUMN `OTHER_INFORMATION` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LEAD_TASKS` MODIFY COLUMN `TASK_DESC` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LESSONS` MODIFY COLUMN `CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LESSONS` MODIFY COLUMN `ATTACHMENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `LESSONS` MODIFY COLUMN `OBJECTIVE` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `NOTIFICATION_EMAIL_CONTENT` MODIFY COLUMN `MAIL_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `SEND_MAIL_LOGS` MODIFY COLUMN `BODY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `SEND_MAIL_LOGS` MODIFY COLUMN `ATTACHMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `SEND_MAIL_LOGS` MODIFY COLUMN `ACTIVITY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `STORIES` MODIFY COLUMN `CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `SUPPORT_COMMENTS` MODIFY COLUMN `COMMENTS` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TEMPLATES` MODIFY COLUMN `TEMPLATE_NAME` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TEMPLATES` MODIFY COLUMN `TEMPLATE_SUBJECT` varchar(255) COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TEMPLATES` MODIFY COLUMN `TEMPLATE_BODY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TEMPLATES` MODIFY COLUMN `TEMPLATE_SIGN` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TICKETS` MODIFY COLUMN `SUBJECT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TICKETS` MODIFY COLUMN `DESCRIPTION` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `TICKETS` MODIFY COLUMN `ATTACHMENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `USERS` MODIFY COLUMN `ADDRESS_LINE_1` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `USERS` MODIFY COLUMN `ADDRESS_LINE_2` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `USERS` MODIFY COLUMN `PROFILE_PICTURE` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `USERS` MODIFY COLUMN `CLIENT_TYPE_ID` int NOT NULL DEFAULT 0;
ALTER TABLE `USER_SIGNS` MODIFY COLUMN `SIGN` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `VIEWS` MODIFY COLUMN `QUERY` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `WEBPAGE_URL` MODIFY COLUMN `URL_TEXT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `WEB_FORMS` MODIFY COLUMN `WEB_FORM_CONTENT` mediumtext COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `WEB_FORMS` MODIFY COLUMN `WEB_FORM_FILE` mediumtext COLLATE utf8mb4_unicode_ci NULL;

-- Demo-only tables (not on eyd) — not dropped automatically:
--   APP_ROUTES
--   ENTITY_TEMP
--   USER_LESSON_MAPPING
--   call_logs
