<?php
    session_start();
    if (!isset($_SESSION['user_id']))
        header('Location: login.html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Friendship Matchmaking</title>
    <script type="module" src="components/results-section/results-section.js"></script>
    <script type="module" src="components/recommended-friends/recommended-friends.js"></script>
    <link rel="stylesheet" href="css/quiz-results.css">
</head>
<body>
    <h1 id="website-title">friendship<br>matchmaking</h1>
    <div id="results-page">
        <h1 class="title">Quiz Results<br>&amp;<br>Recommended Friends</h1>
        <results-section style="width: 100%;"></results-section>
        <recommended-friends></recommended-friends>
    </div>
    <script type="module" src="js/quiz-results.js"></script>
</body>
</html>
