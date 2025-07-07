import { checkId, fetchMatches } from '/COP4813_FriendFinder/Backend/BusinessLogic/backend-functions.js';

class RecommendedFriends extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
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

    connectedCallback() {
        const recommendationsList = this.shadowRoot.getElementById('recommendations-list');

        checkId().then(userIdData => { 
            fetchMatches(userIdData.user_id, 3).then(matches => {
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
            });
        });
    }
}
customElements.define('recommended-friends', RecommendedFriends);