// import functions to get id, submit quiz responses,
// and fetch questions from backend
import { getId, submitQuizResponses, fetchQuestions } from '../../Backend/BusinessLogic/backend-functions.js';

// create JSON object 
// to hold inputted answers
let answers = {};

document.addEventListener('DOMContentLoaded', () => {
    // call function to fetch
    // the questions
    fetchQuestions()
    .then(questions => {
        populateQuestionCont(questions);
    });

    // get the submit button
    const submitButton = document.getElementById('submit-button');

    // if a user clicks on
    // the submit button
    submitButton.addEventListener('click', async () => {
        submitQuizResponses(answers);

        // redirect to quiz results page
        window.location.href = '/Frontend/quiz-results.php';
    });
});

// create function to populate
// the questions container
function populateQuestionCont(questions) {
    // get the quiz container
    const quizContainer = document.getElementById('quiz-container');

    // use a map to create all questions
    // in the quiz container. identify
    // questions by their id. add scroll button
    // to see every question
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
            <button class="scroll-button">Scroll to The Next Question</button>
        </div>
    `).join('');

    // loop through each question
    questions.forEach(question => {
        for (let i = 1; i <= 5; i++) {
            // edit answer's JSON to whichever value (1 through 5)
            // the user selects
            const input = document.getElementById(`option-${question.id}-${i}`);
            if (input) {
                input.addEventListener('change', (e) => {
                    answers[question.id] = input.value;
                });
            }
        }
    });
}