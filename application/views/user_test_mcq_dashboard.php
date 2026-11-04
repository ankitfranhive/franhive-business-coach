<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCQ Test</title>
    <link rel="shortcut icon" type="image/png" href="https://www.franhive.com/images/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .form-check-input:disabled~.form-check-label, .form-check-input[disabled]~.form-check-label { cursor: default; opacity: 1; }
        .test-container { max-width: 900px; margin: auto; margin-top: 50px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .option-container { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .btn-container { display: flex; justify-content: space-between; }
        .form-check-input { width: 20px; height: 20px; transform: scale(1.5); cursor: pointer; margin: 3px 10px; }
        #timer { font-size: 20px; font-weight: bold; color: #28a745; background: #e9ffe9; padding: 10px 20px; border-radius: 10px; display: inline-block; box-shadow: 0px 0px 5px rgba(0,255,0,0.3); }
        input[type='checkbox']:disabled { opacity: 0.5; }
        .option-container .form-check-label { color: #000 !important; }
        .test-instructions-wrap {
            text-align: left;
            background: #fff;
            padding: 1.25rem 1.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
            max-height: 55vh;
            overflow-y: auto;
        }
        .test-instructions-content { font-size: 1.05rem; line-height: 1.65; color: #212529; }
        .test-instructions-content p { margin-bottom: 0.75rem; }
        .test-instructions-content ul, .test-instructions-content ol { margin-bottom: 0.75rem; padding-left: 1.5rem; }
        .test-instructions-content h1, .test-instructions-content h2, .test-instructions-content h3,
        .test-instructions-content h4 { margin-top: 1rem; margin-bottom: 0.5rem; font-weight: 600; }
        .test-instructions-content a { color: #0d6efd; text-decoration: underline; }
        .test-instructions-content table { width: 100%; margin-bottom: 1rem; border-collapse: collapse; }
        .test-instructions-content table td, .test-instructions-content table th {
            border: 1px solid #dee2e6; padding: 0.5rem;
        }
        .test-instructions-content img { max-width: 100%; height: auto; border-radius: 4px; }
        body { background: linear-gradient(180deg, #f0fdfa 0%, #f8fafc 40%, #f1f5f9 100%); min-height: 100vh; }
        .test-page-shell { max-width: 920px; margin: 0 auto; padding: 24px 16px 48px; }
        .test-header {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(15, 118, 110, 0.08);
            border: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .test-header .test-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 1rem;
            line-height: 1.35;
        }
        .section-nav-bar { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; }
        .section-pill {
            border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;
            border-radius: 999px; padding: 8px 16px; font-size: 13px; cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
        .section-pill:hover { background: #ecfdf5; border-color: #99f6e4; color: #0f766e; }
        .section-pill.active { background: #0f766e; border-color: #0f766e; color: #fff; font-weight: 600; box-shadow: 0 2px 8px rgba(15, 118, 110, 0.35); }
        .section-pill .sec-count { opacity: 0.9; font-size: 12px; font-weight: 500; }
        .question-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            padding: 1.5rem 1.75rem;
        }
        .question-card .progress-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f766e;
            margin: 0;
        }
        .question-card .question-text {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.5;
            margin: 1.25rem 0 1.5rem;
        }
        .question-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 0.5rem;
        }
        #timer {
            font-size: 1rem;
            font-weight: 700;
            color: #0f766e;
            background: #ecfdf5;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #99f6e4;
            box-shadow: none;
        }
        .test-actions-bar {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
    </style>
</head>

<?php
$resume_available = (int)($test_details['RESUME_AVAILABLE'] ?? 0) === 1;
$this->load->helper('test_reattempt');
$attempt_row = $user_test_details[0] ?? [];
$has_draft = test_attempt_is_draft($attempt_row);
$start_label = ($resume_available && $has_draft) ? 'Resume Test' : 'Take the Test';
?>
<body>
<div class="container mt-5 text-center">
    <div id="instructionPage" class="test-container">
        <h2 class="mb-3 text-center"><?php echo html_escape($test_details['TEST_NAME']); ?></h2>
        <?php if (trim((string)($test_details['TEST_INSTRUCTIONS'] ?? '')) !== ''): ?>
        <div class="test-instructions-wrap">
            <?php
            $this->load->view('partials/test_instructions_display', [
                'instructions' => $test_details['TEST_INSTRUCTIONS'] ?? '',
            ]);
            ?>
        </div>
        <?php endif; ?>
        <button class="btn btn-primary" id="startTestBtn"><?= html_escape($start_label) ?></button>
        <?php if (!$resume_available): ?>
        <p class="text-muted small mt-2 mb-0">This test cannot be resumed. Complete it in one sitting.</p>
        <?php endif; ?>
    </div>

    <div id="questionPage" style="display:none;">
        <div class="test-page-shell">
            <header class="test-header">
                <h1 class="test-title"><?php echo html_escape($test_details['TEST_NAME']); ?></h1>
                <div id="sectionNavWrap" style="display:none;">
                    <div class="section-nav-bar" id="sectionNavBar"></div>
                </div>
            </header>
            <div id="questionContainer"></div>
            <?php if ($resume_available): ?>
            <div class="test-actions-bar">
                <button type="button" class="btn btn-outline-secondary" id="saveExitBtn">Save &amp; exit</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="submitConfirmModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999;">
    <div style="background:#fff; max-width:480px; margin:12vh auto; padding:24px; border-radius:10px; text-align:left; box-shadow:0 8px 30px rgba(0,0,0,0.2);">
        <h5 class="mb-3">Submit test?</h5>
        <p id="submitModalMessage"></p>
        <div class="text-right mt-3">
            <button type="button" class="btn btn-secondary" id="cancelSubmitBtn">Go back</button>
            <button type="button" class="btn btn-success ml-2" id="confirmSubmitBtn">Submit now</button>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let testStructure = <?php echo json_encode($test_question_structure ?? ['use_sections' => false, 'sections' => [], 'questions' => []]); ?>;
    let useSections = !!(testStructure && testStructure.use_sections);
    let sectionMeta = (testStructure && testStructure.sections) ? testStructure.sections : [];
    let questions = <?php echo json_encode($test_questions); ?>;
    let testType = <?php echo (int)$test_details['TEST_TYPE']; ?>;
    let testId = "<?= (int)($test_details['TEST_ID'] ?? $this->uri->segment(2)) ?>";
    let resumeAvailable = <?= $resume_available ? 'true' : 'false' ?>;
    let savedDraftRaw = <?php
        $draft = null;
        if ($has_draft) {
            $draft = $user_test_details[0]['USER_RESPONSE'];
        }
        echo json_encode($draft);
    ?>;
    let currentIndex = 0;
    let timerInterval;
    let elapsedSeconds = 0;
    let responses = [];
    let clearResponseCount = 2;
    let questionStartTime = 0;
    let saveInFlight = false;

    function isQuestionAnswered(index) {
        const r = responses[index];
        if (!r) return false;
        if (testType === 1) {
            return !!(r.most_likely && r.least_likely);
        }
        return !!r.selected_option;
    }

    function buildResponsesPayload() {
        const out = [];
        for (let i = 0; i < questions.length; i++) {
            if (responses[i]) {
                const row = Object.assign({}, responses[i]);
                if (!row.question_id && questions[i]) {
                    row.question_id = questions[i].QUESTION_ID;
                }
                out.push(row);
            }
        }
        return out;
    }

    function preloadDraft() {
        if (!savedDraftRaw) return;
        let saved = savedDraftRaw;
        if (typeof saved === 'string') {
            try { saved = JSON.parse(saved); } catch (e) { return; }
        }
        if (!Array.isArray(saved)) return;

        saved.forEach(function (r) {
            const qid = r.question_id || (r.question ? r.question.QUESTION_ID : null);
            let idx = questions.findIndex(function (q) {
                return String(q.QUESTION_ID) === String(qid);
            });
            if (idx === -1) return;
            responses[idx] = {
                question_id: questions[idx].QUESTION_ID,
                most_likely: r.most_likely || null,
                least_likely: r.least_likely || null,
                selected_option: r.selected_option || null,
                time_spent: r.time_spent || 0
            };
        });

        for (let i = 0; i < questions.length; i++) {
            if (!isQuestionAnswered(i)) {
                currentIndex = i;
                return;
            }
        }
        currentIndex = Math.max(0, questions.length - 1);
    }

    function restoreSelectionsForIndex(index) {
        const r = responses[index];
        if (!r) return;
        if (testType === 1) {
            if (r.most_likely) {
                const $most = $(`input.most-likely[name='most_likely_${index}'][value='${r.most_likely}']`);
                $most.prop('checked', true);
                $(`input[name='most_likely_${index}']`).not($most).prop('disabled', true);
            }
            if (r.least_likely) {
                const $least = $(`input.least-likely[name='least_likely_${index}'][value='${r.least_likely}']`);
                $least.prop('checked', true);
                $(`input[name='least_likely_${index}']`).not($least).prop('disabled', true);
            }
        } else if (r.selected_option) {
            $(`input[name='mcq_${index}'][value='${r.selected_option}']`).prop('checked', true);
        }
        toggleSubmitButton();
    }

    function countUnanswered() {
        let n = 0;
        for (let i = 0; i < questions.length; i++) {
            if (!isQuestionAnswered(i)) n++;
        }
        return n;
    }

    function saveDraft(exitAfter) {
        if (!resumeAvailable) {
            if (exitAfter) {
                alert('Resume is not enabled for this test. Ask your coach to turn on Resume available on the test, then open the test again.');
            }
            return;
        }
        if (saveInFlight) return;
        saveInFlight = true;
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('/save-test-response') ?>",
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                responses: buildResponsesPayload(),
                test_id: testId,
                is_submitted: false
            }),
            complete: function () { saveInFlight = false; },
            success: function (resp) {
                if (resp && resp.ok === false) {
                    alert(resp.error || 'Could not save progress.');
                    return;
                }
                if (exitAfter) {
                    window.location.href = '<?php echo base_url('my-test') ?>';
                }
            },
            error: function (xhr) {
                var msg = 'Could not save progress. Please try again.';
                try {
                    var j = xhr.responseJSON || JSON.parse(xhr.responseText);
                    if (j && j.error) msg = j.error;
                } catch (e) {}
                alert(msg);
            }
        });
    }

    function sectionsWithQuestions() {
        return sectionMeta.filter(function (sec) {
            return (sec.question_count || 0) > 0;
        });
    }

    function renderSectionNav() {
        if (!useSections || !sectionMeta.length) {
            $('#sectionNavWrap').hide();
            return;
        }
        const visible = sectionsWithQuestions();
        if (!visible.length) {
            $('#sectionNavWrap').hide();
            return;
        }
        $('#sectionNavWrap').show();
        const $bar = $('#sectionNavBar').empty();
        sectionMeta.forEach(function (sec, idx) {
            if ((sec.question_count || 0) <= 0) {
                return;
            }
            const active = questions[currentIndex] && questions[currentIndex].SECTION_KEY === idx;
            const $btn = $('<button type="button" class="section-pill"></button>');
            $btn.toggleClass('active', !!active);
            $btn.html(sec.section_name + ' <span class="sec-count">(' + sec.question_count + ')</span>');
            $btn.on('click', function () {
                jumpToSection(idx);
            });
            $bar.append($btn);
        });
    }

    function jumpToSection(sectionKey) {
        const idx = questions.findIndex(function (q) { return q.SECTION_KEY === sectionKey; });
        if (idx >= 0) {
            currentIndex = idx;
            showQuestion(currentIndex);
        }
    }

    function questionProgressLabel(index) {
        const q = questions[index];
        if (!useSections || !q || q.SECTION_KEY === null || q.SECTION_KEY === undefined) {
            return 'Question ' + (index + 1) + ' of ' + questions.length;
        }
        const sec = sectionMeta[q.SECTION_KEY];
        const secName = sec ? sec.section_name : (q.SECTION_NAME || 'Section');
        const qIn = q.QUESTION_INDEX_IN_SECTION || 1;
        return 'Question ' + qIn + ' of ' + secName;
    }

    function startTest() {
        preloadDraft();
        $("#instructionPage").hide();
        $("#questionPage").show();
        questionStartTime = elapsedSeconds;
        startTimer();
        renderSectionNav();
        showQuestion(currentIndex);
    }

    function formatTime(totalSeconds) {
        const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        return `${hours}:${minutes}:${seconds}`;
    }

    function startTimer() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            elapsedSeconds++;
            $("#timer").text(formatTime(elapsedSeconds));
        }, 1000);
    }

    function showQuestion(index) {
        if (index < 0 || index >= questions.length) return;

        if (responses[currentIndex]) {
            responses[currentIndex].time_spent = elapsedSeconds - questionStartTime;
        }

        let question = questions[index];
        let questionContainer = $("#questionContainer");

        let html = `<div class='question-card'>
                        <div class='question-toolbar'>
                            <p class='progress-label mb-0'>${questionProgressLabel(index)}</p>
                            <div id="timer">00:00:00</div>
                        </div>
                        <div class='question-text text-center'>${question.QUESTION_NAME}</div>`;

        // Options are hydrated by controller/model (new table) OR legacy fallback (option_1..4)
        const opts = Array.isArray(question.options) ? question.options : [];

        if (testType === 1) {
            // --- Type 1: Most/Least logic ---
            html += `<div class='d-flex align-items-center mb-2'>
                        <div class='text-center' style='width: 6%; font-weight: bold;'>Most</div>
                        <div class='text-center' style='width: 6%; font-weight: bold;'>Least</div>
                        <div class='option-text' style='width: 35%; text-align: left;'></div>
                    </div>`;

            opts.forEach((opt) => {
                const order = opt.SORT_ORDER;     // 1..10
                const text  = opt.OPTION_TEXT || "";

                html += `<div class='option-container d-flex align-items-center'>
                            <div class='text-center' style='width: 6%;'>
                                <input type='checkbox' class='form-check-input most-likely' 
                                    name='most_likely_${index}' 
                                    value='option_${order}' 
                                    onclick='handleMostLeastSelection("most", ${index}, this)'>
                            </div>
                            <div class='text-center' style='width: 6%;'>
                                <input type='checkbox' class='form-check-input least-likely' 
                                    name='least_likely_${index}' 
                                    value='option_${order}' 
                                    onclick='handleMostLeastSelection("least", ${index}, this)'>
                            </div>
                            <div class='option-text' style='width: 35%;text-align: left;'>
                                <label class='form-check-label'>${text}</label>
                            </div>
                        </div>`;
            });

            html += `<div class='btn-container mt-4'>
                        <button id='nextBtn' class='btn btn-primary' onclick='nextQuestion()' ${index === questions.length - 1 ? "disabled" : ""}>Next</button>
                        <button id='submitBtn' class='btn btn-success' onclick='submitTest()' disabled>Submit</button>
                    </div>
                    <button id="clearResponseBtn" class="btn btn-warning mt-3" onclick="clearResponses()">Clear Responses (${clearResponseCount})</button>`;

        } else {
            // --- All other test types (3/4/5/etc): Normal MCQ logic ---
            opts.forEach((opt) => {
                const order = opt.SORT_ORDER;
                const text  = opt.OPTION_TEXT || "";

                html += `<div class='option-container'>
                            <input type='radio' name='mcq_${index}' value='option_${order}' class='form-check-input'
                                onclick='handleMCQSelection(${index}, this)'>
                            <label class='form-check-label'>${text}</label>
                        </div>`;
            });

            html += `<div class='btn-container mt-4'>
                        <button id='nextBtn' class='btn btn-primary' onclick='nextQuestion()' ${index === questions.length - 1 ? "disabled" : ""}>Next</button>
                        <button id='submitBtn' class='btn btn-success' onclick='submitTest()' disabled>Submit</button>
                    </div>`;
        }

        html += `</div>`;
        questionContainer.html(html);
        $("#timer").text(formatTime(elapsedSeconds));
        restoreSelectionsForIndex(index);
        questionStartTime = elapsedSeconds;
        renderSectionNav();

        if (index === questions.length - 1) {
            toggleSubmitButton();
        }
    }

    // --- Handlers for Type 1 ---
    window.handleMostLeastSelection = function (type, index, selectedCheckbox) {
        const groupName = type === "most" ? `most_likely_${index}` : `least_likely_${index}`;
        $(`input[name='${groupName}']`).not(selectedCheckbox).prop("checked", false).prop("disabled", true);

        if (!responses[index]) {
            responses[index] = { question_id: questions[index]?.QUESTION_ID || null, most_likely: null, least_likely: null, time_spent: 0 };
        }

        if (type === "most") responses[index].most_likely = $(selectedCheckbox).val();
        else if (type === "least") responses[index].least_likely = $(selectedCheckbox).val();

        responses[index].time_spent = elapsedSeconds - questionStartTime;
        toggleSubmitButton();
        saveDraft(false);
    };

    // --- Handler for MCQ types (3/4/5/etc) ---
    window.handleMCQSelection = function (index, selectedRadio) {
        responses[index] = { question_id: questions[index]?.QUESTION_ID || null, selected_option: $(selectedRadio).val(), time_spent: elapsedSeconds - questionStartTime };
        toggleSubmitButton();
        saveDraft(false);
    };

    // --- Clear for Type 1 only ---
    window.clearResponses = function () {
        if (clearResponseCount > 0) {
            $(`input[name='most_likely_${currentIndex}'], input[name='least_likely_${currentIndex}']`).prop("checked", false).prop("disabled", false);
            if (!responses[currentIndex]) {
                responses[currentIndex] = { question_id: questions[currentIndex]?.QUESTION_ID || null, most_likely: null, least_likely: null, time_spent: 0 };
            }
            responses[currentIndex].most_likely = null;
            responses[currentIndex].least_likely = null;

            clearResponseCount--;
            $("#clearResponseBtn").text(`Clear Responses (${clearResponseCount})`);
            if (clearResponseCount === 0) {
                alert("You have used all your clear attempts.");
                $("#clearResponseBtn").prop("disabled", true);
            }
            toggleSubmitButton();
        }
    };

    function toggleSubmitButton() {
        const submitBtn = $('#submitBtn');
        let anyAnswered = false;
        for (let i = 0; i < questions.length; i++) {
            if (isQuestionAnswered(i)) {
                anyAnswered = true;
                break;
            }
        }
        submitBtn.prop('disabled', !anyAnswered);
    }

    window.nextQuestion = function () {
        const currentResponse = responses[currentIndex] || {};

        if (testType === 1 && (!currentResponse.most_likely || !currentResponse.least_likely)) {
            alert("Please select both Most and Least before proceeding.");
            return;
        }

        // ✅ For all other types (MCQ), selection is required
        if (testType !== 1 && !currentResponse.selected_option) {
            alert("Please select an answer before proceeding.");
            return;
        }

        if (currentIndex < questions.length - 1) {
            currentIndex++;
            showQuestion(currentIndex);
            saveDraft(false);
        }
    };

    function performFinalSubmit() {
        clearInterval(timerInterval);
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('/save-test-response') ?>",
            contentType: 'application/json',
            data: JSON.stringify({
                responses: buildResponsesPayload(),
                test_id: testId,
                is_submitted: true
            }),
            success: function () {
                window.location.href = '<?php echo base_url('my-test-thankyou') ?>';
            },
            error: function () {
                alert('Could not submit test. Please try again.');
            }
        });
    }

    window.submitTest = function () {
        const unanswered = countUnanswered();
        let msg = '';
        if (resumeAvailable) {
            msg = unanswered > 0
                ? 'You have ' + unanswered + ' unanswered question(s). Submitting now will finish the test. To continue later, use Save & exit instead.'
                : 'Submit your test now? You can still use Save & exit if you need to leave without submitting.';
        } else {
            msg = unanswered > 0
                ? 'You have ' + unanswered + ' unanswered question(s). This test cannot be resumed — if you leave, progress may be lost. Submit now?'
                : 'This test cannot be resumed. Submit now to finish?';
        }
        $('#submitModalMessage').text(msg);
        $('#submitConfirmModal').show();
    };

    $("#startTestBtn").click(startTest);
    $('#cancelSubmitBtn').on('click', function () {
        $('#submitConfirmModal').hide();
    });
    $('#confirmSubmitBtn').on('click', function () {
        $('#submitConfirmModal').hide();
        performFinalSubmit();
    });
    $("#saveExitBtn").on('click', function () {
        if (responses[currentIndex]) {
            responses[currentIndex].time_spent = elapsedSeconds - questionStartTime;
        }
        saveDraft(true);
    });
});
</script>

</body>
</html>
