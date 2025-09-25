<?php
include 'db_connect.php'; // نتصل بقاعدة البيانات
session_start(); // نبدأ الجلسة للمستخدم

// نتأكد إذا المستخدم مسجل دخول، إذا لا نحوله لصفحة الأدمن
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: data1.php");
    exit();
}

// نتأكد إن الطلب جاي من الفورم (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // نتأكد إن الجدول والـ id موجودين في الطلب
    if (isset($_POST['table']) && isset($_POST['id'])) {
        $table = mysqli_real_escape_string($conn, $_POST['table']); // نحمي اسم الجدول
        $id = mysqli_real_escape_string($conn, $_POST['id']); // نحمي قيمة  id
        $sql = "";
        $set_values = array(); // مصفوفة بنجمع فيها التعديلات

        // نمر على كل قيمة جاية من الفورم ونجهزها للاستعلام
        foreach ($_POST as $key => $value) {
            if ($key != 'table' && $key != 'id') {
                // نضيف كل عمود مع قيمته بعد الحماية
                $set_values[] = mysqli_real_escape_string($conn, $key) . " = '" . mysqli_real_escape_string($conn, $value) . "'";
            }
        }

        // إذا فيه قيم بنحدثها
        if (!empty($set_values)) {
            $sql = "UPDATE " . $table . " SET " . implode(", ", $set_values); // نبني جملة التحديث
            $sql .= " WHERE "; // نكمل بجملة WHERE
            $id_column = ""; // نحدد اسم العمود حسب الجدول

            // نحدد العمود الأساسي حسب اسم الجدول
            switch ($table) {
                case 'admin':
                    $id_column = "Admin_ID";
                    break;
                case 'faq':
                    $id_column = "id";
                    break;
                case 'courses':
                    $id_column = "Course_ID";
                    break;
                case 'lessons':
                    $id_column = "Lesson_ID";
                    break;
                case 'purchases':
                    $id_column = "Purchase_ID";
                    break;
                case 'session':
                    $id_column = "Session_ID";
                    break;
                case 'student':
                    $id_column = "Student_ID";
                    break;
                case 'tutor':
                    $id_column = "Tutor_ID";
                    break;
                default:
                    // لو الجدول غير معروف نطبع رسالة خطأ
                    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>Error: Unknown table.</div><br>";
                    echo "<div style='text-align: center;'><a href='data1.php' style='background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Back to Admin Panel</a></div>";
                    exit();
            }

            // نكمل جملة WHERE بالعمود و id
            $sql .= $id_column . " = '" . $id . "'";

            // ننفذ الاستعلام
            if (mysqli_query($conn, $sql)) {
               //نجح
                echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; text-align: center;'>Record updated successfully in table: " . htmlspecialchars($table) . " with ID: " . htmlspecialchars($id) . ".</div><br>";
                echo "<div style='text-align: center;'><a href='data1.php' style='background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Back to Admin Panel</a></div>";
            } else {
                // حصل خطأ  
                echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>Error updating record: " . mysqli_error($conn) . "</div><br>";
                echo "<div style='text-align: center;'><a href='edit_record.php?id=" . htmlspecialchars($id) . "&table=" . htmlspecialchars($table) . "' style='background-color: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Try Again</a></div>";
            }
        } else {
            // ما فيه بيانات للتحديث
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>Error: No data to update.</div><br>";
            echo "<div style='text-align: center;'><a href='edit_record.php?id=" . htmlspecialchars($id) . "&table=" . htmlspecialchars($table) . "' style='background-color: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Go Back</a></div>";
        }
    } else {
        // ما تم إرسال الجدول أو  ID
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>Error: Table name or ID not provided.</div><br>";
        echo "<div style='text-align: center;'><a href='data1.php' style='background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Back to Admin Panel</a></div>";
    }

    // نقفل الاتصال بقاعدة البيانات
    mysqli_close($conn);
} else {
    // تم الوصول للصفحة بدون استخدام POST
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>This page cannot be accessed directly.</div><br>";
    echo "<div style='text-align: center;'><a href='data1.php' style='background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Back to Admin Panel</a></div>";
}
?>
