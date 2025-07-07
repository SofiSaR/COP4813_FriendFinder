<?php
    session_start();
    if (!isset($_SESSION['user_id']) && $_SESSION['admin'] !== true)
        header('Location: admin-login.html');
?>
<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">

    <title>Admin Panel</title>
</head>
<body>
    <main>
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <div id="admin-page">
            <h1>Admin Panel</h1>
            <h2>Registered Users</h2>
            <table id="user-management-table"></table>
            <button id="add-user-button">Add User</button>
        </div>
    </main>
    <script type="module" src="js/admin.js"></script>
</body>
</html>
