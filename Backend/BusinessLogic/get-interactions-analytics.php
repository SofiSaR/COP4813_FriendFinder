<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true) {
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized access'
        ]);
        exit();
    }

    // initialize variables
    $num_logins = 0;
    $num_quiz_submissions = 0;
    $num_bio_paragraphs = 0;
    
    // encode the SQL query for getting number of logins
    $jsonSQL = json_encode(['sql' => "SELECT COUNT(*) as count FROM Login_History"]);
    
    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $login_result = json_decode($json_response, true);

    // if the result was unsuccessful, say the login history query failed
    if (!$login_result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Login history query failed: ' . $login_result['message']
        ]);
    }

    // if the count is available, set the number of logins
    if ($login_result['data'] && count($login_result['data']) > 0) {
        $num_logins = $login_result['data'][0]['count'];
    }

    // encode the SQL query for getting number of quiz submissions
    $jsonSQL = json_encode(['sql' => "SELECT CAST((COUNT(*)/25) AS SIGNED) as count FROM Quiz_Responses"]);
    
    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $quiz_result = json_decode($json_response, true);

    // if the result was unsuccessful, say the quiz submissions query failed
    if (!$quiz_result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Quiz submissions query failed: ' . $quiz_result['message']
        ]);
    }

    // if the count is available, set the number of quiz submissions
    if ($quiz_result['data'] && count($quiz_result['data']) > 0) {
        $num_quiz_submissions = $quiz_result['data'][0]['count'];
    }

    // encode the SQL query for getting number of bio submissions
    $jsonSQL = json_encode(['sql' => "SELECT COUNT(*) as count FROM Users WHERE bio IS NOT NULL AND bio != ''"]);
    
    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $users_result = json_decode($json_response, true);

    // if the result was unsuccessful, say the bio paragraphs query failed
    if (!$users_result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Bio paragraphs query failed: ' . $users_result['message']
        ]);
    }

    // if the count is available, set the number of bio paragraphs
    if ($users_result['data'] && count($users_result['data']) > 0) {
        $num_bio_paragraphs = $users_result['data'][0]['count'];
    }
    
    // return the numbers
    echo json_encode([
        'success' => true,
        'data' => [
            'num_logins' => $num_logins,
            'num_quiz_submissions' => $num_quiz_submissions,
            'num_bio_paragraphs' => $num_bio_paragraphs
        ]
    ]);
?>