<?php
session_start();

    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $check_sql = "SELECT id FROM Users WHERE email = '$email'";
    $jsonSQL = json_encode(['sql' => $check_sql]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);

    if (!empty($result)) {
        echo 'false';
        exit();
    }

    $insert_sql = "INSERT INTO Users (first_name, last_name, email, pwd) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
    $jsonSQL = json_encode(['sql' => $insert_sql]);


    curl_reset($ch);

    curl_setopt($ch, CURLOPT_URL, 'query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    curl_exec($ch);

    $get_id_sql = "SELECT id FROM Users WHERE email = '$email'";
    $jsonSQL = json_encode(['sql' => $get_id_sql]);

    curl_reset($ch);

    curl_setopt($ch, CURLOPT_URL, 'query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);
    echo $json_response;

    if (!empty($result)) {
        $_SESSION['user_id'] = $result[0]['id'];
        session_write_close();
        echo 'true';
    }
    else
        echo 'failed';
?>