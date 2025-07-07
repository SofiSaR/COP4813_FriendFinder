<?php
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $sql = "SELECT * FROM Users";

            $jsonSQL = json_encode(['sql' => $sql]);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonSQL)
            ]);

            $json_response = curl_exec($ch);
            $result = json_decode($json_response, true);
            echo $json_response;
            // Example: SELECT * FROM Users WHERE id = $_GET['id']
            break;
        case 'POST':
            // Create (e.g., add user)
            // Example: INSERT INTO Users ...
            break;
        case 'PUT':
            // Update (e.g., update user)
            // Parse input: parse_str(file_get_contents("php://input"), $_PUT);
            // Example: UPDATE Users SET ... WHERE id = $_PUT['id']
            break;
        case 'DELETE':
            // Delete (e.g., delete user)
            // Parse input: parse_str(file_get_contents("php://input"), $_DELETE);
            // Example: DELETE FROM Users WHERE id = $_DELETE['id']
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
    }
?>