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