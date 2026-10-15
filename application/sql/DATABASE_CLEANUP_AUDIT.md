# Database cleanup audit (EYD coach CRM)

**Scope:** Compare `eyd` / `franhive` schema to this codebase (`application/`, excluding logs).  
**Method:** Every table name searched under `application/{controllers,models,views,libraries,hooks,helpers,config,migrations}`.  
**Date:** 2026-10-07  

## Important

- **Do not drop columns on active tables in phase 1.** Many models use `SELECT *` or wide inserts; removing columns breaks silently or at runtime. Phase 1 is **whole tables** with **zero PHP references**.
- **`FD_TASKS` is used** (tasks module, dashboard). All other **`FD_*`** tables are legacy franchise-disclosure data with **no references** in this repo.
- **`call_logs`** is used (`Call_logs_model`) — **keep**, even though it exists only on demo today.
- **`lic_*`** — license module — **keep all**.
- **Backup** `franhive` before demo drops; backup `eyd` before any production drop.

## Summary

| Category | Table count | Action |
|----------|------------:|--------|
| Used in app (keep) | ~71 | No drop |
| No code reference — safe drop (phase 1) | 52 | `demo_drop_unused_tables_phase1.sql` |
| No code reference — review first (phase 2) | 9 | Has row history; confirm with client |
| Demo-only extras | 3–4 | Drop on demo if confirmed |

## Keep — core modules (examples)

`ENTITY`, `USERS`, `USER_ROLE`, `ROLES`, `ROLE_PERMISSION`, `PERMISSIONS`, `PERMISSIONS_MAPPING`, `TEMPLATES`, `CAMPAIGN*`, `FD_TASKS`, `COURSES`, `LESSONS`, `USER_COURSE_MAPPING`, `LANDING_PAGES`, `LANDING_PAGES_VIDEOS`, `LANDINGPAGE_CONTACTS_DATA`, `KNOWLEDGE_CENTER` (via LESSONS/COURSES), `TEST_*` (except `TEST_EVALUATION_REPORT`), `TICKETS*`, `ef_*`, `payment_agreement_*`, `ENROLL_AGREEMENT_DATA`, `password_resets`, `orders`, `CALENDAR_EVENTS`, `LEAD_EMAIL_LOGS`, `CLIENT_TYPE`, `BUSINESS_TYPE`, `COMMON_DROPDOWNS`, `COUNTRIES`, `STATES`, `MODULES`, `BASIC_CONFIG`, etc.

## Phase 1 — drop (no PHP references)

### A. Legacy franchise disclosure (`FD_*` except tasks)

Not referenced anywhere; EYD business coach does not use FDD module in this codebase.

- `FD_AGREEMENTS`, `FD_CALLS`, `FD_CONTRACT_SIGNING`, `FD_CUSTOMER_COMPLAINTS`, `FD_DEFAULT_TERMINATION`, `FD_DOCUMENTS`, `FD_EVENT`, `FD_GUARANTOR`, `FD_INSURANCE`, `FD_LEGAL_VIOLATION`, `FD_LENDERS`, `FD_MARKETING`, `FD_MYSTERY_REVIEW`, `FD_NOTES`, `FD_REAL_ESTATE`, `FD_RENEWAL`, `FD_TERRITORY`

### B. Legacy kickoff / lead satellite tables

- `KICKOFF_CALLS`, `KICKOFF_NOTES`, `KICKOFF_TASKS`
- `LEADS_COMPLIANCE`, `LEADS_CO_APPLICANT`, `LEADS_REALESTATE`, `LEADS_VISIT`
- `LEAD_AGENTS`, `LEAD_CALLS`, `LEAD_NOTES`, `LEAD_PERSONAL_PROFILE`
- `AGREEMENT_DETAILS`, `AGREEMENT_DETAILS_REMARKS`

### C. Old platform / import / admin-console artifacts

- `CAMPAIGN_SUB_MODULES_MAPPING`, `IMPORT_SUB_MODULES_MAPPING`
- `COMMON_FIELD_DATA`, `DASHBOARD_SAVE_VIEW`, `SAVE_VIEW`
- `franhive_data`, `module_fh`, `permission_fh` (on **eyd**; absent on demo)
- `ENTITY_COURSES_MAPPING`
- `LANDING_PAGES_COUPONES` (coupons use Stripe API, not this table)
- `TEST_EVALUATION_REPORT`
- `UNSUBSCRIBE_LIST`, `WEBPAGE_URL`, `USER_SIGNS`, `DELETED_LOGS`, `SUPPORT_COMMENTS`
- `KNOWLEDGE_CENTER_COMMENTS` (no code; comments not wired in KC UI)

### D. Demo-only junk (no code)

- `APP_ROUTES`, `ENTITY_TEMP`, `USER_LESSON_MAPPING`  
- **`call_logs`** — keep if you use Call Logs module (code exists).

## Phase 2 — review before drop (historical data)

| Table | Demo rows (approx) | Notes |
|-------|-------------------:|-------|
| `WEBFORM_REQUEST` | 745 | Old web-form queue; replaced by `ef_*`. Archive/export first. |
| `ACTIVITY_LOGS` | 171 | No current writer in code; audit trail only. |
| `USER_NOTIFICATIONS` | 171 | In-app notifications; no reader in code found. |
| `SEND_MAIL_LOGS` | 23 | Legacy mail log. |
| `SEND_WHATSAPP_LOGS` | 11 | Legacy WhatsApp log. |
| `STORIES` | 4 | Unused in views/controllers. |
| `NOTIFICATION_EMAIL_CONTENT` | 5 | Unused templates store. |
| `USERS_DIVISION_MAPPING` | 15 | Old permission geography. |
| `USERS_LOCATION_MAPPING` | 3 | Same. |
| `USERS_PERMISSIONS_MAPPING` | 0 | Superseded by `USER_ROLE` / `ROLE_PERMISSION`. |

Phase 2 SQL is **not** included by default — export CSV if needed, then drop manually.

## Columns

Automated column-level cleanup was **not** run: risk of breaking dynamic SQL and old reports is too high. After phase 1 table drops, optional phase 3 can target **empty** legacy tables only, or per-table reviews (e.g. duplicate option columns on `TEST_SERIES_QUESTIONS` vs `TEST_SERIES_QUESTION_OPTIONS` — **both** may be needed depending on test UI path; verify in UI before dropping).

## How to run

```bash
# Demo first
mysql -h 134.209.145.105 -P 8972 -u admin -p franhive < application/sql/demo_drop_unused_tables_phase1.sql

# After demo smoke-test (login, leads, tasks, campaigns, eForms, PA, KC, tests, landing pages):
# Backup eyd, then:
mysql -h 134.209.145.105 -P 8972 -u admin -p eyd < application/sql/eyd_drop_unused_tables_phase1.sql
```

## Regenerate analysis

```bash
php application/sql/analyze_unused_schema.php
```
