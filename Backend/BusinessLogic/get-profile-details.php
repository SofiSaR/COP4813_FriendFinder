<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in
    if (!isset($_SESSION['user_id']))
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/login.php');

    // get user ID from session
    $user_id = $_SESSION['user_id'];

    // encode the SQL query that gets the user with the given ID
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Users WHERE id = ? AND account_active = 1",
        'params' => ['i', $user_id]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the profile details query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Profile details query failed: ' . $result['message']
        ]);
        exit();
    }

    // check if user exists and is active
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
        // or their account is inactive
        echo json_encode([
            'success' => false,
            'message' => 'User not found or account inactive'
        ]);
    }
?>