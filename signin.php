<?php
session_start();

include 'db_connect.php';

$userEmail = "";  // Initialize email variable

if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_type'])) {
    $userId = $_COOKIE['user_id'];
    $userType = $_COOKIE['user_type'];

    // Fetch email based on user type
    if ($userType == 'admin') {
        $stmt = $conn->prepare("SELECT Email FROM admin WHERE Admin_ID = ?");
    } elseif ($userType == 'tutor') {
        $stmt = $conn->prepare("SELECT Tutor_Email FROM tutor WHERE Tutor_ID = ?");
    } elseif ($userType == 'student') {
        $stmt = $conn->prepare("SELECT Student_Email FROM student WHERE Student_ID = ?");
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($userEmail);
    $stmt->fetch();
    $stmt->close();
}



// دالة للتحقق من بيانات المستخدم (بالمقارنة المباشرة لكلمة المرور)
// دالة للتحقق من بيانات المستخدم (بالمقارنة الآمنة لكلمة المرور باستخدام password_verify)
function verifyUser($conn, $table, $email, $password, $emailField = 'email', $passwordField = 'Password') {
    // Prepare the query to find the user by email
    $stmt = $conn->prepare("SELECT * FROM $table WHERE $emailField = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user is found with the provided email
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the password is correct using password_verify
        if (isset($user[$passwordField]) && 
        (password_verify($password, $user[$passwordField]) || $password === $user[$passwordField])) {
            $stmt->close();
            return $user;  // Return user data on match
        }
    }

    $stmt->close();
    return false; // Return false if no match or password is incorrect
}

$login_error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // التحقق من المستخدم في جدول admin
    if ($adminUser = verifyUser($conn, 'admin', $email, $password, 'Email', 'Password')) {
        $_SESSION['user_type'] = 'admin';
        $_SESSION['user_id'] = $adminUser['Admin_ID'];
        $_SESSION['username'] = $adminUser['Admin_Name'];
        $_SESSION['signedIN'] = true;

        // Set cookie to remember user for 1 week (604800 seconds)
        setcookie('user_id', $adminUser['Admin_ID'], time() + 604800, "/");  // 1 week expiration
        setcookie('user_type', 'admin', time() + 604800, "/");
        
        header("Location: HOMEPAGE.php");
        exit();
    }

    // التحقق من المستخدم في جدول tutor
    if ($tutorUser = verifyUser($conn, 'tutor', $email, $password, 'Tutor_Email', 'Tutor_Password')) {
        $_SESSION['user_type'] = 'tutor';
        $_SESSION['user_id'] = $tutorUser['Tutor_ID'];
        $_SESSION['username'] = $tutorUser['Tutor_Name'];
        $_SESSION['signedIN'] = true;

        // Set cookie to remember user for 1 week (604800 seconds)
        setcookie('user_id', $tutorUser['Tutor_ID'], time() + 604800, "/");
        setcookie('user_type', 'tutor', time() + 604800, "/");

        header("Location: HOMEPAGE.php");
        exit();
    }

    // التحقق من المستخدم في جدول student
    if ($studentUser = verifyUser($conn, 'student', $email, $password, 'Student_Email', 'password')) {
        $_SESSION['user_type'] = 'student';
        $_SESSION['user_id'] = $studentUser['Student_ID'];
        $_SESSION['username'] = $studentUser['Student_Name'];
        $_SESSION['signedIN'] = true;

        // Set cookie to remember user for 1 week (604800 seconds)
        setcookie('user_id', $studentUser['Student_ID'], time() + 604800, "/");
        setcookie('user_type', 'student', time() + 604800, "/");

        header("Location: HOMEPAGE.php");
        exit();
    }

    $login_error = "Invalid email or password.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up</title>
    <style>
       body {
    font-family: 'Poppins', sans-serif; /* Changed font family */
    background: linear-gradient(to bottom, #8dcff5, #68c3a3); /* Linear gradient background */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.container {
    display: flex;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 600px; /* Adjusted width */
}

.signin-section {
    flex: 1;
    padding: 40px;
    width: 300px; /* Adjusted width */
}

.signup-section {
    flex: 1;
    padding: 40px;
    text-align: center;
    color: #757575;
    background-color: #f7f7f7; /* Changed background color */
    width: 300px; /* Adjusted width */
}

h2 {
    color: #000;
    margin-bottom: 20px;
    text-align: center;
}

.separator {
    color: #bdbdbd;
    margin-bottom: 15px;
    text-align: center;
}

.error-message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
    font-style: normal;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #757575;
    font-size: 0.9em;
    text-align: left;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1em;
}

button {
    background-color: #6a11cb; /* Changed button color */
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    width: 100%;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2575fc; /* Changed hover color */
}

.forgot-password-container {
    display: flex;
    justify-content: flex-start;
    margin-top: 10px;
    margin-bottom: 10px;
}

.forgot-password {
    color: #2575fc; /* Changed link color */
    font-size: 0.9em;
    text-decoration: none;
    direction: ltr;
}

.forgot-password:hover {
    text-decoration: underline;
}

h3 {
    color: #000;
    margin-bottom: 10px;
}

p {
    line-height: 1.6;
    margin-bottom: 20px;
}

.signup-button {
    background-color: #2575fc; /* Changed button color */
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease;
}

.signup-button:hover {
    background-color: #1e50a2; /* Changed hover color */
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

.social-icon {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-size: 18px;
}

.social-icon.purple {
    background-color: #6a11cb; /* Changed icon background color */
}

.footer {
    width: 100%;
    background: transparent;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
    color: black;
    position: absolute;
    bottom: 0;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="signin-section">
            <h2>Sign in</h2>
            <p class="separator">or use email for registration</p>
            <?php if (!empty($login_error)): ?>
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <form method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $userEmail; ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
    </div>
    <div class="forgot-password-container">
        <a href="forgot_password.php" class="forgot-password" style="color: #9c27b0;">Forgot your password?</a>
    </div>
    <button type="submit">Sign In</button>
</form>

        </div>
        <div class="signup-section">
            <h3>Hello, Friend</h3>
            <p>Enter your personal details and start your journey with us.</p>
            <a href="signup.php"><button class="signup-button">Sign Up</button></a>
        </div>
    </div>
</body>
</html>