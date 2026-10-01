<?php
declare(strict_types=1);
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/auth.php';

if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid name and email.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must contain at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = 'An account with this email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
            $stmt->execute([$name, $email, $hash]);
            $success = 'Account created successfully. You can now log in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Create Account</title>
<link rel="stylesheet" href="assets/style.css">
<style>
.form-page{min-height:100vh;background:#eee;display:flex;align-items:center;justify-content:center;padding:30px}
.form-box{width:450px;background:#eee;border-radius:25px;padding:40px;box-shadow:15px 15px 35px #d0d0d0,-15px -15px 35px #fff}
.form-box h1{text-align:center;margin-bottom:10px}.form-box p{text-align:center;color:#777;margin-bottom:25px}
.form-box input{width:100%;height:52px;border:0;border-radius:12px;background:#eee;padding:0 18px;margin-bottom:15px;box-shadow:inset 4px 4px 9px #d5d5d5,inset -4px -4px 9px #fff;outline:none}
.form-box button{width:100%;height:52px;border:0;border-radius:12px;background:#eee;font-weight:bold;box-shadow:6px 6px 12px #d5d5d5,-6px -6px 12px #fff;cursor:pointer}
.form-box a{display:block;text-align:center;margin-top:20px;color:#b33a44}
</style>
</head>
<body>
<div class="form-page">
<div class="form-box">
<h1>Sign Up</h1>
<p>Create your account</p>
<?php if($error): ?><div class="msg error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<?php if($success): ?><div class="msg success"><?=htmlspecialchars($success)?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token())?>">
<input name="name" placeholder="Full name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password (minimum 6 characters)" required>
<input name="confirm_password" type="password" placeholder="Confirm password" required>
<button type="submit">CREATE ACCOUNT</button>
</form>
<a href="index.php">← Back to Login</a>
</div>
</div>
</body>
</html>
