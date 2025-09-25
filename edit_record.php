<?php
include 'db_connect.php'; // äÊÕá ÈÞÇÚÏÉ ÇáÈíÇäÇÊ
session_start(); // äÈÏÃ ÌáÓÉ ááãÓÊÎÏã

include 'header.php'; // äÖíÝ ÇáåíÏÑ ááÕÝÍÉ
include 'footer.php'; // äÖíÝ ÇáÝæÊÑ ááÕÝÍÉ

// äÊÍÞÞ ÅÐÇ ÇáãÓÊÎÏã ãÓÌá ÏÎæá
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: data1.php"); // ÅÐÇ ãæ ãÓÌá¡ äÍæáå áÕÝÍÉ ÇáÃÏãä
    exit();
}

// äÔíß ÅÐÇ æÕáäÇ ID æÌÏæá ãä ÇáÑÇÈØ
if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']); // äÍãí ÇáÜ ID
    $table = mysqli_real_escape_string($conn, $_GET['table']); // äÍãí ÇÓã ÇáÌÏæá
    $record_data = null;
    $error_message = "";

    // ÈäÌåÒ ÇáÇÓÊÚáÇã ÍÓÈ ÇÓã ÇáÌÏæá Çááí ÌÇí
    $sql = "";
    $id_column = "";

    switch ($table) {
        case 'admin':
            $sql = "SELECT Admin_ID, Admin_Name, Email FROM admin WHERE Admin_ID = ?";
            $id_column = "Admin_ID";
            break;
        case 'faq':
            $sql = "SELECT id, question, answer FROM faq WHERE id = ?";
            $id_column = "id";
            break;
        case 'courses':
            $sql = "SELECT Course_ID, Course_Name, Description, image FROM courses WHERE Course_ID = ?";
            $id_column = "Course_ID";
            break;
        case 'lessons':
            $sql = "SELECT Lesson_ID, Lesson_Name, Description, File, Price, Course_ID, Tutor_ID, approved, admin_id FROM lessons WHERE Lesson_ID = ?";
            $id_column = "Lesson_ID";
            break;
        case 'purchases':
            $sql = "SELECT Purchase_ID, Course_ID, Lesson_ID, Student_ID, Price, Payment_ID, Purchase_Date FROM purchases WHERE Purchase_ID = ?";
            $id_column = "Purchase_ID";
            break;
        case 'session':
            $sql = "SELECT Session_ID, Lesson_ID, Tutor_ID, Course_ID, Session_DateTime, Rating, Comment, Student_ID FROM session WHERE Session_ID = ?";
            $id_column = "Session_ID";
            break;
        case 'student':
            $sql = "SELECT Student_ID, Student_Name, Student_Email, Student_Number FROM student WHERE Student_ID = ?";
            $id_column = "Student_ID";
            break;
        case 'tutor':
            $sql = "SELECT Tutor_ID, Tutor_Name, Tutor_Email, Tutor_Number, Specialization FROM tutor WHERE Tutor_ID = ?";
            $id_column = "Tutor_ID";
            break;
        default:
            $error_message = "Unknown table."; // ÅÐÇ ÇáÌÏæá ÛíÑ ãÚÑæÝ
            break;
    }

    // ÅÐÇ Ýíå ÇÓÊÚáÇã¡ ääÝÐå
    if ($sql) {
        $stmt = mysqli_prepare($conn, $sql); // äÌåÒ ÇáÇÓÊÚáÇã
        mysqli_stmt_bind_param($stmt, "s", $id); // äÍØ ÞíãÉ ÇáÜ ID
        mysqli_stmt_execute($stmt); // ääÝÐ ÇáÇÓÊÚáÇã
        $result = mysqli_stmt_get_result($stmt); // äÌíÈ ÇáäÊíÌÉ
        if ($result && mysqli_num_rows($result) > 0) {
            $record_data = mysqli_fetch_assoc($result); // äÎÒä ÇáÈíÇäÇÊ
        } else {
            $error_message = "Record not found with this ID in table " . htmlspecialchars($table) . "."; // ãÇ áÞíäÇ ÇáÓÌá
        }
        mysqli_stmt_close($stmt); // äÞÝá ÇáÇÓÊÚáÇã
    }
} else {
    $error_message = "Record ID or table name not provided."; // ãÇ æÕáäÇ ID Ãæ ÌÏæá
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #b8d6f5, #68c3a3);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            direction: ltr;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            text-align: left;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            text-align: left;
        }
        input[type="text"], input[type="email"], input[type="number"], textarea, select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 5px;
            direction: ltr;
            text-align: left;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .save-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin: 5px;
            font-size: 16px;
        }
        .back-button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin: 5px;
            font-size: 16px;
        }
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data</h2>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php elseif ($record_data): ?>
            <form method="post" action="update_record.php">
                <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php
                // Display input fields based on record data
                foreach ($record_data as $column => $value):
                    $label = str_replace('_', ' ', $column); // Basic label formatting
                    ?>
                    <div class="form-group">
                        <label for="<?php echo htmlspecialchars($column); ?>"><?php echo htmlspecialchars(ucwords($label)); ?>:</label>
                        <input type="text" id="<?php echo htmlspecialchars($column); ?>" name="<?php echo htmlspecialchars($column); ?>" value="<?php echo htmlspecialchars($value); ?>">
                    </div>
                    <?php
                endforeach;
                ?>
                <div class="actions">
                    <button type="submit" class="save-button">Save Changes</button>
                    <a href="data1.php" class="back-button">Back</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>