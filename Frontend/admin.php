<?php
    // use query.php to run SQL queries
    require_once __DIR__.'/../Backend/Database/query.php';
    // initialize page name
    $page_name = basename(__FILE__);

    // create the statement for updating the page visit count
    // for the current page
    $jsonSQL = json_encode([
        'sql' => "UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?",
        'params' => ['s', $page_name]
    ]);

    // execute the query 
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Page visits query failed: ' . $result['message']
        ]);
    }

    // start the session
    session_start();

    // user must be logged in as admin
    if (!isset($_SESSION['user_id']) || $_SESSION['admin'] !== true)
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/admin-login.php');
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
    <link rel="stylesheet" href="/Frontend/css/admin.css">
    <!-- for the JS script -->
    <script type="module" src="/Frontend/js/admin.js"></script>
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
