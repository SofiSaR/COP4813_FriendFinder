<?php
    session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, pwd, account_active FROM Users WHERE email = '$email'";

    $jsonSQL = json_encode(['sql' => $sql]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSQL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonSQL)
    ]);

    $json_response = curl_exec($ch);
    $result = json_decode($json_response, true);

    curl_close($ch);

    if (!empty($result) && password_verify($password, $result[0]['pwd']) && $result[0]['account_active'] == 1) {
        $_SESSION['user_id'] = $result[0]['id'];
        $_SESSION['admin'] = false;
        session_write_close();
        echo 'true';
    }
    else if (!empty($result) && password_verify($password, $result[0]['pwd']) && $result[0]['account_active'] == 0) {
        echo 'inactive';
    }
    else
        echo 'false';
?>
