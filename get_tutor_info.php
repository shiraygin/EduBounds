<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "system";
$dbname = "edu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['tutor_id'])) {
    $tutor_id = $_GET['tutor_id'];

    // Fetch tutor data
    $sql_tutor = "SELECT Tutor_Name, Specialization FROM tutor WHERE Tutor_ID = $tutor_id";
    $result_tutor = $conn->query($sql_tutor);
    if ($result_tutor->num_rows > 0) {
        $tutor = $result_tutor->fetch_assoc();

        // Fetch lessons for this tutor
        $sql_lessons = "SELECT Lesson_Name FROM lessons WHERE Tutor_ID = $tutor_id";
        $result_lessons = $conn->query($sql_lessons);
        $lessons = [];
        while ($lesson = $result_lessons->fetch_assoc()) {
            $lessons[] = $lesson['Lesson_Name'];
        }

        // Fetch average rating
        $sql_rating = "SELECT ROUND(AVG(Rating),1) AS avg_rating FROM session WHERE Tutor_ID = $tutor_id";
        $result_rating = $conn->query($sql_rating);
        $rating = $result_rating->fetch_assoc()['avg_rating'];

        // Check if the average rating is NULL, and set it to 'N/A' if so
        if ($rating === NULL) {
            $rating = 'N/A';
        }

        // Return the tutor data as JSON
        echo json_encode([
            'tutor_name' => $tutor['Tutor_Name'],
            'specialty' => $tutor['Specialization'],
            'avg_rating' => $rating,
            'lessons' => $lessons
        ]);
    } else {
        echo json_encode(['error' => 'Tutor not found']);
    }
} else {
    echo json_encode(['error' => 'No tutor ID provided']);
}

$conn->close();
?>
