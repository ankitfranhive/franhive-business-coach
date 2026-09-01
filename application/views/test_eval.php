<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<style>
    body { font-family: Arial, sans-serif; background:#f4f6f9; }
    h4, h5 { color:#FCB22B; font-weight:700; }
    .card { border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.05); margin-bottom:18px; border:0; }
    .card-header { background:#fff; border-bottom:1px solid #eee; }
    .score-summary { background:#fff; border-radius:10px; padding:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
    .btn-warning { background:#FCB22B; border-color:#FCB22B; }
    .btn-warning:hover { background:#e0a21e; border-color:#e0a21e; }
    .marks-input input { max-width:140px; }
    .text-muted-small { color:#6c757d; font-size:.9rem; }
</style>

<?php
// ✅ Helper function should be outside loop
function htmlToTextWithLists($html) {
    $html = preg_replace('/<li[^>]*>\s*/i', "- ", $html);
    $html = preg_replace('/<\/li>/i', "\n", $html);

    $html = preg_replace('/<(\/)?p[^>]*>/i', "\n", $html);
    $html = preg_replace('/<br\s*\/?>/i', "\n", $html);

    $text = strip_tags($html);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace("/\n{2,}/", "\n\n", $text);

    return trim($text);
}
?>

<body>
<div class="main-container">
<div class="pd-ltr-20 xs-pd-20-10">
<div class="min-height-200px">

    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4>User Test Response</h4>
            </div>
            <div class="col-md-6 text-right">
                <a class="btn btn-warning" href="<?= base_url('/test-reports'); ?>">Back to all reports</a>
            </div>
        </div>
    </div>

    <?php
    $responses      = json_decode($user_response['USER_RESPONSE'], true);
    $questionsById  = $questions_by_id ?? [];
    $orderIds       = $question_ids_order ?? [];
    $response_id    = $this->uri->segment(2);
    $totalTestMarks = isset($total_marks) ? (float)$total_marks : 0;

    if (empty($questionsById) && !empty($questions)) {
        foreach ($questions as $q) {
            $questionsById[(int)$q['QUESTION_ID']] = $q;
        }
    }
    ?>

    <!-- Test Summary -->
    <div class="score-summary mb-3">
        <div class="d-flex justify-content-between">
            <div><strong>Total Questions:</strong> <?= (int)($total_questions ?? 0) ?></div>
            <div><strong>Total Marks:</strong> <span id="total-test-marks"><?= $totalTestMarks ?></span></div>
        </div>
        <div class="mt-2"><strong id="total-marks-display">User Score: 0</strong></div>
    </div>

    <?php foreach ($responses as $idx => $response): 
        
        $qid = 0;
        if (!empty($response['question']['QUESTION_ID'])) {
            $qid = (int)$response['question']['QUESTION_ID'];
        } elseif (!empty($response['QUESTION_ID'])) {
            $qid = (int)$response['QUESTION_ID'];
        }

        $row      = ($qid && isset($questionsById[$qid])) ? $questionsById[$qid] : null;
        $qName    = $row['QUESTION_NAME'] ?? ($response['question']['QUESTION_NAME'] ?? 'Question');
        $maxMarks = isset($row['QUESTION_MARKS']) ? (float)$row['QUESTION_MARKS'] : 0;
    ?>

        <div class="card question-card" data-qid="<?= $qid ?>" data-max="<?= $maxMarks ?>">
            <div class="card-header">
                <h5 class="mb-0"><?= htmlspecialchars($qName, ENT_QUOTES, 'UTF-8') ?></h5>
            </div>

            <div class="card-body">
                <div class="row align-items-start">

                    <!-- ✅ Answer -->
                    <div class="col-md-7 mb-2">
                        <div class="text-muted-small mb-1"><strong>Answer:</strong></div>

                        <div style="white-space: pre-line;">
                            <?= isset($response['answer']) 
                                ? htmlspecialchars(htmlToTextWithLists($response['answer']), ENT_QUOTES, 'UTF-8') 
                                : '' ?>
                        </div>
                    </div>

                    <!-- ✅ Marks Input -->
                    <div class="col-md-5 marks-input">
                        <label class="text-muted-small mb-1">
                            <strong>Marks Obtained</strong> (max <?= rtrim(rtrim(number_format($maxMarks,2,'.',''), '0'), '.') ?>)
                        </label>
                        <input 
                            class="form-control mark-field"
                            type="number"
                            name="question_marks[]"
                            min="0"
                            step="1"
                            max="<?= $maxMarks ?>"
                            oninput="validateMarks(this)"
                        />
                        <input type="hidden" class="question-id" name="question_ids[]" value="<?= $qid ?>">
                        <small class="text-muted">Question Marks: <?= rtrim(rtrim(number_format($maxMarks,2,'.',''), '0'), '.') ?></small>
                    </div>

                </div> <!-- ✅ row end -->
            </div> <!-- ✅ card-body end -->
        </div>

    <?php endforeach; ?>

    <form id="marksForm" class="mt-2">
        <button type="button" class="btn btn-warning" onclick="submitMarks()">Submit Marks</button>
    </form>

</div>
</div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
function clamp(val, min, max) {
    if (isNaN(val)) return 0;
    if (val < min) return min;
    if (val > max) return max;
    return val;
}

function validateMarks(input) {
    var card = input.closest('.question-card');
    var max = parseFloat(card.getAttribute('data-max')) || 0;
    var v = parseFloat(input.value) || 0;
    v = clamp(v, 0, max);
    input.value = v.toString();
    updateTotalMarks();
}

function updateTotalMarks() {
    var total = 0;
    document.querySelectorAll('.mark-field').forEach(function(inp){
        total += parseFloat(inp.value) || 0;
    });
    document.getElementById('total-marks-display').textContent = 'User Score: ' + total;
}

function submitMarks() {
    var marksData = [];
    document.querySelectorAll('.question-card').forEach(function(card){
        var qid = parseInt(card.getAttribute('data-qid')) || 0;
        var max = parseFloat(card.getAttribute('data-max')) || 0;
        var inp = card.querySelector('.mark-field');
        var v = parseFloat(inp.value) || 0;
        v = clamp(v, 0, max);
        inp.value = v;
        marksData.push({ questionId: qid, marksObtained: v, questionMax: max });
    });

    var totalMarks = 0;
    marksData.forEach(function(m){ totalMarks += m.marksObtained; });

    var totalTestMarks = parseFloat(document.getElementById('total-test-marks').textContent) || 0;
    var response_id = <?= (int)$response_id ?>;

    $.ajax({
        url: '<?= base_url('save-user-eval'); ?>',
        type: 'POST',
        data: {
            marksData: marksData,
            totalMarks: totalMarks,
            totalTestMarks: totalTestMarks,
            response_id: response_id
        },
        success: function () {
            window.location.href = '<?= base_url('/test-reports'); ?>';
        },
        error: function (err) {
            console.error(err);
        }
    });
}
</script>

</body>
</html>
