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

    // start the session
    session_start();

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: /COP4813_FriendFinder/Frontend/admin-login.php');
?>
<!DOCTYPE html>
<!-- doc language is english -->
<html lang="en">
<head>
    <!-- for character encoding -->
    <meta charset="UTF-8">
    <!-- for scaling and responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- for page title -->
    <title>Admin Panel</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="css/admin.css">
    <!-- for the JS script -->
    <script type="module" src="js/admin.js"></script>
</head>
<body>
    <main>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the admin page -->
        <div id="admin-page">
            <!-- main header -->
            <h1>Admin Panel</h1>
            <!-- second header -->
            <h2>Registered Users</h2>
            <!-- for the user management table -->
            <table id="user-management-table"></table>
            <!-- add user button -->
            <button id="add-user-button">Add User</button>
        </div>
    </main>
</body>
</html>
