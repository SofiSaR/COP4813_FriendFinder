<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/admin-login.php');

    // prepare and execute query 
    // to fetch user data
    $jsonSQL = json_encode(['sql' => "
        SELECT 
            COUNT(*) as total_users,
            SUM(account_active = 1) as active_users,
            SUM(account_active = 0) as inactive_users
        FROM Users
    "]);
    
    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the active and inactive users query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Active and inactive users query failed: ' . $result['message']
        ]);
        exit();
    }

    // check if user exists and is active
    if ($result['data'] && count($result['data']) > 0) {
        // fetch user data
        $user_data = $result['data'];
        
        // return success response
        // with user data
        echo json_encode([
            'success' => true,
            'data' => $user_data
        ]);
    } else {
        // return failure message since user activity data was not found
        echo json_encode([
            'success' => false,
            'message' => 'User activity data not found'
        ]);
    }
?>