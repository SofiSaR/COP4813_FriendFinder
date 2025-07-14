document.addEventListener('DOMContentLoaded', function() {
    getUserStats();
    getQuizStats();

    // get the package for a column char from Google Chars
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
    if (unit === 'Days') {
        if (isNaN(start) || isNaN(end)) {
            return [['Day', 'Registrations'] , ['Select Dates', 0]];
        }
        const response = await fetch(`/COP4813_FriendFinder/Backend/Database/registration-stats.php?unit=${encodeURIComponent(unit)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        // wait for json response
        const registrationStats = await response.json();
        const registrationData = registrationStats.data;

        let days = [['Day', 'Registrations']];
        let i = 0;
        for (let j = start; j <= end; j.setDate(j.getDate() + 1)) {
            const formatted = j.toISOString().slice(0, 10);
            if (i < registrationData.length && formatted === registrationData[i]?.day) {
                days.push([formatted, registrationData[i].registrations]);
                i++;
            }
            else {
                days.push([formatted, 0]);
            }
        }
        return days;
    } else if (unit === 'Weeks') {
        if (isNaN(start) || isNaN(end)) {
            return [['Day', 'Registrations'] , ['Select Dates', 0]];
        }
        const response = await fetch(`/COP4813_FriendFinder/Backend/Database/registration-stats.php?unit=${encodeURIComponent(unit)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        // wait for json response
        const registrationStats = await response.json();
        const registrationData = registrationStats.data;

        let weeks = [['Week', 'Registrations']];

        const dayOfFirstWeekStartInt = start.getDay();

        const firstWeekSunday = start;
        firstWeekSunday.setDate(start.getDate() - dayOfFirstWeekStartInt);

        let i = 0;
        for (let j = firstWeekSunday; j <= end; j.setDate(j.getDate() + 7)) {
            const jSaturday = new Date(j);
            jSaturday.setDate(j.getDate() + 6);

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

            const weekInt = registrationData[i]?.week;
            const year = parseInt(weekInt.toString().slice(0, 4), 10);
            const weekNum = parseInt(weekInt.toString().slice(4), 10);

            // Find the first day of the year
            const jan1 = new Date(year, 0, 1);
            // Find the first Sunday of the year
            const weekStartOffset = (7 - jan1.getDay()) % 7;
            const weekStart = new Date(year, 0, 1 + weekStartOffset);

            // Calculate the start Sunday of the week
            const weekSunday = new Date(weekStart);
            weekSunday.setDate(weekStart.getDate() + (weekNum - 1) * 7);

            if (i < registrationData.length && j.toISOString().slice(0, 10) === weekSunday.toISOString().slice(0, 10)) {
                weeks.push([formatted, registrationData[i].registrations]);
                i++;
            }
            else {
                weeks.push([formatted, 0]);
            }
        }
        return weeks;
    } else if (unit === 'Months') {
        if (isNaN(start) || isNaN(end)) {
            return [['Day', 'Registrations'] , ['Select Dates', 0]];
        }
        const response = await fetch(`/COP4813_FriendFinder/Backend/Database/registration-stats.php?unit=${encodeURIComponent(unit)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        // wait for json response
        const registrationStats = await response.json();
        const registrationData = registrationStats.data;

        let months = [['Month', 'Registrations']];
        let i = 0;
        for (let j = start; j <= end; j.setMonth(j.getMonth() + 1)) {
            const formatted = j.toISOString().slice(0, 7);
            if (i < registrationData.length && formatted === registrationData[i]?.month) {
                months.push([formatted, registrationData[i].registrations]);
                i++;
            }
            else {
                months.push([formatted, 0]);
            }
        }
        return months;
    }
}

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
    const userRegistrations = document.getElementById('user-registrations');

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