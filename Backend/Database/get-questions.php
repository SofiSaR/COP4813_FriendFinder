<?php
    // encode the SQL query    
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Quiz_Questions;"
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

    // if the result was unsuccessful, say the question fetch failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Question fetch failed: ' . $result['message']
        ]);
        // close the connection
        curl_close($ch);
        exit();
    }

    // otherwise, return the success message
    echo json_encode([
        'success' => true,
        'data' => $result['data']
    ]);

    // close the curl connection
    curl_close($ch);
?>