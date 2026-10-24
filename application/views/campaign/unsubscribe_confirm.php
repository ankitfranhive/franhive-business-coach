<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unsubscribed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width:480px;">
        <div class="card-body text-center p-4">
            <h1 class="h4 mb-3">You’re unsubscribed</h1>
            <p class="text-muted mb-0">
                <?php if (!empty($email)): ?>
                    <strong><?= html_escape($email) ?></strong> will no longer receive campaign emails from this portal.
                <?php else: ?>
                    You will no longer receive campaign emails from this portal.
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
</body>
</html>
