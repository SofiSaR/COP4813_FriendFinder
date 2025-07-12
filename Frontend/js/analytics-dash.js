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
                <p id="total-users">${userStats.total_users}</p>
            </div>
            <div class="analytics-card">
                <h3>Active Users</h3>
                <p id="active-users">${userStats.active_users}</p>
            </div>
            <div class="analytics-card">
                <h3>Inactive Users</h3>
                <p id="inactive-users">${userStats.inactive_users}</p>
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
                <p id="total-users">${quizStats.num_quiz_submissions}</p>
            </div>
        `;
    }
}