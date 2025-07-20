<?php
    function runSQLQuery($jsonSQL) {
        // enable error reporting
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // set database connection parameters
        $servername = "sql308.infinityfree.com";
        $username = "if0_39047183";
        $password = "POWer4ll";
        $dbname = "if0_39047183_FriendFinder";

        // connect to the database
        $db = new mysqli($servername, $username, $password, $dbname);
        if ($db->connect_error) {
            return json_encode([
                'success' => false,
                'message' => 'Connection failed: ' . $db->connect_error
            ]);
        }

        // check if the database contains any tables
        $db_check = $db->query("SELECT COUNT(*) as num_rows FROM information_schema.tables WHERE table_schema = '$dbname'");
        $row = $db_check->fetch_assoc();
        $num_rows = $row['num_rows'];
        if ($num_rows == 0) {
            // if it doesn't, run FriendFinder.sql to create and populate it
            $creation_script = file_get_contents(__DIR__.'/FriendFinder.sql');
            if (mysqli_multi_query($db, $creation_script)) {
                do {
                    if ($result = mysqli_store_result($db)) {
                        mysqli_free_result($result);
                    }
                    if (mysqli_error($db)) {
                        return json_encode([
                            'success' => false,
                            'message' => "Error: " . mysqli_error($db) . "<br>"
                        ]);
                    }
                } while (mysqli_next_result($db));
            } else {
                die('Error running SQL file: ' . $db->error);
            }
        }

        // decode the JSON that was passed to this script from the requesting script
        $data = json_decode($jsonSQL, true);

        // get the SQL query and parameters from the JSON
        $query = $data['sql'];
        $params = isset($data['params']) ? $data['params'] : null;

        // prepare the statement received from the JSON
        $stmt = $db->prepare($query);
        // check for successful statement preparation
        if (!$stmt) {
            // return statement preparation failed message
            $error = $db->error;
            // close database connection
            $db->close();
            return json_encode([
                'success' => false,
                'message' => 'Query preparation failed: ' . $error
            ]);
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
            $error = $stmt->error;
            // close the prepared statement and database connection
            $stmt->close();
            $db->close();
            return json_encode([
                'success' => false,
                'message' => "Statement execution failed: " . $error . "\nQuery: " . $data
            ]);
        }

        // get the result
        $result = $stmt->get_result();
        // if the query was a SELECT query and there is no result set, return an error message
        if ($result === false && strpos(strtoupper($query), 'SELECT') !== false) {
            $error = $stmt->error;
            // close the prepared statement and database connection
            $stmt->close();
            $db->close();
            return json_encode([
                'success' => false,
                'message' => 'Getting result failed: ' . $error
            ]);
        }

        // if the script reaches this point, the query was a success

        // close the prepared statement
        $stmt->close();
        // close the database connection
        $db->close();

        // if the query was a SELECT query,
        // return the results as JSON
        if (strpos($query, 'SELECT') !== false) {
            return json_encode([
                'success' => true,
                'data' => $result->fetch_all(MYSQLI_ASSOC)
            ]);
        }
        else {
            // otherwise return a success message that contains the action performed
            $firstWord = explode(' ', trim($query))[0];
            $databaseAction = ucfirst(strtolower($firstWord));
            return json_encode([
                'success' => true,
                'message' => "$databaseAction executed successfully"
            ]);
        }
    }
?>