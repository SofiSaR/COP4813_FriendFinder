let scores = {};

export async function checkId() {
    const response = await fetch('/COP4813_FriendFinder/Backend/Database/get-user-id.php', { credientials: 'same-origin' });
    return response.json();
}

export async function fetchQuestions() {
    const questionsQuery = "SELECT * FROM Quiz_Questions;";
    const questions = await fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: questionsQuery})
    });
    return questions.json();
}

export function submitQuizResponses(answers, userId) {
    let quizResponsesSql = `SELECT * FROM Quiz_Responses WHERE user_id = ${userId};`;
    fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: quizResponsesSql})
    })
    .then(response => response.json())
    .then(data => {
        if (data.length > 0) {
            // update quiz responses for this user with new quiz respones
            let updates = Object.entries(answers).map(([question_id, answer]) =>
                `UPDATE Quiz_Responses SET response = ${answer} WHERE user_id = ${userId} AND question_id = ${question_id}`
            ).join('; ');
            fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sql: updates})
            });

            // update sociability score for this user with new sociability score
            updateOrInsertQuizScores('update', 'Sociability', userId);
        }
        else {
            let inserts = `INSERT INTO Quiz_Responses (user_id, question_id, response) VALUES ${Object.entries(answers).map(([question_id, answer]) => `(${userId}, ${question_id}, ${answer})`).join(', ')};`;
            console.log(inserts);
            fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sql: inserts})
            });

            updateOrInsertQuizScores('insert', 'Sociability', userId);
        }
    });
}

export function updateOrInsertQuizScores(mode, category, userId) {

    let categoryQuery = `SELECT * FROM Quiz_Responses JOIN Quiz_Questions ON Quiz_Responses.question_id = Quiz_Questions.id WHERE Quiz_Responses.user_id = ${userId} AND Quiz_Questions.category = '${category}';`;
    fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: categoryQuery})
    })
    .then(response => response.json())
    .then(data => {
        console.log("response data: ", data);
        let categoryScore = 0;
        data.forEach(row => { categoryScore += (row.response - 1) * 5; });
        scores[category] = categoryScore;
        console.log(scores);

        if (mode == 'update') {
            let updateCategoryScore = `UPDATE Quiz_Scores SET ${category.toLowerCase()}_score = ${categoryScore} WHERE user_id = ${userId}`;
            console.log("updateCategoryScore: ", updateCategoryScore);
            fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sql: updateCategoryScore})
            });
        }
        else if (mode == 'insert' && Object.keys(scores).length == 5) {
            let insertCategoryScores = `INSERT INTO Quiz_Scores (user_id, sociability_score, adventurousness_score, reliability_score, athleticism_score, availability_score) VALUES (${userId}, ${scores['Sociability']}, ${scores['Adventurousness']}, ${scores['Reliability']}, ${scores['Athleticism']}, ${scores['Availability']});`;
            console.log("insertCategoryScores: ", insertCategoryScores);
            fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sql: insertCategoryScores})
            }). then(() => {
                let quizScoresSql = `SELECT * FROM Quiz_Scores WHERE user_id = ${userId};`;
                fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ sql: quizScoresSql})
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Quiz Scores Saved Successfully: ", data.length > 0)
                });
            });
        }
        switch (Object.keys(scores).length) {
            case 0:
                console.log("failed to store scores");
                break;
            case 1:
                updateOrInsertQuizScores(mode, 'Adventurousness', userId);
                break;
            case 2:
                updateOrInsertQuizScores(mode, 'Reliability', userId);
                break;
            case 3:
                updateOrInsertQuizScores(mode, 'Athleticism', userId);
                break;
            case 4:
                updateOrInsertQuizScores(mode, 'Availability', userId);
                break;
            default:
                break;
        }
    });
}

export async function fetchScores(userId) {
    const scoresQuery = `SELECT * FROM Quiz_Scores WHERE user_id = ${userId};`;
    console.log("Scores query:", scoresQuery);
    const scoresFetched = await fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: scoresQuery})
    });
    // console.log("Scores fetched:", scoresFetched.json());
    return scoresFetched.json();
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
    const matches = await fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: matchesQuery })
    });
    return matches.json();
}

export async function fetchUserProfile(userId) {
    const userQuery = `SELECT * FROM Users WHERE id = ${userId};`;
    const user = await fetch(`/COP4813_FriendFinder/Backend/Database/query.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sql: userQuery })
    });
    return user.json();
}