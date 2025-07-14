<?php
    // initialize page name
    $page_name = basename(__FILE__);

    // database connection parameters
    $conn = new mysqli("localhost", "root", "", "FriendFinder");

    // handle connection 
    // error
    if ($conn->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    // update the page visit count
    // for the current page
    $stmt = $conn->prepare("UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?");

    // bind the page name to 
    // the prepared statement
    $stmt->bind_param("s", $page_name);

    // execute the query
    $stmt->execute();

    // close prepared 
    // statement
    $stmt->close();

    // close connection
    $conn->close();

    // start the session
    session_start();

    // user must be logged in
    if (!isset($_SESSION['user_id']))
        header('Location: login.html');
?>
<!DOCTYPE html>
<!-- doc language is english -->
<html lang="en">
<head>
    <!-- for character encoding -->
    <meta charset="UTF-8">
    <!-- for scaling and responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- for page title -->
    <title>Friendship Matchmaking</title>
    <!-- for the CSS stylesheet -->
    <link rel="stylesheet" href="css/quiz-results.css">
    <!-- for the JS scripts -->
    <script type="module" src="js/quiz-results.js"></script>
    <script type="module" src="components/results-section/results-section.js"></script>
    <script type="module" src="components/recommended-friends/recommended-friends.js"></script>
</head>
<body>
    <!-- for the website title -->
    <h1 id="website-title">friendship<br>matchmaking</h1>
    <!-- for the quiz results page -->
    <div id="results-page">
        <!-- header -->
        <h1 class="title">Quiz Results<br>&amp;<br>Recommended Friends</h1>
        <!-- for the results section and recommended friends -->
        <results-section style="width: 100%;"></results-section>
        <recommended-friends></recommended-friends>
    </div>
</body>
</html>
