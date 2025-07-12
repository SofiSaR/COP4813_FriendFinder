<?php
    // start the session
    session_start();

    // set content type to JSON
    header('Content-Type: application/json');

    // user must be logged in
    if (!isset($_SESSION['user_id']) && $_SESSION['admin'] !== true)
        header('Location: admin-login.html');

    // database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "FriendFinder";

    // try to connect to
    // the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    // prepare and execute query 
    // to fetch user data
    $stmt = $conn->prepare("
        SELECT CAST((COUNT(*)/25) AS INT) as num_quiz_submissions FROM Quiz_Responses;
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    // check if user exists and is active
    if ($result->num_rows > 0) {
        // fetch user data
        $num_quiz_submissions = $result->fetch_assoc();
        
        // return success response
        // with user data
        echo json_encode([
            'success' => true,
            'data' => $num_quiz_submissions
        ]);
    } else {
        // user doesn't exist
        // or their account is inactive
        echo json_encode([
            'success' => false,
            'message' => 'User not found or account inactive'
        ]);
    }

    // close prepared 
    // statement
    $stmt->close();

    // close connection 
    // if it exists
    if (isset($conn)) {
        $conn->close();
    }
?>