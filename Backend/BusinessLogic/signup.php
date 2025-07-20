<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // get form data
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $jsonSQL = json_encode([
        'sql' => "SELECT id FROM Users WHERE email = ?",
        'params' => ['s', $email]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Check for user pre-existence query failed: ' . $result['message']
        ]);
        exit();
    }

    // if user already exists, return false
    if (count($result['data']) > 0) {
        echo json_encode([
            'status' => 'false',
            'message' => 'User already exists'
        ]);
        exit();
    }

    // insert the user into the database
    $jsonSQL = json_encode([
        'sql' => "INSERT INTO Users (first_name, last_name, email, pwd) VALUES (?, ?, ?, ?)",
        'params' => ['ssss', $firstName, $lastName, $email, $hashedPassword]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'User insertion failed: ' . $result['message']
        ]);
        exit();
    }

    // get the user ID 
    // from the database
    $jsonSQL = json_encode([
        'sql' => "SELECT id FROM Users WHERE email = ?",
        'params' => ['s', $email]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Could not check for successful insertion: ' . $result['message']
        ]);
        exit();
    }

    if (count($result['data']) > 0) {
        // store user ID for session 
        // and history logging
        $_SESSION['user_id'] = $result['data'][0]['id'];

        // close the session
        session_write_close();

        // return success
        echo json_encode([
            'status' => 'true',
            'message' => 'Registration successful'
        ]);
    }
    else
        // registration failed
        echo json_encode([
            'status' => 'failed',
            'message' => 'Registration failed'
        ]);
?>