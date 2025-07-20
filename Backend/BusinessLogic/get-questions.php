<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';
    
    // encode the SQL query    
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Quiz_Questions;"
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the question fetch failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Question fetch failed: ' . $result['message']
        ]);
        exit();
    }

    // otherwise, return the success message
    echo json_encode([
        'success' => true,
        'data' => $result['data']
    ]);
?>