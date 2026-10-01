<?php
declare(strict_types=1);
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$oldEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $oldEmail = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($oldEmail, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Please enter a valid email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$oldEmail]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="switcher">
    <button class="gold-btn" onclick="showDesign('gold')">GOLD DESIGN</button>
    <button class="white-btn" onclick="showDesign('white')">WHITE DESIGN</button>
</div>

<section id="goldPage" class="page active">
    <div class="bubble b1"></div><div class="bubble b2"></div><div class="bubble b3"></div>
    <div class="bubble b4"></div><div class="bubble b5"></div><div class="bubble b6"></div>

    <div class="gold-card">
        <div class="gold-content">
            <h1>Login</h1>

            <?php if ($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="post" action="index.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

                <div class="input-wrap">
                    <span class="input-icon">✉</span>
                    <input class="gold-input" type="email" name="email"
                           value="<?= htmlspecialchars($oldEmail) ?>"
                           placeholder="Email" autocomplete="email" required>
                </div>

                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input class="gold-input" id="goldPassword" type="password"
                           name="password" placeholder="Password"
                           autocomplete="current-password" required>
                    <button class="eye" type="button" onclick="togglePassword('goldPassword',this)">👁</button>
                </div>

                <a class="gold-forgot" href="forgot.php">Forgot Password?</a>
                <button class="gold-login" type="submit">Login</button>
            </form>

            <div class="msg">Don't have an account? <a href="register.php" style="color:white">Sign up</a></div>
        </div>
    </div>
</section>

<section id="whitePage" class="page">
    <div class="white-card">
        <div class="white-content">
            <h1>Login</h1>
            <p class="subtitle">Sign in to your account</p>

            <?php if ($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="post" action="index.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

                <div class="input-wrap">
                    <span class="input-icon">👤</span>
                    <input class="white-input" type="email" name="email"
                           value="<?= htmlspecialchars($oldEmail) ?>"
                           placeholder="Email" autocomplete="email" required>
                </div>

                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input class="white-input" id="whitePassword" type="password"
                           name="password" placeholder="Password"
                           autocomplete="current-password" required>
                    <button class="eye" type="button" onclick="togglePassword('whitePassword',this)">👁</button>
                </div>

                <div class="white-options">
                    <label class="remember">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a class="forgot" href="forgot.php">Forgot password?</a>
                </div>

                <button class="white-login" type="submit">SIGN IN</button>
            </form>

            <p class="signup">Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </div>
</section>

<script src="assets/app.js"></script>
</body>
</html>
