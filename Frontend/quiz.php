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

    // run the query by sending $jsonSQL to the function in query.php
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
    <link rel="stylesheet" href="/Frontend/css/quiz.css">
    <!-- for the JS script -->
    <script type="module" src="/Frontend/js/quiz.js"></script>
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
