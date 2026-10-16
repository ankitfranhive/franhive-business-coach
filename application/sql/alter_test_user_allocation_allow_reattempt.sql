-- Run once on eyd / demo DBs so "Re Assigned Test" shows Reattempt Test for students.
ALTER TABLE TEST_USER_ALLOCATION
  ADD COLUMN ALLOW_REATTEMPT TINYINT(1) NOT NULL DEFAULT 0
  AFTER IS_REMOVED;
