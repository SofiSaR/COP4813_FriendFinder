import { checkId, fetchScores } from '/COP4813_FriendFinder/Backend/BusinessLogic/backend-functions.js';

class ResultsSection extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.innerHTML += `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" href="components/results-section/results-section.css">
        </head>
        <body>
            <div id="results-section">
                <div class="pink-container">
                    <h3 class="scores-title">Category Scores</h3>
                    <div class="score-row"><span class="score-label">Sociability:</span> <span class="score-value">0%</span></div>
                    <div class="score-row"><span class="score-label">Adventurousness:</span> <span class="score-value">0%</span></div>
                    <div class="score-row"><span class="score-label">Reliability:</span> <span class="score-value">0%</span></div>
                    <div class="score-row"><span class="score-label">Athleticism:</span> <span class="score-value">0%</span></div>
                    <div class="score-row"><span class="score-label">Availability:</span> <span class="score-value">0%</span></div>
                </div>
                <div class="bar-chart">
                    <div class="bar-categories">
                        <span class="bar-label">Sociability</span>
                        <span class="bar-label">Adventurousness</span>
                        <span class="bar-label">Reliability</span>
                        <span class="bar-label">Athleticism</span>
                        <span class="bar-label">Availability</span>
                    </div>
                    <div class="bar-chart-wo-categories">
                        <div class="bar-legend">
                            <span class="legend-dot"></span> Category Score
                        </div>
                        <div class="bar-chart-wo-cat-leg-nums">
                            <div class="bars"></div>
                            <div class="x-axis">
                                <span class="x-axis-unit"></span>
                                <span class="x-axis-unit"></span>
                                <span class="x-axis-unit"></span>
                                <span class="x-axis-unit"></span>
                                <span class="x-axis-unit"></span>
                            </div>
                        </div>
                        <div class="x-axis-labels">
                            <span class="number">0</span>
                            <span class="number">20</span>
                            <span class="number">40</span>
                            <span class="number">60</span>
                            <span class="number">80</span>
                            <span class="number">100</span>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        `;
    }

    connectedCallback() {
        checkId().then(userIdData => { 
            fetchScores(userIdData.user_id).then(scoreData => { this.showScores(scoreData[0]); });
        });
    }

    showScores(scores) {
        console.log("Scores:", scores);
        const scoreSet = [ scores.sociability_score, scores.adventurousness_score, scores.reliability_score, scores.athleticism_score, scores.availability_score ];

        const barsContainer = this.shadowRoot.querySelector('.bars');
        const scoreValues = this.shadowRoot.querySelectorAll('.score-value');

        scoreValues.forEach((value, index) => {
            value.textContent = `${scoreSet[index]}%`;
        });

        // Clear any existing bars
        barsContainer.innerHTML = '';

        // Create and append five bar divs
        for (let i = 0; i < 5; i++) {
            const bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.width = `${scoreSet[i]}%`;
            barsContainer.appendChild(bar);
        }
    }
}
customElements.define('results-section', ResultsSection);