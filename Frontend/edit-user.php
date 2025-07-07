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
    <link rel="stylesheet" href="css/edit-user.css">
    <title>Edit User Info</title>
</head>
<body>
    <main>
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <div id="edit-user-page">
            <h1>Edit User</h1>
            <form id="edit-user-form">
                <label for="pfpUrl">Profile Pic URL:</label>
                <input type="text" id="pfpUrl" name="pfpUrl">

                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="phone-number">Phone Number:</label>
                <input type="text" id="phone-number" name="phone-number" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password">

                <label for="bio">Bio:</label>
                <textarea name="bio" rows="4" cols="50"></textarea>

                <label for="bio-approved">Bio Approved:</label>
                <input type="checkbox" name="bio-approved" value="no">

                <label for="account-active">Account Active:</label>
                <input type="checkbox" name="account-active" value="no">

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>
    <script type="module" src="js/edit-user.js"></script>
</body>
</html>