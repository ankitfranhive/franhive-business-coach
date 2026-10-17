-- Reassign → student sees "Reattempt Test". Run on eyd / demo (once).
-- Step 1 (required): create grant table — code uses this when admin clicks "Re Assigned Test".
CREATE TABLE IF NOT EXISTS TEST_USER_REATTEMPT_GRANT (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  test_id INT UNSIGNED NOT NULL,
  granted_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_user_test (user_id, test_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 2 (optional): only if column does not exist yet
-- ALTER TABLE TEST_USER_ALLOCATION ADD COLUMN ALLOW_REATTEMPT TINYINT(1) NOT NULL DEFAULT 0;
