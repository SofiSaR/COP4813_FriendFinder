let scores = {};

export async function checkId() {
    const response = await fetch('../Backend/Database/get-user-id.php', { credientials: 'same-origin' });
    return response.json();
}

export async function fetchQuestions() {
    const questionsQuery = "SELECT * FROM Quiz_Questions;";
    const questions = await fetch(`../BackEnd/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: questionsQuery})
    });
    return questions.json();
}

export function submitQuizResponses(answers) {
    let sql = `SELECT * FROM Quiz_Responses WHERE user_id = ${userId};`;
    quizAlreadyTaken = false;
    fetch(`../Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: sql})
    })
    .then(response => response.json())
    .then(data => { quizAlreadyTaken = data.length > 0; });
    if (quizAlreadyTaken) {
        // update quiz responses for this user with new quiz respones
        let updates = Object.entries(answers).map(([question_id, answer]) =>
            `UPDATE Quiz_Responses SET response = ${answer} WHERE user_id = ${userId} AND question_id = ${question_id}`
        ).join('; ');
        fetch(`../Database/query.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ sql: encodeURIComponent(updates)})
        });

        // update sociability score for this user with new sociability score
        updateOrInsertQuizScores('update', 'Sociability');
    }
    else {
        let inserts = `INSERT INTO Quiz_Responses (user_id, question_id, response) VALUES ${Object.entries(answers).map(([question_id, answer]) => `(${userId}, ${question_id}, ${answer})`).join(', ')};`;
        console.log(inserts);
        fetch(`../Database/query.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ sql: encodeURIComponent(inserts)})
        });

        updateOrInsertQuizScores('insert', 'Sociability');
    }
}

export function updateOrInsertQuizScores(mode, category) {

    let categoryQuery = `SELECT response FROM Quiz_Responses JOIN Quiz_Questions ON Quiz_Responses.question_id = Quiz_Questions.id WHERE Quiz_Responses.user_id = ${userId} AND Quiz_Questions.category = '${category}';`;
    fetch(`../Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: categoryQuery})
    })
    .then(response => response.json())
    .then(data => {
        let categoryScore = 0;
        data.forEach(row => { categoryScore += (row.response - 1) * 5; });
        scores[category] = categoryScore;

        if (mode == 'update') {
            let updateCategoryScore = `UPDATE Quiz_Scores SET ${category.toLower()}_score = ${categoryScore} WHERE user_id = ${userId}`;
            fetch(`../Database/query.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sql: encodeURIComponent(updateCategoryScore)})
            });
        }
        else if (mode == 'insert' && scores.length == 5) {
            let insertCategoryScores = `INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score) VALUES (${scores['Sociability']}, ${scores['Adventurousness']}, ${scores['Reliability']}, ${scores['Athleticism']}, ${scores['Availabiity']});`;
            fetch(`../Database/query.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sql: encodeURIComponent(insertCategoryScores)})
            });
        }
        else {
            switch (scores.length) {
                case 0:
                    console.log("failed to store scores");
                    break;
                case 1:
                    calculateQuizScores(mode, 'Adventurousness');
                    break;
                case 2:
                    calculateQuizScores(mode, 'Reliability');
                    break;
                case 3:
                    calculateQuizScores(mode, 'Athleticism');
                    break;
                case 4:
                    calculateQuizScores(mode, 'Availability');
                    break;
                default:
                    break;
            }
        }
    });
}

export async function fetchScores(userId) {
    const scoresQuery = `SELECT * FROM Quiz_Scores WHERE user_id = ${userId};`;
    const scores = await fetch(`../Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: scoresQuery})
    });
    return scores.json();
}

export async function fetchMatches(userId, limit) {
    const matchesQuery =
        `SELECT Users.*,
            (100 - (
                (0.20 * ABS(other_users_scores.sociability_score - this_users_scores.sociability_score)) +
                (0.20 * ABS(other_users_scores.adventurousness_score - this_users_scores.adventurousness_score)) +
                (0.20 * ABS(other_users_scores.reliability_score - this_users_scores.reliability_score)) +
                (0.20 * ABS(other_users_scores.athleticism_score - this_users_scores.athleticism_score)) +
                (0.20 * ABS(other_users_scores.availability_score - this_users_scores.availability_score))
            )) AS similarity_score
        FROM Users
        JOIN Quiz_Scores other_users_scores ON other_users_scores.user_id = Users.id
        JOIN Quiz_Scores this_users_scores ON this_users_scores.user_id = ${userId}
        WHERE Users.id != ${userId}
        ORDER BY similarity_score
        DESC LIMIT ${limit};`;
    const matches = await fetch(`../Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: matchesQuery})
    });
    return matches.json();
}