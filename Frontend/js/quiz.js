let userId = 0;
const answers = {};

document.addEventListener('DOMContentLoaded', () => {
    checkId();
    fetchQuestions();

    const submitButton = document.getElementById('submit-button');

    submitButton.addEventListener('click', () => {
        // console.log(userId);
        let sql = `SELECT * FROM Quiz_Responses WHERE user_id = ${userId};`;
        quizAlreadyTaken = false;
        fetch(`../BackEnd/Database/query.php?sql=${sql}`)
            .then(response => response.json())
            .then(data => { quizAlreadyTaken = data.length > 0; });
        if (quizAlreadyTaken) {
            const updates = Object.entries(answers).map(([question_id, answer]) =>
                `UPDATE Quiz_Responses SET response = ${answer} WHERE user_id = ${userId} AND question_id = ${question_id}`
            ).join('; ');
            const sql = updates + ';';
            fetch(`../BackEnd/Database/query.php?sql=${sql}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Answers submitted successfully!');
                } else {
                    alert('Error submitting answers.');
                }
            });
        }
        sql = `INSERT INTO Quiz_Responses (user_id, question_id, response) VALUES ${Object.entries(answers).map(([question_id, answer]) => `(${userId}, ${question_id}, ${answer})`).join(', ')};`;
        console.log(sql);
        fetch(`../BackEnd/Database/query.php?sql=${sql}`);
        window.location.href = 'quiz-results.php';
    });
});

function checkId() {
    fetch('../Backend/Database/get-user-id.php', { credientials: 'same-origin' })
    .then(response => response.json())
    .then(data => { userId = data.user_id; });
}

function fetchQuestions() {
    const sql = "SELECT * FROM Quiz_Questions;";
    fetch(`../BackEnd/Database/query.php?sql=${sql}`)
        .then(response => response.json())
        .then(data => populateQuestionCont(data));
}

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