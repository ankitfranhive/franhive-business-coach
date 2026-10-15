# Sync demo (`franhive`) schema from EYD (`eyd`)

Compared live on **134.209.145.105:8972** using `INFORMATION_SCHEMA`.

## Summary (generated 2026-10-07)

| Item | Count |
|------|------:|
| Tables on EYD missing on demo | 17 |
| Columns missing on demo (existing tables) | 70 |
| Columns with type/default mismatch | 136 |
| Tables on demo only (not dropped) | 4 |

### Missing tables on demo (created in safe script)

- `CALENDAR_EVENTS`, `LANDINGPAGE_CONTACTS_DATA`, `LEAD_EMAIL_LOGS`
- `TEST_SERIES_QUESTION_OPTIONS`
- eForms: `ef_form_types`, `ef_form_type_fields`, `ef_templates`, `ef_requests`, `ef_submissions`, `ef_submission_values`
- Payment agreement: `payment_agreement_requests`, `payment_agreement_form_settings`
- `password_resets`, `orders`, `module_fh`, `permission_fh`, `franhive_data`

### Demo-only tables (left as-is)

- `APP_ROUTES`, `ENTITY_TEMP`, `USER_LESSON_MAPPING`, `call_logs`

### Notable missing columns (fixes dashboard / campaigns / PA / landing pages)

- **CAMPAIGN**: `WORKFLOW_STATUS`, `TOTAL_RECIPIENTS`, `SENT_COUNT`, `LAST_ACTIVITY_AT`, `TIMEZONE`
- **Campaign mappings**: send schedule columns on `CAMPAIGN_TEMPLATES_MAPPING`, `CONTACT_SOURCE` on `CAMPAIGN_TEMPLATE_USER_MAPPING`
- **ENROLL_AGREEMENT_DATA**: full payment-agreement field set
- **LANDING_PAGES**: payment/pricing/email template columns
- **ENTITY**: `LEAD_SENDING_ORDER`, `CLIENT_SENDING_ORDER`, `TEAMS_*` (if not already added)
- **TEMPLATES**: `MERGE_TAGS`
- **LESSONS**: thumbnail/video/course link fields

Full lists: `sync_demo_schema_from_eyd_summary.json`

## How to run on demo

1. **Backup** the `franhive` database first.
2. Run the **safe** script in one session (table order respects foreign keys — eForms: `ef_form_types` → `ef_form_type_fields` → `ef_templates` → `ef_requests` → `ef_submissions` → `ef_submission_values`):


```bash
mysql -h 134.209.145.105 -P 8972 -u admin -p franhive < application/sql/sync_demo_schema_from_eyd_safe.sql
```

3. Optionally run **modify** script after review (collation / text → mediumtext, etc.):

```bash
mysql -h 134.209.145.105 -P 8972 -u admin -p franhive < application/sql/sync_demo_schema_from_eyd_modify_optional.sql
```

4. Re-run comparison:

```bash
php application/sql/generate_demo_sync_from_eyd.php
```

## Files

| File | Purpose |
|------|---------|
| `sync_demo_schema_from_eyd_safe.sql` | `CREATE TABLE IF NOT EXISTS` + `ADD COLUMN` only |
| `sync_demo_schema_from_eyd_modify_optional.sql` | `MODIFY COLUMN` to match EYD (optional) |
| `sync_demo_schema_from_eyd.sql` | Combined (safe + modify) |
| `sync_demo_schema_from_eyd_summary.json` | Machine-readable diff |
| `generate_demo_sync_from_eyd.php` | Regenerate scripts from live DBs |

## Notes

- Scripts target database **`franhive`** (demo tenant). EYD production data is **not** copied—schema only.
- `CREATE TABLE` uses definitions from EYD; `AUTO_INCREMENT` values in DDL are cosmetic.
- Foreign keys on new eForms / test option tables require parent tables to exist—run the full safe script in one go.
- License module `lic_*` tables were identical or absent on both at compare time; not listed in the diff.
