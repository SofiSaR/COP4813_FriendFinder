// initialize charts when 
// Google Charts library is loaded
function initializeCharts() {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(() => {
        loadInteractionsAnalytics();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // call functions when
    // the page loads
    getUserStats();
    getQuizStats();
    initializeCharts();
});

// function to get user
// statistics
async function getUserStats() {
    // fetch user statistics
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/user-statistics.php', {
        method: 'GET',
        credentials: 'same-origin'
    });

    // if response is not ok
    if (!response.ok) {
        // display error message
        throw new Error(`HTTP error. Status: ${response.status}`);
    }

    // wait for json response
    const userStats = await response.json();

    // if success is true
    if (userStats.success) {
            // call function to
            // update user stats
            updateUserStats(userStats.data);
    } else {
        // display error message
        console.error('Error fetching user data:', userStats.message);
    }    
}

// function to update 
// user stats
function updateUserStats(userStats) {
    // access user-stats section
    const analyticsCards = document.getElementById('user-stats');

    // update number of total users,
    // number of active users, and
    // number of inactive users
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

// function to get quiz
// statistics
async function getQuizStats() {
    // fetch quiz statistics
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/quiz-stats.php', {
        method: 'GET',
        credentials: 'same-origin'
    });

    // if response is not ok
    if (!response.ok) {
        // display error message
        throw new Error(`HTTP error. Status: ${response.status}`);
    }

    // wait for json response
    const quizStats = await response.json();

    // if success is true
    if (quizStats.success) {
            // call function to
            // update quiz stats
            updateQuizStats(quizStats.data);
    } else {
        // display error message
        console.error('Error fetching user data:', quizStats.message);
    }    
}

// function to update 
// quiz stats
function updateQuizStats(quizStats) {
    // access quiz-stats section
    const quizStatsElem = document.getElementById('quiz-stats');

    // update number of quiz submissions
    // and quiz submission rate
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

// function to load interactions 
// analytics (charts)
async function loadInteractionsAnalytics() {
    try {
        // fetch interactions analytics data
        const response = await fetch('/COP4813_FriendFinder/Backend/Database/get-interactions-analytics.php', {
            method: 'GET',
            credentials: 'same-origin'
        });
        
        // if response is not ok
        if (!response.ok) {
            // display error message
            throw new Error(`HTTP error. Status: ${response.status}`);
        }
        
        // get the response text
        const responseText = await response.text();
        
        // try to parse as JSON
        const result = JSON.parse(responseText);
        
        // if success is true
        if (result.success) {
            // initialize data
            const data = result.data;
            
            // call function to update 
            // interactions stats cards
            updateInteractionsStatsCards(data);
            
            // call function to draw 
            // pie chart
            drawInteractionsPieChart(data);
        } else {
            // display error messages
            console.error('Failed to load interactions analytics:', result.message);
            showError('interactions-stats', 'Failed to load interactions data');
        }
    } catch (error) {
        // display error messages
        console.error('Error fetching interactions analytics:', error);
        console.error('Error details:', error.message);
        showError('interactions-stats', 'Error loading interactions data');
    }
}

// function to update interactions
// stats cards
function updateInteractionsStatsCards(data) {
    // access interactions-stats section
    const container = document.getElementById('interactions-stats');
    
    // update number of logins,
    // quiz submissions, and bio paragraphs
    container.innerHTML = `
        <div class="analytics-card">
            <h3>Total Logins</h3>
            <p class="big-number">${formatNumber(data.num_logins)}</p>
        </div>
        <div class="analytics-card">
            <h3>Quiz Submissions</h3>
            <p class="big-number">${formatNumber(data.num_quiz_submissions)}</p>
        </div>
        <div class="analytics-card">
            <h3>Bio Paragraphs</h3>
            <p class="big-number">${formatNumber(data.num_bio_paragraphs)}</p>
        </div>
    `;
}

// function to draw interactions pie chart
function drawInteractionsPieChart(data) {
    // create data table
    const chartData = google.visualization.arrayToDataTable([
        ['Activity Type', 'Count'],
        ['Logins', parseInt(data.num_logins)],
        ['Quiz Submissions', parseInt(data.num_quiz_submissions)],
        ['Bio Submissions', parseInt(data.num_bio_paragraphs)]
    ]);

    const options = {
        // for pie chart title and colors
        title: 'User Interactions Breakdown',
        colors: ['#800080', '#0000FF', '#ffc0cb'],
    };

    // draw pie chart using google charts
    const chart = new google.visualization.PieChart(document.getElementById('interactionsPieChart'));
    chart.draw(chartData, options);
}

// function to format numbers
function formatNumber(num) {
    return parseInt(num).toLocaleString();
}

// function to display error messages
function showError(containerId, message) {
    // access containerId
    const container = document.getElementById(containerId);

    // print error message
    container.innerHTML = `
        <div class="error-message">
            <p>${message}</p>
        </div>
    `;
}