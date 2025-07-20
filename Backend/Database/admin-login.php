<?php
    // start the session
    session_start();

    // get email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // encode the SQL query that gets the user id and password from the database
    $jsonSQL = json_encode([
        'sql' => "SELECT id, pwd FROM Admins WHERE email = ?",
        'params' => ['s', $email]
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

    // close the connection
    curl_close($ch);

    // if the query failed, return the error message and exit the script
    if (!$result['success']) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Admins query failed: ' . $result['message']
        ]);
        exit();
    }

    // if admin exists and password is correct
    if (count($result['data']) > 0 && password_verify($password, $result['data'][0]['pwd'])) {
        // store user ID for session 
        // and history logging
        $_SESSION['user_id'] = $result['data'][0]['id'];
        $_SESSION['admin'] = true;
        session_write_close();
        echo json_encode([
            'status' => 'true',
            'message' => 'Admin login successful'
        ]);
    }
    // admin doesn't exist or password is incorrect
    else
        echo json_encode([
            'status' => 'false',
            'message' => 'Invalid email or password'
        ]);
?>
