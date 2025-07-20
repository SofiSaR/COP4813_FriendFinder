<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // set content type to JSON
    header('Content-Type: application/json');

    $userIdData = json_decode(file_get_contents('php://input'), true);
    $userId = $userIdData['user_id'] ?? null;

    // encode the SQL query that gets the user with the given ID
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Users WHERE id = ?;",
        'params' => ['i', $userId]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the user profile query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'User profile query failed: ' . $result['message']
        ]);
        exit();
    }

    // check if the result contains data
    if (count($result['data']) > 0) {
        // fetch user data
        $user_data = $result['data'];

        // return success response
        // with user data
        echo json_encode([
            'success' => true,
            'data' => $user_data
        ]);
    } else {
        // user doesn't exist
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
    }
?>