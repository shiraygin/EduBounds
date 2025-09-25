<?php
session_start();
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['signedIN']) || $_SESSION['signedIN'] !== true) {
    die("Access denied. Please sign in first.");
}

$role = $_SESSION['user_type'];
$id = $_SESSION['user_id'];

$conn->set_charset("utf8mb4");

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;

    if ($role === 'student') {
        $stmt = $conn->prepare("UPDATE student SET Student_Name = ?, Student_Email = ?, Student_Number = ? WHERE Student_ID = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
    } elseif ($role === 'tutor') {
        $stmt = $conn->prepare("UPDATE tutor SET Tutor_Name = ?, Tutor_Email = ?, Tutor_Number = ? WHERE Tutor_ID = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
    } elseif ($role === 'admin') {
        $stmt = $conn->prepare("UPDATE admin SET Admin_Name = ?, Email = ? WHERE Admin_ID = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
    }

    $stmt->execute();
    $stmt->close();
}

// ✅ Fetch user data
$user = [];

if ($role === 'student') {
    $stmt = $conn->prepare("SELECT Student_Name AS username, Student_Email AS email, Student_Number AS phone FROM student WHERE Student_ID = ?");
} elseif ($role === 'tutor') {
    $stmt = $conn->prepare("SELECT Tutor_Name AS username, Tutor_Email AS email, Tutor_Number AS phone, Specialization, wallet FROM tutor WHERE Tutor_ID = ?");
} elseif ($role === 'admin') {
    $stmt = $conn->prepare("SELECT Admin_Name AS username, Email AS email, NULL AS phone FROM admin WHERE Admin_ID = ?");
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// ✅ Fetch courses for student or tutor
$courses = [];
if ($role === 'student') {
    // Get courses purchased by the student from 'purchases' table
    $stmt = $conn->prepare("SELECT c.Course_Name, c.Description 
                            FROM courses c 
                            JOIN purchases p ON p.Course_ID = c.Course_ID 
                            WHERE p.Student_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $courses = $stmt->get_result();
    $stmt->close();
} elseif ($role === 'tutor') {
   // ✅ Fetch lessons uploaded by the tutor
$stmt = $conn->prepare("
SELECT l.Lesson_Name, l.Description, l.File, l.Price, c.Course_Name
FROM lessons l
JOIN courses c ON l.Course_ID = c.Course_ID
WHERE l.Tutor_ID = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$courses = $stmt->get_result(); // Consider renaming $courses to $lessons for clarity
$stmt->close();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <p></p>
    
    <title>Account Information</title>
    <style>
     body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #add8e6, #e0ffff);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    overflow: hidden; /* Prevent body overflow */
}

.profile-container {
    background: #fff;
    padding: 30px 40px;
    border-radius: 10px;
    width: 600px;
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
    max-height: 90vh; /* Ensure the container doesn't exceed viewport height */
    overflow-y: auto; /* Allow scrolling if content overflows */
}

h2 {
    text-align: center;
    color: #444;
    margin-bottom: 30px;
}

label {
    font-weight: bold;
    margin-top: 20px;
    display: block;
    color: #333;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.readonly {
    background-color: #f0f0f0;
}

.save-btn {
    float: right;
    margin-top: 20px;
    background-color: #add8e6;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    color: #333;
}

.footer {
    margin-top: 40px;
    text-align: center;
    font-size: 14px;
    color: #555;
}

.courses-section {
    margin-top: 30px;
}

.courses-section h3 {
    margin-bottom: 15px;
    color: #007bff;
}

/* Make the course list scrollable */
.course-list {
    list-style-type: none;
    padding: 0;
    max-height: 300px; /* Set a max height for the course list */
    overflow-y: auto; /* Allow scrolling if the course list is too long */
}

.course-list li {
    background-color: #f9f9f9;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

    </style>
</head>
<body>

<div class="profile-container">
    <h2>Account Information</h2>
    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <?php if ($role !== 'admin'): ?>
            <label>Phone Number:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
        <?php endif; ?>

        <label>User Type:</label>
        <input type="text" class="readonly" value="<?= htmlspecialchars($role) ?>" readonly>

        <?php if ($role === 'tutor'): ?>
            <label>Specialization:</label>
            <input type="text" class="readonly" value="<?= htmlspecialchars($user['Specialization'] ?? 'Not specified') ?>" readonly>

            <label>Wallet Balance:</label>
            <input type="text" class="readonly" value="<?= htmlspecialchars($user['wallet'] ?? '0') ?> SAR" readonly>
        <?php endif; ?>

        <label>Password:</label>
        <input type="password" class="readonly" value="********" readonly>

        <button class="save-btn" type="submit">Save Changes</button>
    </form>

    <!-- Display Courses for Student or Tutor -->
    <div class="courses-section">
        <?php if ($role == 'student' || $role == 'tutor'): ?>
            <h3>My Courses</h3>
            <?php if ($courses->num_rows > 0): ?>
                <ul class="course-list">
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <li>
                            <strong><?= htmlspecialchars($course['Course_Name']) ?></strong><br>
                            <?= htmlspecialchars($course['Description']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No courses found.</p>
            <?php endif; ?>
        <?php else: ?>
            
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

<?php
$conn->close();
?>
