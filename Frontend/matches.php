<?php
    session_start();
    if (!isset($_SESSION['user_id']))
        header('Location: login.html');
?>
<!-- for document structure, meta tags, and title -->
<!-- HTML5 document type -->
<!DOCTYPE html>
<!-- doc language is english -->
<html lang="en">
<head>
    <!-- character encoding -->
    <meta charset="UTF-8">
    <!-- scaling and responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>Friendship Matchmaking - Matches</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="css/matches.css">
</head>
<body>
    <header>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the matches icon -->
        <div id="matches-icon">
            <img src="../Backend/images/icons/match-icon.webp" alt="match icon"> 
        </div>
    </header>
    <main>    
        <!-- for the matches page -->
        <div id="matches-page">
            <!-- for the main container -->
            <div id="main-container">
                <!-- for the first profile - hannah -->
                <div class="profile">
                    <!-- for the profile pic -->
                    <div class="profile-pic">
                        <img src="../Backend/images/icons/hannah-pic.webp" alt="Hannah's profile picture">
                    </div>
                    <!-- for the name tag -->
                    <div class="name-tag">
                        <img src="../Backend/images/icons/name-tag-icon.webp" alt="Name tag icon">
                        <span>Hannah</span>
                    </div>
                    <!-- for the profile link button -->
                    <button class="profile-link">
                        <img src="../Backend/images/icons/link.webp" alt="Profile link icon">
                        <span>View Profile</span>
                    </button>
                </div>
                <!-- for the second profile - jeremiah -->
                <div class="profile">
                    <!-- for the profile pic -->
                    <div class="profile-pic">
                        <img src="../Backend/images/icons/jeremiah-pic.webp" alt="Jeremiah's profile picture">
                    </div>
                    <!-- for the name tag -->
                    <div class="name-tag">
                        <img src="../Backend/images/icons/name-tag-icon.webp" alt="Name tag icon">
                        <span>Jeremiah</span>
                    </div>
                    <!-- for the profile link button -->
                    <button class="profile-link">
                        <img src="../Backend/images/icons/link.webp" alt="Profile link icon">
                        <span>View Profile</span>
                    </button>
                </div>
                <!-- for the third profile - nicolas -->
                <div class="profile">
                    <!-- for the profile pic -->
                    <div class="profile-pic">
                        <img src="../Backend/images/icons/nicolas-pic.webp" alt="Nicolas' profile picture">
                    </div>
                    <!-- for the name tag -->
                    <div class="name-tag">
                        <img src="../Backend/images/icons/name-tag-icon.webp" alt="Name tag icon">
                        <span>Nicolas</span>
                    </div>
                    <!-- for the profile link button -->
                    <button class="profile-link">
                        <img src="../Backend/images/icons/link.webp" alt="Profile link icon">
                        <span>View Profile</span>
                    </button>
                </div>
                <!-- for the fourth profile - huda -->
                <div class="profile">
                    <!-- for the profile pic -->
                    <div class="profile-pic">
                        <img src="../Backend/images/icons/huda-pic.webp" alt="Huda's profile picture">
                    </div>
                    <!-- for the name tag -->
                    <div class="name-tag">
                        <img src="../Backend/images/icons/name-tag-icon.webp" alt="Name tag icon">
                        <span>Huda</span>
                    </div>
                    <!-- for the profile link button -->
                    <button class="profile-link">
                        <img src="../Backend/images/icons/link.webp" alt="Profile link icon">
                        <span>View Profile</span>
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>