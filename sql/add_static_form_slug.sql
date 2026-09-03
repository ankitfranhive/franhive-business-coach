-- Add support for static eForms (e.g. IICT Enrolment and Payment Agreement).
-- Run this once on your database. If columns/tables already exist, ignore the errors.

ALTER TABLE ef_requests
  ADD COLUMN static_form_slug VARCHAR(64) NULL DEFAULT NULL
  COMMENT 'When set, form fields come from config instead of template';

ALTER TABLE ef_submissions
  ADD COLUMN static_form_slug VARCHAR(64) NULL DEFAULT NULL
  COMMENT 'When set, submission is for a static form (e.g. iict_enrolment_agreement)';

-- Table to store editable IICT form content/fields (admin "Edit IICT Form" page).
CREATE TABLE IF NOT EXISTS ef_static_forms (
  slug VARCHAR(64) NOT NULL PRIMARY KEY,
  config_json LONGTEXT NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
);
