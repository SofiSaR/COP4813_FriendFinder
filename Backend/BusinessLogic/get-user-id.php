<?php
    // start the session
    session_start();

    // check if user is logged in
    if (isset($_SESSION['user_id']))
        // user is logged in
        echo json_encode(['user_id' => $_SESSION['user_id']]);
    else
        // user is not logged in
        echo json_encode(['user_id' => null]);
?>