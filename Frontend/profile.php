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

    // start the session
    session_start();

    // user must be logged in
    if (!isset($_SESSION['user_id']))
        header('Location: login.html');

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
    <link rel="stylesheet" href="css/profile.css">
    <!-- for the JS script -->
    <script type="module" src="js/profile.js"></script>
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
    </main>
</body>
</html>