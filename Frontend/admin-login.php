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
    <link rel="stylesheet" href="css/login.css">
    <!-- for JS script -->
    <script src="js/admin-login.js"></script>
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
                    <p id="invalid-password-msg">Password must be at least 8 characters.</p>
                    <p id="existing-email-msg">Email already exists.</p>
                    <p id="login-failure-msg">Login Failure.</p>
                    <!-- for login button -->
                    <button type="submit" id="login-button" value="login">Log In</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>