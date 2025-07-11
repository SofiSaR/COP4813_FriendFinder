<?php
    session_start();
    if (!isset($_SESSION['user_id']))
        header('Location: login.html');
?>
<!-- for document structure, meta tags, and title -->
<!-- HTML5 document type -->
<!DOCTYPE html>
<!-- doc language is english -->
<html lang="en">
<head>
    <!-- character encoding -->
    <meta charset="UTF-8">
    <!-- scaling and responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>Friendship Matchmaking - Profile</title>
    <!-- for CSS stylesheet -->
    <link rel="stylesheet" href="css/profile.css">
    <!-- for the JS script -->
    <script src="js/profile.js"></script>
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