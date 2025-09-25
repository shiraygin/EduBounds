<?php
include 'db_connect.php';
include 'header.php';

if (!isset($_SESSION['selected_course'])) {
    echo "No course selected.";
    exit;
}

$courseId = intval($_SESSION['selected_course']);

$courseStmt = $conn->prepare("SELECT * FROM courses WHERE Course_ID = ?");
$courseStmt->bind_param("i", $courseId);
$courseStmt->execute();
$course = $courseStmt->get_result()->fetch_assoc();

$lessonStmt = $conn->prepare("SELECT * FROM lessons WHERE Course_ID = ? AND approved = 1");
$lessonStmt->bind_param("i", $courseId);
$lessonStmt->execute();
$lessons = $lessonStmt->get_result()->fetch_all(MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lessonName'], $_POST['lessonDescription'], $_POST['lessonPrice'])) {
    $lessonName = $_POST['lessonName'];
    $lessonDescription = $_POST['lessonDescription'];
    $lessonPrice = floatval($_POST['lessonPrice']);

    if (isset($_FILES['lessonFile']) && $_FILES['lessonFile']['error'] === 0) {
        $file = $_FILES['lessonFile'];
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $insertLesson = $conn->prepare("INSERT INTO lessons (Lesson_Name, Description, File, Price, Course_ID, Tutor_ID) VALUES (?, ?, ?, ?, ?, ?)");
            $tutorId = $_SESSION['user_id']; // Get tutor ID from the session

            $insertLesson->bind_param("sssddi", $lessonName, $lessonDescription, $filePath, $lessonPrice, $courseId, $tutorId);
            $insertLesson->execute();
            echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            
            exit();
        }
    }
}

if (isset($_GET['delete_lesson'])) {
    $deleteId = intval($_GET['delete_lesson']);
    $conn->query("DELETE FROM lessons WHERE Lesson_ID = $deleteId");
    echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitReview'])) {
    $lessonId = intval($_POST['submitReview']);
    $reviewText = $_POST['reviewText'];
    $rating = intval($_POST['rating']);
    $studentId = $_SESSION['user_id']; // Reviewer is a student

    $lessonInfoStmt = $conn->prepare("SELECT Tutor_ID, Course_ID FROM lessons WHERE Lesson_ID = ?");
    $lessonInfoStmt->bind_param("i", $lessonId);
    $lessonInfoStmt->execute();
    $lessonInfo = $lessonInfoStmt->get_result()->fetch_assoc();

    if ($lessonInfo) {
        $tutorId = $lessonInfo['Tutor_ID'];
        $courseId = $lessonInfo['Course_ID'];
        $sessionDateTime = date("Y-m-d H:i:s"); // Current datetime

        // Insert into session table
        $insertReview = $conn->prepare("INSERT INTO session (Lesson_ID, Tutor_ID, Course_ID, Session_DateTime, Rating, Comment) VALUES (?, ?, ?, ?, ?, ?)");
        $insertReview->bind_param("iiisis", $lessonId, $tutorId, $courseId, $sessionDateTime, $rating, $reviewText);
        $insertReview->execute();

        echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";

        exit();
    } else {
        echo "Lesson not found.";
    }
}

