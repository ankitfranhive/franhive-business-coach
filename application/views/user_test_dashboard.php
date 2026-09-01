<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Series Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- CKEditor script -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        h1 { color: #FCB22B; }
        button { background-color: #FCB22B; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        div#question { font-size: 22px; padding: 10px 0px; }

        #finishTestModal, #submitTestModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        #finishTestModal > div, #submitTestModal > div {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
        }

        #buttonContainer { display: flex; justify-content: space-between; align-items: center; padding: 10px; }
        #buttonContainer > div { display: flex; }
        #buttonContainer button { margin: 5px; }

        @media (max-width: 600px) {
            #buttonContainer { flex-direction: column; }
            #buttonContainer > div { width: 100%; justify-content: space-around; }
            #finishTestModal > div, #submitTestModal > div { width: 90%; }
        }
    </style>
</head>

<body>

<div class="container">
    <h1 class="mt-4"><?=$test_details['TEST_NAME']?></h1>

    <div id="progressBarContainer" style="width: 100%; background-color: #eee;">
        <div id="progressBar" style="width: 0%; height: 20px; background-color: #4CAF50;"></div>
        <div id="questionCount" style="text-align: center; margin-top: 5px;"></div>
    </div>

    <div id="questionContainer">
        <div class="question-name" id="question">[Question will be displayed here]</div>
        <textarea id="answer"></textarea>
        <div id="wordCountDisplay">Word Count: 0</div>
        <br>

        <div id="buttonContainer">
            <div>
                <button class="btn btn-warning" id="backButton" onclick="previousQuestion()" style="display: none;">Back</button>
                <button class="btn btn-primary" id="nextButton" onclick="nextQuestion()">Next</button>
            </div>
            <div>
                <button class="btn btn-warning" id="skipButton" onclick="skipQuestion()">Skip</button>
                <button class="btn btn-success" id="finishButton" onclick="finishTest()">Save My Responses</button>
                <button class="btn btn-success" id="submitButton" onclick="submitAnswers()" style="display: none;">Submit</button>
            </div>
        </div>
    </div>

    <!-- Finish Modal -->
    <div id="finishTestModal">
        <div>
            <p id="modalContent">You have unattempted questions. Do You Want to Exit?</p>
            <button class="btn btn-warning" onclick="saveResponse(true)">Yes, Exit</button>
            <button class="btn btn-success" onclick="closeModal()">Close</button>
        </div>
    </div>

    <!-- Submit Modal -->
    <div id="submitTestModal">
        <div>
            <p id="modalContent1">You have attempted X questions and skipped Y questions.</p>
            <button class="btn btn-success" onclick="confirmSubmit()">Yes, Submit</button>
            <button class="btn btn-danger" onclick="closeModal1()">Cancel</button>
        </div>
    </div>
</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
let currentQuestionIndex = 0;
let testQuestions = <?php echo json_encode($test_questions); ?>;

// ✅ USER_RESPONSE IS JSON STRING
let user_test_details = <?php echo isset($user_test_details[0]['USER_RESPONSE']) ? json_encode($user_test_details[0]['USER_RESPONSE']) : "null"; ?>;

let responses = [];
let editor;


// ✅ CKEditor init
function initializeCKEditor() {
    CKEDITOR.replace('answer', {
        on: {
            instanceReady: function(evt) {
                editor = evt.editor;

                // ✅ Attach word count listener
                editor.on('change', function() {
                    var wordCount = countWords(editor.getData());
                    document.getElementById('wordCountDisplay').innerText = 'Word Count: ' + wordCount;
                });

                // ✅ ✅ ✅ IMPORTANT: Run Resume logic only AFTER editor is ready
                preloadSavedResponses();
                moveToFirstUnanswered();
                displayCurrentQuestion();
                updateProgressBar();
            }
        }
    });
}


// ✅ Resume Logic (FIXED)
function preloadSavedResponses() {
    if (!user_test_details) return;

    let savedResponses = [];

    try {
        savedResponses = JSON.parse(user_test_details); // ✅ parse JSON string
    } catch (e) {
        console.error("❌ Invalid USER_RESPONSE JSON", e);
        return;
    }

    responses = []; // reset in case multiple calls

    savedResponses.forEach(sr => {
        let seqIndex = sr.questionId;
        let savedQuestionId = sr.QUESTION_ID || (sr.question ? sr.question.QUESTION_ID : null);

        let finalIndex = -1;

        // ✅ primary: sequence match
        if (seqIndex !== undefined && testQuestions[seqIndex]) {
            if (String(testQuestions[seqIndex].QUESTION_ID) === String(savedQuestionId)) {
                finalIndex = seqIndex;
            }
        }

        // ✅ fallback: QUESTION_ID match
        if (finalIndex === -1 && savedQuestionId) {
            finalIndex = testQuestions.findIndex(q => String(q.QUESTION_ID) === String(savedQuestionId));
        }

        if (finalIndex !== -1) {
            responses.push({
                questionId: finalIndex,
                QUESTION_ID: testQuestions[finalIndex].QUESTION_ID,
                question: testQuestions[finalIndex],
                answer: sr.answer || ""
            });
        }
    });

    console.log("✅ Resume Loaded Responses:", responses);
}


// ✅ Move to first unanswered
function moveToFirstUnanswered() {
    for (let i = 0; i < testQuestions.length; i++) {
        let response = responses.find(r => r.questionId === i);
        if (!response || response.answer.trim() === '') {
            currentQuestionIndex = i;
            break;
        }
    }
}


// ✅ Display Question + Fill Answer
function displayCurrentQuestion() {
    if (!editor) return; // extra safety

    let currentQuestion = testQuestions[currentQuestionIndex];

    document.getElementById('question').innerText =
        "Question " + (currentQuestionIndex + 1) + ": " + currentQuestion.QUESTION_NAME;

    let existingResponse = responses.find(r => r.questionId === currentQuestionIndex);

    if (existingResponse && existingResponse.answer) {
        editor.setData(existingResponse.answer);
    } else {
        editor.setData('');
    }

    document.getElementById('backButton').style.display = currentQuestionIndex > 0 ? "block" : "none";
    updateProgressBar();
}


// ✅ Word Count
function countWords(text) {
    return text.replace(/<[^>]*>/g, '').trim().split(/\s+/).filter(word => word.length > 0).length;
}


// ✅ Save Response
function saveResponse(is_nxt) {
    let answer = editor.getData();
    let currentQuestion = testQuestions[currentQuestionIndex];

    let existingIndex = responses.findIndex(r => r.questionId === currentQuestionIndex);

    if (existingIndex !== -1) {
        responses[existingIndex].answer = answer;
        responses[existingIndex].QUESTION_ID = currentQuestion.QUESTION_ID;
    } else {
        responses.push({
            questionId: currentQuestionIndex,
            QUESTION_ID: currentQuestion.QUESTION_ID,
            question: currentQuestion,
            answer: answer
        });
    }

    if (is_nxt) {
        var test_id = window.location.href.split('/').pop();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('/save-test-response')?>",
            contentType: "application/json",
            data: JSON.stringify({
                responses: responses,
                test_id: test_id,
                is_submitted: false
            }),
            success: function(res) {
                window.location.href = '<?php echo base_url('my-test-thankyou')?>';
            },
            error: function() {
                console.error("Error saving response");
            }
        });
    }
}


