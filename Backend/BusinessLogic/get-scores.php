<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // set content type to JSON
    header('Content-Type: application/json');

    $userIdData = json_decode(file_get_contents('php://input'), true);
    $userId = $userIdData['user_id'];

    // encode the SQL query that gets the user with the given ID
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Quiz_Scores WHERE user_id = ?;",
        'params' => ['i', $userId]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the quiz scores query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Quiz scores query failed: ' . $result['message']
        ]);
        exit();
    }

    // check if the result contains data
    if (count($result['data']) > 0) {
        // return success response
        // with score data
        echo json_encode([
            'success' => true,
            'data' => $result['data']
        ]);
    } else {
        // quiz scores don't exist
        echo json_encode([
            'success' => false,
            'message' => 'Quiz scores not found'
        ]);
    }
?>