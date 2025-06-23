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
    <link rel="stylesheet" href="css/quiz-results.css">
</head>
<body>
    <h1 id="website-title">friendship<br>matchmaking</h1>
    <div id="results-page">
        <h1 class="title">Quiz Results<br>&amp;<br>Recommended Friends</h1>
        <div id="results-section">
            <div class="pink-container">
                <h3 class="scores-title">Category Scores</h3>
                <div class="score-row"><span class="score-label">Sociability:</span> <span class="score-value">35%</span></div>
                <div class="score-row"><span class="score-label">Adventurousness:</span> <span class="score-value">77%</span></div>
                <div class="score-row"><span class="score-label">Reliability:</span> <span class="score-value">68%</span></div>
                <div class="score-row"><span class="score-label">Athleticism:</span> <span class="score-value">59%</span></div>
                <div class="score-row"><span class="score-label">Availability:</span> <span class="score-value">43%</span></div>
            </div>
            <div class="bar-chart-container">
                <div class="bar-legend">
                    <span class="legend-dot"></span> Category Score
                </div>
                <div class="bar-row">
                    <span class="bar-label">Sociability</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width:35%"></div>
                    </div>
                </div>
                <div class="bar-row">
                    <span class="bar-label">Adventurousness</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width:77%"></div>
                    </div>
                </div>
                <div class="bar-row">
                    <span class="bar-label">Reliability</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width:68%"></div>
                    </div>
                </div>
                <div class="bar-row">
                    <span class="bar-label">Athleticism</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width:59%"></div>
                    </div>
                </div>
                <div class="bar-row">
                    <span class="bar-label">Availability</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width:43%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="recommendations pink-container">
            <div class="friend-card">
                <span class="friend-name">Chloe</span>
                <span class="friend-match">
                    <span class="match-circle">98%</span>
                </span>
                <button class="view-profile">View Profile</button>
            </div>
            <div class="friend-card">
                <span class="friend-name">Francisco</span>
                <span class="friend-match">
                    <span class="match-circle">97%</span>
                </span>
                <button class="view-profile">View Profile</button>
            </div>
            <div class="friend-card">
                <span class="friend-name">Diego</span>
                <span class="friend-match">
                    <span class="match-circle">95%</span>
                </span>
                <button class="view-profile">View Profile</button>
            </div>
            <div class="friend-card more-card">
                <span class="friend-name">More</span>
                <span class="friend-arrow">&#9660;</span>
            </div>
        </div>
    </div>
    <script src="js/quiz-results.js"></script>
</body>
</html>
