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

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/admin-login.php');
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
    <link rel="stylesheet" href="/Frontend/css/analytics-dash.css">
    <!-- for using google charts pie and bar chart -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- for the JS script -->
    <script src="/Frontend/js/analytics-dash.js"></script>
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
        <!-- for the links to take admin to admin page / logout -->
        <div class="links-container">
            <a href="/Frontend/admin.php" class="admin-link">Go to Admin Page</a>
            <a href="/Frontend/logout.php" class="logout-link">Logout</a>
        </div>
    </main>
</body>
</html>