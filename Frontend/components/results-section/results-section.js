// import backend functions
import { fetchScores } from 'https://friendshipmatchmaking.infinityfreeapp.com/Backend/BusinessLogic/backend-functions.js';

// create a class for 
// results section
class ResultsSection extends HTMLElement {
    static get observedAttributes() {
        return ['user-id'];
    }
    
    // constructor
    constructor() {
        // call parent constructor
        super();

        // attach shadow
        this.attachShadow({ mode: 'open' });

        // update category scores
        // and bar chart with the 
        // category scores
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

    async attributeChangedCallback(name, oldValue, newValue) {
        if (name === 'user-id') {
            if (newValue) {
                const scoreData = await fetchScores(newValue);
                if (!scoreData || scoreData.length === 0) {
                    this.shadowRoot.querySelector('#results-section').innerHTML = '<p>Quiz not yet taken</p>';
                    return;
                }
                this.showScores(scoreData[0]);
            }
            else {
                console.error("User ID not found. Cannot fetch scores.");
                return;
            }
        }
    }

    // display user's scores
    showScores(scores) {
        // set the scores
        const scoreSet = [ scores.sociability_score, scores.adventurousness_score, scores.reliability_score, scores.athleticism_score, scores.availability_score ];

        // create bar graph with 
        // proper category scores
        const barsContainer = this.shadowRoot.querySelector('.bars');
        const scoreValues = this.shadowRoot.querySelectorAll('.score-value');
        scoreValues.forEach((value, index) => {
            value.textContent = `${scoreSet[index]}%`;
        });

        // clear any existing bars
        barsContainer.innerHTML = '';

        // create and append five bar divs
        for (let i = 0; i < 5; i++) {
            const bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.width = `${scoreSet[i]}%`;
            barsContainer.appendChild(bar);
        }
    }
}

// register custom element
customElements.define('results-section', ResultsSection);