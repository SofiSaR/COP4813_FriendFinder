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

// prepare and execute query to get page visits data
$stmt = $conn->prepare("SELECT page_name, visit_count FROM Page_Visits");

if (!$stmt) {
    // statement preparation failed
    echo json_encode([
        'success' => false,
        'message' => 'Query preparation failed: ' . $conn->error
    ]);

    // close connection
    $conn->close();

    exit();
}

// execute the statement
// and get the result
$stmt->execute();
$result = $stmt->get_result();

// check if we have data
if ($result->num_rows > 0) {
    // fetch all rows from the result
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    // return success response with data
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
} else {
    // no data found
    echo json_encode([
        'success' => false,
        'message' => 'No page visit data found'
    ]);
}

// close prepared statement
$stmt->close();

// close connection
$conn->close();
?>