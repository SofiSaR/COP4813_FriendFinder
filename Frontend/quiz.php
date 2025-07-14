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
        header('Location: /COP4813_FriendFinder/Frontend/login.php');
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
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="css/quiz.css">
    <!-- for the JS script -->
    <script type="module" src="js/quiz.js"></script>
</head>
<body>
    <main>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the quiz page -->
        <div id="quiz-page">
            <!-- for the quiz time icon -->
            <img id="quiz-time-img" src="../Backend/images/icons/quiz-time.webp">
            <!-- for the quiz container -->
            <div id="quiz-container">
                <!-- message for incomplete quiz -->
                <p id="no-answer-msg">Please answer all questions before proceeding.</p>
            </div>
        </div>
        <!-- for the submit button -->
        <button id="submit-button">SUBMIT ></button>
    </main>
</body>
</html>
