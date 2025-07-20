<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true) {
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized access'
        ]);
        exit();
    }

    // encode the SQL query for the count of page visits for each page
    $jsonSQL = json_encode(['sql' => "SELECT page_name, visit_count FROM Page_Visits"]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the page visits query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Page visits query failed: ' . $result['message']
        ]);
        exit();
    }

    // check if we have data
    if (count($result['data']) > 0) {
        // return success response with data
        echo json_encode([
            'success' => true,
            'data' => $result['data']
        ]);
    } else {
        // no data found
        echo json_encode([
            'success' => false,
            'message' => 'No page visit data found'
        ]);
    }
?>