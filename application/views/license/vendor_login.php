<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sign in</title>
<style>
body{margin:0;background:#111;color:#eee;font-family:sans-serif;}
form{max-width:280px;margin:20vh auto;padding:24px;background:#1c1c1c;border:1px solid #333;}
input{width:100%;box-sizing:border-box;margin:0 0 10px;padding:8px;background:#111;border:1px solid #444;color:#eee;}
button{width:100%;padding:8px;background:#333;color:#eee;border:0;cursor:pointer;}
</style>
</head>
<body>
<form method="post" action="<?= htmlspecialchars(base_url($slug . '/auth')); ?>">
<input type="email" name="email" autocomplete="username" required>
<input type="password" name="password" autocomplete="current-password" required>
<button type="submit">Continue</button>
</form>
</body>
</html>
