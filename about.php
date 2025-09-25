

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - EduBonds</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #b8d6f5, #68c3a3);
            text-align: center;
            color: #0d47a1;
            padding-top: 60px; /* Prevents header overlap */
        }

      

        /* About Us Section */
        .about-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
        }

        .about-container h1 {
            color: #1565c0;
            font-size: 2.5em;
        }

        .about-container p {
            font-size: 1.2em;
            color: #333;
            line-height: 1.6;
        }

        /* Founders Section */
        .founders-section {
            margin: 50px auto;
            text-align: center;
            max-width: 1200px;
        }

        .founders-section h2 {
            font-size: 2em;
            color: #1565c0;
            margin-bottom: 20px;
            
        }

        .founders-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .founder-card {
            width: 200px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .founder-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }

        .founder-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .founder-card h3 {
            font-size: 1.2em;
            color: #0d47a1;
            margin: 10px 0;
        }

        .founder-card p {
            font-size: 0.9em;
            color: #333;
        }

        /* Back Button */
        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #0056b3;
            transform: scale(1.05);
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
 

    </style>
</head>
<body>

   
    <!-- About Us Section -->
    <div class="about-container">
        <h1>About EduBonds</h1>
        <p>Welcome to <strong>EduBonds</strong>, a platform designed to help university students share knowledge and learn from each other. Our goal is to make learning easier by providing a space where students can find high-quality study materials, tutorials, and notes.</p>
        <p>If you're skilled in a subject, you can upload your notes, courses, or even videos and sell them at affordable prices, helping others while earning money. Our platform is designed as a marketplace with an easy-to-use search system, categorized materials, and a secure payment system.</p>
        <p>Whether you're looking for help in a difficult subject or you want to share your knowledge, EduBonds is here to support you.</p>
    </div>

    <!-- Founders Section -->
    <div class="founders-section">
        <h2>Meet Our Founders</h2>
        <div class="founders-container">
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/07/bf/57/07bf57ba271b9b4294cfaa2d05f56f33.jpg" alt="Founder">
                <h3>Amjad Abdullah Aljoufi</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/a7/22/7a/a7227a15e0ae9fa3aaa705e6154d97aa.jpg" alt="Founder">
                <h3>Atheer Abdullah Aljoufi</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/a4/75/50/a4755017056b3360d660ae60c138d4e7.jpg" alt="Founder">
                <h3>Zainab Nader Alkhater</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/18/a0/a2/18a0a2d73ea98d5e7df74215296d20ba.jpg" alt="Founder">
                <h3>Leen Majed Alajami</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/86/1d/8e/861d8e89234c5fc503d1757c4760fc59.jpg" alt="Founder">
                <h3>Hutoon Abdulaziz Alghamdi</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/e1/57/13/e157132b908070f5deb0aa85eb4f1aa3.jpg" alt="Founder">
                <h3>Atheer Alansari</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/85/ca/90/85ca906dfd8bbe6af4adca53d5025287.jpg" alt="Founder">
                <h3>Sakinah Alabbas</h3>
            </div>
        </div>
    </div>

      <!-- About Us Section -->
    <div class="about-container">
        <h1>About Hana's Bakery</h1>
        <p>Welcome to <strong>Hana’s Bakery</strong> — where every treat is baked with love, joy, and a touch of magic. We specialize in handcrafted sweets that delight the senses and bring smiles to every occasion.</p>
        <p>From layered cakes and gooey cookies to colorful pancakes and cupcakes, we believe in using quality ingredients and creative passion to craft unforgettable flavors.</p>
        <p>Whether you're celebrating a birthday, hosting a brunch, or just craving something sweet, Hana’s Bakery is here to sprinkle your day with happiness.</p>
    </div>

    <!-- Founders Section -->
    <div class="founders-section">
        <h2>Meet Our Team</h2>
        <div class="founders-container">
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/07/bf/57/07bf57ba271b9b4294cfaa2d05f56f33.jpg" alt="Founder">
                <h3>Amjad Aljoufi</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/a7/22/7a/a7227a15e0ae9fa3aaa705e6154d97aa.jpg" alt="Founder">
                <h3>Atheer Aljoufi</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/a4/75/50/a4755017056b3360d660ae60c138d4e7.jpg" alt="Founder">
                <h3>Zainab Alkhater</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/18/a0/a2/18a0a2d73ea98d5e7df74215296d20ba.jpg" alt="Founder">
                <h3>Leen Alajami</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/86/1d/8e/861d8e89234c5fc503d1757c4760fc59.jpg" alt="Founder">
                <h3>Hutoon Alghamdi</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/e1/57/13/e157132b908070f5deb0aa85eb4f1aa3.jpg" alt="Founder">
                <h3>Atheer Alansari</h3>
            </div>
            <div class="founder-card">
                <img src="https://i.pinimg.com/736x/85/ca/90/85ca906dfd8bbe6af4adca53d5025287.jpg" alt="Founder">
                <h3>Sakinah Alabbas</h3>
            </div>
        </div>
    </div>

</body>
</html>


