<?php
session_start();

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user_found = false;
    $user_table = '';
    $stmt = null; // تهيئة المتغير $stmt

    try {
        // البحث عن المستخدم في جدول admin
        $stmt = $conn->prepare("SELECT Admin_ID FROM admin WHERE Email = ?");
        if (!$stmt) {
            throw new Exception("Error preparing statement (admin): " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement (admin): " . $stmt->error);
        }
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $user_found = true;
            $user_table = 'admin';
        }
        $stmt->close();
        $stmt = null; // إعادة تعيين $stmt

        // البحث عن المستخدم في جدول tutor
        if (!$user_found) {
            $stmt = $conn->prepare("SELECT Tutor_ID FROM tutor WHERE Tutor_Email = ?");
            if (!$stmt) {
                throw new Exception("Error preparing statement (tutor): " . $conn->error);
            }
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement (tutor): " . $stmt->error);
            }
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $user_found = true;
                $user_table = 'tutor';
            }
            $stmt->close();
            $stmt = null; // إعادة تعيين $stmt
        }

        // البحث عن المستخدم في جدول student
        if (!$user_found) {
            $stmt = $conn->prepare("SELECT Student_ID FROM student WHERE Student_Email = ?");
            if (!$stmt) {
                throw new Exception("Error preparing statement (student): " . $conn->error);
            }
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement (student): " . $stmt->error);
            }
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $user_found = true;
                $user_table = 'student';
            }
            $stmt->close();
            $stmt = null; // إعادة تعيين $stmt
        }

        if ($user_found) {
            // توجيه المستخدم إلى صفحة إعادة تعيين كلمة المرور
            header("Location: reset_password.php?email=" . urlencode($email) . "&table=" . urlencode($user_table));
            exit();
        } else {
            $_SESSION['forgot_password_error'] = "لم يتم العثور على مستخدم بهذا البريد الإلكتروني.";
            header("Location: forgot_password.php");
            exit();
        }

    } catch (Exception $e) {
        // تسجيل الخطأ أو عرضه
        error_log("Error in reset_password_process.php: " . $e->getMessage());
        $_SESSION['forgot_password_error'] = "حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.";
        header("Location: forgot_password.php");
        exit();
    } finally {
        if ($stmt) {
            $stmt->close();
        }
        $conn->close();
    }

} else {
    // إذا تم الوصول إلى الصفحة مباشرة بدون طلب POST
    header("Location: signin.php"); // أو أي صفحة مناسبة أخرى
    exit();
}
?>