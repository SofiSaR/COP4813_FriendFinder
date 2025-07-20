<?php
    // initialize page name
    $page_name = basename(__FILE__);

    // create the statement for updating the page visit count
    // for the current page
    $jsonSQL = json_encode([
        'sql' => "UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?",
        'params' => ['s', $page_name]
    ]);

    // initialize cURL to send the 
    // query to the database API
    $ch = curl_init();

    // set the cURL options
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    // execute the query 
    // and get the result
    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Page visits query failed: ' . $result['message']
        ]);
    }

    // close the connection
    curl_close($ch);

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
