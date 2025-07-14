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
    <title>Edit User Info</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="css/edit-user.css">
    <!-- for the JS script -->
    <script type="module" src="js/edit-user.js"></script>
</head>
<body>
    <main>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the edit user page -->
        <div id="edit-user-page">
            <!-- header -->
            <h1>Edit User</h1>
            <!-- for the form -->
            <form id="edit-user-form">
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
                <input type="text" id="phone_number" name="phone_number">

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
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>
</body>
</html>