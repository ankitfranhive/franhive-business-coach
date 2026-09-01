<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Test Management System</title>
</head>
<body>

<div class="container mt-5">
    <h2>Add New Test</h2>
    <form id="addTestForm">
        <div class="form-group">
            <label for="testName">Test Name:</label>
            <input type="text" class="form-control" id="testName" required>
        </div>
        <div class="form-group">
            <label for="duration">Total Duration (minutes):</label>
            <input type="number" class="form-control" id="duration" required>
        </div>
        <div class="form-group">
            <label for="startDate">Start Date:</label>
            <input type="date" class="form-control" id="startDate" required>
        </div>
        <div class="form-group">
            <label for="totalQuestions">Total Questions:</label>
            <input type="number" class="form-control" id="totalQuestions" required>
        </div>
        <button type="button" class="btn btn-primary" id="addTestBtn">Add Test</button>
    </form>

    <div id="addQuestionForm" class="mt-4" style="display: none;">
        <h2>Add New Question</h2>
        <form>
            <div class="form-group">
                <label for="questionTitle">Question Title:</label>
                <input type="text" class="form-control" id="questionTitle" required>
            </div>
            <div class="form-group">
                <label for="questionMarks">Question Marks:</label>
                <input type="number" class="form-control" id="questionMarks" required>
            </div>
            <!-- Add more question details fields as needed -->

            <button type="button" class="btn btn-success" id="addQuestionBtn">Add Question</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>


$(document).ready(function() {
    $("#addTestBtn").click(function() {
        $("#addTestForm").hide();
        $("#addQuestionForm").show();
    });

    $("#addQuestionBtn").click(function() {
        // Handle adding question logic here

        // For demo purposes, let's go back to the add test form
        $("#addTestForm").show();
        $("#addQuestionForm").hide();
    });
});


</script>
</body>
</html>


