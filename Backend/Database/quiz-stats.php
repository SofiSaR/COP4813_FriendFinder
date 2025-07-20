<?php
    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: /COP4813_FriendFinder/Frontend/admin-login.php');

    // encode the SQL query for getting all users
    $jsonSQL = json_encode(['sql' => "
        SELECT 
         (SELECT CAST((COUNT(*)/25) AS INT) FROM Quiz_Responses) as num_quiz_submissions,
         (SELECT COUNT(*) FROM Users) as num_users;
    "]);

    // initialize cURL to send the 
    // query to the database API
    $ch = curl_init();

    // set the cURL options
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    // execute the query to fetch user data
    // and get the result
    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the quiz submissions query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Quiz submissions query failed: ' . $result['message']
        ]);
        // close the connection
        curl_close($ch);
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

    // close the connection
    curl_close($ch);
?>