<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/admin-login.php');

    // encode the SQL query for getting all users
    $jsonSQL = json_encode(['sql' => "
        SELECT 
         (SELECT CAST((COUNT(*)/25) AS INT) FROM Quiz_Responses) as num_quiz_submissions,
         (SELECT COUNT(*) FROM Users) as num_users;
    "]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the quiz submissions query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Quiz submissions query failed: ' . $result['message']
        ]);
        exit();
    }

    // check if we have data
    if (count($result['data']) > 0) {
        
        // return success response
        // with user submission count
        echo json_encode([
            'success' => true,
            'data' => $result['data']
        ]);
    } else {
        // query returned no data
        echo json_encode([
            'success' => false,
            'message' => 'Quiz submissions query failed: ' . $result['message']
        ]);
    }
?>