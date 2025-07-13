<?php
    // start the session
    session_start();

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) && $_SESSION['admin'] !== true)
        header('Location: admin-login.html');
?>
<DOCTYPE html>
<!-- doc language is english -->
<html lang="en">
<head>
    <!-- character encoding -->
    <meta charset="UTF-8">
    <!-- scaling and responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>Analytics Dashboard</title>
    <!-- for the CSS stylesheet -->
    <link rel="stylesheet" href="css/analytics-dash.css">
    <!-- to use google charts pie chart -->
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
                </div>
                <div class="analytics-cards" id="interactions-stats"></div>
            </div>
        </div>
    </main>
</body>
</html>