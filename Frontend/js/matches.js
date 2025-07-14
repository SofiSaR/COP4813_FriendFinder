// import backend functions
import { fetchMatches, checkId, fetchUserProfile } from '/COP4813_FriendFinder/Backend/BusinessLogic/backend-functions.js';


// call the function to load
// the matches when the page loads
document.addEventListener('DOMContentLoaded', () => {
    checkId().then(userIdData => { loadMatches(userIdData.user_id); });
});

// create profile HTML element
// which will have the img, name tag,
// and profile link for each of the top 20 matches
function createProfileElement(user) {
    // create profile div
    const profileDiv = document.createElement('div');
    profileDiv.className = 'profile';
    
    // use profile picture 
    // or default icon
    const profilePicUrl = user.pfpUrl || '/COP4813_FriendFinder/Backend/images/icons/pink-profile-icon.webp';
    
    // update the pic, name, and link
    profileDiv.innerHTML = `
        <!-- for the profile pic -->
        <div class="profile-pic">
            <img src="${profilePicUrl}" alt="${user.first_name}'s profile picture">
        </div>
        <!-- for the name tag -->
        <div class="name-tag">
            <img src="../Backend/images/icons/name-tag-icon.webp" alt="Name tag icon">
            <span>${user.first_name}</span>
        </div>
        <!-- for the profile link button -->
        <button class="profile-link" onclick="viewProfile(${user.id})">
            <img src="../Backend/images/icons/link.webp" alt="Profile link icon">
            <span>View Profile</span>
        </button>
    `;
    
    return profileDiv;
}

// redirect user if they
// click on link to view
// someone's profile page
function viewProfile(userId) {
    // redirect to profile 
    // page using user's id
    window.location.href = `/COP4813_FriendFinder/Frontend/profile.php?user_id=${userId}`;
}

// function to load and display
// user's top 20 matches
async function loadMatches(userId) {
    try {
        // get the main-container section
        // section from the matches page
        const mainContainer = document.getElementById('main-container');

        // show that the matches 
        // are loading
        mainContainer.innerHTML = '<div class="loading">Loading your matches...</div>';

        // if the user is not logged in
        if (!userId) {
            // display error message
            mainContainer.innerHTML = '<div class="error">Unable to load matches. Please log in to view your matches.</div>';

            // redirect to login page
            window.location.href = '/COP4813_FriendFinder/Frontend/login.php';

            return;
        }
        
        // call function to 
        // fetch top 20 matches
        const matchesData = await fetchMatches(userId, 20);
        
        // clear loading state
        mainContainer.innerHTML = '';
        
        // if no matches are found
        if (!matchesData || matchesData.length === 0) {
            // display message stating so
            mainContainer.innerHTML = '<div class="no-matches">No matches found yet. Please wait for more matches to appear!</div>';
            return;
        }
        
        // create and append profile elements
        matchesData.forEach(user => {
            const profileElement = createProfileElement(user);
            mainContainer.appendChild(profileElement);
        });
        
    } 
    catch (error) {
        // log error to console 
        // for debugging
        console.error('Error loading matches:', error);

        // get the main-container section
        // section from the matches page 
        const mainContainer = document.getElementById('main-container');

        // display error message
        mainContainer.innerHTML = '<div class="error">Error loading matches. Please try again later.</div>';
    }
}

// make viewProfile function
// globally accessible
window.viewProfile = viewProfile;

// function to load
// the matches again
function refreshMatches() {
    checkId().then(userIdData => { loadMatches(userIdData.user_id); });
}

// make refresh function 
// globally accessible
window.refreshMatches = refreshMatches;