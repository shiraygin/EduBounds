<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to bottom right, #b8d6f5, #68c3a3);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.container {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 40px;
    width: 400px;
    max-width: 90%;
    text-align: center;
}

h2 {
    color: #333333;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #555555;
    font-size: 0.95em;
}

input[type="email"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1em;
}

button {
    background-color:rgb(77, 158, 168); /* Matches main site's purple button */
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    width: 100%;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color:rgb(148, 226, 224);
}

.back-link {
    display: block;
    margin-top: 20px;
    text-align: center;
    color:rgb(31, 149, 162);
    text-decoration: none;
    font-size: 0.9em;
}

.back-link:hover {
    text-decoration: underline;
}

.error-message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php if (isset($_SESSION['forgot_password_error'])): ?>
            <p class="error-message"><?php echo $_SESSION['forgot_password_error']; unset($_SESSION['forgot_password_error']); ?></p>
        <?php endif; ?>
        <form action="reset_password_process.php" method="post">
            <div class="form-group">
                <label for="email">Enter your email address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Submit</button>
        </form>
        <a href="signin.php" class="back-link">Back to Sign In</a>
    </div>
</body>
</html>