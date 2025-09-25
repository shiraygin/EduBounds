<?php
session_start();

// Enable error reporting for debugging (REMOVE IN PRODUCTION)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $table = $_POST['table'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['reset_password_error'] = "Passwords do not match.";
        header("Location: reset_password.php?email=" . urlencode($email) . "&table=" . urlencode($table));
        exit();
    }

    // حفظ كلمة المرور كنص عادي (بدون تشفير)
    // تشفير كلمة المرور باستخدام password_hash
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $email_field = '';
    $password_field = 'Password'; // Default password field
    $allowed_tables = ['admin', 'tutor', 'student']; // Define allowed table names

    if (!in_array($table, $allowed_tables)) {
        $_SESSION['reset_password_error'] = "Invalid table specified.";
        header("Location: reset_password.php?email=" . urlencode($email) . "&table=" . urlencode($table));
        exit();
    }

    if ($table === 'tutor') {
        $email_field = 'Tutor_Email';
        $password_field = 'Tutor_Password';
    } elseif ($table === 'student') {
        $email_field = 'Student_Email';
        $password_field = 'Password'; // تأكد من أن هذا الاسم مطابق لقاعدة البيانات
    } else {
        $email_field = 'Email'; // Default for admin table
    }

    // Prepare the SQL statement with proper parameter binding
    $sql = "UPDATE " . $table . " SET " . $password_field . " = ? WHERE " . $email_field . " = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $_SESSION['reset_password_success'] = "Your password has been reset successfully. You can now <a href='signin.php'>sign in</a> with your new password.";
            header("Location: reset_password_success.php");
            exit();
        } else {
            $_SESSION['reset_password_error'] = "Error updating password: " . $stmt->error;
            header("Location: reset_password.php?email=" . urlencode($email) . "&table=" . urlencode($table));
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['reset_password_error'] = "Error preparing SQL statement: " . $conn->error;
        header("Location: reset_password.php?email=" . urlencode($email) . "&table=" . urlencode($table));
        exit();
    }
} else {
    // If the page is accessed directly without a POST request
    header("Location: signin.php"); // Or any other appropriate page
    exit();
}

$conn->close();
?>