<?php
    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in
    if (!isset($_SESSION['user_id']))
        header('Location: /COP4813_FriendFinder/Frontend/login.php');

    // get user ID from session
    $user_id = $_SESSION['user_id'];

    $limitNum = json_decode(file_get_contents('php://input'), true);
    $limit = $limitNum['limit_num'] ?? null;

    // encode the SQL query that gets the user with the given ID
    $jsonSQL = json_encode([
        'sql' => 
            "SELECT Users.*,
                (100 - (
                    (0.20 * ABS(other_users_scores.sociability_score - this_users_scores.sociability_score)) +
                    (0.20 * ABS(other_users_scores.adventurousness_score - this_users_scores.adventurousness_score)) +
                    (0.20 * ABS(other_users_scores.reliability_score - this_users_scores.reliability_score)) +
                    (0.20 * ABS(other_users_scores.athleticism_score - this_users_scores.athleticism_score)) +
                    (0.20 * ABS(other_users_scores.availability_score - this_users_scores.availability_score))
                )) AS similarity_score
            FROM Users
            JOIN Quiz_Scores other_users_scores ON other_users_scores.user_id = Users.id
            JOIN Quiz_Scores this_users_scores ON this_users_scores.user_id = ?
            WHERE Users.id != ?
            ORDER BY similarity_score
            DESC LIMIT ?;",
        'params' => ['iii', $user_id, $user_id, $limit]
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

    // if the result was unsuccessful, say the match retrieval query failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Match retrieval query failed: ' . $result['message']
        ]);
        // close the connection
        curl_close($ch);
        exit();
    }

    // check if the result contains data
    if (count($result['data']) > 0) {
        // return success response
        // with score data
        echo json_encode([
            'success' => true,
            'data' => $result['data']
        ]);
    } else {
        // matches not found
        echo json_encode([
            'success' => false,
            'message' => 'Matches not found'
        ]);
    }

    // close the connection
    curl_close($ch);
?>