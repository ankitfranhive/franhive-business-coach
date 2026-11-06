# Tests & Value Test — What’s New & How to Use It

This guide is for **admins and coaches**. It explains what we built and the steps to run tests day to day—no technical setup required.

---

## What is a Value Test?

A **Value Test** is a special test type where each question asks how much a statement applies to the learner:

- **Me**
- **I don’t care**
- **Not me**

Some questions may only offer **Me** and **Not me** (two options)—that is supported.

After the learner **submits**, you open their report to see counts, totals, and (if you use sections) a breakdown by section. You then **mark the attempt as evaluated** when you are ready for the learner to see that status on their side.

---

## What we built (summary for your client)

### Value Test & reporting
- Full **Value Test** flow: create test → assign → learner takes test → **automatic report** for you (no manual marking per question).
- Reports show **Me / I don’t care / Not me** counts and **weighted totals** (based on marks you set on each question).
- **I don’t care** is counted in the report but **does not** add to the Me or Not me weighted totals.
- **PDF download** of the same report for sharing or records.
- **Mark as evaluated** when you have reviewed the attempt.

### Sections (optional, for new-style tests)
- Split a test into **sections** (e.g. Section A, Section B).
- Learners see the **test name at the top**, then **section tabs** (only sections that have questions).
- Progress shows as **Question 1 of Section A** (simple wording for learners).
- Your report can show **results by section**, by internal **value number** (for your methodology), and **even/odd value** summaries—learners do not see “value numbers” on screen.

### Resume & reattempt
- **Resume available** (per test): learner can **Save & exit** and continue later from **My Tests** → **Resume**.
- **Reattempt available** (per test): after submit and you **mark evaluated**, learner may take the test again (subject to your rules and any manual reassign you use today).
- Clear **loading message** when saving progress so learners know the system is working.

### Questions & test management
- Questions can have **2 or more** answer options (not forced to four).
- **Bulk add** questions to a test (search, filter, select many at once).
- **Bulk assign** tests to users from allocation screens.
- **Faster** test and question list pages when you have many items.

### Older tests
- Tests created **before** sections were turned on behave as before: **same flat list of questions**, same style of report (without section breakdown unless you enable sections and assign questions).

---

## How to create and run a Value Test

### Step 1 — Create the test
1. Go to **Tests** → **Add new Test**.
2. Choose **Value Test** as the test type.
3. Enter name, dates, and instructions.
4. Turn on if you need them:
   - **Resume available** — allow Save & exit and Resume.
   - **Reattempt available** — allow another attempt after you mark the previous one evaluated.
   - **Use sections** — only if you want section-wise questions and section reports (see Step 3).
5. Save.

### Step 2 — Add questions and assign learners
1. Open the test → **Add Questions** (one at a time or **bulk select** from the list).
2. Use **MCQ** questions with options labeled **Me**, **I don’t care**, **Not me** (or **Me** / **Not me** for two-option items).
3. Set **marks** on each question if you use weighted totals in the report.
4. **Test allocation** → assign the test to the right users (bulk assign supported).

### Step 3 — Sections (only if you enabled “Use sections”)
1. Edit the test → link **Manage sections & assignments**.
2. Add section names in order (first section = first value in your backend mapping).
3. Assign **each question** to a section and save.
4. Sections with **no questions** do not appear to the learner.

### Step 4 — Learner experience
1. **My Tests** → **Take the Test** (or **Resume** if they saved progress).
2. Answer questions → **Save & exit** (if resume is on) or **Submit** when done.
3. They see a thank-you / not evaluated status until you complete Step 5.

### Step 5 — Your review (evaluation flow)
1. Go to **Test reports** (or your usual path to open an attempt).
2. Open the learner’s **Value Test report**:
   - Overall Me / I don’t care / Not me counts  
   - Weighted Me and Not me totals  
   - Question-by-question answers  
   - If sections are on: by section, by value number, even/odd summaries (for your analysis)
3. **Download PDF** if needed.
4. Click **Mark as evaluated** when finished.

### Step 6 — Reattempt (if allowed)
- If **Reattempt available** is on and the attempt is **evaluated**, the learner may see **Reattempt Test** on My Tests (within the test dates).
- You can still use your existing **reassign / grant reattempt** process where you use it today.

---

## Quick reference — admin toggles

| Setting | What it does for the learner |
|--------|------------------------------|
| **Resume available** | Save & exit; Resume from My Tests |
| **Reattempt available** | New attempt after submit + you marked evaluated |
| **Use sections** | Section tabs + section-wise reports for you |

---

## Tips for best results

- Use clear option labels: **Me**, **I don’t care**, **Not me** (or Me / Not me for two options).
- Turn **Resume available** on before learners start if they may need more than one sitting.
- For section reports, assign **every question** to a section after you add sections.
- After changing **Resume available**, ask learners to **refresh** the test page if it was already open.

---

## Other test types (unchanged)

- **Profiling (Most/Least)**, **Subjective**, and **MCQ/BRS-style** tests work as before; this release mainly adds and improves **Value Test**, **sections**, **resume/reattempt**, and **admin question/allocation** tools.

---

*Client-facing guide — what we built and how to use it.*
