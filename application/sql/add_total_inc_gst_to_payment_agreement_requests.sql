-- Run once on your database (MySQL / MariaDB).
-- Adds the Total (inc GST) amount set when staff send a payment agreement link.

ALTER TABLE `payment_agreement_requests`
ADD COLUMN `total_inc_gst` DECIMAL(12, 2) NULL DEFAULT NULL
COMMENT 'Total inc GST set when sending the form link'
AFTER `business_name`;
