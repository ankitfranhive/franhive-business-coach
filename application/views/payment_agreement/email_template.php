<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Agreement Consent Form</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <p>Hi  <?= html_escape($client_name); ?>,</p>

    <p>Empower Your Destiny sent you a document. Click below to see the details:</p>

    <p>
       <a href="<?= $form_url; ?>" target="_blank"><?= $form_url; ?> View Document</a>
    </p>

    <p>Unable to see the document button?   <a href="<?= $form_url; ?>" target="_blank"><?= $form_url; ?> View Document</a></p>

   

    <p>Regards,<br>Empower Your Destiny</p>
</body>
</html>