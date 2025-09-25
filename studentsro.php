<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Rules - EduBonds</title>
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
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
        }

        .switch-button:hover {
            background-color: #0056b3;
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
            color: #007bff;
        }
		
		@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.animated-img {
    animation: float 3s ease-in-out infinite;
}

    </style>
</head>
<body>

<div class="container">
    <!-- Switch to teacher rules page -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Left side: student link -->
         <a href="teacher.php" class="switch-button">Are you a teacher? </a>

        <!-- Right side: other buttons -->
        <div>
           
            <a href="HOMEPAGE.php" class="switch-button" style="background-color:#b8d6f5;">Home</a>
        </div>
    </div>
	
    <div class="section flex-row">
        <div class="text-box">
            <h1>Struggling with your studies?</h1>
            <p>Finding it hard to keep up in school? Can't understand that one subject no matter how hard you try?</p>
        </div>
        
    </div>


  <!-- Solution Section -->
<div class="section flex-row">
    <div class="image-box">
        <img src="https://www.undospress.es/wp-content/uploads/elementor/thumbs/shutterstock_1441084625FILEminimizer-of93qzrd6i1f2le98yolj5llf4mcc4k6kiujrjnhbk.jpg" 
             alt="Solution for student" 
             class="animated-img">
    </div>
    <div class="text-box">
        <h1>We've got your back!</h1>
        <p>With EduBonds, you can easily find a teacher who fits your needs. Ask questions, schedule lessons, and improve your grades with real support.</p>
    </div>
</div>


   

    <!-- Rules Section -->
    <div class="section">
        <h1>Student Rules:</h1>
        <ul>
            <li>Respect your teacher and their time.</li>
            <li>Be honest about your learning needs.</li>
            <li>Ensure timely payment for lessons.</li>
            <li>Communicate clearly and politely.</li>
            <li>Use the platform to genuinely improve your skills.</li>
        </ul>
    </div>
	    <!-- Auth Buttons at the Bottom -->
    <div style="text-align: center; margin-top: 40px;">
        <p style="font-size: 18px; margin-bottom: 16px;">Don't have an account? sign up with us or sign in if you have an account !</p>
        <a href="signup.php" class="switch-button" style="background-color: #007bff; margin: 5px;">sign up</a>
        <a href="signin.php" class="switch-button" style="background-color: #6c757d; margin: 5px;">signin</a>
    </div>

</div>

</body>
</html>
