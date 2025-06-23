<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "FriendFinder";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    $db_check = $conn->query("SHOW DATABASES LIKE '$dbname'");
    if ($db_check->num_rows == 0) {
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
    $conn->close();

    $db = new mysqli($servername, $username, $password, $dbname);
    if ($db->connect_error) {
        echo "<script>console.log('Error connecting to database');</script>";
        die("Connection failed: " . $db->connect_error);
    }

    $query = $_GET['sql'];
    $result = $db->query($query);

    if (strpos($query, 'SELECT') !== false)
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>