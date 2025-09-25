<?php
session_start();
include 'db_connect.php';  // Include database connection

// Handle POST request from JS to save lesson IDs in session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input from fetch
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['lesson_ids']) && is_array($input['lesson_ids'])) {
        $_SESSION['selected_lessons'] = $input['lesson_ids'];
        echo json_encode(['success' => true]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid lesson data']);
        exit();
    }
}

// Handle GET request to show payment page
$selectedLessonIds = isset($_SESSION['selected_lessons']) ? $_SESSION['selected_lessons'] : [];

$lessons = [];
$totalPrice = 0;

if (!empty($selectedLessonIds)) {
    $placeholders = implode(',', array_fill(0, count($selectedLessonIds), '?'));
    $stmt = $conn->prepare("SELECT Lesson_Name, Price FROM lessons WHERE Lesson_ID IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($selectedLessonIds)), ...$selectedLessonIds);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $lessons[] = $row;
        $totalPrice += $row['Price'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lessons to be Paid for</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .payment-container {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 360px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .payment-option {
            margin-bottom: 15px;
        }

        select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .pay-now {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .pay-now:hover {
            background-color: #0056b3;
        }

        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Lessons to be Paid for</h2>

    <?php if (empty($lessons)): ?>
        <p>No lessons selected for payment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Lesson Name</th>
                    <th>Price (SAR)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lessons as $lesson): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lesson['Lesson_Name']); ?></td>
                        <td>SAR <?php echo number_format($lesson['Price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total: SAR <?php echo number_format($totalPrice, 2); ?></strong></p>

        <form action="payment_processor.php" method="POST">
            <div class="payment-option">
                <label for="payment-method">Choose Payment Method:</label>
                <select name="payment_method" id="payment-method" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="apple_pay">Apple Pay</option>
                    <option value="tabby">Tabby</option>
                </select>
            </div>
            <button class="pay-now" type="submit">Pay Now</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
