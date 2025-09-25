<?php
session_start();
include 'db_connect.php'; // Make sure this connects to $conn

// Check if form was submitted and required session variables exist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['signedIN']) && $_SESSION['signedIN'] === true) {
    $studentId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $selectedLessonIds = $_SESSION['selected_lessons'] ?? [];
    $paymentMethod = $_POST['payment_method'] ?? 'unknown';
    $paymentId = uniqid('PAY_'); // Simulate a payment ID

    if (empty($selectedLessonIds)) {
        die("No lessons selected.");
    }

    // Fetch lesson details (we need lesson info + tutor ID + course ID + price)
    $placeholders = implode(',', array_fill(0, count($selectedLessonIds), '?'));
    $stmt = $conn->prepare("SELECT Lesson_ID, Price, Tutor_ID, Course_ID FROM lessons WHERE Lesson_ID IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($selectedLessonIds)), ...$selectedLessonIds);
    $stmt->execute();
    $result = $stmt->get_result();

    $totalAmount = 0;
    $tutorPayments = []; // Track earnings per tutor

    // Begin transaction
    $conn->begin_transaction();

    try {
        while ($row = $result->fetch_assoc()) {
            $lessonId = $row['Lesson_ID'];
            $price = $row['Price'];
            $tutorId = $row['Tutor_ID'];
            $courseId = $row['Course_ID'];

            // Insert into purchases table
            $purchaseStmt = $conn->prepare("INSERT INTO purchases (Course_ID, Lesson_ID, Student_ID, Price, Payment_ID) VALUES (?, ?, ?, ?, ?)");
            $purchaseStmt->bind_param("iiiis", $courseId, $lessonId, $studentId, $price, $paymentId);
            $purchaseStmt->execute();
            $purchaseStmt->close();

            // Add to total and tutor's pending earnings
            $totalAmount += $price;
            if (!isset($tutorPayments[$tutorId])) {
                $tutorPayments[$tutorId] = 0;
            }
            $tutorPayments[$tutorId] += $price;
        }

        // Update each tutor's wallet
        foreach ($tutorPayments as $tutorId => $amount) {
            $walletUpdate = $conn->prepare("UPDATE tutor SET wallet = wallet + ? WHERE Tutor_ID = ?");
            $walletUpdate->bind_param("di", $amount, $tutorId);
            $walletUpdate->execute();
            $walletUpdate->close();
        }

        // Commit transaction
        $conn->commit();

        // Clear selected lessons from session
        unset($_SESSION['selected_lessons']);

        // Show confirmation
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Payment Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f8f8;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .confirmation-container {
                    background: #fff;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .confirmation-container h1 {
                    color: green;
                }
                .confirmation-container p {
                    margin: 10px 0;
                }
                a.button {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    border-radius: 5px;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class='confirmation-container'>
                <h1>✅ Payment Successful</h1>
                <p>Payment ID: <strong>$paymentId</strong></p>
                <p>You paid <strong>SAR " . number_format($totalAmount, 2) . "</strong> via <strong>$paymentMethod</strong>.</p>
                <a class='button' href='HOMEPAGE.php'>Back to Home</a>
            </div>
        </body>
        </html>
        ";
    } catch (Exception $e) {
        $conn->rollback();
        die("Payment failed: " . $e->getMessage());
    }

    $stmt->close();

} else {
    echo "⚠️ Unauthorized or invalid request.";
}
?>
