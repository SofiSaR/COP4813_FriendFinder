<?php
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            // encode the SQL query for getting all users
            $jsonSQL = json_encode(['sql' => "SELECT * FROM Users"]);

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

            // if the result was unsuccessful, say the users query failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Users query failed: ' . $result['message']
                ]);
                // close the connection
                curl_close($ch);
                break;
            }

            // otherwise, return the users
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);

            // close the curl connection
            curl_close($ch);

            break;
        case 'POST':
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
                    $_POST['pfpUrl'], 
                    $_POST['first_name'], 
                    $_POST['last_name'], 
                    $_POST['phone_number'], 
                    $_POST['email'], 
                    password_hash($_POST['pwd'], PASSWORD_DEFAULT), 
                    $_POST['bio'], 
                    $_POST['bio_approved'], 
                    $_POST['account_active']
                ]
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

            // if the result was unsuccessful, say the user insert failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User insert failed: ' . $result['message']
                ]);
                // close the connection
                curl_close($ch);
                break;
            }

            // otherwise, return the success message
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);

            // close the curl connection
            curl_close($ch);

            break;
        case 'PUT':
            // update a user 
            // in the database
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

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

            // if the result was unsuccessful, say the user update failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User update failed: ' . $result['message']
                ]);
                // close the connection
                curl_close($ch);
                break;
            }

            // otherwise, return the success message
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);

            // close the curl connection
            curl_close($ch);

            break;
        case 'DELETE':
            // delete a user 
            // from the database
            $userId = $_GET['userId'];

            // encode the SQL query    
            $jsonSQL = json_encode([
                'sql' => "
                    DELETE FROM Users
                    WHERE id = ?;
                ",
                'params' => ['i', $userId]
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

            // if the result was unsuccessful, say the user deletion failed
            if (!$result['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User deletion failed: ' . $result['message']
                ]);
                // close the connection
                curl_close($ch);
                break;
            }

            // otherwise, return the success message
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);

            // close the curl connection
            curl_close($ch);

            header('Location: /COP4813_FriendFinder/Frontend/admin.html');

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