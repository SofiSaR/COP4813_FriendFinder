<?php
    // start the session
    session_start();

    // get email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // get user id, password, and
    // whether their account is active
    // from the database
    $sql = "SELECT id, pwd, account_active FROM Users WHERE email = '$email'";

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

    // if user exists, password is correct, and account is active
    if (!empty($result) && password_verify($password, $result[0]['pwd']) && $result[0]['account_active'] == 1) {
        // store user ID for session 
        // and history logging
        $user_id = $result[0]['id'];

        // set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['admin'] = false;

        // insert login history record to
        // table, after successful login
        $history_sql = "INSERT INTO Login_History (user_id) VALUES ($user_id)";
        $history_jsonSQL = json_encode(['sql' => $history_sql]);

        // initialize new cURL connection 
        // for history logging
        $history_ch = curl_init();

        // set the cURL options
        curl_setopt($history_ch, CURLOPT_URL, 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php');
        curl_setopt($history_ch, CURLOPT_POST, 1);
        curl_setopt($history_ch, CURLOPT_POSTFIELDS, $history_jsonSQL);
        curl_setopt($history_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($history_ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($history_jsonSQL)
        ]);
        
        // execute the history insert query
        $history_response = curl_exec($history_ch);
        curl_close($history_ch);

        // close session writing 
        // and return success
        session_write_close();
        echo 'true';
    }
    // check if user exists, password is correct, 
    // but account is deactivated
    else if (!empty($result) && password_verify($password, $result[0]['pwd']) && $result[0]['account_active'] == 0) {
        // return inactive status
        echo 'inactive';
    }
    // login failed
    else
        echo 'false';
?>
