<?php
session_start();

    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $check_sql = "SELECT id FROM Users WHERE email = '$email'";
    $url = 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php?sql=' . urlencode($check_sql);
    $json_response = file_get_contents($url);
    $result = json_decode($json_response, true);

    if (!empty($result)) {
        echo 'false';
        exit();
    }

    $insert_sql = "INSERT INTO Users (first_name, last_name, email, pwd) VALUES ('$firstName', '$lastName', '$email', '$password')";
    $url = 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php?sql=' . urlencode($insert_sql);
    file_get_contents($url);

    $get_id_sql = "SELECT id FROM Users WHERE email = '$email'";
    $url = 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php?sql=' . urlencode($get_id_sql);
    $json_response = file_get_contents($url);
    $result = json_decode($json_response, true);

    if (!empty($result)) {
        $_SESSION['user_id'] = $result[0]['id'];
        session_write_close();
        echo 'true';
    }
    else
        echo 'failed';
?>