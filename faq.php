<?php 
include 'header.php';
include 'db_connect.php';

// Handle new question submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question-title'], $_POST['question-content'])) {
    $title = $_POST['question-title'];
    $content = $_POST['question-content'];

    if ($conn) {
        $stmt = $conn->prepare("INSERT INTO faq (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            echo "<script>alert('Your question has been submitted!');</script>";
        } else {
            echo "<script>alert('Failed to submit question.');</script>";
        }

        $stmt->close();
    }
}

// Handle answer submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['answer'], $_POST['question_id'])) {
    $answer = $_POST['answer'];
    $questionId = $_POST['question_id'];

    if ($conn) {
        $stmt = $conn->prepare("UPDATE faq SET answers = ? WHERE id = ?");
        $stmt->bind_param("si", $answer, $questionId);

        if ($stmt->execute()) {
            echo "<script>alert('Answer submitted!');</script>";
        } else {
            echo "<script>alert('Failed to submit answer.');</script>";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBond Community</title>

    <style>
      /* General body styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f4f8;
    margin: 0;
    padding-top: 80px;
    color: #333;
}

h1 {
    font-size: 2.5em;
    margin: 0;
}

/* Main Container */
.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 20px;
}

/* Forum Section Styles */
.forum-section {
    margin-bottom: 30px;
}

.forum-section h2 {
    background-color: #FFFFFF;
    padding: 15px;
    border-radius: 8px;
    color: #333;
    font-size: 1.8em;
    margin-bottom: 20px;
}

/* Question Box Styles */
.question-box {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.question-box input[type="text"],
.question-box textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 1.1em;
}

.question-box button {
    background-color: #007bff;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 1.2em;
    transition: 0.3s;
    width: 100%;
}

.question-box button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Question Item Styles */
.question-item {
    background-color: #fff;
    padding: 25px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.question-item h3 {
    font-size: 1.6em;
    margin-bottom: 10px;
}

.question-item p {
    font-size: 1.1em;
    line-height: 1.6;
    margin-bottom: 15px;
}

.question-item button {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 1em;
    transition: 0.3s;
    margin-top: 15px;
}

.question-item button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Answer Form Styles */
.answer-form {
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.answer-form textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 1.1em;
    height: 100px;
}

.answer-form button {
    background-color: #56a78a;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 1.1em;
    transition: 0.3s;
    width: 100%;
}

.answer-form button:hover {
    background-color: #3e8265;
    transform: scale(1.05);
}


    .question-box {
        padding: 20px;
    }

    .question-box button {
        width: auto;
        margin-top: 15px;
    }

    .question-item button {
        width: auto;
    }
}

    </style>
</head>

<body>
    <p> </p>
    <p> </p>
<div class="container">

<?php if (
    isset($_SESSION['signedIN']) &&
    $_SESSION['signedIN'] === true &&
    isset($_SESSION['user_type']) &&
    in_array($_SESSION['user_type'], ['student', 'tutor'])
): ?>
    <section class="forum-section">
        <h2>Ask a New Question</h2>
        <div class="question-box">
            <form id="question-form" method="POST" action="">
                <label for="question-title">Question Title:</label>
                <input type="text" id="question-title" name="question-title" placeholder="Enter your question title" required>

                <label for="question-content">Question Details:</label>
                <textarea id="question-content" name="question-content" rows="4" placeholder="Enter your question details here" required></textarea>

                <button type="submit">Ask Question</button>
            </form>
        </div>
    </section>
<?php endif; ?>


       
        <section class="forum-section">
            <h2>Questions</h2>
            <div id="questions-list">
                <?php
                $query = "SELECT * FROM faq ORDER BY created_at DESC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="question-item">';
                        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                        echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';

                        // Display answer if exists
                        if (!empty($row['answers'])) {
                            echo '<div><strong>Answer:</strong><br>' . nl2br(htmlspecialchars($row['answers'])) . '</div>';
                        }

                        if (
                            isset($_SESSION['signedIN']) &&
                            $_SESSION['signedIN'] === true &&
                            isset($_SESSION['user_type']) &&
                            $_SESSION['user_type'] === 'admin'
                        ) {
                            // Answer Button for Admin
                            echo '<button onclick="toggleAnswerForm(' . $row['id'] . ')">Answer</button>';
                        
                            // Hidden Answer Form
                            echo '
                                <form method="POST" class="answer-form" id="answer-form-' . $row['id'] . '" style="display: none; margin-top: 10px;">
                                    <textarea name="answer" rows="3" placeholder="Write your answer..." required></textarea>
                                    <input type="hidden" name="question_id" value="' . $row['id'] . '">
                                    <br><button type="submit">Submit Answer</button>
                                </form>
                            ';
                        }
                        
                        echo '</div>';
                    }
                } else {
                    echo '<p>No questions yet.</p>';
                }
                ?>
            </div>
        </section>
    </div>

    <script>
        function toggleAnswerForm(id) {
            const form = document.getElementById('answer-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</body>
</html>

<?php include 'footer.php'; ?>