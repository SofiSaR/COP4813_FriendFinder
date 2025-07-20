<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in
    if (!isset($_SESSION['user_id']) && $_SESSION['admin'] !== true)
        header('Location: admin-login.html');

    $unit = isset($_GET['unit']) ? $_GET['unit'] : null;
    $start = isset($_GET['start']) ? $_GET['start'] : null;
    $end = isset($_GET['end']) ? $_GET['end'] : null;

    // prepare and execute query 
    // to fetch user data
    if ($unit === 'Days') {
        $jsonSQL = json_encode([
            'sql' => "
                SELECT DATE_FORMAT(registration_date, '%Y-%m-%d') AS day, COUNT(*) AS registrations
                FROM Users
                WHERE registration_date BETWEEN ? AND ?
                GROUP BY day
                ORDER BY day;
            ",
            'params' => ['ss', $start, $end]
        ]);
    }
    else if ($unit === 'Weeks') {
        $jsonSQL = json_encode([
            'sql' => "
                SELECT YEARWEEK(registration_date, 0) AS week, COUNT(*) AS registrations
                FROM Users
                WHERE registration_date BETWEEN ? AND ?
                GROUP BY week
                ORDER BY week;
            ",
            'params' => ['ss', $start, $end]
        ]);
    }
    else if ($unit === 'Months') {
        $jsonSQL = json_encode([
            'sql' => "
                SELECT DATE_FORMAT(registration_date, '%Y-%m') AS month, COUNT(*) AS registrations
                FROM Users
                WHERE registration_date BETWEEN ? AND ?
                GROUP BY month
                ORDER BY month;
            ",
            'params' => ['ss', $start, $end]
        ]);
    }
    
    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'User registrations query failed: ' . $result['message']
        ]);
        exit();
    }
    
    echo json_encode([
        'success' => true,
        'data' => $result['data']
    ]);
?>