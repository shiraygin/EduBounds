<?php 
include 'db_connect.php';
include 'header.php';


$random_courses = [];
$query = "SELECT * FROM courses ORDER BY RAND() LIMIT 2";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $random_courses[] = $row;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBonds - Study With Us</title>
    <p> </p>
    
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #b8d6f5, #68c3a3);
            text-align: center;
            padding-top: 80px; /* Space below the fixed navbar */
            transition: 0.3s;
        }


        .btn {
            padding: 12px 25px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        /* Hero Section */
        .hero {
            padding: 60px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero h1 {
            font-size: 2.8em;
            font-weight: bold;
            margin-bottom: 10px;
            
        }

        .hero .btn {
            font-size: 1.2em;
        }

        .llogo {
            width: 300px;
            height: 300px;
            animation: bounce 2s infinite alternate;
        }

        .info-section {
    background: white;
    padding: 40px;
    border-radius: 20px;
    margin: 40px auto;
    display: flex;
    justify-content: center; /* Center them */
    gap: 30px; /* Space between squares */
    max-width: 1200px;
    flex-wrap: wrap;
    animation: blinkColor 1s infinite alternate; /* Blink effect */
}

.info-box {
    width: 250px;
    text-align: center;
    background: #f9f9f9; /* Light background */
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    animation: blinkColor 1s infinite alternate; /* Blink effect */
}

.info-box:hover {
    transform: translateY(-5px); /* Lift on hover */
}

.info-box img {
    width: 200px;
    height: auto;
    margin-bottom: 15px;
}
        /* Popular Courses Section */
        .courses-section {
            padding: 50px;
        }

        .course-filters {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .filter-btn {
            padding: 10px 20px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            background: white;
            font-size: 1em;
            transition: 0.3s;
        }

        .filter-btn:hover {
            background: #007bff;
            color: white;
        }

        .course-list {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: auto;
            margin-bottom: 80px;
        }

        .course-card {
            width: 220px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }

        .course-card img {
            width: 100px;
            margin-bottom: 15px;
        }

        /* Animations */
        @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

  
.greeting-text {
    font-size: 1.5em;
    font-weight: bold;
    color: #007bff; /* Initial color */
    margin-top: 10px;
    text-align: center;
    animation: blinkColor 1s infinite alternate; /* Blink effect */
}

/* Define the blinking animation */
@keyframes blinkColor {
    0% {
        color: #007bff; /* First color */
    }
    50% {
        color: #003366; /* Second color */
    }
    100% {
        color: #007bff; /* Back to first color */
    }
}

/* Styling the username part */
.username {
    font-size: 1.6em;
    font-weight: bolder;
    transition: color 0.3s ease;
}

    </style>
</head>
<body>


    <!-- Hero Section -->
    <div class="hero">
        <h1>STUDY WITH US <br> <strong>EDUBONDS</strong></h1>
        <img src="Ed.png" alt="EduBonds Logo" class="llogo">
        <br>

        <?php if (!isset($_SESSION['signedIN']) || $_SESSION['signedIN'] !== true): ?>
    <!-- If the user is not logged in, show the "Get Started" button -->
    <button class="btn" onclick="location.href='teacher.php'">Get Started</button>
<?php else: ?>
    <!-- If the user is logged in, show a personalized greeting -->
    <p class="greeting-text">Hi, <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
<?php endif; ?>


    </div>

    <!-- Information Section -->
    <div class="info-section">
        <div class="info-box">
            <img src="https://i.pinimg.com/originals/7d/c6/c4/7dc6c4a72bee8112fd708a419116cbab.gif" alt="Discover">
            <h3>Discover</h3>
            <p>Explore a wide variety of engaging subjects and interactive lessons.</p>
        </div>
        <div class="info-box">
            <img src="https://i.pinimg.com/originals/3f/4f/f4/3f4ff439ff698abc3000d6b5be4cafb8.gif" alt="Enroll">
            <h3>Enroll</h3>
            <p>Join a class that excites you and learn alongside curious classmates.</p>
        </div>
        <div class="info-box">
            <img src="https://i.pinimg.com/originals/1e/bb/ad/1ebbadda5cbd38662d07f54f0ee14679.gif" alt="Learn">
            <h3>Learn</h3>
            <p>Gain knowledge from expert teachers and develop essential skills.</p>
        </div>
    </div>

    <!-- Random Courses Section -->
<div class="courses-section">
    <h2>Featured Courses</h2>
    <p>Explore these handpicked courses and start learning today!</p>

    <div class="course-list" id="courses" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
    <?php foreach ($random_courses as $course): ?>

       
        <?php if (isset($_SESSION['signedIN']) && $_SESSION['signedIN'] === true): ?>
    <!-- Lesson Navigation or Button (this part will only show if signed in) -->
    <a href="coursesp.php?select_id=<?php echo $course['Course_ID']; ?>" style="text-decoration: none; color: inherit;">
<?php endif; ?>

        <div class="course-card" style="width: 250px; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 15px; transition: transform 0.2s;">
                <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['Course_Name']); ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                <h3 style="color: #007bff;"><?php echo htmlspecialchars($course['Course_Name']); ?></h3>
                <p style="font-size: 14px;"><?php echo htmlspecialchars($course['Description']); ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php include 'about.php'; ?>


<!-- Contact Section -->
<?php 
include 'footer.php';
?>
</body>
</html>

   