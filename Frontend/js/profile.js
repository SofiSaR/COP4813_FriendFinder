document.addEventListener('DOMContentLoaded', function() {
    // function to get and display 
    // user's profile information
    async function loadUserProfile() {
        // fetch user data from 
        // the PHP API endpoint
        const response = await fetch('/COP4813_FriendFinder/Backend/Database/get-profile-details.php', {
            method: 'GET',
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error. Status: ${response.status}`);
        }

        // wait for json response
        const userData = await response.json();        

        // if json response is
        // successful
        if (userData.success) {
            // update profile picture
            const profileImg = document.getElementById('profile-img');
            if (profileImg) {
                // use provide picture
                // or default icon
                profileImg.src = userData.data.pfpUrl || '/COP4813_FriendFinder/Backend/images/icons/pink-profile-icon.webp';
            }

            // call function to
            // update contact details
            updateContactDetails(userData.data);

            // call function to
            // update bio content
            updateBioContent(userData.data);

        } else {
            // display error message
            console.error('Error fetching user data:', userData.message);
        }    
    }

    // function to update contact
    // details displayed through profile.php
    function updateContactDetails(userData) {
        // get the contact-details
        // section from the profile page
        const contactDetails = document.getElementById('contact-details');
        if (contactDetails) {
            // update the full name,
            // email, and phone number
            contactDetails.innerHTML = `
                <div class="contact-item">
                    <span class="contact-label">Name:</span>
                    <span>${userData.first_name} ${userData.last_name}</span>
                </div>
                <div class="contact-item">
                    <span class="contact-label">Email:</span>
                    <span>${userData.email}</span>
                </div>
                <div class="contact-item">
                    <span class="contact-label">Phone #:</span>
                    <span>${userData.phone_number || 'Not provided'}</span>
                </div>
            `;
        }
    }

    // function to update 
    // bio content
    function updateBioContent(userData) {
        // get the bio-content
        // section from the profile page
        const bioContent = document.getElementById('bio-content');
        if (bioContent) {
            // update the bio
            // text
            const bioText = userData.bio || 'Add a bio to let others know more about you!';
            bioContent.innerHTML = `<p>${bioText}</p>`;
        }
    }

    // load the profile
    // when page loads
    loadUserProfile();
});