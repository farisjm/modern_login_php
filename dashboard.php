<?php
declare(strict_types=1);
require __DIR__ . '/config/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard</title><link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard">
<header class="topbar">
<strong>My Dashboard</strong>
<a class="logout" href="logout.php">Logout</a>
</header>
<div class="dash-box">
<h1>Welcome, <?=htmlspecialchars($_SESSION['user_name'])?> 👋</h1>
<p style="margin-top:12px">You are successfully logged in.</p>
<p style="margin-top:8px;color:#777"><?=htmlspecialchars($_SESSION['user_email'])?></p>
</div>
</body>
</html>
