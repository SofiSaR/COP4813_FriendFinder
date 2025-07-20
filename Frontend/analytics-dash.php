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

    // start the session
    session_start();

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: /COP4813_FriendFinder/Frontend/admin-login.php');
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
    <title>Analytics Dashboard</title>
    <!-- for the CSS stylesheet -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="css/analytics-dash.css">
    <!-- for using google charts pie and bar chart -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- for the JS script -->
    <script src="js/analytics-dash.js"></script>
</head>
<body>
    <main>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the analytics page -->
        <div id="analytics-page">
            <!-- header -->
            <h1>Analytics Dashboard</h1>
            <!-- for user stats -->
            <div class="analytics-container">
                <h2>User Statistics</h2>
                <div class="analytics-cards" id="user-stats"></div>
                <div class="analytics-cards" id="user-registrations"></div>
            </div>
            <!-- for quiz stats -->
            <div class="analytics-container">
                <h2>Quiz Statistics</h2>
                <div class="analytics-cards" id="quiz-stats"></div>
            </div>
            <!-- for interactions analytics -->
            <div class="analytics-container">
            <h2>Interactions Analytics</h2>
                <div class="chart-container">
                    <div id="interactionsPieChart" style="width: 100%; height: 400px;"></div>
                    <div id="page_visits_bar_chart" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="analytics-cards" id="interactions-stats"></div>
            </div>
        </div>
    </main>
</body>
</html>