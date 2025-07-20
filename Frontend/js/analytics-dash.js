// initialize charts when 
// Google Charts library is loaded
function initializeCharts() {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(() => {
        loadInteractionsAnalytics();
        drawPageVisitsBarChart();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // call functions when
    // the page loads
    getUserStats();
    getQuizStats();
    initializeCharts();

    // get the package for a column chart from Google Charts
    google.charts.load('current', {packages: ['corechart']});
    // set the chart with the drawChart function
    google.charts.setOnLoadCallback(drawChart);
});

function initialize() {
    drawChart(); // Initial draw
}

async function drawChart() {
    // get time unit and date values from inputs
    const unit = document.getElementById('unitOfTimeDropdown').value;
    const startDateStr = document.getElementById('start-date').value;
    const endDateStr = document.getElementById('end-date').value;

    // presicely get date information from input value strings
    const [startYear, startMonth, startDay] = startDateStr.split('-').map(Number);
    const [endYear, endMonth, endDay] = endDateStr.split('-').map(Number);

    // create start and end date objects from this information
    const startDate = new Date(startYear, startMonth - 1, startDay);
    const endDate = new Date(endYear, endMonth - 1, endDay);

    // call getChartData to set chart data
    const chartData = await getChartData(unit, startDate, endDate);
    // set the top value for the y-axis
    const maxRegistrations = Math.max(...chartData.slice(1).map(row => row[1]));
    const axisMax = Math.max(5, maxRegistrations);
    // set the data for the chart from chartData
    const data = google.visualization.arrayToDataTable(chartData);

    // set design choices for chart
    const options = {
        title: '',
        titleTextStyle: {
            color: "#9E79B6",
            fontName: "Aptos",
            fontSize: 18,
            bold: false,
            italic: false
        },
        // set pixel lengths for areas around the chart
        chartArea: {
            left: 60,
            right: 20,
            top: 40,
            bottom: 200
        },
        hAxis: {
            // set the x-axis title from the unit of time selected
            title: unit,
            textStyle: {
                color: "black",
                fontName: "Aptos",
                fontSize: 16
            },
            // undo default axis title italicization
            titleTextStyle: {
                italic: false
            },
            // give axis values a slant to make them fet better
            slantedText: true,
            slantedTextAngle: 75
        },
        vAxis: {
            title: 'Registrations',
            textStyle: {
                color: "black",
                fontName: "Aptos",
                fontSize: 16
            },
            // undo default axis title italicization
            titleTextStyle: {
                italic: false
            },
            // use the axisMax value previously set to set the top value of the y-axis
            viewWindow: {
                min: 0,
                max: axisMax
            }
        },
        legend: {
            position: 'none',
            textStyle: {
                color: "black",
                fontName: "Aptos",
                fontSize: 14
            }
        },
        // this is the section that pops up when hovering over a bar
        tooltip: {
            textStyle: {
                color: "black",
                fontName: "Aptos",
                fontSize: 12
            }
        },
        colors: [ '#DE8AAA' ]
    };

    // get the chart container and draw the chart inside it
    var chart = new google.visualization.ColumnChart(document.getElementById('chartContainer'));
    chart.draw(data, options);
}

async function getChartData(unit, start, end) {
    // if the unit selected is Days
    if (unit === 'Days') {
        // if the start or end dates have not been selected, fill the table with empty data
        if (isNaN(start) || isNaN(end)) {
            return [['Day', 'Registrations'] , ['Select Dates', 0]];
        }
        // get the registration stats for the unit, the start date, and the end date
        const response = await fetch(`/COP4813_FriendFinder/Backend/Database/registration-stats.php?unit=${encodeURIComponent(unit)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        // wait for json response
        const registrationStats = await response.json();
        // if success is false
        if (!registrationStats.success) {
            console.error('Failed to fetch registration stats:', registrationStats.message);
            return [['Day', 'Registrations'], ['Select Dates', 0]];
        }

        // continue since success is true
        const registrationData = registrationStats.data;

        // start the days array with the names of the axes
        let days = [['Day', 'Registrations']];
        // loop through each day from start to end
        let i = 0;
        for (let j = start; j <= end; j.setDate(j.getDate() + 1)) {
            // format the date the loop is currently on to YYYY-MM-DD
            const formatted = j.toISOString().slice(0, 10);
            // if the current date matches a date in the registration data
            if (i < registrationData.length && formatted === registrationData[i]?.day) {
                // add the formatted date and the number of registrations for that date to the days array
                days.push([formatted, registrationData[i].registrations]);
                // increment the index for the registration data so that the next date can be compared
                i++;
            }
            else {
                // if the date does not match, add the formatted date and 0 registrations to the days array
                days.push([formatted, 0]);
            }
        }
        // return the days array for use by the table
        return days;
    }
    // if the unit selected is Weeks
    else if (unit === 'Weeks') {
        // if the start or end dates have not been selected, fill the table with empty data
        if (isNaN(start) || isNaN(end)) {
            return [['Week', 'Registrations'] , ['Select Dates', 0]];
        }
        // get the registration stats for the unit, the start date, and the end date
        const response = await fetch(`/COP4813_FriendFinder/Backend/Database/registration-stats.php?unit=${encodeURIComponent(unit)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        // wait for json response
        const registrationStats = await response.json();

        // if success is false
        if (!registrationStats.success) {
            console.error('Failed to fetch registration stats:', registrationStats.message);
            return [['Week', 'Registrations'], ['Select Dates', 0]];
        }

        // continue since success is true
        const registrationData = registrationStats.data;

        // start the weeks array with the names of the axes
        let weeks = [['Week', 'Registrations']];
        // get the integer day of the week of the start date
        const dayOfFirstWeekStartInt = start.getDay();
        // get the sunday of that week
        const firstWeekSunday = start;
        firstWeekSunday.setDate(start.getDate() - dayOfFirstWeekStartInt);

        // loop through every Sunday thereafter before the end date
        let i = 0;
        for (let j = firstWeekSunday; j <= end; j.setDate(j.getDate() + 7)) {
            // get the Saturday of the week
            const jSaturday = new Date(j);
            jSaturday.setDate(j.getDate() + 6);

            // format the week as YYYY-MM-DD - YYYY-MM-DD,
            // where the first day of the first week should be the start date if the start date comes after that week's Sunday,
            // and the last day of the last week should be the end date if the end date comes before that week's Saturday
            let formatted = '';
            if (start > j)
                formatted = `${start.toISOString().slice(0, 10)} - ${jSaturday.toISOString().slice(0, 10)}`;
            else if (end < jSaturday)
                formatted = `${j.toISOString().slice(0, 10)} - ${end.toISOString().slice(0, 10)}`;
            else
                formatted = `${j.toISOString().slice(0, 10)} - ${jSaturday.toISOString().slice(0, 10)}`;

            if (i >= registrationData.length) {
                weeks.push([formatted, 0]);
                continue;
            }

            // get the week number and year from the registration data
            const weekInt = registrationData[i]?.week;
            const year = parseInt(weekInt.toString().slice(0, 4), 10);
            const weekNum = parseInt(weekInt.toString().slice(4), 10);

            // Find the first day of that year
            const jan1 = new Date(year, 0, 1);
            // Find the first Sunday of that year
            const weekStartOffset = (7 - jan1.getDay()) % 7;
            const weekStart = new Date(year, 0, 1 + weekStartOffset);

            // Find the date that the week from registrationData[i] starts on
            const weekSunday = new Date(weekStart);
            weekSunday.setDate(weekStart.getDate() + (weekNum - 1) * 7);

            // if we're not at the end of registration data
            // and the week from registrationData[i] matches the week we're currently on
            if (i < registrationData.length && j.toISOString().slice(0, 10) === weekSunday.toISOString().slice(0, 10)) {
                // add the formatted week and the number of registrations for that week to the weeks array
                weeks.push([formatted, registrationData[i].registrations]);
                // increment the index for the registration data so that the next week can be compared
                i++;
            }
            else {
                // otherwise, add the formatted week and 0 registrations to the weeks array
                weeks.push([formatted, 0]);
            }
        }
        // return the weeks array for use by the table
        return weeks;
    }
    // if the unit selected is Months
    else if (unit === 'Months') {
        // if the start or end dates have not been selected, fill the table with empty data
        if (isNaN(start) || isNaN(end)) {
            return [['Month', 'Registrations'], ['Select Dates', 0]];
        }
        // get the registration stats for the unit, the start date, and the end date
        const response = await fetch(`/COP4813_FriendFinder/Backend/Database/registration-stats.php?unit=${encodeURIComponent(unit)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        // wait for json response
        const registrationStats = await response.json();
        const registrationData = registrationStats.data;

        // if success is false
        if (!registrationStats.success) {
            console.error('Failed to fetch registration stats:', registrationStats.message);
            return [['Month', 'Registrations'], ['Select Dates', 0]];
        }

        // start the months array with the names of the axes
        let months = [['Month', 'Registrations']];
        let i = 0;
        // loop through each month from the start date to the end date
        for (let j = start; j <= end; j.setMonth(j.getMonth() + 1)) {
            // format the month as YYYY-MM
            const formatted = j.toISOString().slice(0, 7);
            // if the current month matches a month in the registration data
            if (i < registrationData.length && formatted === registrationData[i]?.month) {
                // add the formatted month and the number of registrations for that month to the months array
                months.push([formatted, registrationData[i].registrations]);
                // increment the index for the registration data so that the next month can be compared
                i++;
            }
            else {
                // if the month does not match, add the formatted month and 0 registrations to the months array
                months.push([formatted, 0]);
            }
        }
        // return the months array for use by the table
        return months;
    }
}

// function to get user
// statistics
async function getUserStats() {
    // fetch user statistics
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/user-statistics.php', {
        method: 'GET',
        credentials: 'same-origin'
    });

    // wait for json response
    const userStats = await response.json();

    // if the response JSON's success value is false
    if (!userStats.success) {
        // display error message and return
        console.error('Failed to fetch user statistics:', userStats.message);
        return;
    }

    // call function to
    // update user stats with the date
    updateUserStats(userStats.data[0]);   
}

// function to update 
// user stats
function updateUserStats(userStats) {
    // access user-stats section
    const analyticsCards = document.getElementById('user-stats');
    const userRegistrations = document.getElementById('user-registrations');

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

    if (userRegistrations) {
        userRegistrations.innerHTML = `
            <div class="analytics-card">
                <h3>User Registrations Over Time</h3>
                <p class="smaller-text">
                    View by 
                    <select id="unitOfTimeDropdown" name="unitOfTime">
                        <option value="Days">Days</option>
                        <option value="Weeks">Weeks</option>
                        <option value="Months">Months</option>
                    </select>
                    from
                    <input type="date" id="start-date" name="start-date">
                    to
                    <input type="date" id="end-date" name="end-date">
                </p>
                <div id="chartContainer" style="width: 100%; height: 500px;"></div>
            </div>
        `;

        const timeUnitInput = document.getElementById('unitOfTimeDropdown');
        timeUnitInput.addEventListener('change', function(event) {
            drawChart();
        });

        const startDateInput = document.getElementById('start-date');
        startDateInput.addEventListener('change', function(event) {
            drawChart();
        });

        const endDateInput = document.getElementById('end-date');
        endDateInput.addEventListener('change', function(event) {
            drawChart();
        });
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

    // wait for json response
    const quizStats = await response.json();

    // if quizStats's success value is false
    if (!quizStats.success) {
        // display error message
        console.error('Failed to fetch quiz statistics:', quizStats.message);
        return;
    }

    // call function to
    // update quiz stats with the data
    updateQuizStats(quizStats.data[0]); 
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
    // fetch interactions analytics data
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/get-interactions-analytics.php', {
        method: 'GET',
        credentials: 'same-origin'
    });
    
    // get the response text
    const interactionsData = await response.json();

    // if interactionsData's success value is false
    if (!interactionsData.success) {
        // display error message
        console.error('Failed to fetch interactions analytics:', interactionsData.message);
        return;
    }
    
    // call function to update 
    // interactions stats cards
    updateInteractionsStatsCards(interactionsData.data);
    
    // call function to draw 
    // pie chart
    drawInteractionsPieChart(interactionsData.data);

    // call function to draw
    // bar graph
    drawPageVisitsBarChart();
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
        colors: ['#9E79B6', '#DE8AAA', '#F9D1DF'],
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

// function to draw page visits bar chart
function drawPageVisitsBarChart() {
    // fetch page visits data
    fetch('/COP4813_FriendFinder/Backend/Database/get-page-visits.php')
        .then(response => response.json())
        .then(pageVisits => {
            // if success is false
            if (!pageVisits.success) {
                // display error message
                console.error('Failed to fetch page visits:', pageVisits.message);
                return;
            }

            // create data array
            const dataArr = [['Page', 'Visits']];
            pageVisits.data.forEach(row => {
                dataArr.push([row.page_name, parseInt(row.visit_count)]);
            });

            // create data table
            const data = google.visualization.arrayToDataTable(dataArr);

            // for bar graph title
            const options = {
                title: 'Page Visits',
                colors: ['#DE8AAA']
            };

            // draw bar graph using google charts
            const chart = new google.visualization.BarChart(document.getElementById('page_visits_bar_chart'));
            chart.draw(data, options);
        });
}