<?php
    // allow request methods
    header("Access-Control-Allow-Origin: https://friendshipmatchmaking.infinityfreeapp.com");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header("Access-Control-Allow-Headers: Content-Type");

    // use query.php to run SQL queries
    require __DIR__.'/../Database/query.php';

    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $method = $data['action'];

    switch ($method) {
        case 'get':
            // encode the SQL query for getting all users
            $jsonSQL = json_encode(['sql' => "SELECT * FROM Users"]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the users query failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Users query failed: ' . $result['message']
                ]);
                break;
            }

            // otherwise, return the users
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);

            break;
        case 'add':
            // insert a new user
            // into the database

            // encode the SQL query    
            $jsonSQL = json_encode([
                'sql' => "
                    INSERT INTO Users (pfpUrl, first_name, last_name, phone_number, email, pwd, bio, bio_approved, account_active)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
                ",
                'params' => [
                    'sssssssii', 
                    $data['pfpUrl'], 
                    $data['first_name'], 
                    $data['last_name'], 
                    $data['phone_number'], 
                    $data['email'], 
                    password_hash($data['pwd'], PASSWORD_DEFAULT), 
                    $data['bio'], 
                    $data['bio_approved'], 
                    $data['account_active']
                ]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the user insert failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User insert failed: ' . $result['message']
                ]);
                break;
            }

            // otherwise, return the success message
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);

            break;
        case 'update':
            // update a user 
            // in the database

            // encode the SQL query    
            $jsonSQL = json_encode([
                'sql' => "
                    UPDATE Users
                    SET pfpUrl = ?,
                        first_name = ?,
                        last_name = ?,
                        phone_number = ?,
                        email = ?,
                        pwd = ?,
                        bio = ?,
                        bio_approved = ?,
                        account_active = ?
                    WHERE id = ?;
                ",
                'params' => [
                    'sssssssiii',
                    $data['pfpUrl'],
                    $data['first_name'],
                    $data['last_name'],
                    $data['phone_number'],
                    $data['email'],
                    password_hash($data['pwd'], PASSWORD_DEFAULT),
                    $data['bio'],
                    $data['bio_approved'],
                    $data['account_active'],
                    $data['id']
                ]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the user update failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User update failed: ' . $result['message']
                ]);
                break;
            }

            // otherwise, return the success message
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);

            break;
        case 'delete':
            // delete a user 
            // from the database
            $userId = $data['userId'];

            // encode the SQL query    
            $jsonSQL = json_encode([
                'sql' => "
                    DELETE FROM Users
                    WHERE id = ?;
                ",
                'params' => ['i', $userId]
            ]);

            // run the query by sending $jsonSQL to the function in query.php
            // and get the result
            $json_response = runSQLQuery($jsonSQL);
            $result = json_decode($json_response, true);

            // if the result was unsuccessful, say the user deletion failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User deletion failed: ' . $result['message']
                ]);
                break;
            }

            // otherwise, return the success message
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);

            header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/admin.html');

            break;
        default:
            // if none of the above methods were used
            // return a 405 Method Not Allowed response
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            exit;
    }
?>