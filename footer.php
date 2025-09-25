<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to right, #85d8ce, #b3d6f0);
            justify-content: space-between; /* Ensure footer is at the bottom */
        }

        .footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center footer contents horizontally */
            align-items: center; /* Center footer contents vertically */
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            font-family: Arial, sans-serif;
            font-size: 15px;
            color: #000;
            width: 100%;
        }

        .footer-section {
            margin: 5px 10px;
            text-align: center; /* Center text within each section */
        }

        .footer-section b {
            color: #333;
        }

        .social-icons a {
            color: #000;
            margin: 0 8px;
            font-size: 18px;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #0077b6;
        }

        @media (max-width: 600px) {
            .footer {
                flex-direction: column;
                text-align: center;
            }

            .social-icons {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <footer class="footer">
        <div class="footer-section">
            📧 Contact us: <b>edubonds@gmail.com</b>
        </div>
        <div class="footer-section social-icons">
            <a href="#"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
            <a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a>
            <a href="#"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
            <a href="#"><i class="fab fa-instagram" aria-hidden="true"></i></a>
            <a href="HOMEPAGE.php"><i class="fas fa-home" aria-hidden="true"></i></a>
        </div>
        <div class="footer-section">
            &copy; <?php echo date("Y"); ?> <b>EDUBONDS</b>. All rights reserved.
        </div>
    </footer>
</body>
</html>
