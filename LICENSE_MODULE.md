# License / AMC enforcement

CodeIgniter **3** (`system/core/CodeIgniter.php`). This module is a separate `lic_` subsystem. It is not wired into `USERS`, `ROLES`, or `PERMISSIONS`.

## Hidden vendor panel

- URL: `/sys-38564376`
- Change the slug in `application/config/license_local.php` (`license_admin_slug`) **before production**. Update is picked up by `application/config/routes.php`.
- Vendor login (separate from CRM users):
  - Email: `vendor@franhive.internal`
  - Password: `FhLic-8d75e1a3c5`
  - Change the password hash with `php -r 'echo password_hash("your-pass", PASSWORD_DEFAULT), "\n";'` and put it in `license_vendor_password_hash`.
- Unauthenticated probes, wrong slug, `/LicenseVendor`, or IPs outside `license_admin_allowed_ips` return a **plain 404**.
- Login form is only on the exact slug (minimal, unbranded).

## Secrets

Do **not** commit real secrets. Copy:

```
application/config/license_local.php.example
→ application/config/license_local.php
```

That file is gitignored. Values can also come from environment:

- `LICENSE_SIGNING_SECRET` — HMAC key for license tokens
- `LICENSE_ADMIN_SLUG`
- `LICENSE_ADMIN_ALLOWED_IPS` — comma-separated; empty = any IP
- `LICENSE_CRON_TOKEN`
- `LICENSE_ALERT_EMAIL` / `LICENSE_ALERT_WEBHOOK`
- `LICENSE_HEARTBEAT_URL`

Signing secret is only used by `LicenseVerifier` and the vendor save path. Editing `lic_licenses.expiry_date` in SQL without re-signing in the panel fails verification (fail closed).

## How enforcement works

`post_controller_constructor` hook: `application/hooks/LicenseEnforcer.php`.

- Skips CLI, vendor panel, cron, and anyone not logged into the CRM.
- If **no** license row exists yet, the CRM stays open (so first deploy is not a lockout).
- If a license exists: HMAC token must match `client_code` + `expiry_date` + `status`. Then expiry + `grace_period_days` is checked.
- Result is cached in the CRM session for 20 minutes (`license_session_ttl`).
- **Super Admin variant** is **not** `ROLE_NAME == 'Super Admin'`. It is whichever CRM role currently has **permission id 9** (User Management Full Access). Other roles get a generic “contact your administrator” page with no billing language.

## Cron jobs

Run from **your** scheduler when possible. If this later runs on the client’s on-prem box, they could disable the cron — treat missed heartbeats (48h+) as a manual follow-up.

```bash
php index.php LicenseCron notify
php index.php LicenseCron integrity
php index.php LicenseCron daily
```

HTTP (optional):

```
/LicenseCron/daily?token=liccron-7f3c1a90e2b64d18
```

Suggested crontab:

```
15 7 * * * php /path/to/index.php LicenseCron daily
```

Notifications reuse `send_email()` and, if present, a `TEMPLATES` row with `MODULE_NAME = License`. Placeholders: `[Client Name]`, `[Expiry Date]`, `[Days Left]`, `[Plan Name]`, `[Grace Period]`. Thresholds: 30/15/7/3/1 days and `expired`. SMS is logged as `not_configured` (campaign module is email).

Integrity hashes: `LicenseVerifier.php`, `LicenseEnforcer.php`, `blocker.php`. Seal a baseline from the vendor Logs screen, or the first integrity cron will seal one.

Heartbeat: logged in `lic_heartbeat_log`. If `LICENSE_HEARTBEAT_URL` is set, it POSTs `{client_code, license_status, file_hash}`.

## Schema

Tables are created automatically on first model load (`CREATE TABLE IF NOT EXISTS`). You can also enable CI migrations and run `20260916120000_license_amc`.

## Test plan

1. Open `/sys-38564376`, log in, create client code `eyd` (or this install’s subdomain), save a license with a future expiry. Confirm a signed token is stored.
2. In SQL, change `lic_licenses.expiry_date` only. Log into the CRM: after cache TTL (or new session) it should block.
3. Restore by saving the license again in the vendor panel (re-signs).
4. Set expiry to yesterday with grace `0`, save. CRM Super Admin (permission 9) sees AMC/renew copy. A My Courses user sees only the generic message — view-source should have no “AMC” / “subscription” / “expir”.
5. Add a billing contact, click **Send test notification**.
6. Seal baseline, edit `application/hooks/LicenseEnforcer.php`, run `php index.php LicenseCron integrity` — expect a mismatch log and alert path.
7. `/not-a-real-slug` and `/LicenseVendor` return plain 404.
