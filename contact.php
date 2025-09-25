<?php 
session_start();
include 'db_connect.php';
?>
include 'header.php';
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="help, how can i, help center">
    <title>Help Center</title>
    <style>
       body {
     font-family: 'Poppins', sans-serif; 
  background: linear-gradient(to bottom, #8dcff5, #68c3a3);
    margin: 0;
    padding-top: 70px;
    min-height: 100vh; 
    display: flex;
    flex-direction: column;
}

     
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 30px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .logo {
            width: 120px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li {
            display: inline;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 1em;
            color: black;
            font-weight: bold;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #007bff;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Header Section */
        header {
            background-image: url('help.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 30px 10px;
            text-align: center;
        }

        .search-box {
            margin: 20px 0;
        }

        .search-box input {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        .container {
            max-width: 4000px;
            margin: 50px auto 0;
            background: white;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .faq {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .faq h3 {
            margin: 0;
            cursor: pointer;
        }

        .answer {
            display: none;
            margin-top: 10px;
            color: #555;
        }

        .contact {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .footer {
            width: 100%;
            background: transparent;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: black;
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo-container">
            <a href="index.html">
                <img src="/Ed.png" alt="EduBonds Logo" class="logo">
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="HOMEPAGE.html">Home</a></li>
                <li><a href="gallery.html">Courses</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="faq.html">FAQ</a></li>
                <li><a href="map.html">MAP</a></li>
            </ul>
        </nav>
        <div class="nav-actions">
            <button class="btn" onclick="location.href='signup.html'">Sign Up</button>
        </div>
    </div>

    <!-- Hero Section -->
    <header>
        <h1>Help Center</h1>
        <div class="search-box">
            <input type="text" placeholder="Search here..." id="searchInput" onkeyup="filterFAQs()">
        </div>
    </header>

    <!-- FAQ Section -->
    <div class="container">
        <h2>Frequently Asked Questions</h2>

        <div class="faq">
            <h3 onclick="toggleAnswer(this)">How can I reset my password?</h3>
            <div class="answer">
                <p>You can reset your password by going to the login page and clicking on "Forgot Password".</p>
            </div>
        </div>

        <div class="faq">
            <h3 onclick="toggleAnswer(this)">What are the steps to create a new account?</h3>
            <div class="answer">
                <p>To create a new account, visit the registration page and fill out the required information.</p>
            </div>
        </div>
		
		 <div class="faq">
    <h3 onclick="toggleAnswer(this)">Where can I find my account information?</h3>
    <div class="answer">
        <p>You can find your account information in the header at the top right, where the user icon is located.</p>
    </div>
    </div>	
		
		<div class="faq">
    <h3 onclick="toggleAnswer(this)">What are the available payment methods?</h3>
    <div class="answer">
        <p>We offer the following payment methods:</p>
        <ul>
            <li>Credit Card</li>
            <li>Apple Pay</li>
            <li>Tabby</li>
        </ul>
    </div>
</div>


        <div class="faq">
            <h3 onclick="toggleAnswer(this)">How can I leave a review for the Java course?</h3>
            <div class="answer">
                <p>To leave a review for the course, click on the "+ Write review" button,</p>
				<p>then write your opinion in the provided text box and click "Submit". The review will be displayed in the review list.</p>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact">
            <h2>Contact Us</h2>
            <p>If you have any other inquiries, you can reach us via email: <strong> edubonds@gmail.com</strong></p>
        </div>
    </div>

    <script>
        function toggleAnswer(element) {
            const answer = element.nextElementSibling;
            answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
        }

        function filterFAQs() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const faqs = document.querySelectorAll('.faq');

            faqs.forEach(faq => {
                const question = faq.querySelector('h3').textContent.toLowerCase();
                faq.style.display = question.includes(filter) ? 'block' : 'none';
            });
        }
    </script>
<footer class="footer">
        <span>Connect with us: <b>edubonds@gmail.com</b></span>
        <span> EDUBONDS © 2025</span>
    </footer>
</body>
</html>
<?php include 'footer.php'; ?>