// import functions to get id, submit quiz responses,
// and fetch questions from backend
import { getId, submitQuizResponses, fetchQuestions } from '../../Backend/BusinessLogic/backend-functions.js';

// create JSON object 
// to hold inputted answers
let answers = {};

// create const variable
// for total # of questions
const totalQuestions = 25;

document.addEventListener('DOMContentLoaded', () => {
    // check if user has already completed
    // the quiz once (no retakes allowed)
    if (localStorage.getItem('quizCompleted') === 'true') {
        // call function to show
        // already completed message
        // and button to go to quiz results page
        showAlreadyCompletedMessage();
        return;
    }

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
    submitButton.addEventListener('click', async (e) => {
        // prevent default
        // form submission
        e.preventDefault();

        // check if all questions
        // have been answered
        if (!checkAllQuestionsAnswered()) {
            // display error message
            alert('Please answer all questions.');
        }
        else {
            // call function to submit
            // quiz responses
            await submitQuizResponses(answers);

            // redirect to quiz results page
            window.location.href = '/Frontend/quiz-results.php';
        }
    });
});

// create function to show
// message stating that the user
// has already completed the quiz once
// and add button to go to quiz results page
function showAlreadyCompletedMessage() {
    // create quiz container
    // with message and button
    const quizContainer = document.getElementById('quiz-container');
    quizContainer.innerHTML = `
        <div style="text-align: center; padding: 50px;">
            <h2>You Already Completed The Quiz!</h2>
            <p>You can only take this quiz once.</p>
            <button onclick="window.location.href='/Frontend/quiz-results.php'">
                View Results
            </button>
        </div>
    `;
}

// create function to check
// if all quiz questions have 
// been answered 
function checkAllQuestionsAnswered() {
    // check if number of 
    // answered questions equals 25
    return Object.keys(answers).length === totalQuestions;
}

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

                    // call function to change
                    // submit button state
                    changeSubmitButtonState();
                });
            }
        }
    });
}

// create function to either enable / disable
// submit button, based on whether all questions
// have been answered or not
function changeSubmitButtonState() {
    // get the submit button
    const submitButton = document.getElementById('submit-button');

    // call function to check
    // whether quiz is complete / incomplete
    const isComplete = checkAllQuestionsAnswered();
    
    // enable / disable submit button
    // based on isComplete variable
    submitButton.disabled = !isComplete;
    submitButton.textContent = isComplete ? 'Submit Quiz' : 'Please Answer All Questions';
}