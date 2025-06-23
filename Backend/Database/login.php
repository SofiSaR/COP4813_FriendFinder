<?php
    session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, pwd FROM Users WHERE email = '$email'";

    $url = 'http://localhost/COP4813_FriendFinder/Backend/Database/query.php?sql=' . urlencode($sql);
    $json_response = file_get_contents($url);
    $result = json_decode($json_response, true);

    // if (!empty($result) && password_verify($password, $result[0]['pwd'])) {
    if (!empty($result) && $password === $result[0]['pwd']) {
        $_SESSION['user_id'] = $result[0]['id'];
        session_write_close();
        echo 'true';
    }
    else
        echo 'false';
?>
