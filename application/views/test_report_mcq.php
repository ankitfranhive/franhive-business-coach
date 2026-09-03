<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<style>
    body { font-family: 'Arial', sans-serif; background-color: #eef1f7; color: #333; }
    .container { margin-top: 20px; }
    h1, h3, h4 { color: #FCB22B; text-align: center; }
    .user-details, .summary-cards, .question-table { text-align: center; margin-bottom: 30px; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
    .user-photo { width: 100px; height: 100px; border-radius: 50%; border: 3px solid #FCB22B; margin-bottom: 10px; }
    .summary-card { display: inline-block; width: 28%; background: #fff; padding: 15px; margin: 10px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .summary-card h4 { font-size: 1.1rem; color: #555; margin-bottom: 5px; }
    .summary-card p { font-size: 1.4rem; font-weight: bold; }
    .summary-card.correct { border-top: 4px solid #4CAF50; color: #4CAF50; }
    .summary-card.wrong { border-top: 4px solid #F44336; color: #F44336; }
    .summary-card.attempted { border-top: 4px solid #2196F3; color: #2196F3; }
    .report-table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
    .report-table th { background-color: #FCB22B; color: #fff; padding: 12px; }
    .report-table td { padding: 10px; text-align: center; border: 1px solid #dee2e6; }
    .report-table tr:nth-child(even) { background-color: #f9f9f9; }
    .correct-answer { color: #4CAF50; font-weight: bold; }
    .wrong-answer { color: #F44336; font-weight: bold; }
</style>
</head>
<body>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <!-- Page Header -->
            <div class="page-header">
                <h4>Test Report</h4>
            </div>

            <!-- User Details -->
            <div class="user-details">
                <img src="<?= $user_data['PROFILE_PICTURE'] ?: 'default-user.png' ?>" class="user-photo" alt="User Photo">
                <h3><?= $user_data['NAME'] ?></h3>
                <p><strong>Email:</strong> <?= $user_data['EMAIL'] ?></p>
                <p><strong>Test Name:</strong> <?= $user_data['TEST_NAME'] ?></p>
                <p><strong>Total Questions:</strong> <?= $user_data['TOTAL_QUESTIONS'] ?></p>
                <p><strong>Test Start Date:</strong> <?= $user_data['TEST_START_DATE'] ?></p>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card attempted">
                    <h4>Attempted</h4>
                    <p><?= $attempted ?></p>
                </div>
                <div class="summary-card correct">
                    <h4>Correct</h4>
                    <p><?= $correct ?></p>
                </div>
                <div class="summary-card wrong">
                    <h4>Wrong</h4>
                    <p><?= $wrong ?></p>
                </div>
            </div>

            <!-- Question Breakdown -->
            <div class="question-table">
                <h4>Question-wise Analysis</h4>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Q.No</th>
                            <th>Question</th>
                            <th>Your Answer</th>
                            <th>Correct Answer</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $qNo = 1;
                        foreach ($question_analysis as $qa):
                            $isCorrect = $qa['is_correct'];
                        ?>
                        <tr>
                            <td><?= $qNo++ ?></td>
                            <td><?= htmlspecialchars($qa['question_text']) ?></td>
                            <td class="<?= $isCorrect ? 'correct-answer' : 'wrong-answer' ?>">
                                <?= htmlspecialchars($qa['your_answer']) ?>
                            </td>
                            <td class="correct-answer"><?= htmlspecialchars($qa['correct_answer']) ?></td>
                            <td><?= $isCorrect ? '✔' : '✘' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</body>
</html>
