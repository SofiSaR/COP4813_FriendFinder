<?php
    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';
    // start the session
    session_start();

    // check if user is logged in
    if (isset($_SESSION['user_id'])) {
        // user is logged in
        $userId = $_SESSION['user_id'];
    }
    else {
        // user is not logged in
        $userId = null;
        echo json_encode([
            'success' => false,
            'message' => 'User not logged in']);
        exit();
    }

    // get the answers JSON passed in
    $answers = json_decode(file_get_contents('php://input'), true);

    // initialize scores json
    $scores = [];



    // get the quiz responses for this user
    // to check if the user has already submitted the quiz before
    $jsonSQL = json_encode([
        'sql' => "SELECT * FROM Quiz_Responses WHERE user_id = ?;",
        'params' => ['i', $userId]
    ]);

    // run the query by sending $jsonSQL to the function in query.php
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the result was unsuccessful, say the check for previous quiz submission failed
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Check for previous quiz submission failed: ' . $result['message']
        ]);
        exit();
    }

    // if the user has already submitted the quiz before
    // update the quiz responses for this user with new quiz responses
    if (count($result['data']) > 0) {
        // update each quiz response
        foreach ($answers as $question_id => $answer) {
            $jsonSQL = json_encode([
                'sql' => "UPDATE Quiz_Responses SET response = ? WHERE user_id = ? AND question_id = ?",
                'params' => ['iii', $answer, $userId, $question_id]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say quiz response updates failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Quiz response updates failed: ' . $result['message']
                ]);
                exit();
            }
        }

        $categories = ['Sociability', 'Adventurousness', 'Reliability', 'Athleticism', 'Availability'];
        foreach ($categories as $category) {
            // update or insert quiz scores for each category
            updateOrInsertQuizScores('update', $category);
        }

        // if all scores have been updated successfully
        echo json_encode([
            'success' => true,
            'message' => 'Quiz responses and scores updated successfully.'
        ]);

        exit();
    }
    else {
        // if the user has not submitted the quiz before
        // insert new quiz responses for this user
        foreach ($answers as $question_id => $answer) {
            $jsonSQL = json_encode([
                'sql' => "INSERT INTO Quiz_Responses (user_id, question_id, response) VALUES (?, ?, ?)",
                'params' => ['iii', $userId, $question_id, $answer]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say quiz response inserts failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Quiz response inserts failed: ' . $result['message']
                ]);
                exit();
            }
        }

        $categories = ['Sociability', 'Adventurousness', 'Reliability', 'Athleticism', 'Availability'];
        foreach ($categories as $category) {
            // update or insert quiz scores for each category
            updateOrInsertQuizScores('insert', $category);
        }

        // if all scores have been inserted successfully
        echo json_encode([
            'success' => true,
            'message' => 'Quiz responses and scores inserted successfully.'
        ]);

        exit();
    }

    exit();
    
    // function to update or insert
    // quiz scores in the database
    function updateOrInsertQuizScores($mode, $category) {
        global $userId;
        global $scores;

        // get the responses for each question in the given category for this user
        $jsonSQL = json_encode([
            'sql' => "SELECT * FROM Quiz_Responses JOIN Quiz_Questions ON Quiz_Responses.question_id = Quiz_Questions.id WHERE Quiz_Responses.user_id = ? AND Quiz_Questions.category = ?;",
            'params' => ['is', $userId, $category]
        ]);

        // run the query by sending $jsonSQL to the function in query.php
        // and get the result
        $json_response = runSQLQuery($jsonSQL);
        $result = json_decode($json_response, true);

        // if the result was unsuccessful, say the retrieval of quiz responses failed
        if (!$result['success']) {
            echo json_encode([
                'success' => false,
                'message' => 'Retrieval of quiz responses failed: ' . $result['message']
            ]);
            exit();
        }

        // calculate and store the score for this category
        $categoryScore = 0;
        foreach ($result['data'] as $row) {
            $categoryScore += ($row['response'] - 1) * 5;
        }
        $scores[$category] = $categoryScore;

        // if updating category score
        if ($mode == 'update') {
            // update the quiz score for this category
            $jsonSQL = json_encode([
                'sql' => "UPDATE Quiz_Scores SET ? = ? WHERE user_id = ?",
                'params' => ['sii', strtolower($category) . '_score', $categoryScore, $userId]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the category score update failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Category score update failed: ' . $result['message']
                ]);
                exit();
            }
        }
        else if ($mode == 'insert' && count($scores) == 5) {
            // if inserting category score
            // insert the quiz scores
            $jsonSQL = json_encode([
                'sql' => "INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score) VALUES (?, ?, ?, ?, ?, ?)",
                'params' => ['iiiiii', $userId, $scores['Sociability'], $scores['Adventurousness'], $scores['Reliability'], $scores['Athleticism'], $scores['Availability']]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the category score insertion failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Category score insertion failed: ' . $result['message']
                ]);
                exit();
            }

            // check for successful insertion
            $jsonSQL = json_encode([
                'sql' => "SELECT * FROM Quiz_Scores WHERE user_id = ?;",
                'params' => ['i', $userId]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the category score insertion check failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Category score insertion check failed: ' . $result['message']
                ]);
                exit();
            }
        }
    }
?>