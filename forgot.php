<?php
declare(strict_types=1);
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/auth.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Generic response avoids revealing whether an account exists.
        $message = 'If an account exists for this email, follow the password reset instructions sent to it.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Forgot Password</title><link rel="stylesheet" href="assets/style.css">
<style>
.box{min-height:100vh;background:#eee;display:flex;align-items:center;justify-content:center;padding:20px}
.card{width:440px;background:#eee;padding:40px;border-radius:25px;box-shadow:15px 15px 35px #d0d0d0,-15px -15px 35px #fff}
.card h1{text-align:center}.card p{text-align:center;color:#777;margin:12px 0 25px}.card input{width:100%;height:52px;border:0;border-radius:12px;background:#eee;padding:0 18px;box-shadow:inset 4px 4px 9px #d5d5d5,inset -4px -4px 9px #fff;outline:none;margin-bottom:15px}.card button{width:100%;height:52px;border:0;border-radius:12px;background:#eee;font-weight:bold;box-shadow:6px 6px 12px #d5d5d5,-6px -6px 12px #fff;cursor:pointer}.card a{display:block;text-align:center;margin-top:20px;color:#b33a44}
</style>
</head>
<body>
<div class="box"><div class="card">
<h1>Forgot Password?</h1>
<p>Enter your email to request a password reset.</p>
<?php if($error): ?><div class="msg error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<?php if($message): ?><div class="msg success"><?=htmlspecialchars($message)?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token())?>">
<input type="email" name="email" placeholder="Email" required>
<button type="submit">SEND RESET REQUEST</button>
</form>
<a href="index.php">← Back to Login</a>
</div></div>
</body>
</html>
