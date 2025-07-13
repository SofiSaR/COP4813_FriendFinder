document.addEventListener('DOMContentLoaded', function() {
    getUserStats();
    getQuizStats();
});

async function getUserStats() {
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/user-statistics.php', {
        method: 'GET',
        credentials: 'same-origin'
    });

    if (!response.ok) {
        throw new Error(`HTTP error. Status: ${response.status}`);
    }

    // wait for json response
    const userStats = await response.json();

    if (userStats.success) {
            updateUserStats(userStats.data);
    } else {
        // display error message
        console.error('Error fetching user data:', userStats.message);
    }    
}

function updateUserStats(userStats) {
    const analyticsCards = document.getElementById('user-stats');

    if (analyticsCards) {
        analyticsCards.innerHTML = `
            <div class="analytics-card">
                <h3>Total Users</h3>
                <p id="total-users" class="big-number">${userStats.total_users}</p>
            </div>
            <div class="analytics-card">
                <h3>Active Users</h3>
                <p id="active-users" class="big-number">${userStats.active_users}</p>
            </div>
            <div class="analytics-card">
                <h3>Inactive Users</h3>
                <p id="inactive-users" class="big-number">${userStats.inactive_users}</p>
            </div>
        `;
    }
}

async function getQuizStats() {
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/quiz-stats.php', {
        method: 'GET',
        credentials: 'same-origin'
    });

    if (!response.ok) {
        throw new Error(`HTTP error. Status: ${response.status}`);
    }

    // wait for json response
    const quizStats = await response.json();

    if (quizStats.success) {
            updateQuizStats(quizStats.data);
    } else {
        // display error message
        console.error('Error fetching user data:', quizStats.message);
    }    
}

function updateQuizStats(quizStats) {
    const quizStatsElem = document.getElementById('quiz-stats');

    if (quizStatsElem) {
        quizStatsElem.innerHTML = `
            <div class="analytics-card">
                <h3>Number of Quiz Submissions</h3>
                <p id="quiz-submissions" class="big-number">${quizStats.num_quiz_submissions}</p>
            </div>
            <div class="analytics-card">
                <h3>Quiz Submission Rate</h3>
                <div class="rate-and-pie">
                    <span class="match-circle-circle" style="background: conic-gradient(var(--dark-pink) calc(${Math.round((quizStats.num_quiz_submissions / quizStats.num_users) * 100)}*3.6deg), transparent 0deg);"></span>
                    <p id="quiz-submission-rate" class="big-number">${Math.round((quizStats.num_quiz_submissions / quizStats.num_users) * 100)}%</p>
                </div>
            </div>
        `;
    }
}