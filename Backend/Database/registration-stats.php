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

    $unit = isset($_GET['unit']) ? $_GET['unit'] : null;
    $start = isset($_GET['start']) ? $_GET['start'] : null;
    $end = isset($_GET['end']) ? $_GET['end'] : null;

    // prepare and execute query 
    // to fetch user data
    if ($unit === 'Days') {
        $stmt = $conn->prepare("
            SELECT DATE_FORMAT(registration_date, '%Y-%m-%d') AS day, COUNT(*) AS registrations
            FROM Users
            WHERE registration_date BETWEEN ? AND ?
            GROUP BY day
            ORDER BY day;
        ");
    }
    else if ($unit === 'Weeks') {
        $stmt = $conn->prepare("
            SELECT YEARWEEK(registration_date, 0) AS week, COUNT(*) AS registrations
            FROM Users
            WHERE registration_date BETWEEN ? AND ?
            GROUP BY week
            ORDER BY week;
        ");
    }
    else if ($unit === 'Months') {
        $stmt = $conn->prepare("
            SELECT DATE_FORMAT(registration_date, '%Y-%m') AS month, COUNT(*) AS registrations
            FROM Users
            WHERE registration_date BETWEEN ? AND ?
            GROUP BY month
            ORDER BY month;
        ");
    }
    
    if(isset($stmt)) {
        $stmt->bind_param("ss", $start, $end);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode([
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid unit specified or no data found'
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