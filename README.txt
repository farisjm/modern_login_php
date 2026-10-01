MODERN LOGIN - PHP + MySQL
=============================

Requirements:
- XAMPP (Apache + MySQL)
- PHP 8+
- MySQL/MariaDB

INSTALLATION
------------

1. Copy the "modern_login_php" folder into:
   C:\xampp\htdocs\

2. Start Apache and MySQL in XAMPP.

3. Open phpMyAdmin:
   http://localhost/phpmyadmin

4. Import:
   database.sql

5. Open:
   http://localhost/modern_login_php/

FILES
-----
index.php       Login page with both designs
register.php    User registration
forgot.php      Forgot-password request page
dashboard.php   Protected page after login
logout.php      Session logout
database.sql    MySQL database/table
config/db.php   Database connection
config/auth.php Session + CSRF helpers
assets/style.css
assets/app.js

DATABASE
--------
Default XAMPP MySQL settings:
Host: localhost
Database: login_system
Username: root
Password: empty

If your MySQL password is different, edit:
config/db.php

SECURITY
--------
- Passwords use password_hash()
- Login uses password_verify()
- PDO prepared statements are used
- Sessions are regenerated after login
- CSRF token is used for forms
- Dashboard requires login

NOTE
----
The forgot-password page currently demonstrates the request flow.
A production reset system should create a one-time expiring reset token
and send it through a properly configured mail provider.
