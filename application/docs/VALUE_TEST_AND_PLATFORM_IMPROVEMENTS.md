# Value Test (Type 6) & Test/Question Platform Guide

This document describes how the **Value Test** works, how **evaluation/scoring** is computed, how to **set up** tests and questions, and what has been **improved** in the test and question modules.

---

## 1. Test types (quick reference)

| Type | Name (admin)        | Student experience        | Evaluation |
|------|---------------------|---------------------------|------------|
| 1    | Profiling / DISC    | Most / Least per question | Admin report |
| 2    | Subjective          | Text answers              | Manual     |
| 3–5  | MCQ / BRS-style     | Single-choice MCQ         | Auto / BRS report |
| **6**| **Value Test**      | Me / I don’t care / Not me (MCQ) | **Auto-scored report + admin “Mark evaluated”** |

Value Test uses the same MCQ UI as types 3–5 (`user_test_mcq_dashboard.php`).

---

## 2. Value Test — purpose

A Value Test measures how strongly the learner identifies with statements using three response buckets:

- **Me** — aligns with the statement  
- **I don’t care** — neutral / excluded from weighted score totals  
- **Not me** — does not align  

Reports show **counts**, **weighted totals** (using each question’s `QUESTION_MARKS`), **per-question detail**, and optionally **section/value analytics** when sections are enabled.

---

## 3. Creating a Value Test (admin)

### 3.1 Create the test

1. **Tests → Add new Test**
2. Set **Test type** = **Value Test (6)**
3. Set dates, instructions, total questions/marks (informational for admin)
4. Optional flags:
   - **Resume available** — student can **Save & exit** and continue later (draft, `IS_SUBMITTED = 0`)
   - **Reattempt available** — after submit + **evaluated**, student may take again (policy + admin grant still apply)
   - **Use sections** — enable section-wise questions (see §7)
5. Save the test

### 3.2 Add questions to the test

1. **Add Questions** on the test (single add or **bulk select** on the question bank page)
2. Questions must be **MCQ type** with options stored in `TEST_SERIES_QUESTION_OPTIONS` (or legacy `option_1`… columns as fallback)

### 3.3 Question options (2 or 3+ options)

- **Minimum 2 options**, up to 10 (admin add/edit question)
- **Recommended labels** (for reliable auto-classification):
  - 3 options: `Me`, `I don't care`, `Not me` (order 1, 2, 3)
  - 2 options: `Me`, `Not me` (order 1, 2) — no “don’t care” option

**Classification order** (see `application/helpers/test_report_pdf_helper.php`):

1. **Text first** — normalize label and match “not me”, “don’t care”, “me”
2. **If text is ambiguous** — fall back to **position**:
   - 2 options: 1 = Me, 2 = Not me  
   - 3+ options: 1 = Me, 2 = I don’t care, 3 = Not me (first/middle/last rules for longer lists)

So custom wording works if it clearly contains those phrases; otherwise position matters.

### 3.4 Assign to users

**Test allocation** — assign test to users (bulk assign supported on user list).

---

## 4. Student flow (Value Test)

1. **My Tests** → open test in date window  
2. Read instructions → **Take the Test** / **Resume Test** (if draft exists and resume is on)  
3. Answer each question (radio options)  
4. **Save & exit** (only if **Resume available** on the test)  
5. **Submit** when finished → thank-you page  
6. Until admin marks **Evaluated**, student typically sees **Not Evaluated** on listings (no full value report on student side for type 6 in standard listings)

**Blocked cases**

- Already **submitted** without reattempt permission → cannot reopen test URL  
- **Resume off** → draft save rejected server-side; complete in one sitting  

---

## 5. Evaluation logic (how scores are computed)

Evaluation is **automatic from stored responses** when admin opens the report or downloads PDF. No manual per-question marking is required for type 6.

**Code entry point:** `test_report_value_pdf_data($CI, $responseRow)` in `application/helpers/test_report_pdf_helper.php`.

### 5.1 Input

- Latest **submitted** attempt: `TEST_USER_RESPONSE.USER_RESPONSE` (JSON array of `{ question_id, selected_option, time_spent, … }`)
- Questions: `TEST_SERIES_QUESTIONS` (`QUESTION_NAME`, `QUESTION_MARKS`)
- Options: `TEST_SERIES_QUESTION_OPTIONS` keyed by `option_{SORT_ORDER}`

### 5.2 Per-question processing

For each answered question:

1. Read `selected_option` (e.g. `option_2`)
2. Classify option → `me` | `dont_care` | `not_me` (see §3.3)
3. **Counts:** increment global `choice_counts[me|dont_care|not_me]`
4. **Weighted score:**
   - **Me** → add `QUESTION_MARKS` to `weighted_me_total`, weighted = marks  
   - **Not me** → add `QUESTION_MARKS` to `weighted_not_me_total`, weighted = marks  
   - **I don’t care** → weighted = 0, **excluded** from weighted totals (shown as “Excluded” in report)

### 5.3 Report outputs (admin HTML + PDF)

| Output | Description |
|--------|-------------|
| Choice counts | Total Me / I don’t care / Not me (answered questions) |
| Weighted totals | Sum of marks for Me and Not me separately |
| Question-by-question | Text, chosen label, marks, weighted, in-total or excluded |
| **Section analytics** (if `USE_SECTIONS = 1`) | Per-section counts + weighted Me/Not me |
| **By value number** | Section sort 1 → **Value 2**, sort 2 → **Value 3**, … (backend mapping only) |
| **Even / odd values** | Aggregate counts for value numbers 2,4,6… vs 3,5,7… |

