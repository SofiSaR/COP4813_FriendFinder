<?php
    // start the session
    session_start();

    // get form data
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $check_sql = "SELECT id FROM Users WHERE email = '$email'";
    $jsonSQL = json_encode(['sql' => $check_sql]);

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

    if (!empty($result)) {
        echo 'false';
        exit();
    }

    // insert the user into the database
    $insert_sql = "INSERT INTO Users (first_name, last_name, email, pwd) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
    $jsonSQL = json_encode(['sql' => $insert_sql]);

    // reset the cURL connection
    curl_reset($ch);

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
    curl_exec($ch);

    // get the user ID 
    // from the database
    $get_id_sql = "SELECT id FROM Users WHERE email = '$email'";
    $jsonSQL = json_encode(['sql' => $get_id_sql]);

    // reset the cURL connection
    curl_reset($ch);

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

    if (!empty($result)) {
        // store user ID for session 
        // and history logging
        $_SESSION['user_id'] = $result[0]['id'];

        // close the session
        session_write_close();

        // return success
        echo 'true';
    }
    else
        // registration failed
        echo 'failed';
?>