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
            break;
        case 'POST':
            $sql = "
                INSERT INTO Users (pfpUrl, first_name, last_name, phone_number, email, pwd, bio, bio_approved, account_active)
                VALUES
                (
                    '{$_POST['pfpUrl']}',
                    '{$_POST['first_name']}',
                    '{$_POST['last_name']}',
                    '{$_POST['phone_number']}',
                    '{$_POST['email']}',
                    '{$_POST['pwd']}',
                    '{$_POST['bio']}',
                    {$_POST['bio_approved']},
                    {$_POST['account_active']}
                );";

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

            $text_response = curl_exec($ch);
            echo $text_response;
            break;
        case 'PUT':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $sql = "
                UPDATE Users
                SET pfpUrl = '{$data['pfpUrl']}',
                    first_name = '{$data['first_name']}',
                    last_name = '{$data['last_name']}',
                    phone_number = '{$data['phone_number']}',
                    email = '{$data['email']}',
                    pwd = '{$data['pwd']}',
                    bio = '{$data['bio']}',
                    bio_approved = {$data['bio_approved']},
                    account_active = {$data['account_active']}
                WHERE id = {$data['id']};
            ";

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

            $text_response = curl_exec($ch);
            echo $text_response;

            break;
        case 'DELETE':
            $userId = $_GET['userId'];
            $sql = "
                DELETE FROM Users
                WHERE id = {$userId};
            ";

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

            $text_response = curl_exec($ch);
            header('Location: /COP4813_FriendFinder/Frontend/admin.html');
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
    }
?>