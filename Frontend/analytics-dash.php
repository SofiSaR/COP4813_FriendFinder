<?php
    session_start();
    if (!isset($_SESSION['user_id']) && $_SESSION['admin'] !== true)
        header('Location: admin-login.html');
?>
<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="css/analytics-dash.css">

    <title>Analytics Dashboard</title>
</head>
<body>
    <main>
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <div id="analytics-page">
            <h1>Analytics Dashboard</h1>
            <div class="analytics-container">
                <h2>User Statistics</h2>
                <div class="analytics-cards" id="user-stats"></div>
                <div class="analytics-cards" id="user-registrations"></div>
            </div>
            <div class="analytics-container">
                <h2>Quiz Statistics</h2>
                <div class="analytics-cards" id="quiz-stats"></div>
            </div>
        </div>
    </main>
    <script type="module" src="js/analytics-dash.js"></script>
</body>
</html>