$lessonStmt = $conn->prepare("SELECT lessons.*, tutor.Tutor_Name FROM lessons JOIN tutor ON lessons.Tutor_ID = tutor.Tutor_ID WHERE lessons.Course_ID = ? AND lessons.approved = 1");
$lessonStmt->bind_param("i", $courseId);
$lessonStmt->execute();
$lessons = $lessonStmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['pay_now'])) {
    $total_amount = $_POST['total_amount'];

    if (isset($_SESSION['selected_lessons']) && !empty($_SESSION['selected_lessons'])) {
        echo "<h3>Payment Processed Successfully</h3>";
        echo "<p>Total Amount: SAR " . number_format($total_amount, 2) . "</p>";
        unset($_SESSION['selected_lessons']);
    } else {
        echo "<p>No lessons selected for payment.</p>";
    } }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lessons - <?php echo htmlspecialchars($course['Course_Name']); ?></title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #b8d6f5, #68c3a3);
            text-align: center;
        }


        .cart-icon {
    position: fixed;
    top: 100px;
    right: 25px;
    cursor: pointer;
    font-size: 24px;
    z-index: 1001;
}

        .course-details, .lesson-list, .add-lesson {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 80%;
            margin: 20px auto;
        }
        .lesson-item {
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px;
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 5px;
        }
        .button:hover {
            background-color: #68c3a3;
        }
        input, textarea {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .cart-window {
            display: none;
            position: fixed;
            top: 150px;
            right: 20px;
            width: 300px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .checkout-animation {
            display: none;
            margin-top: 20px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        img {
            width: 300px;  /* Adjust the image size */
            height: auto;
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

        .help-button {
    position: fixed;
    bottom: 25px;
    right: 25px;
    background-color: #007bff;
    color: white;
    font-size: 20px;
    padding: 12px 16px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.help-modal {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 25px;
    width: 300px;
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    padding: 20px;
    z-index: 1001;
}

.help-content {
    text-align: center;
}

.close-help {
    position: absolute;
    top: 5px;
    right: 12px;
    font-size: 20px;
    cursor: pointer;
}

    </style>
</head>
<body>
    <p></p>
    <div class="header">
    <div class="cart-icon" onclick="toggleCart()">🛒 <span id="cart-count">0</span></div>
        <h2><?php echo htmlspecialchars($course['Course_Name']); ?> - Lessons</h2>
        <img src="https://i.pinimg.com/736x/fb/5c/ec/fb5cec2edd660293c853ba3c04950801.jpg" alt="lessons">

        
    </div>

    <div class="course-details">
        <p><strong>Description:</strong> <?php echo htmlspecialchars($course['Description']); ?></p>
        <?php if (
    isset($_SESSION['signedIN']) &&
    $_SESSION['signedIN'] === true &&
    isset($_SESSION['user_type']) &&
    $_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'tutor'
): ?>
    <button class="button" onclick="toggleLessonForm()">Add New Lesson</button>
<?php endif; ?>

    </div>



    <div class="add-lesson" id="lessonForm" style="display: none;">
        <h3>Add New Lesson</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="lessonName" placeholder="Lesson Name" required><br>
            <textarea name="lessonDescription" placeholder="Lesson Description" required></textarea><br>
            <input type="file" name="lessonFile" required><br>
            <input type="number" name="lessonPrice" placeholder="Price (SAR)" step="0.01" required><br>
            <button class="button" type="submit">Add Lesson</button>
        </form>
    </div>

    <div class="lesson-list">
    <h3>Lessons</h3> 
    <?php foreach ($lessons as $lesson): ?>
    <div class="lesson-item">
        <h4><?php echo htmlspecialchars($lesson['Lesson_Name']); ?></h4>
        <p><?php echo htmlspecialchars($lesson['Description']); ?></p>
        <p><strong>Instructor:</strong> <?php echo htmlspecialchars($lesson['Tutor_Name']); ?></p>
        <p><strong>Price:</strong> SAR <?php echo $lesson['Price']; ?></p>

        <?php 
            // Display "Add to Cart" button for students who haven't purchased the lesson
            if (isset($_SESSION['signedIN']) && $_SESSION['signedIN'] === true && $_SESSION['user_type'] === 'student') {
                $studentId = $_SESSION['user_id'];
                $lessonId = $lesson['Lesson_ID'];

                // Query to check if the student has purchased this lesson
                $stmt = $conn->prepare("SELECT * FROM purchases WHERE Student_ID = ? AND Lesson_ID = ?");
                $stmt->bind_param("ii", $studentId, $lessonId);
                $stmt->execute();
                $result = $stmt->get_result();

                // Show "Add to Cart" button only if the student hasn't purchased the lesson
                if ($result->num_rows === 0) {
                    echo '<button class="button" onclick="addToCart(\'' . addslashes($lesson['Lesson_Name']) . '\', ' . $lesson['Price'] . ', ' . $lesson['Lesson_ID'] . ')">Add to Cart</button>';
                }
            }
        ?>

<?php if (
    isset($_SESSION['signedIN']) &&
    $_SESSION['signedIN'] === true &&
    isset($_SESSION['user_type']) &&
    (
        in_array($_SESSION['user_type'], ['admin', 'tutor']) && 
        ($_SESSION['user_id'] === $lesson['Tutor_ID'] || $_SESSION['user_type'] === 'admin')
    ) || 
    ($_SESSION['user_type'] === 'student' && isset($_SESSION['user_id']))
): ?>
    <?php
        // Check if the student has purchased the lesson (if user is student)
        if ($_SESSION['user_type'] === 'student') {
            $studentId = $_SESSION['user_id'];
            $lessonId = $lesson['Lesson_ID'];

            // Query to check if the student has purchased this lesson
            $stmt = $conn->prepare("SELECT * FROM purchases WHERE Student_ID = ? AND Lesson_ID = ?");
            $stmt->bind_param("ii", $studentId, $lessonId);
            $stmt->execute();
            $result = $stmt->get_result();

            // Only show file link if the student has purchased the lesson
            if ($result->num_rows > 0) {
                echo '<a href="' . htmlspecialchars($lesson['File']) . '" target="_blank">View File</a><br><br>';
            }
        }
        // If user is admin or tutor, always show the file link
        else {
            echo '<a href="' . htmlspecialchars($lesson['File']) . '" target="_blank">View File</a><br><br>';
        }
    ?>
<?php endif; ?>

        <?php if (
            isset($_SESSION['signedIN']) &&
            $_SESSION['signedIN'] === true &&
            isset($_SESSION['user_type']) &&
            $_SESSION['user_type'] === 'admin'
        ): ?>
            <a href="?delete_lesson=<?php echo $lesson['Lesson_ID']; ?>" class="button" onclick="return confirm('Delete this lesson?')">Delete</a>
        <?php endif; ?>
        
        <!-- Review Button for Students who have purchased the lesson -->
        <?php if (
            isset($_SESSION['signedIN']) &&
            $_SESSION['signedIN'] === true &&
            isset($_SESSION['user_type']) &&
            $_SESSION['user_type'] === 'student'
        ): ?>
            <?php
                $userId = $_SESSION['user_id'];
                $lessonId = $lesson['Lesson_ID'];

                // Check if the student has purchased the lesson
                $stmt = $conn->prepare("SELECT * FROM purchases WHERE Student_ID = ? AND Lesson_ID = ?");
                $stmt->bind_param("ii", $userId, $lessonId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Student has purchased the lesson, so show the "Add Review" button
                    echo '<button class="button" onclick="toggleReviewForm(' . $lesson['Lesson_ID'] . ')">Add Review</button>';
                }
            ?>
        <?php endif; ?>

        <!-- Review Form (Initially Hidden) -->
        <div class="review-form" id="reviewForm_<?php echo $lesson['Lesson_ID']; ?>" style="display: none;">
            <h3>Write a Review for <?php echo htmlspecialchars($lesson['Lesson_Name']); ?></h3>
            <form action="" method="POST">
                <textarea name="reviewText" placeholder="Write your review here..." required></textarea><br>
                <input type="number" name="rating" placeholder="Rating (1-5)" min="1" max="5" required><br>
                <button class="button" type="submit" name="submitReview" value="<?php echo $lesson['Lesson_ID']; ?>">Submit Review</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>


    <div class="cart-window" id="cart">
        <h3>Your Cart</h3>
        <ul id="cart-items" style="list-style: none; padding: 0; text-align: left;"></ul>
        <p><strong>Total:</strong> SAR <span id="cart-total">0.00</span></p>
        <button class="button" onclick="checkout()">Pay Now</button>

        <div class="checkout-animation" id="checkout-animation">
            <p>Processing Payment...</p>
            <div class="spinner"></div>
        </div>
    </div>

    
    

    <script>


let cart = [];

function addToCart(name, price, id) {//add to cart
    cart.push({ name, price, id }); // <-- include lesson ID here
    updateCart();
    alert(name + ' added to cart.');
}

function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    cartItems.innerHTML = '';
    let total = 0;
    cart.forEach(item => {
        const li = document.createElement('li');
        li.innerText = item.name + ' - SAR ' + item.price.toFixed(2);
        cartItems.appendChild(li);
        total += item.price;
    });

    cartCount.innerText = cart.length;
    cartTotal.innerText = total.toFixed(2);
}

function toggleCart() {
    const cartDiv = document.getElementById('cart');
    cartDiv.style.display = cartDiv.style.display === 'none' ? 'block' : 'none';
}

function checkout() {
    const lessonIds = cart.map(item => item.id); // Collect the lesson IDs from the cart.

    // Send lessonIds to the server using a POST request
    fetch('payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lesson_ids: lessonIds }) // Send the lesson IDs as JSON.
    })
    .then(response => response.json()) // Expect JSON response
    .then(data => {
        // Check if data is received successfully
        if (data.success) {
            // Proceed with the redirection after server confirms data was saved
            window.location.href = 'payment.php'; // Redirect to payment page
        } else {
            // Handle failure response
            alert('Failed to send data to payment page.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}




        function toggleReviewForm(lessonId) {
    const reviewForm = document.getElementById('reviewForm_' + lessonId);
    reviewForm.style.display = reviewForm.style.display === 'none' ? 'block' : 'none';
}

function toggleLessonForm() {
    const lessonForm = document.getElementById('lessonForm');
    lessonForm.style.display = lessonForm.style.display === 'none' ? 'block' : 'none';
}

function toggleReviewForm(lessonId) {
    const reviewForm = document.getElementById('reviewForm_' + lessonId);
    reviewForm.style.display = reviewForm.style.display === 'none' ? 'block' : 'none';
}

function toggleHelp() {
    const helpModal = document.getElementById('help-modal');
    helpModal.style.display = helpModal.style.display === 'none' ? 'block' : 'none';
}

function showHelp(topic) {
    let message = '';
    switch (topic) {
        case 'view':
            message = 'To view lessons, browse the list on the main page. Click on a lesson to see more details.';
            break;
        case 'add':
            message = 'To add a lesson to your cart, click the "+" button next to it.';
            break;
        case 'pay':
            message = 'To pay, go to your cart and click "Pay Now". We accept credit/debit cards.';
            break;
        case 'review':
            message = 'After payment, go to your "My Lessons" section to review or download the content.';
            break;
        case 'admin':
            message = 'Admins and tutors can manage lessons via the dashboard (add/edit/delete).';
            break;
    }
    alert(message);
}



    </script>

    <!-- Help Button -->
<div class="help-button" onclick="toggleHelp()">❓</div>

<!-- Help Modal -->
<div id="help-modal" class="help-modal">
    <div class="help-content">
        <span class="close-help" onclick="toggleHelp()">&times;</span>
        <h3>Need Help?</h3>
        <p>Welcome! Here’s how you can use this page:</p>
        <ul style="text-align:left;">
       
  <li><button onclick="showHelp('view')">📚 View lessons in this course</button></li>
  <li><button onclick="showHelp('add')">🛒 Add lessons to your cart</button></li>
  <li><button onclick="showHelp('pay')">💳 Click "Pay Now" to purchase lessons</button></li>
  <li><button onclick="showHelp('review')">📝 After purchase, review the lesson</button></li>
  <li><button onclick="showHelp('admin')">🎓 Tutors/admins can add or delete lessons</button></li>
</ul>

        </ul>
    </div>
</div>

</body>
</html>

