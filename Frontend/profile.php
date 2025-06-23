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
    <link rel="stylesheet" href="/Frontend/css/profile.css">
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
                                <!-- add pic src -->
                                <img id="profile-img" src="Frontend/images/icons/profile-picture.webp" alt="profile picture">
                            </div>
                            <!-- for the contact info -->
                            <div id="contact-info">
                                <!-- header -->
                                <h2>Contact Info</h2>
                                <!-- for the contact details -->
                                <div id="contact-details">
                                    <!-- contact item and label for name -->
                                    <div class="contact-item">
                                        <span class="contact-label">Name:</span>
                                        <span>Dionne Davenport</span>
                                    </div>
                                    <!-- contact item and label for email -->
                                    <div class="contact-item">
                                        <span class="contact-label">Email:</span>
                                        <span>dionne@gmail.com</span>
                                    </div>
                                    <!-- contact item and label for phone -->
                                    <div class="contact-item">
                                        <span class="contact-label">Phone #:</span>
                                        <span>+1 (777) 777-777</span>
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
                                <!-- add paragraph -->
                                <p>Hi, guys! I am Dionne, and I love trying new foods, reading, and exploring different places. 
                                When I am not working as a chef, you will find me planning my next adventure. 
                                I am looking for spontaneous friends who share my interests and enjoy outdoor activities.
                                Please feel free to contact me if you believe we would get along. I look forward to hearing from you!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>