// import backend function
import { fetchUserProfile } from '/COP4813_FriendFinder/Backend/BusinessLogic/backend-functions.js';

document.addEventListener('DOMContentLoaded', () => {    
    // use profileUserId that was passed from PHP
    // or the current user's id
    const userIdToLoad = window.profileUserId || window.currentUserId;
    
    // load profile for
    // match user
    if (userIdToLoad) {
        loadUserProfile(userIdToLoad);
    } else {
        // log error to console
        console.error('No user ID available to load profile');
        updateProfileDetails({ error: 'No user ID available' });
    }
});

// function to get and display
// user's profile information
async function loadUserProfile(userId) {
    try {
        // use fetchUserProfile function
        // to fetch user's profile array
        const userProfileArray = await fetchUserProfile(userId);
        
        // get the first user's profile
        const userProfile = userProfileArray[0];
        
        // if user's profile
        // doesn't exist
        if (!userProfile) {
            throw new Error('User profile not found');
        }
        
        // use function to update 
        // profile details
        updateProfileDetails(userProfile);
        
    } catch (error) {        
        // get the contact-details
        // section from the profile page
        // and display error message
        const contactDetails = document.getElementById('contact-details');
        if (contactDetails) {
            contactDetails.innerHTML = `
                <div class="contact-item">
                    <span class="contact-label">Error:</span>
                    <span>Failed to load profile</span>
                </div>
            `;
        }
        
        // get the bio-content
        // section from the profile page
        // and display error message
        const bioContent = document.getElementById('bio-content');
        if (bioContent) {
            bioContent.innerHTML = '<p>Error loading bio</p>';
        }
    }
}

// function to update profile details
function updateProfileDetails(userProfile) {
    // handle error case
    if (userProfile.error) {
        // get the contact-details
        // section from the profile page
        // and display error message
        const contactDetails = document.getElementById('contact-details');
        if (contactDetails) {
            contactDetails.innerHTML = `
                <div class="contact-item">
                    <span class="contact-label">Error:</span>
                    <span>${userProfile.error}</span>
                </div>
            `;
        }
        return;
    }
    
    // update profile picture
    const profileImg = document.getElementById('profile-img');
    if (profileImg) {
        // use provided picture
        // or default icon
        profileImg.src = userProfile.pfpUrl || '/COP4813_FriendFinder/Backend/images/icons/pink-profile-icon.webp';;
    }
    
    // get the contact-details
    // section from the profile page
    const contactDetails = document.getElementById('contact-details');
    if (contactDetails) {
        // update the full name,
        // email, and phone number
        contactDetails.innerHTML = `
            <div class="contact-item">
                <span class="contact-label">Name:</span>
                <span>${userProfile.first_name || 'Not provided'} ${userProfile.last_name || ''}</span>
            </div>
            <div class="contact-item">
                <span class="contact-label">Email:</span>
                <span>${userProfile.email || 'Not provided'}</span>
            </div>
            <div class="contact-item">
                <span class="contact-label">Phone #:</span>
                <span>${userProfile.phone || 'Not provided'}</span>
            </div>
        `;
    }
    
    // get the bio-content
    // section from the profile page
    const bioContent = document.getElementById('bio-content');
    if (bioContent) {
        // update bio with text
        // or display message
        bioContent.innerHTML = `<p>${userProfile.bio || 'Add a bio to let others know more about you!'}</p>`;
    }
}