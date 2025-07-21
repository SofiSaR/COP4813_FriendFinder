<?php
    // use query.php to run SQL queries
    require_once __DIR__.'/../Backend/Database/query.php';
    // initialize page name
    $page_name = basename(__FILE__);

    // create the statement for updating the page visit count
    // for the current page
    $jsonSQL = json_encode([
        'sql' => "UPDATE Page_Visits SET visit_count = visit_count + 1 WHERE page_name = ?",
        'params' => ['s', $page_name]
    ]);

    // execute the query 
    // and get the result
    $json_response = runSQLQuery($jsonSQL);
    $result = json_decode($json_response, true);

    // if the query failed, return the error message
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => 'Page visits query failed: ' . $result['message']
        ]);
    }

    // start the session
    session_start();

    // user must be logged in
    if (!isset($_SESSION['user_id']))
        header('Location: https://friendshipmatchmaking.infinityfreeapp.com/Frontend/login.php');

    // get user ID from the URL parameter
    // if none, default to the logged-in user's profile
    $profile_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
    
    // ensure only valid ints
    // are passed as user ids
    $profile_user_id = filter_var($profile_user_id, FILTER_VALIDATE_INT);
    
    // ff the user ID is invalid, 
    // redirect to own profile
    if (!$profile_user_id) {
        $profile_user_id = $_SESSION['user_id'];
    }
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
    <title>Friendship Matchmaking - Profile</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="/Frontend/css/profile.css">
    <!-- for the JS script -->
    <script type="module" src="/Frontend/js/profile.js"></script>
    <script>
        // make the profile user ID
        // available to JS
        window.profileUserId = <?php echo json_encode($profile_user_id); ?>;
        window.currentUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
    </script>
</head>
<body>
    <main>
        <!-- for the website title -->
        <h1 id="website-title">friendship<br>matchmaking</h1>
        <!-- for the profile page -->
        <div id="profile-page">
            <!-- for the main container -->
            <div id="main-container">
                <!-- for the profile container -->
                <div id="profile-container">
                    <!-- for the left panel -->
                    <div id="left-panel">
                        <!-- for the profile card -->
                        <div id="profile-card">
                            <!-- for the profile picture -->
                            <div id="profile-picture">
                                <!-- pic will be loaded dynamically -->
                                <img id="profile-img" src="../Backend/images/icons/profile-picture.webp" alt="profile picture">
                            </div>
                            <!-- for the contact info -->
                            <div id="contact-info">
                                <!-- header -->
                                <h2>Contact Info</h2>
                                <!-- for the contact details 
                                which will be loaded dynamically -->
                                <div id="contact-details">
                                    <!-- contact item and label for name -->
                                    <div class="contact-item">
                                        <span class="contact-label">Name:</span>
                                        <span>Loading...</span>
                                    </div>
                                    <!-- contact item and label for email -->
                                    <div class="contact-item">
                                        <span class="contact-label">Email:</span>
                                        <span>Loading...</span>
                                    </div>
                                    <!-- contact item and label for phone -->
                                    <div class="contact-item">
                                        <span class="contact-label">Phone #:</span>
                                        <span>Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- for the right panel -->
                    <div id="right-panel">
                        <!-- header -->
                        <h1>Profile Details</h1>
                        <!-- for the bio section -->
                        <div id="bio-section">
                            <!-- header -->
                            <h2>Bio</h2>
                            <!-- for the bio content -->
                            <div id="bio-content">
                                <!-- paragraph will be
                                loaded dynamically -->
                                <p>Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- for the links to take user to quiz results page / matches page / logout -->
        <div class="links-container">
            <a href="/Frontend/quiz-results.php" class="quiz-results-link">Go to Quiz Results Page</a>
            <a href="/Frontend/matches.php" class="matches-link">Go to Matches Page</a>
            <a href="/Frontend/logout.php" class="logout-link">Logout</a>
        </div>
    </main>
</body>
</html>