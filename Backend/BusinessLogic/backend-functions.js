// function to get user id
export async function getId() {
    const response = await fetch('/Backend/BusinessLogic/get-user-id.php', { credentials: 'same-origin' });
    const responseJSON = await response.json();
    if (responseJSON.user_id == null) {
        console.error("ID fetching failed");
        return null;
    }
    return responseJSON.user_id;
}

// function to fetch the
// quiz questions from the database
export async function fetchQuestions() {
    const questions = await fetch('/Backend/BusinessLogic/get-questions.php');
    const questionsJSON = await questions.json();
    if (!questionsJSON.success) {
        console.error("Failed to fetch questions: ", questionsJSON.message);
        return {};
    }
    else if (!questionsJSON.data){
        console.error("Failed to fetch questions: ", questionsJSON.message);
        return {};
    }
    else
        return questionsJSON.data;
}

// function to submit the
// quiz responses to the database
export async function submitQuizResponses(answers) {
    const response = await fetch('/Backend/BusinessLogic/submit-quiz-responses.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(answers),
        credentials: 'same-origin'
    });
    const responseJSON = await response.json();
    if (responseJSON.success)
            console.log("Quiz responses submitted successfully");
    else
        console.error("Failed to submit quiz responses: ", responseJSON.message);
}

// function to fetch quiz 
// scores from the database
export async function fetchScores(userId) {
    const scoresFetched = await fetch(`/Backend/BusinessLogic/get-scores.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId })
    });
    const scoresFetchedJSON = await scoresFetched.json();
    if (!scoresFetchedJSON.success) {
        console.error("Failed to fetch scores: ", scoresFetchedJSON.message);
        return {};
    }
    return scoresFetchedJSON.data;
}

// function to fetch the currently
// logged in user's top 20 matches
export async function fetchMatches(limit) {
    const matches = await fetch(`/Backend/BusinessLogic/get-matches.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ limit_num: limit }),
        credentials: 'same-origin'
    });
    const matchesJSON = await matches.json();
    if (!matchesJSON.success) {
        console.error("Failed to fetch matches: ", matchesJSON.message);
        return {};
    }
    return matchesJSON.data;
}

// function to fetch
// a user's profile information
export async function fetchUserProfile(userId) {
    const user = await fetch(`/Backend/BusinessLogic/get-user-profile.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId })
    });
    const userJSON = await user.json();
    console.log(userJSON);
    if (!userJSON.success) {
        console.error("Failed to fetch user profile: ", userJSON.message);
        return {};
    }
    return userJSON.data;
}