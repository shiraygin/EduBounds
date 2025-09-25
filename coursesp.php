<?php
// Include database connection
include 'header.php';
include 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// -------------------- Handle Select Course --------------------
if (isset($_GET['select_id'])) {
    $_SESSION['selected_course'] = $_GET['select_id']; // Save Course_ID in session
    echo "<script>window.location.href = 'gallary.php';</script>"; // Redirect to gallary.php using JavaScript
    exit();
}

// -------------------- Handle Delete Course --------------------
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM courses WHERE Course_ID = $delete_id");
    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>"; // Redirect to the same page using JavaScript
    exit();
}

// -------------------- Fetch Courses --------------------
$courses = [];
$query = "SELECT * FROM courses";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

// -------------------- Add New Course --------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['courseName'], $_POST['courseDescription'])) {
    $courseName = $_POST['courseName'];
    $courseDescription = $_POST['courseDescription'];

    if (isset($_FILES['courseImage']) && $_FILES['courseImage']['error'] == 0) {
        $image = $_FILES['courseImage'];
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
            $sql = "INSERT INTO courses (Course_Name, Description, image) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $courseName, $courseDescription, $uploadPath);
            if ($stmt->execute()) {
                echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";

                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Please upload a valid image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBonds - Courses</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #b8d6f5, #68c3a3);
            text-align: center;
        }

        h1 {
            padding: 20px;
            color:rgb(0, 0, 0);
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .course-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 250px;
        }

        .course-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .course-card h3 {
            color: #1565c0;
        }

        .course-card p {
            color: #333;
            font-size: 14px;
        }

        .button {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #68c3a3;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 20px auto;
        }

        .modal {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
        }

        .close-btn {
            margin-top: 10px;
            display: inline-block;
            background-color: #999;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
        }

        .close-btn:hover {
            background-color: #666;
        }
    </style>
</head>
<body>
    <h1>Our Courses</h1>

    <?php if (isset($_SESSION['selected_course'])): ?>
        <!-- <p style="color: green;">Selected Course ID: <?php echo $_SESSION['selected_course']; ?></p> -->
    <?php endif; ?>

    <div class="gallery">
        <?php if (count($courses) > 0): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['Course_Name']; ?>">
                    <h3><?php echo htmlspecialchars($course['Course_Name']); ?></h3>
                    <p><?php echo htmlspecialchars($course['Description']); ?></p>

                    <?php if (
    isset($_SESSION['signedIN']) &&
    $_SESSION['signedIN'] === true &&
    isset($_SESSION['user_type']) &&
    in_array($_SESSION['user_type'], ['admin', 'tutor', 'student'])
): ?>
       <!-- Select Button -->
       <a href="?select_id=<?php echo $course['Course_ID']; ?>" class="button">Select</a>
<?php endif; ?>
                 

                    <!-- Delete Button -->
                    <?php if (
    isset($_SESSION['signedIN']) &&
    $_SESSION['signedIN'] === true &&
    isset($_SESSION['user_type']) &&
    $_SESSION['user_type'] === 'admin'
): ?>
    <a href="?delete_id=<?php echo $course['Course_ID']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
<?php endif; ?>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No courses available.</p>
        <?php endif; ?>
    </div>

    <!-- Add Course Button -->
    <?php if (
    isset($_SESSION['signedIN']) &&
    $_SESSION['signedIN'] === true &&
    isset($_SESSION['user_type']) &&
    in_array($_SESSION['user_type'], ['admin', 'tutor'])
): ?>
    <button class="button" id="addCourseBtn">Add New Course</button>
<?php endif; ?>


    <!-- Modal to Add Course -->
    <div id="addCourseModal" class="modal" style="display:none;">
        <h2>Add New Course</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Course Name:</label><br>
            <input type="text" name="courseName" required><br><br>

            <label>Course Description:</label><br>
            <textarea name="courseDescription" required></textarea><br><br>

            <label>Course Image:</label><br>
            <input type="file" name="courseImage" accept="image/*" required><br><br>

            <button type="submit" class="button">Add</button>
            <button type="button" class="close-btn" onclick="closeModal()">Close</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Ensure the "Add New Course" button is clickable
        const addCourseBtn = document.getElementById('addCourseBtn');
        const addCourseModal = document.getElementById('addCourseModal');
        const closeBtn = document.querySelector('.close-btn');

        // Check if elements exist
        if (addCourseBtn && addCourseModal && closeBtn) {
            // Add event listener to show the modal when the button is clicked
            addCourseBtn.addEventListener('click', () => {
                addCourseModal.style.display = 'block'; // Show the modal
            });

            // Add event listener to close the modal when close button is clicked
            closeBtn.addEventListener('click', () => {
                addCourseModal.style.display = 'none'; // Hide the modal
            });
        }
    });
    </script>
</body>
</html>
