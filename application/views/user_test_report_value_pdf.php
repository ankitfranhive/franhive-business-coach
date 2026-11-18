<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>body { font-family: DejaVu Sans, Arial, sans-serif; margin: 20px; }</style>
</head>
<body>
<?php $this->load->view('partials/value_test_result_table', [
    'result_rows'   => $result_rows ?? [],
    'student_name'  => $student_name ?? '',
    'result_date'   => $result_date ?? '',
]); ?>
</body>
</html>
