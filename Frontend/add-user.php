<?php
    // initialize page name
    $page_name = basename(__FILE__);

    // create the statement for updating the page visit count
    // for the current page
    $jsonSQL = json_encode([
        'sql' => "UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?",
        'params' => ['s', $page_name]
    ]);

    // initialize cURL to send the 
    // query to the database API
    $ch = curl_init();

    // set the cURL options
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    // execute the query 
    // and get the result
    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Page visits query failed: ' . $result['message']
        ]);
    }

    // close the connection
    curl_close($ch);
    
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
    <title>Add User</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="css/add-user.css">
    <!-- for the JS script -->
    <script type="module" src="js/add-user.js"></script>
</head>
<body>
    <main>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the add user page -->
        <div id="add-user-page">
            <!-- header -->
            <h1>Add User</h1>
            <!-- for the form -->
            <form id="add-user-form">
                <!-- for the input fields -->

                <!-- profile pic url -->
                <label for="pfpUrl">Profile Pic URL:</label>
                <input type="text" id="pfpUrl" name="pfpUrl">

                <!-- first name -->
                <label for="first-name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <!-- last name -->
                <label for="last-name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <!-- phone number -->
                <label for="phone-number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number">

                <!-- email -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <!-- password -->
                <label for="pwd">Password:</label>
                <input type="password" id="pwd" name="pwd" required>

                <!-- bio -->
                <label for="bio">Bio:</label>
                <textarea name="bio" rows="4" cols="50"></textarea>

                <!-- checkbox groups -->

                <!-- bio approved (0 or 1)-->
                <div class="checkbox-group">
                    <label for="bio_approved">Bio Approved:</label>
                    <input type="checkbox" name="bio_approved">
                </div>

                <!-- account active (0 or 1) -->
                <div class="checkbox-group">
                    <label for="account_active">Account Active:</label>
                    <input type="checkbox" name="account_active">
                </div>

                <!-- submit button -->
                <button type="submit">Add User</button>
            </form>
        </div>
    </main>
</body>
</html>