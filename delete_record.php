<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: data1.php"); // لو اليوزر ما سجل دخول بيرجعه لصفحة الدخول
    exit();
}

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']); //مهم عشان  SQL Injection (حمايه من هجومات ضد البيانات )
    $table = mysqli_real_escape_string($conn, $_GET['table']);  //مهم عشان  SQL Injection (حمايه من هجومات ضد البيانات )
    $success = false;
    $error_message = "";

    // عشان نحدد الجدول وال اي دي للحذف
    $sql = "";
    $id_column = ""; 
    //يدور عن ال اي دي المطلوب ويحذف
    switch ($table) {
        case 'admin':
            $sql = "DELETE FROM admin WHERE Admin_ID = ?";
            $id_column = "Admin_ID";
            break;
        case 'faq':
            $sql = "DELETE FROM faq WHERE id = ?";
            $id_column = "id";
            break;
        case 'courses':
            $sql = "DELETE FROM courses WHERE Course_ID = ?";
            $id_column = "Course_ID";
            break;
        case 'lessons':
            $sql = "DELETE FROM lessons WHERE Lesson_ID = ?";
            $id_column = "Lesson_ID";
            break;
        case 'purchases':
            $sql = "DELETE FROM purchases WHERE Purchase_ID = ?";
            $id_column = "Purchase_ID";
            break;
        case 'session':
            $sql = "DELETE FROM session WHERE Session_ID = ?";
            $id_column = "Session_ID";
            break;
        case 'student':
            $sql = "DELETE FROM student WHERE Student_ID = ?";
            $id_column = "Student_ID";
            break;
        case 'tutor':
            $sql = "DELETE FROM tutor WHERE Tutor_ID = ?";
            $id_column = "Tutor_ID";
            break;
        default:
            $error_message = "Unknown table";
            break;
    }

    if ($sql) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id); // استخدام "s" لأن الـ ID غالباً بيكون نصي (حتى لو كان رقم في الداتا بيس)
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $success = true;
                $message = "The record with ID: " . htmlspecialchars($id) . " has been deleted From the table " . htmlspecialchars($table) . " Successfully.";
            } else {
                $error_message = "No record with this ID was found in the table." . htmlspecialchars($table) . ".";
            }
        } else {
            $error_message = "An error occurred while deleting the record: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $error_message = "No record ID or table name was passed.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حذف سجل</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #f8d7da, #f5c6cb); 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            direction: rtl; 
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 500px;
        }
        .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .back-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
            display: inline-block;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <p class="success-message"><?php echo $message; ?></p>
            <a href="data1.php" class="back-button"> Return to data page </a>
        <?php elseif ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
            <a href="data1.php" class="back-button"> Return to data page </a>
        <?php else: ?>
            <p>The deletion process is being processed....</p>
            <a href="data1.php" class="back-button">Return to data page</a>
        <?php endif; ?>
    </div>
</body>
</html>