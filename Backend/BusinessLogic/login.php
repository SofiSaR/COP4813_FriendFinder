<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // get email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // encode the SQL query that gets the user id, password, and
    // whether their account is active from the database
    $jsonSQL = json_encode([
        'sql' => "SELECT id, pwd, account_active FROM Users WHERE email = ?",
        'params' => ['s', $email]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the check for matching email query failed
    if (!$result['success']) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Check for matching email query failed: ' . $result['message']
        ]);
        exit();
    }

    // if user exists and password is correct
    if (count($result['data']) > 0 && password_verify($password, $result['data'][0]['pwd'])) {
        // if the account is inactive
        if ($result['data'][0]['account_active'] == 0) {
            // return inactive status
            echo json_encode([
                'status' => 'inactive',
                'message' => 'Account is deactivated'
            ]);
            exit();
        }

        // store user ID for session 
        // and history logging
        $user_id = $result['data'][0]['id'];

        // set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['admin'] = false;

        // insert login history record to
        // table, after successful login
        $history_jsonSQL = json_encode([
            'sql' => "INSERT INTO Login_History (user_id) VALUES (?)",
            'params' => ['i', $user_id]
        ]);

        // run the query by sending $jsonSQL to the function in query.php
        // and get the result
        $history_response = runSQLQuery($jsonSQL);
        $history_result = json_decode($json_response, true);

        // if the result was unsuccessful, say the login history query failed
        if (!$history_result['success']) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Login history query failed: ' . $history_result['message']
            ]);
            exit();
        }

        // close session writing 
        // and return success
        session_write_close();
        echo json_encode([
            'status' => 'true',
            'message' => 'Login successful'
        ]);
    }
    // login failed
    else {
        echo json_encode([
            'status' => 'false',
            'message' => 'Login failed'
        ]);
    }
?>
