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
    <title>Friendship Matchmaking</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="/Frontend/css/login.css">
    <!-- for JS script -->
    <script src="/Frontend/js/admin-login.js"></script>
</head>
<body>
    <main>
        <!-- for website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for login page -->
        <div id="login-page">
            <!-- for profile icon -->
            <img id="profile-icon" src="../Backend/images/icons/pink-profile-icon.webp">
            <!-- for signin buttons -->
            <div id="signin-buttons">
                <button id="login-switch" class="signin-button">Admin LogIn</button>
            </div>
            <!-- for signin container -->
            <div id="signin-container" class="pink-container">
                <!-- for signin form -->
                <form id="signin-form">
                    <!-- for email and password fields -->
                    <div class="input-w-icon">
                        <img id="email-icon" class="input-icon" src="../Backend/images/icons/email-icon.webp" alt="email icon">
                        <input type="email" id="email-field" placeholder="email">
                    </div>
                    <div class="input-w-icon">
                        <img id="lock-icon" class="input-icon" src="../Backend/images/icons/lock-icon.webp" alt="password icon">
                        <input type="password" id="password-field" placeholder="password">
                    </div>
                    <!-- for error messages -->
                    <p id="empty-fields-msg">All fields are required.</p>
                    <p id="invalid-email-msg">Invalid email.</p>
                    <p id="invalid-login-msg">Incorrect email or password.</p>
                    <p id="login-failure-msg">Login failure.</p>
                    <!-- for login button -->
                    <button type="submit" id="login-button" value="login">Log In</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>