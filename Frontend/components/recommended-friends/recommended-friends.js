// import backend functions
import { fetchMatches } from 'https://friendshipmatchmaking.infinityfreeapp.com/Backend/BusinessLogic/backend-functions.js';

// create a class for 
// recommended friends
class RecommendedFriends extends HTMLElement {
    // constructor
    constructor() {
        // call parent constructor
        super();

        // attach shadow
        this.attachShadow({ mode: 'open' });

        // update recommended friends' cards
        // with their names and profile links
        this.shadowRoot.innerHTML += `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" href="components/recommended-friends/recommended-friends.css">
        </head>
        <body>
            <div class="recommendations pink-container">
                <div id="recommendations-list"></div>
                <div class="friend-card more-card" onclick="window.location.href='matches.php'" style="cursor: pointer;">
                    <span class="friend-name more-text">More</span>
                    <img src="../Backend/images/icons/chevron-purple-right.webp" class="more-arrow">
                </div>
            </div>
        </body>
        </html>
        `;
    }

    async connectedCallback() {
        // get recommednations-list
        const recommendationsList = this.shadowRoot.getElementById('recommendations-list');

        // fetch three matches and
        // display them
        const matches = await fetchMatches(3);
        if (matches && matches.length > 0) {
            recommendationsList.innerHTML = matches.map(match => `
                <div class="friend-card">
                    <div class="name-and-circle">
                        <span class="friend-name">${match.first_name}</span>
                        <span class="match-circle">
                            <span class="inner-circle">${Math.round(match.similarity_score)}%</span>
                            <span class="match-circle-circle" style="background: conic-gradient(var(--dark-pink) calc(${match.similarity_score}*3.6deg), transparent 0deg);"></span>
                        </span>
                    </div>
                    <button class="view-profile" onclick="window.location.href='profile.php?userId=${match.id}'">View Profile</button>
                </div>
            `).join('');
        }
        else
            recommendationsList.innerHTML = '<p>No recommendations available.</p>';
    }
}

// register custom element
customElements.define('recommended-friends', RecommendedFriends);