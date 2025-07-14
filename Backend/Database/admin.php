<?php
    // initialize page name
    $page_name = basename(__FILE__);

    // database connection parameters
    $conn = new mysqli("localhost", "root", "", "FriendFinder");

    // handle connection 
    // error
    if ($conn->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    // update the page visit count
    // for the current page
    $stmt = $conn->prepare("UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?");

    // bind the page name to 
    // the prepared statement
    $stmt->bind_param("s", $page_name);

    // execute the query
    $stmt->execute();

    // close prepared 
    // statement
    $stmt->close();

    // close connection
    $conn->close();

    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            // get all users
            $sql = "SELECT * FROM Users";

            // encode the SQL query
            $jsonSQL = json_encode(['sql' => $sql]);

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
            echo $json_response;

            break;
        case 'POST':
            // insert a new user
            // into the database
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

            // encode the SQL query    
            $jsonSQL = json_encode(['sql' => $sql]);

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
            // and get the text response
            $text_response = curl_exec($ch);
            echo $text_response;

            break;
        case 'PUT':
            // update a user 
            // in the database
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

            // encode the SQL query    
            $jsonSQL = json_encode(['sql' => $sql]);

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
            // and get the text response
            $text_response = curl_exec($ch);
            echo $text_response;

            break;
        case 'DELETE':
            // delete a user 
            // from the database
            $userId = $_GET['userId'];
            $sql = "
                DELETE FROM Users
                WHERE id = {$userId};
            ";

            // encode the SQL query    
            $jsonSQL = json_encode(['sql' => $sql]);

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
            // and get the text response
            $text_response = curl_exec($ch);
            header('Location: /COP4813_FriendFinder/Frontend/admin.html');

            break;
        default:
            // error handling
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
    }
?>