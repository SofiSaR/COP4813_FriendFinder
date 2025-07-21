<?php
    // use query.php to run SQL queries
    require_once __DIR__.'/../Backend/Database/query.php';
    // initialize page name
    $page_name = basename(__FILE__);

    // create the statement for updating the page visit count
    // for the current page
    $jsonSQL = json_encode([
        'sql' => "UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?",
        'params' => ['s', $page_name]
    ]);

    // execute the query 
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Page visits query failed: ' . $result['message']
        ]);
    }

    // start the session
    session_start();

    // user must be logged in
    if (!isset($_SESSION['user_id']))
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/login.php');
?>
<!-- for document structure, meta tags, and title -->
<!-- HTML5 document type -->
<!DOCTYPE html>
<!-- doc language is english -->
<html lang="en">
<head>
    <!-- for character encoding -->
    <meta charset="UTF-8">
    <!-- for scaling and responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- for page title -->
    <title>Friendship Matchmaking - Matches</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="/Frontend/css/matches.css">
    <!-- for the JS script -->
    <script type="module" src="/Frontend/js/matches.js"></script>
</head>
<body>
    <header>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the links to take user to profile page / logout -->
        <div class="links-container">
            <a href="/Frontend/profile.php" class="profile-link">My Profile</a>
            <a href="/Backend/BusinessLogic/logout.php" class="logout-link">Logout</a>
        </div>
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