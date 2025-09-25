<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
    <div class="logo-container">
        <a href="HOMEPAGE.php">
            <img src="Ed.png" alt="EduBonds Logo" class="logo" width="120">
        </a>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="HOMEPAGE.php">Home</a></li>

            
            

        
            <li><a href="coursesp.php">Courses</a></li>
        

            <li><a href="map.php">Map</a></li>
            <li><a href="faq.php">FAQ</a></li>

            <?php if (isset($_SESSION['signedIN']) && $_SESSION['signedIN'] === true && $_SESSION['user_type'] === 'admin'): ?>
            <li><a href="data1.php">DB Editior</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['signedIN']) && $_SESSION['signedIN'] === true): ?>
            <li><a href="rate.php">tutors</a></li>
        <?php endif; ?>
        </ul>
    </nav>
    <div class="nav-actions">
        <!-- Search Form -->
        <form method="GET" action="header.php">
            <input type="text" name="search_query" class="search-bar" placeholder="🔍 Search for courses..." id="search" required>
            <button type="submit" style="display: none;">Search</button>
        </form>

        <?php if (isset($_SESSION['signedIN']) && $_SESSION['signedIN'] === true): ?>
    <a href="account.php" style="color: black; text-decoration: none; font-weight: bold;">
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </a>
     <a href="logout.php" class="btn">logout</a>
    <?php else: ?>
    <a href="signin.php" class="btn">Sign In</a>
    <?php endif; ?>

    </div>
</div>

<?php
// Check if the search query exists in the GET request and store it in the session
if (isset($_GET['search_query'])) {
    $_SESSION['search_term'] = $_GET['search_query']; // Store the search term in session
    header("Location: results.php");
    exit();
}
?>




<style>
/* Navbar Styling */
.navbar {
    display: flex;
    position: sticky;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background: rgba(255, 255, 255, 0.8); /* Lighter background */
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    top: 0;
    z-index: 1000; /* Ensures navbar stays on top */
    transition: background 0.3s ease;
    width: 100%; /* Ensure navbar takes the full width */
    box-sizing: border-box; /* Includes padding and border in the element's total width */
}

/* Navbar hover effect */
.navbar:hover {
    background: rgba(255, 255, 255, 0.9); /* Slightly darken the background on hover */
}

/* Logo Styling */
.logo {
    height: 50px;
    width: 120px; /* Set a fixed width for better proportion */
    object-fit: contain; /* Keeps the aspect ratio of the logo */
    animation: bounce 2s infinite alternate;
}

.logo:hover {
    transform: scale(1.05); /* Slightly enlarge the logo on hover */
}

/* Navigation Links Styling */
.nav-links {
    list-style: none;
    display: flex;
    gap: 25px;
}

.nav-links li {
    display: inline-block;
}

.nav-links a {
    text-decoration: none;
    font-size: 1.1em;
    color: black;
    font-weight: bold;
    transition: color 0.3s ease, transform 0.3s ease;
}

.nav-links a:hover {
    color: #007bff; /* Hover color */
    transform: translateY(-3px); /* Subtle lift effect */
}

.search-bar {
            padding: 10px;
            width: 250px;
            border-radius: 20px;
            border: 1px solid #ccc;
            text-align: center;
        }

/* Action Button Styling */
.nav-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.3s;
}

.btn:hover {
    background-color: #0056b3;
    transform: translateY(-3px); /* Button lift effect on hover */
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column; /* Stack navbar items vertically on smaller screens */
        text-align: center;
        padding: 20px 10px;
    }

    .nav-links {
        flex-direction: column;
        gap: 10px;
    }

    .nav-links a {
        font-size: 1.2em; /* Slightly larger font size for mobile */
    }

    .btn {
        padding: 12px 25px; /* Increase button padding on mobile */
        font-size: 1.1em;
    }
}

  /* Animations */
  @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

</style>

