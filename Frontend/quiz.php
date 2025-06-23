<?php
    session_start();
    if (!isset($_SESSION['user_id']))
        header('Location: login.html');
?>
<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/quiz.css">

    <title>Friendship Matchmaking</title>
</head>
<body>
    <main>
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <div id="quiz-page">
            <img id="quiz-time-img" src="../Backend/images/icons/quiz-time.webp">
            <div id="quiz-container">
                <p id="no-answer-msg">Please answer all questions before proceeding.</p>
            </div>
        </div>
        <button id="submit-button">SUBMIT ></button>
    </main>
    <script src="js/quiz.js"></script>
</body>
</html>
