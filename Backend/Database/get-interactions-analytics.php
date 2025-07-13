<?php
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

// database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FriendFinder";

// try to connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit();
}

try {
    // initialize variables
    $num_logins = 0;
    $num_quiz_submissions = 0;
    $num_bio_paragraphs = 0;
    
    // get number of logins
    $login_stmt = $conn->prepare("SELECT COUNT(*) as count FROM Login_History");
    if ($login_stmt) {
        $login_stmt->execute();
        $login_result = $login_stmt->get_result();
        if ($login_result && $login_result->num_rows > 0) {
            $num_logins = $login_result->fetch_assoc()['count'];
        }
        $login_stmt->close();
    }
    
    // get number of quiz submissions
    $quiz_stmt = $conn->prepare("SELECT CAST((COUNT(*)/25) AS SIGNED) as count FROM Quiz_Responses");
    if ($quiz_stmt) {
        $quiz_stmt->execute();
        $quiz_result = $quiz_stmt->get_result();
        if ($quiz_result && $quiz_result->num_rows > 0) {
            $num_quiz_submissions = $quiz_result->fetch_assoc()['count'];
        }
        $quiz_stmt->close();
    }
    
    // get number of bio submissions
    $users_stmt = $conn->prepare("SELECT COUNT(*) as count FROM Users WHERE bio IS NOT NULL AND bio != ''");
    if ($users_stmt) {
        $users_stmt->execute();
        $users_result = $users_stmt->get_result();
        if ($users_result && $users_result->num_rows > 0) {
            $num_bio_paragraphs = $users_result->fetch_assoc()['count'];
        }
        $users_stmt->close();
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
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Query error: ' . $e->getMessage()
    ]);
}

// close connection if it exists
if (isset($conn)) {
    $conn->close();
}
?>