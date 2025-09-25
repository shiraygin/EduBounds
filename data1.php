<?php
include 'db_connect.php';
session_start();

$error = "";
$correct_code = "db123";
$loggedin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$message = "";
$table = "";

//form submission for login
if (isset($_POST['login_submit'])) {
    $entered_code = $_POST['code'];
    if ($entered_code === $correct_code) {
        $_SESSION['loggedin'] = true;
        $loggedin = true;
        echo "<script>window.location.href = 'data1.php';</script>";

        exit();
    } else {
        $error = "The password you entered is incorrect.";
    }
}
//logout action
if (isset($_GET['logout'])) {
    session_destroy();
    $loggedin = false;
    echo "<script>window.location.href = 'data1.php';</script>";

    exit();
}
//chooses an action (button)
if ($loggedin && isset($_POST['action'])) {
    switch ($_POST['action']) {
        //admin code
      case 'showAdmin':
    $message = "Displaying Admin data...";
    //button+table
    $table = "<table><tr><td colspan='4' style='padding: 0;'><a href='add_admin.php' style='background-color: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; margin-bottom: 5px; width: calc(100% - 2px); display: block; text-align: center; text-decoration: none;'>Add</a></td></tr><tr><th>Admin_ID</th><th>Admin_Name</th><th>Email</th><th>Actions</th></tr>";
        //Fetch data from table
        if ($conn) {
        $sql = "SELECT Admin_ID, Admin_Name, Email FROM admin";
        // Loop in sql table rows
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($admin = mysqli_fetch_assoc($result)) {
                $table .= "<tr><td>" . htmlspecialchars($admin['Admin_ID']) . "</td><td>" . htmlspecialchars($admin['Admin_Name']) . "</td><td>" . htmlspecialchars($admin['Email']) .
                            "</td><td><a href='delete_record.php?id=" . htmlspecialchars($admin['Admin_ID']) . "&table=admin' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($admin['Admin_ID']) . "&table=admin' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>";
            }
        } else {
            $message = "There are no admin data to display.";
        }
        mysqli_free_result($result);
    }
    $table .= "</table>";
    break;
    //faq code
        case 'showFaq':
            $message = "Displaying FAQ data...";
                //button+table
            $table = "<table><tr><th>ID</th><th>Title</th><th>Content</th><th>Created At</th><th>Answers</th></tr>";
            //Fetch data from table
            if ($conn) {
                $sql = "SELECT id, title, content, created_at, answers FROM faq";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($faq = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($faq['id']) . "</td><td>" . htmlspecialchars($faq['title']) . "</td><td>" . htmlspecialchars($faq['content']) . "</td><td>" . htmlspecialchars($faq['created_at']) . "</td><td>" . htmlspecialchars($faq['answers']) . "</td></tr>";
                    }
                } else {
                    $message = "There are no FAQ data to display.";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;

            //course code
        case 'showCourses':
            $message = "Showing course data...";
                //button+table
            $table = "<table><tr><th>Course_ID</th><th>Course_Name</th><th>Description</th><th>Image</th><th>Actions</th></tr>";
            //Fetch data from table
            if ($conn) {
                $sql = "SELECT Course_ID, Course_Name, Description, image FROM courses";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($course = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($course['Course_ID']) . "</td><td>" . htmlspecialchars($course['Course_Name']) . "</td><td>" . htmlspecialchars($course['Description']) . "</td><td>" . htmlspecialchars($course['image']) .
                                    "</td><td><a href='delete_record.php?id=" . htmlspecialchars($course['Course_ID']) . "&table=courses' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($course['Course_ID']) . "&table=courses' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>"; }
                } else {
                    $message = "There are no course data to display.";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;

            //lessons code
        case 'showLessons':
            $message = "Showing lesson data...";
            //button+table
            $table = "<table><tr><th>Lesson_ID</th><th>Lesson_Name</th><th>Description</th><th>File</th><th>Price</th><th>Course_ID</th><th>Tutor_ID</th><th>Approved</th><th>Admin_ID</th><th>Actions</th></tr>";
            //Fetch data from table
            if ($conn) {
                $sql = "SELECT Lesson_ID, Lesson_Name, Description, File, Price, Course_ID, Tutor_ID, approved, admin_id FROM lessons";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($lesson = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($lesson['Lesson_ID']) . "</td><td>" . htmlspecialchars($lesson['Lesson_Name']) . "</td><td>" . htmlspecialchars($lesson['Description']) . "</td><td>" . htmlspecialchars($lesson['File']) . "</td><td>" . htmlspecialchars($lesson['Price']) . "</td><td>" . htmlspecialchars($lesson['Course_ID']) . "</td><td>" . htmlspecialchars($lesson['Tutor_ID']) . "</td><td>" . htmlspecialchars($lesson['approved']) . "</td><td>" . htmlspecialchars($lesson['admin_id']) .
                                    "</td><td><a href='delete_record.php?id=" . htmlspecialchars($lesson['Lesson_ID']) . "&table=lessons' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($lesson['Lesson_ID']) . "&table=lessons' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>"; }
                } else {
                    $message = "There are no lessons to display.";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;
            //purchase code
        case 'showPurchases':
            $message = "Displaying purchase data...";
            //button+table
            $table = "<table><tr><th>Purchase_ID</th><th>Course_ID</th><th>Lesson_ID</th><th>Student_ID</th><th>Price</th><th>Payment_ID</th><th>Purchase_Date</th><th>Actions</th></tr>";
           //Fetch data from table
            if ($conn) {
                $sql = "SELECT Purchase_ID, Course_ID, Lesson_ID, Student_ID, Price, Payment_ID, Purchase_Date FROM purchases";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($purchase = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($purchase['Purchase_ID']) . "</td><td>" . htmlspecialchars($purchase['Course_ID']) . "</td><td>" . htmlspecialchars($purchase['Lesson_ID']) . "</td><td>" . htmlspecialchars($purchase['Student_ID']) . "</td><td>" . htmlspecialchars($purchase['Price']) . "</td><td>" . htmlspecialchars($purchase['Payment_ID']) . "</td><td>" . htmlspecialchars($purchase['Purchase_Date']) .
                                    "</td><td><a href='delete_record.php?id=" . htmlspecialchars($purchase['Purchase_ID']) . "&table=purchases' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($purchase['Purchase_ID']) . "&table=purchases' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>"; }
                } else {
                    $message = "There is no purchase data to display.";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;
            //session code 
        case 'showSession':
            $message = "Displaying session data...";
            //button+table
            $table = "<table><tr><th>Session_ID</th><th>Lesson_ID</th><th>Tutor_ID</th><th>Course_ID</th><th>Session_DateTime</th><th>Rating</th><th>Comment</th><th>Student_ID</th><th>Actions</th></tr>";
           //Fetch data from table
            if ($conn) {
                $sql = "SELECT Session_ID, Lesson_ID, Tutor_ID, Course_ID, Session_DateTime, Rating, Comment, Student_ID FROM session";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($session = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($session['Session_ID']) . "</td><td>" . htmlspecialchars($session['Lesson_ID']) . "</td><td>" . htmlspecialchars($session['Tutor_ID']) . "</td><td>" . htmlspecialchars($session['Course_ID']) . "</td><td>" . htmlspecialchars($session['Session_DateTime']) . "</td><td>" . htmlspecialchars($session['Rating']) . "</td><td>" . htmlspecialchars($session['Comment']) . "</td><td>" . htmlspecialchars($session['Student_ID']) .
                                    "</td><td><a href='delete_record.php?id=" . htmlspecialchars($session['Session_ID']) . "&table=session' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($session['Session_ID']) . "&table=session' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>"; }
                } else {
                    $message = "There is no session data to display..";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;

            //student code
        case 'showStudent':
            $message = "Displaying student data...";
            //button+table
            $table = "<table><tr><th>Student_ID</th><th>Student_Name</th><th>Student_Email</th><th>Student_Number</th><th>Actions</th></tr>";
             //Fetch data from table
            if ($conn) {
                $sql = "SELECT Student_ID, Student_Name, Student_Email, Student_Number FROM student";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($student = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($student['Student_ID']) . "</td><td>" . htmlspecialchars($student['Student_Name']) . "</td><td>" . htmlspecialchars($student['Student_Email']) . "</td><td>" . htmlspecialchars($student['Student_Number']) .
                                    "</td><td><a href='delete_record.php?id=" . htmlspecialchars($student['Student_ID']) . "&table=student' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($student['Student_ID']) . "&table=student' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>"; }
                } else {
                    $message = "There are no student data to display.";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;

            //tutor code
        case 'showTutor':
            $message = "Displaying tutor data...";
            //button+table
            $table = "<table><tr><th>Tutor_ID</th><th>Tutor_Name</th><th>Tutor_Email</th><th>Tutor_Number</th><th>Specialization</th><th>Actions</th></tr>";
            //Fetch data from table
            if ($conn) {
                $sql = "SELECT Tutor_ID, Tutor_Name, Tutor_Email, Tutor_Number, Specialization FROM tutor";
                $result = mysqli_query($conn, $sql);
               // Loop in sql table rows
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($tutor = mysqli_fetch_assoc($result)) {
                        $table .= "<tr><td>" . htmlspecialchars($tutor['Tutor_ID']) . "</td><td>" . htmlspecialchars($tutor['Tutor_Name']) . "</td><td>" . htmlspecialchars($tutor['Tutor_Email']) . "</td><td>" . htmlspecialchars($tutor['Tutor_Number']) . "</td><td>" . htmlspecialchars($tutor['Specialization']) .
                                    "</td><td><a href='delete_record.php?id=" . htmlspecialchars($tutor['Tutor_ID']) . "&table=tutor' style='background-color: red; color: white; border: none; padding: 1px 10px; margin-left: 5px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Delete</a>
    <a href='edit_record.php?id=" . htmlspecialchars($tutor['Tutor_ID']) . "&table=tutor' style='background-color: #007bff; color: white; border: none; padding: 1px 10px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Edit</a>
    </td></tr>"; }
                } else {
                    $message = "There are no tutor data to display.";
                }
                mysqli_free_result($result);
            }
            $table .= "</table>";
            break;

    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $loggedin ? 'Data System' : 'Sign in'; ?></title>
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
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 800px;
            max-width: 1200px;
            text-align: center;
             position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
        }
        h2, h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 15px;
            color: #555;
            text-align: left;
        }
        input[type="text"] {
            width: 650px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .logout-button .logout-btn {
        background-color: #808080; 
        color: white; 
        border: none;
        padding: 10px 15px;
        margin: 5px;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
    }


        button {
            background-color: #7b1fa2;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
       
        .login-button button[type="submit"] {
            background-color: #007bff;
    color: white;
    width: 150px; 
    padding: 10px 15px;
        }
        .login-button button[type="submit"]:hover {
            background-color: #007bff;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 10px;
        }
        .button-group button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .button-group button:hover {
            background-color: darkgreen;
        }  


        .logout-button {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        .logout-button a {
            text-decoration: none;
        }
        .data-display {
            margin-top: 20px;
            text-align: center;
            width: 100%;
            overflow-x: auto;
        }
        .data-display table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-display th, .data-display td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        .data-display th {
            background-color: #f2f2f2;
        }
        
        .back-button {
        display: flex;
        justify-content: center;
        margin-top: 10px; 
    }

    .back-btn {
        background-color: #808080; 
        color: white;
        border: none;
        padding: 10px 15px;
        margin: 5px;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
    }

    .back-btn:hover {
        background-color: #666666; 
    }
    .scrollable-table-container {
    max-height: 500px; /* or any height you prefer */
    overflow-x: auto;
    overflow-y: auto;
    width: 100%;
}

    </style>
</head>
<body>
<p></p>
<div class="container">
    <?php if (!$loggedin): ?>
        <h2>Log in to the data system</h2>
        <form method="post">
            <div class="form-group">
    <label for="code" style="text-align: center; display: block;">Access code</label>
    <input type="text" id="code" name="code" required>
</div>
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="login-button">
                <button type="submit" name="login_submit">Log in</button>
            </div>
        </form>
        <div class="back-button">
            <a href="HOMEPAGE.php"><button class="back-btn">Back</button></a>
        </div>
    <?php else: ?>
        <h1>Data system</h1>
        <form method="post">
            <div class="button-group">
    <button type="submit" name="action" value="showAdmin">Admin</button>
    <button type="submit" name="action" value="showCourses">Courses</button>
    <button type="submit" name="action" value="showLessons">Lessons</button>
    <button type="submit" name="action" value="showPurchases">Purchases</button>
    <button type="submit" name="action" value="showSession">Sessions</button>
    <button type="submit" name="action" value="showStudent">Students</button>
    <button type="submit" name="action" value="showTutor">Tutors</button>
    <button type="submit" name="action" value="showFaq">FAQ</button>
</div>
        </form>

        <div class="logout-button">
            <a href="?logout=true"><button class="logout-btn">Log out</button></a>
        </div>

        <div class="data-display">
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($table): ?>
        <div class="scrollable-table-container">
            <?php echo $table; ?>
        </div>
    <?php endif; ?>
</div>

    <?php endif; ?>

    
   
</div>
 <?php include 'footer.php'; ?> </body>