// ✅ Next
function nextQuestion() {
    saveResponse(false);

    let answerText = editor.getData().trim();
    if (answerText === '') {
        alert("Please write an answer before proceeding.");
        return;
    }

    if (currentQuestionIndex < testQuestions.length - 1) {
        currentQuestionIndex++;
        displayCurrentQuestion();
    } else {
        document.getElementById('nextButton').style.display = 'none';
        document.getElementById('submitButton').style.display = 'block';
    }
}


// ✅ Previous
function previousQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        displayCurrentQuestion();
    }
}


// ✅ Skip
function skipQuestion() {
    let currentQuestion = testQuestions[currentQuestionIndex];

    let existing = responses.find(r => r.questionId === currentQuestionIndex);
    if (!existing) {
        responses.push({
            questionId: currentQuestionIndex,
            QUESTION_ID: currentQuestion.QUESTION_ID,
            question: currentQuestion,
            answer: ''
        });
    }

    if (currentQuestionIndex < testQuestions.length - 1) {
        currentQuestionIndex++;
        displayCurrentQuestion();
    }
}


// ✅ Progress bar
function calculateAttemptedQuestions() {
    let attempted = 0;
    testQuestions.forEach((q, i) => {
        let r = responses.find(res => res.questionId === i);
        if (r && r.answer.trim() !== '') attempted++;
    });
    return attempted;
}

function updateProgressBar() {
    let total = testQuestions.length;
    let attempted = calculateAttemptedQuestions();
    let progress = (attempted / total) * 100;

    document.getElementById('progressBar').style.width = progress + "%";
    document.getElementById('questionCount').innerText = `Question ${currentQuestionIndex + 1} of ${total}`;
}


// ✅ Finish
function finishTest() {
    let unattempted = testQuestions.length - calculateAttemptedQuestions();

    if (unattempted > 0) {
        document.getElementById('modalContent').innerText =
            "You have " + unattempted + " unattempted questions. Exit & Save?";
        document.getElementById('finishTestModal').style.display = "block";
    } else {
        submitAnswers();
    }
}


// ✅ Submit modal
function submitAnswers() {
    let attempted = calculateAttemptedQuestions();
    let skipped = testQuestions.length - attempted;

    document.getElementById('modalContent1').innerText =
        `You have attempted ${attempted} and skipped ${skipped}. Submit test?`;

    document.getElementById('submitTestModal').style.display = "block";
}


// ✅ Confirm Submit
function confirmSubmit() {
    document.getElementById('submitTestModal').style.display = "none";

    var test_id = window.location.href.split('/').pop();

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('/save-test-response')?>",
        contentType: "application/json",
        data: JSON.stringify({
            responses: responses,
            test_id: test_id,
            is_submitted: true
        }),
        success: function(res) {
            window.location.href = '<?php echo base_url('my-test-thankyou')?>';
        },
        error: function() {
            console.error("Error submitting test");
        }
    });
}


// ✅ Close Modals
function closeModal() {
    document.getElementById('finishTestModal').style.display = "none";
}
function closeModal1() {
    document.getElementById('submitTestModal').style.display = "none";
}


// ✅ ONLY call editor init here
window.onload = function() {
    initializeCKEditor();
};
</script>


</body>
</html>
