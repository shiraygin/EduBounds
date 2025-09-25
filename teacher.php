<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Registration Rules - EduBonds</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: ltr;
            background: linear-gradient(to bottom, #b8d6f5, #68c3a3);
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .switch-button {
            display: inline-block;
            margin-bottom: 30px;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
        }

        .switch-button:hover {
            background-color: #218838;
        }

        h1, h2 {
            color: #1a1a1a;
        }

        .section {
            margin-bottom: 50px;
        }

        .flex-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .text-box {
            flex: 1;
            min-width: 280px;
        }

        .image-box {
            flex: 1;
            min-width: 280px;
            text-align: center;
        }

        .image-box img {
            max-width: 100%;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Animation */
        .animated-img {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        ul {
            padding-left: 20px;
            line-height: 1.7;
        }

        ul li::before {
            content: "✔ ";
            color: #28a745;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Top navigation bar with left and right alignment -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Left side: student link -->
        <a href="studentsro.php" class="switch-button">Are you a student looking for a teacher?</a>

        <!-- Right side: other buttons -->
        <div>
            
            <a href="HOMEPAGE.php" class="switch-button" style="background-color:#b8d6f5;">Home</a>
        </div>
    </div>

    <!-- Intro Section -->
    <div class="section flex-row">
        <div class="text-box">
            <h1>No stable income? Struggling financially?</h1>
            <p>Do you have skills or knowledge but no opportunity to share them? Are you tired of teaching informally without proper recognition or fair pay?</p>
        </div>
        <div class="image-box">
            <img src="https://i.pinimg.com/736x/16/5b/1c/165b1c06a5b97e03e48d0a62a3c33558.jpg" alt="Thinking teacher">
        </div>
    </div>

    <!-- Solution Section -->
    <div class="section flex-row">
        <div class="image-box">
            <img src="https://i.pinimg.com/736x/90/85/b6/9085b654992a24c3ab8f115296f3678e.jpg" alt="Teaching opportunity" class="animated-img">
        </div>
        <div class="text-box">
            <h2>Here’s your solution!</h2>
            <p>EduBonds is your opportunity to become an independent teacher. Share your knowledge, help students grow, and get paid fairly for your time and effort.</p>
        </div>
    </div>

    <!-- Rules Section -->
    <div class="section">
        <h1>Teacher Registration Rules:</h1>
        <ul>
            <li>You must have real expertise or experience in a specific subject.</li>
            <li>Be honest, professional, and avoid any dishonest practices.</li>
            <li>Communicate respectfully and clearly with your students.</li>
            <li>Your focus should be on teaching and making a difference—not just money.</li>
        </ul>
    </div>
	  <!-- Auth Buttons at the Bottom -->
    <div style="text-align: center; margin-top: 40px;">
        <p style="font-size: 18px; margin-bottom: 16px;">Don't have an account? sign up with us or sign in if you have an account! </p>
        <a href="signup.php" class="switch-button" style="background-color: #007bff; margin: 5px;">sign up</a>
        <a href="signin.php" class="switch-button" style="background-color: #6c757d; margin: 5px;">signin</a>
    </div>
</div>

</body>
</html>
