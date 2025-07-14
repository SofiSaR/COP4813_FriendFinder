<?php
    // start the session
    session_start();

    // get email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // get user id and password
    // from the database
    $sql = "SELECT id, pwd FROM Admins WHERE email = '$email'";

    // encode the SQL query
    $jsonSQL = json_encode(['sql' => $sql]);

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

    // if user exists and password is correct
    if (!empty($result) && password_verify($password, $result[0]['pwd'])) {
        // store user ID for session 
        // and history logging
        $_SESSION['user_id'] = $result[0]['id'];
        $_SESSION['admin'] = true;
        session_write_close();
        echo 'true';
    }
    else
        // login failed
        echo 'false';
?>
