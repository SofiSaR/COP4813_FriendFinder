document.addEventListener('DOMContentLoaded', () => {
    // variable for error messages
    const errorMessages = document.querySelectorAll('[id$="msg"]');

    // loop to initially hide all error msgs
    for (let i = 0; i < errorMessages.length; i++)
        errorMessages[i].style.display = 'none';

    // get signin-form and use submit event listener
    document.getElementById('signin-form').addEventListener('submit', function(event) {
        // prevent default form submission behavior
        event.preventDefault();

        // variable to check whether
        // the action is for sign up or login
        // depending on the button
        const action = event.submitter.value;

        // variables for email and password
        const email = document.getElementById('email-field').value.trim();
        const password = document.getElementById('password-field').value.trim();

        // initialize index for first
        // error message to 0
        let msgIndex = 0;

        // variable to maintain the
        // valid email pattern
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // if email or password fields
        // are empty
        if (!email || !password) {
            // display error msg
            errorMessages[msgIndex].style.display = '';
            return;
        }

        // hide the error msg regarding
        // empty fields and increment msg index
        errorMessages[msgIndex].style.display = 'none';
        msgIndex++;

        // if the email has an 
        // invalid pattern
        if (!emailPattern.test(email)) {
            // display error msg
            errorMessages[msgIndex].style.display = '';
            return;
        }

        // hide the error msg regarding
        // invalid email pattern and increment msg index
        errorMessages[msgIndex].style.display = 'none';
        msgIndex++;

        // if the admin is logging in
        if (action === 'login') {
            // create object to capture form fields
            const loginData = new FormData();

            // add email and password to
            // the object
            loginData.append('email', email);
            loginData.append('password', password);

            // send formData to admin-login.php script
            fetch('../Backend/Database/admin-login.php', {
                method: 'POST',
                body: loginData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(text => {
                // log text to console for debugging
                console.log(text);

                // if credentials are
                // valid (successful login)
                if (text === 'true') {
                    // redirect to the admin page
                    window.location.href = 'admin.php';
                    return;
                }
                else {
                    // display error msg
                    errorMessages[msgIndex].style.display = '';
                    return;
                }
            });
            return;
        }
        // hide the error msg regarding
        // login failure
        errorMessages[msgIndex].style.display = 'none';
    });
});
