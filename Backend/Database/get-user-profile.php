<?php
    // set content type to JSON
    header('Content-Type: application/json');

    $userIdData = json_decode(file_get_contents('php://input'), true);
    $userId = $userIdData['user_id'] ?? null;

    // encode the SQL query that gets the user with the given ID
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Users WHERE id = ?;",
        'params' => ['i', $userId]
    ]);

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

    // execute the query 
    // and get the result
    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the user profile query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'User profile query failed: ' . $result['message']
        ]);
        // close the connection
        curl_close($ch);
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

    // close the connection
    curl_close($ch);
?>