Section analytics: `value_test_apply_section_analytics()` in `application/helpers/test_sections_helper.php`.

### 5.4 Admin “evaluation” workflow

Value Test is **scored on view**; **evaluation** in the product sense = admin confirms and locks the attempt:

1. **Test reports** → open user attempt (or eval URL with response id)  
2. Review HTML report (`test_report_value.php`)  
3. **Download PDF** (`downloadTestReportPdf` / student equivalent where enabled)  
4. Click **Mark as evaluated** → `submit-test-eval/{response_id}` → sets `IS_EVAL = 1`  

Until `IS_EVAL = 1`, listings show **Not Evaluated** for type 6.

**Reattempt:** After evaluated, user may **Reattempt** if test **Reattempt available** and/or admin **Reassign / grant reattempt** (same rules as other tests).

---

## 6. Value numbers (sections → backend “values”)

Used only in **admin/PDF analytics**, not shown to students on the question screen.

| Section order (`SORT_ORDER`) | Value number |
|-----------------------------|--------------|
| 1 | 2 |
| 2 | 3 |
| … | … |
| N | N + 1 |

- **Even values:** 2, 4, 6, … — combined Me / don’t care / Not me counts  
- **Odd values:** 3, 5, 7, … — combined counts  

Formula: `value_number = section_sort_order + 1` (`value_number_from_section_sort()`).

---

## 7. Optional sections (new tests only)

Legacy tests without sections behave as a **flat question list** (unchanged).

### Enable

1. Edit test → **Use sections**  
2. **Manage sections & assignments** (`test-sections/{test_id}`)  
3. Define sections (names, order)  
4. Assign each mapped question to a section → save  

### Student UI

- Test **title** on top  
- **Section pills** only for sections with **≥ 1 question**  
- Progress: **Question {n} of {Section name}** (no “Value #” on student UI)  

### Schema

- `application/sql/test_sections_schema.sql`  
- Or auto-migrate via `ensure_test_sections_schema()` on first use  

---

## 8. Resume & reattempt

| Feature | DB / flag | Behavior |
|---------|-----------|----------|
| Resume | `TEST_SERIES.RESUME_AVAILABLE = 1` | Draft saves (`IS_SUBMITTED = 0`); **Resume** on My Tests |
| Reattempt | `REATTEMPT_AVAILABLE` + evaluated, or admin grant | New attempt row after submit |

SQL reference: `application/sql/test_series_resume_reattempt.sql`

Draft detection uses latest response row with dynamic column names (`user_id` / `USER_ID`, etc.).

---

## 9. Questions module improvements

| Area | Improvement |
|------|-------------|
| Options | Add/edit question: **2–10 options** (not forced to 4) |
| Bulk assign | **Add questions to test**: search, filters, pagination, **bulk select** + add |
| Performance | **Server-side DataTables** for question bank (`admin-test-questions-datatable`) |
| Indexes | `application/sql/test_admin_performance_indexes.sql` for faster admin/reports |

---

## 10. Test admin & allocation improvements

| Area | Improvement |
|------|-------------|
| Test allocation | User list filters; **bulk assign tests** to a user |
| Test listing / reports | Lighter queries; report query uses indexed-friendly “latest attempt” pattern |
| Loaders | Admin test pages: loading overlay + DataTables “Processing…” |
| Schema cache | `application/cache/test_schema_v5.ok` — avoids repeated column checks |

---

## 11. Value Test UI improvements

| Area | Improvement |
|------|-------------|
| Student dashboard | Cleaner layout: title → sections → question card |
| Sections | Hide empty sections; no Value # on question label |
| Reports | Section / value / even-odd blocks in **HTML + PDF** when sections enabled |
| Student reports | Type 6: Evaluated / Not Evaluated until admin marks eval |

---

## 12. Key files (developers)

| Topic | Path |
|-------|------|
| Value scoring & PDF data | `application/helpers/test_report_pdf_helper.php` |
| Section/value analytics | `application/helpers/test_sections_helper.php` |
| Admin value report view | `application/views/test_report_value.php` |
| Value PDF view | `application/views/test_report_value_pdf.php` |
| Student MCQ / value UI | `application/views/user_test_mcq_dashboard.php` |
| Save attempt | `Welcome::saveTestResponse`, `User_test_model::saveUserResponse` |
| Admin eval | `Admin::testEval`, `Admin::submitTestEval` |
| Sections admin | `Admin::manageTestSections`, `views/admin/manage_test_sections.php` |

---

## 13. Checklist — new Value Test go-live

- [ ] Test type **6**, dates correct  
- [ ] **Resume** / **Reattempt** flags set as intended  
- [ ] Questions added (Me / don’t care / Not me labels or correct order)  
- [ ] Each question has sensible **QUESTION_MARKS** (used in weighted totals)  
- [ ] Optional: **Use sections** + all questions assigned to sections  
- [ ] Users allocated  
- [ ] Pilot: submit attempt → admin report + PDF → **Mark evaluated**  
- [ ] Run DB indexes + section/resume SQL on production if not already applied  

---

## 14. FAQ

**Q: Does “I don’t care” count in the Me or Not me total?**  
No. It appears in counts only; weighted Me/Not me totals ignore it.

**Q: Can we use 2-option questions only?**  
Yes. Option 1 = Me, option 2 = Not me (by label or position).

**Q: Do old tests break when we enable sections?**  
No. Tests without **Use sections** keep flat order and global-only value reports.

**Q: Where is “Combined Me + Not me”?**  
Removed from value reports per product decision.

---

*Last updated: platform state as of Value Test sections, resume/reattempt, performance, and UI improvements.*
