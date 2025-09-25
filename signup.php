<?php
include 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}

if (!$conn) {
    die("<script>alert('Database connection failed!'); window.location.href='signup.php';</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(strip_tags(trim($_POST["Name"])));
    $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
    $number = trim($_POST["number"]);
    $password = $_POST["Password"];
    $confirm_password = $_POST["ConfirmPassword"];
    $account_type = $_POST["account_type"];
    $specialization = isset($_POST["specialization"]) ? htmlspecialchars(strip_tags(trim($_POST["specialization"]))) : "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//valdiotion
        $_SESSION['error'] = "Invalid email!";
        header("Location: signup.php");
        exit();
    }

    if (!ctype_digit($number) || strlen($number) < 10 || strlen($number) > 11) {//valdiation
        $_SESSION['error'] = "Invalid phone number! Must be digits only.";
        header("Location: signup.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: signup.php");
        exit();
    }
    
    // Password restrictions
    $password_regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/';
    
    // Validate password using regex
    if (!preg_match($password_regex, $password)) {
        $_SESSION['error'] = "Password must be at least 8 characters long, contain at least one number, one letter, and one special character (e.g., @, #, $, etc.).";
        header("Location: signup.php");
        exit();
    }
    

    if ($account_type === "tutor" && empty($specialization)) {
        $_SESSION['error'] = "Please enter your specialization.";
        header("Location: signup.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);


    if ($account_type === "student") {
        $table = "student";
        $insert_query = "INSERT INTO $table (Student_Name, Student_Email, Student_Number, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssss", $name, $email, $number, $hashed_password);
    } elseif ($account_type === "tutor") {
        $table = "tutor";
        $insert_query = "INSERT INTO $table (Tutor_Name, Tutor_Email, Tutor_Number, Tutor_Password, Specialization) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssss", $name, $email, $number, $hashed_password, $specialization);
    } else {
        $_SESSION['error'] = "Invalid account type!";//valdition
        header("Location: signup.php");
        exit();
    }

    // Check duplicates across both tables
    $check_query = $conn->prepare("SELECT * FROM student WHERE Student_Email = ? OR Student_Number = ?");
    $check_query->bind_param("ss", $email, $number);
    $check_query->execute();
    $result1 = $check_query->get_result();

    $check_query = $conn->prepare("SELECT * FROM tutor WHERE Tutor_Email = ? OR Tutor_Number = ?");
    $check_query->bind_param("ss", $email, $number);
    $check_query->execute();
    $result2 = $check_query->get_result();

    if ($result1->num_rows > 0 || $result2->num_rows > 0) {
        $_SESSION['error'] = "Email or phone number already exists!";
        header("Location: signup.php");
        exit();
    }

    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully!'); window.location.href='HOMEPAGE.php';</script>";
    } else {
        $_SESSION['error'] = "Error during registration!";
        header("Location: signup.php");
        exit();
    }

    $check_query->close();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Account</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to bottom, #8dcff5, #68c3a3);
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
.container {
    display: flex;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 600px;
}
.left, .right {
    padding: 40px;
    width: 50%;
}
.left h2, .right h2 {
    margin: 0 0 20px;
}
.icon-container {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
}
.icon {
    font-size: 24px;
    color: #6a11cb;
}
input, select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}
button {
    width: 100%;
    padding: 10px;
    background: #6a11cb;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
button:hover {
    background: #2575fc;
}
</style>
</head>
<body>
<div class="container">
    <div class="left">
        <h2>Welcome Back</h2>
        <p>To keep connected with us, please sign in with your personal information.</p>
        <button onclick="location.href='signin.php'">Sign In</button>
        <p></p>
        <button onclick="location.href='HOMEPAGE.php'">Back</button>
    </div>

    <div class="right">
        <h2>Create Account</h2>
        <div class="icon-container">
            <i class="fas fa-envelope icon"></i>
            <i class="fab fa-instagram icon"></i>
            <i class="fab fa-facebook icon"></i>
        </div>
        <p>or use email for registration</p>
        <form method="POST" action="signup.php">
            <input type="text" name="Name" placeholder="Full Name" required>
            <input type="email" name="Email" placeholder="Email" required>
            <input type="text" name="number" placeholder="Phone Number" required>
            <input type="password" name="Password" placeholder="Password" required>
            <input type="password" name="ConfirmPassword" placeholder="Confirm Password" required>

            <select name="account_type" id="accountTypeSelect" required>
                <option value="">Select Account Type</option>
                <option value="student">Student</option>
                <option value="tutor">Tutor</option>
            </select>

            <input type="text" name="specialization" id="specializationField" placeholder="Specialization (for tutors only)" style="display:none;">

            <button type="submit">Create Account</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const accountTypeSelect = document.getElementById('accountTypeSelect');
    const specializationField = document.getElementById('specializationField');

    function toggleSpecializationField() {
        if (accountTypeSelect.value === 'tutor') {
            specializationField.style.display = 'block';
            specializationField.required = true;
        } else {
            specializationField.style.display = 'none';
            specializationField.required = false;
        }
    }

    accountTypeSelect.addEventListener('change', toggleSpecializationField);
    toggleSpecializationField(); // Run on page load
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>
