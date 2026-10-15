-- eForms tables only — run on demo DB `franhive` when full sync fails on FK order.
-- Order: ef_form_types → ef_form_type_fields → ef_templates → ef_requests → ef_submissions → ef_submission_values

SET NAMES utf8mb4;
SET SESSION sql_mode = '';
USE `franhive`;

DROP TABLE IF EXISTS `ef_submission_values`;
DROP TABLE IF EXISTS `ef_submissions`;
DROP TABLE IF EXISTS `ef_requests`;
DROP TABLE IF EXISTS `ef_form_type_fields`;
DROP TABLE IF EXISTS `ef_templates`;
DROP TABLE IF EXISTS `ef_form_types`;

CREATE TABLE IF NOT EXISTS `ef_form_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ef_submission_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submission_id` bigint unsigned NOT NULL,
  `field_name` varchar(80) NOT NULL,
  `field_label` text NOT NULL,
  `value_text` mediumtext,
  PRIMARY KEY (`id`),
  KEY `idx_sub` (`submission_id`),
  CONSTRAINT `ef_submission_values_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `ef_submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
