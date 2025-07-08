document.addEventListener('DOMContentLoaded', () => {
    // get interactive elements
    const loginSwitch = document.getElementById('login-switch');
    const signupSwitch = document.getElementById('signup-switch');
    const nameFields = document.getElementById('name-fields');
    const loginButton = document.getElementById('login-button');
    const signupButton = document.getElementById('signup-button');

    // get error messages
    const errorMessages = document.querySelectorAll('[id$="msg"]');

    // loop to initially hide all error msgs
    for (let i = 0; i < errorMessages.length; i++)
        errorMessages[i].style.display = 'none';

    // hide login button initially
    // because sign up switch is the default
    loginButton.style.display = 'none';

    // if user clicks on login switch,
    // use the login form
    loginSwitch.addEventListener('click', () => {
        if (!loginSwitch.classList.contains('active')) {
            // user can toggle between login
            // and sign up pages
            signupSwitch.classList.toggle('active');
            loginSwitch.classList.toggle('active');

            // hide name fields, because we
            // will only use email and password
            nameFields.style.display = 'none';

            // display login button
            loginButton.style.display = '';

            // hide sign up button
            signupButton.style.display = 'none';
        }
    });

    // if user clicks on signup switch,
    // use the sign up form
    signupSwitch.addEventListener('click', () => {
        if (!signupSwitch.classList.contains('active')) {
            // user can toggle between login
            // and sign up pages
            loginSwitch.classList.toggle('active');
            signupSwitch.classList.toggle('active');

            // display first and last
            // name fields
            nameFields.style.display = '';
            signupButton.style.display = '';

            // hide login button
            loginButton.style.display = 'none';
        }
    });

    // get signin-form and use submit event listener
    document.getElementById('signin-form').addEventListener('submit', function(event) {
        // prevent default form submission behavior
        event.preventDefault();

        // variable to check whether
        // the action is for sign up or login
        // depending on the button
        const action = event.submitter.value;

        // variable for signing up as a user
        const firstName = document.getElementById('first-name-field').value.trim();
        const lastName = document.getElementById('last-name-field').value.trim();
        const email = document.getElementById('email-field').value.trim();
        const password = document.getElementById('password-field').value.trim();

        // variable to maintain the
        // valid email pattern
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // initially hide all error msgs
        errorMessages.forEach(msg => msg.style.display = 'none');

        // if email or password fields
        // are empty
        if (!email || !password) {
            // display error msg
            document.getElementById('empty-fields-msg').style.display = '';
            return;
        }

        // hide the error msg regarding
        // empty fields
        document.getElementById('empty-fields-msg').style.display = 'none';

        // if email pattern is invalid
        if (!emailPattern.test(email)) {
            // display error msg
            document.getElementById('invalid-email-msg').style.display = '';
            return;
        }

        // hide the error msg regarding
        // invalid email pattern
        document.getElementById('invalid-email-msg').style.display = 'none';

        // if user is logging in
        if (action === 'login') {
            // create object to capture login data
            const loginData = new FormData();

            // get the email and password
            loginData.append('email', email);
            loginData.append('password', password);

            // send loginData to login.php script
            fetch('../Backend/Database/login.php', {
                method: 'POST',
                body: loginData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(text => {
                // log text to console for debugging
                console.log(text);

                // if login is successful
                if (text === 'true') {
                    // redirect to the 
                    // profile page
                    window.location.href = 'profile.php';
                    return;
                }

                // if user's account is inactive
                if (text === 'inactive') {
                    // display error msg
                    document.getElementById('inactive-acc-msg').style.display = '';
                    return;
                }

                // hide error msg about user's
                // account being inactive
                document.getElementById('inactive-acc-msg').style.display = 'none';

                // if login fails 
                // (incorrect email/password)
                if (text === 'false') {
                    // display error msg
                    document.getElementById('invalid-login-msg').style.display = '';
                    return;
                }
            });
            return;
        }

        // hide error msg about failed login
        // due to invalid credentials
        document.getElementById('invalid-login-msg').style.display = 'none';

        // if user is signing up
        if (action === 'signup') {
            // if password is under
            // 8 characters long
            if (password.length < 8) {
                // display error msg
                document.getElementById('invalid-password-msg').style.display = '';
                return;
            }

            // hide error msg about password
            // length being too short
            document.getElementById('invalid-password-msg').style.display = 'none';

            // create object to capture signup data
            const signupData = new FormData();

            // get the first name, last name,
            // email, and password
            signupData.append('firstName', firstName);
            signupData.append('lastName', lastName);
            signupData.append('email', email);
            signupData.append('password', password);

            // send signupData to signup.php script
            fetch('../Backend/Database/signup.php', {
                method: 'POST',
                body: signupData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(text => {
                // if signup is successful
                if (text === 'true') {
                    // redirect to quiz page
                    window.location.href = 'quiz.php';
                    return;
                }

                // if user tries to sign up
                // with an existing email
                if (text === 'false') {
                    // display error msg
                    document.getElementById('existing-email-msg').style.display = '';
                    return;
                }

                // hide error msg about
                // existing email being used
                document.getElementById('existing-email-msg').style.display = 'none';

                // if login fails after
                // account is created
                if (text === 'failed') {
                    // display error msg
                    document.getElementById('login-failure-msg').style.display = '';
                    return;
                }

                // hide error msg about
                // login failure
                document.getElementById('login-failure-msg').style.display = 'none';
            });
            return;
        }
    });
});
