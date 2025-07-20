<?php
    // enable error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // set database connection parameters
    $servername = "sql308.infinityfree.com";
    $username = "if0_39047183";
    $password = "POWer4ll";
    $dbname = "if0_39047183_FriendFinder";

    // try to connect to the database server
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    // check if the database exists
    $db_check = $conn->query("SHOW DATABASES LIKE '$dbname'");
    if ($db_check->num_rows == 0) {
        // if it doesn't, run FriendFinder.sql to create and populate it
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

    // close the database server connection
    $conn->close();

    // try to connect to the database
    $db = new mysqli($servername, $username, $password, $dbname);
    if ($db->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $db->connect_error);
    }

    // decode the JSON that was passed to this script from the requesting script
    $data = json_decode(file_get_contents('php://input'), true);

    // get the SQL query and parameters from the JSON
    $query = $data['sql'];
    $params = isset($data['params']) ? $data['params'] : null;

    // prepare the statement received from the JSON
    $stmt = $db->prepare($query);
    // check for successful statement preparation
    if (!$stmt) {
        // return statement preparation failed message
        echo json_encode([
            'success' => false,
            'message' => 'Query preparation failed: ' . $db->error
        ]);

        // close database connection
        $db->close();
        // quit this script here
        exit();
    }

    // bind any parameters included to the prepared statement
    if ($params) {
        $types = array_shift($params);
        $stmt->bind_param($types, ...$params);
    }

    // execute the query
    $executed = $stmt->execute();
    // if the statment failed to execute, return an error message
    if (!$executed) {
        echo json_encode([
            'success' => false,
            'message' => "Statement execution failed: " . $stmt->error . "\nQuery: " . $data
        ]);
        // close the prepared statement and database connection
        $stmt->close();
        $db->close();
        // quit this script here
        exit();
    }

    // get the result
    $result = $stmt->get_result();
    // if the query was a SELECT query and there is no result set, return an error message
    if ($result === false && strpos(strtoupper($query), 'SELECT') !== false) {
        echo json_encode([
            'success' => false,
            'message' => 'Getting result failed: ' . $stmt->error
        ]);
        // close the prepared statement and database connection
        $stmt->close();
        $db->close();
        // quit this script here
        exit();
    }

    // if the script reaches this point, the query was a success

    // if the query was a SELECT query,
    // return the results as JSON
    if (strpos($query, 'SELECT') !== false) {
        echo json_encode([
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ]);
    }
    else {
        // otherwise return a success message that contains the action performed
        $firstWord = explode(' ', trim($query))[0];
        $databaseAction = ucfirst(strtolower($firstWord));
        echo json_encode([
            'success' => true,
            'message' => "$databaseAction executed successfully"
        ]);
    }

    // close the prepared statement
    $stmt->close();
    // close the database connection
    $db->close();
    exit();
?>