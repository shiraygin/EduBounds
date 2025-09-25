<?php
include 'header.php';
include 'db_connect.php';




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tutors</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #b8d6f5, #68c3a3);
            margin: 0;
            padding: 20px;
        }

        .tutors-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .tutor-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .tutor-card:hover {
            transform: scale(1.03);
        }

        .tutor-card h3 {
            margin: 10px 0;
            color: #333;
        }

        .tutor-card p {
            color: #555;
            margin: 5px 0;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            text-align: left;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .dashboard {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .dashboard .section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dashboard .section h4 {
            margin-bottom: 10px;
            color: #333;
        }

        .dashboard .section p {
            margin: 5px 0;
            color: #555;
        }

        ul.lesson-list {
            padding-left: 20px;
        }

        ul.lesson-list li {
            margin-bottom: 5px;
            color: #333;
        }

        img {
    width: 300px;  /* Adjust the image size */
    height: auto;
    display: block;             /* Makes margin auto work */
    margin: 0 auto;             /* Centers the image horizontally */
    animation: bounce 1s ease-in-out infinite, fadeIn 2s ease-in-out; /* Bounce and fade-in animations */
}

        /* Bounce animation (up and down movement) */
        @keyframes bounce {
            0% {
                transform: translateY(0); /* Start at the original position */
            }
            50% {
                transform: translateY(-10px); /* Move up by 10px */
            }
            100% {
                transform: translateY(0); /* Return to the original position */
            }
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0; /* Start invisible */
            }
            to {
                opacity: 1; /* Fade in to full visibility */
            }
        }
    </style>
</head>
<body>

<h1 style="text-align: center; color: #333;">Our Tutors</h1>
<img src="https://i.pinimg.com/736x/7b/a4/e3/7ba4e351746781f2de68602439cb84a0.jpg" alt="lessons">

<div class="tutors-container">
    <?php
    $sql = "SELECT t.Tutor_ID, t.Tutor_Name, t.Specialization,
                (SELECT COUNT(*) FROM lessons l WHERE l.Tutor_ID = t.Tutor_ID) AS lesson_count,
                (SELECT ROUND(AVG(s.Rating),1) FROM session s WHERE s.Tutor_ID = t.Tutor_ID) AS avg_rating
            FROM tutor t";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tutor_id = $row['Tutor_ID'];
            $tutor_name = htmlspecialchars($row['Tutor_Name']);
            $specialization = htmlspecialchars($row['Specialization']);
            $lesson_count = $row['lesson_count'];
            $avg_rating = $row['avg_rating'] ?? 'N/A';

            echo "
            <div class='tutor-card' onclick='openModal($tutor_id)'>
                <h3>$tutor_name</h3>
                <p><strong>Specialty:</strong> $specialization</p>
                <p><strong>Lessons:</strong> $lesson_count</p>
                <p><strong>Avg Rating:</strong> $avg_rating ⭐</p>
            </div>
            ";
        }
    } else {
        echo "<p>No tutors found.</p>";
    }
    ?>
</div>

<!-- Modal -->
<div id="tutorModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle">Tutor Info</h3>

        <div class="dashboard">
            <div class="section">
                <h4>Specialty</h4>
                <p id="specialty"></p>
            </div>
            <div class="section">
                <h4>Lessons</h4>
                <ul class="lesson-list" id="lessonList">
                    <!-- Lessons will appear here -->
                </ul>
            </div>
            <div class="section">
                <h4>Avg Rating</h4>
                <p id="avgRating"></p>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(tutorId) {
        fetch(`get_tutor_info.php?tutor_id=${tutorId}`)
            .then(res => res.json())
            .then(data => {
                // Populate the modal with data
                document.getElementById('modalTitle').textContent = data.tutor_name;
                document.getElementById('specialty').textContent = data.specialty;
                document.getElementById('avgRating').textContent = data.avg_rating + " ⭐";

                // Clear existing lessons and add new ones
                const list = document.getElementById('lessonList');
                list.innerHTML = '';

                if (data.lessons.length === 0) {
                    list.innerHTML = '<li>No lessons available.</li>';
                } else {
                    data.lessons.forEach(name => {
                        const li = document.createElement('li');
                        li.textContent = name;
                        list.appendChild(li);
                    });
                }

                document.getElementById('tutorModal').style.display = 'block';
            })
            .catch(err => {
                alert("Failed to fetch tutor info.");
                console.error(err);
            });
    }

    function closeModal() {
        document.getElementById('tutorModal').style.display = 'none';
    }

    // Close on outside click
    window.onclick = function(event) {
        if (event.target == document.getElementById('tutorModal')) {
            closeModal();
        }
    }
</script>

</body>
</html>

<?php include 'footer.php'; ?>
