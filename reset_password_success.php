<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(to bottom, #b8d6f5, #68c3a3); /* ��� ���� ������� */
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        h2 {
            color: #000; /* ��� ��� ������ ������� (����) */
            margin-bottom: 20px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .login-link {
            color:rgb(39, 176, 169); /* ��� ��� ������� (������) */
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Successful</h2>
        <?php if (isset($_SESSION['reset_password_success'])): ?>
            <p><?php echo $_SESSION['reset_password_success']; unset($_SESSION['reset_password_success']); ?></p>
        <?php else: ?>
            <p>Your password has been reset successfully. You can now <a href="signin.php" class="login-link">sign in</a> with your new password.</p>
        <?php endif; ?>
    </div>
</body>
</html>