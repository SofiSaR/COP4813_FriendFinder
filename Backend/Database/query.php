<?php
    // for error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "FriendFinder";

    // try to connect to the database
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    // check the database
    $db_check = $conn->query("SHOW DATABASES LIKE '$dbname'");
    if ($db_check->num_rows == 0) {
        // database doesn't exist, so create it
        $creation_script = file_get_contents('FriendFinder.sql');
        if (mysqli_multi_query($conn, $creation_script)) {
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
                if (mysqli_error($conn)) {
                    echo "Error: " . mysqli_error($conn) . "<br>";
                }
            } while (mysqli_next_result($conn));
        } else {
            die('Error running SQL file: ' . $conn->error);
        }
    }

    // close connection
    $conn->close();

    // try to connect to the database again
    $db = new mysqli($servername, $username, $password, $dbname);
    if ($db->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $db->connect_error);
    }

    // decode the JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // execute the SQL query
    // and get the result
    $query = $data['sql'];
    $result = $db->query($query);

    // encode the result as JSON
    if (strpos($query, 'SELECT') !== false)
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>