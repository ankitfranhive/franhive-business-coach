<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Link Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/core.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/style.css'); ?>">
    <style>
        body { background: #f5f6fa; }
        .message-wrap {
            max-width: 700px;
            margin: 80px auto;
            padding: 20px;
        }
        .message-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="message-wrap">
        <div class="message-card">
            <h2 class="text-danger mb-20">Link Expired</h2>
            <p>This payment agreement link has expired.</p>
            <p>Please contact the sender and request a new form link.</p>
        </div>
    </div>
</body>
</html>