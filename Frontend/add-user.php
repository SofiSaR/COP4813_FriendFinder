<?php
    session_start();
    if (!isset($_SESSION['user_id']) && $_SESSION['admin'] !== true)
        header('Location: admin-login.html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/add-user.css">
    <title>Add User</title>
</head>
<body>
    <main>
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <div id="add-user-page">
            <h1>Add User</h1>
            <form id="add-user-form">
                <label for="pfpUrl">Profile Pic URL:</label>
                <input type="text" id="pfpUrl" name="pfpUrl">

                <label for="first-name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="phone-number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="pwd">Password:</label>
                <input type="password" id="pwd" name="pwd" required>

                <label for="bio">Bio:</label>
                <textarea name="bio" rows="4" cols="50"></textarea>

                <div class="checkbox-group">
                    <label for="bio_approved">Bio Approved:</label>
                    <input type="checkbox" name="bio_approved">
                </div>

                <div class="checkbox-group">
                    <label for="account_active">Account Active:</label>
                    <input type="checkbox" name="account_active">
                </div>

                <button type="submit">Add User</button>
            </form>
        </div>
    </main>
    <script type="module" src="js/add-user.js"></script>
</body>
</html>