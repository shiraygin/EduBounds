<?php
include 'header.php';
include 'db_connect.php';

if (isset($_SESSION['search_term'])) {
    $searchTerm = $_SESSION['search_term'];
} else {
    echo "<p>No search term provided.</p>";
    exit();
}

$searchTermLike = "%$searchTerm%";

// Fetch lessons
$sql_lessons = $conn->prepare("SELECT * FROM lessons WHERE Lesson_Name LIKE ? OR Description LIKE ?");
$sql_lessons->bind_param('ss', $searchTermLike, $searchTermLike);
$sql_lessons->execute();
$result_lessons = $sql_lessons->get_result();

// Fetch courses
$sql_courses = $conn->prepare("SELECT * FROM courses WHERE Course_Name LIKE ? OR Description LIKE ?");
$sql_courses->bind_param('ss', $searchTermLike, $searchTermLike);
$sql_courses->execute();
$result_courses = $sql_courses->get_result();

// Fetch tutors with their ratings and lesson counts
$sql_tutors = $conn->prepare("
    SELECT t.Tutor_ID, t.Tutor_Name, t.Specialization, 
           (SELECT COUNT(*) FROM lessons l WHERE l.Tutor_ID = t.Tutor_ID) AS lesson_count,
           (SELECT ROUND(AVG(s.Rating), 1) FROM session s WHERE s.Tutor_ID = t.Tutor_ID) AS avg_rating
    FROM tutor t
    WHERE t.Tutor_Name LIKE ?
");
$sql_tutors->bind_param('s', $searchTermLike);
$sql_tutors->execute();
$result_tutors = $sql_tutors->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #e0ffe0);
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .section {
            margin-bottom: 50px;
        }

        .section h2 {
            color: #007bff;
            margin-bottom: 20px;
            border-bottom: 2px solid #add8e6;
            padding-bottom: 10px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }

        .card h3 {
            margin-top: 0;
            color: #007bff;
        }

        .card p {
            color: #555;
            margin: 10px 0 0;
        }

        .no-results {
            color: #999;
            font-style: italic;
        }

        .rating {
            color: gold;
            font-size: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h1>

    <!-- Courses -->
    <div class="section">
        <h2>Courses</h2>
        <div class="grid">
            <?php if ($result_courses->num_rows > 0): ?>
                <?php while ($row = $result_courses->fetch_assoc()): ?>
                    <a href="coursesp.php?id=<?= $row['Course_ID'] ?>" class="card">
                        <h3><?= htmlspecialchars($row['Course_Name']) ?></h3>
                        <p><?= htmlspecialchars($row['Description']) ?></p>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-results">No courses match your search.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lessons -->
    <div class="section">
        <h2>Lessons</h2>
        <div class="grid">
            <?php if ($result_lessons->num_rows > 0): ?>
                <?php while ($row = $result_lessons->fetch_assoc()): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="gallary.php?id=<?= $row['Lesson_ID'] ?>" class="card">
                    <?php else: ?>
                        <div class="card">
                    <?php endif; ?>
                        <h3><?= htmlspecialchars($row['Lesson_Name']) ?></h3>
                        <p><?= htmlspecialchars($row['Description']) ?></p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <p class="no-results">Please sign in to view the lesson details.</p>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-results">No lessons match your search.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tutors -->
    <div class="section">
        <h2>Tutors</h2>
        <div class="grid">
            <?php if ($result_tutors->num_rows > 0): ?>
                <?php while ($row = $result_tutors->fetch_assoc()): ?>
                    <a href="tutordetails.php?id=<?= $row['Tutor_ID'] ?>" class="card">
                        <h3><?= htmlspecialchars($row['Tutor_Name']) ?></h3>
                        <p>Specialization: <?= htmlspecialchars($row['Specialization'] ?? 'Not specified') ?></p>
                        <p><strong>Lesson count:</strong> <?= $row['lesson_count'] ?></p>
                        
                        <!-- Average Rating -->
                        <p><strong>Average Rating:</strong> 
                            <?php
                                $avgRating = $row['avg_rating'];
                                if ($avgRating) {
                                    // Display stars based on rating
                                    $fullStars = floor($avgRating);
                                    $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
                                    $emptyStars = 5 - $fullStars - $halfStar;

                                    // Output stars
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<span class="rating">⭐</span>';
                                    }
                                    if ($halfStar) {
                                        echo '<span class="rating">⭐</span>';
                                    }
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<span class="rating">☆</span>';
                                    }
                                    echo " ($avgRating)";
                                } else {
                                    echo 'Not available';
                                }
                            ?>
                        </p>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-results">No tutors match your search.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

<?php
$sql_lessons->close();
$sql_courses->close();
$sql_tutors->close();
$conn->close();
?>
