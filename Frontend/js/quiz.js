import { checkId, submitQuizResponses, fetchQuestions } from '../Backend/BusinessLogic/backend-functions.js';

let answers = {};

document.addEventListener('DOMContentLoaded', () => {
    checkId();
    populateQuestionCont(fetchQuestions());

    const submitButton = document.getElementById('submit-button');

    submitButton.addEventListener('click', () => {
        submitQuizResponses(answers)
        window.location.href = 'quiz-results.php';
    });
});

function populateQuestionCont(questions) {
    const quizContainer = document.getElementById('quiz-container');
    quizContainer.innerHTML = questions.map(question => `
        <div class="question-container">
            <h3 class="question">${question.prompt_text}</h3>
        </div>
        <div class="answer-container">
            <div class="radio-bar-group">
                <input type="radio" id="option-${question.id}-1" name="bar-${question.id}" value="1">
                <label for="option-${question.id}-1">1</label>
                <input type="radio" id="option-${question.id}-2" name="bar-${question.id}" value="2">
                <label for="option-${question.id}-2">2</label>
                <input type="radio" id="option-${question.id}-3" name="bar-${question.id}" value="3">
                <label for="option-${question.id}-3">3</label>
                <input type="radio" id="option-${question.id}-4" name="bar-${question.id}" value="4">
                <label for="option-${question.id}-4">4</label>
                <input type="radio" id="option-${question.id}-5" name="bar-${question.id}" value="5">
                <label for="option-${question.id}-5">5</label>
            </div>
            <button id="scroll-button">Scroll to The Next Question</button>
        </div>
    `).join('');

    questions.forEach(question => {
        for (let i = 1; i <= 5; i++) {
            const input = document.getElementById(`option-${question.id}-${i}`);
            if (input) {
                input.addEventListener('change', (e) => {
                    answers[question.id] = input.value;
                });
            }
        }
    });
}