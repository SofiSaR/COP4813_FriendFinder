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
    <!-- for the JS script -->
    <script type="module" src="js/matches.js"></script>
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
            <!-- for the main container, which is loaded 
            dynamically and contains the profile pic,
            name tag, and profile page links for the 
            user's top 20 matches -->
            <div id="main-container"></div>
        </div>
    </main>
</body>
</html>