document.addEventListener('DOMContentLoaded', () => {

    const loginSwitch = document.getElementById('login-switch');
    const signupSwitch = document.getElementById('signup-switch');
    const nameFields = document.getElementById('name-fields');
    const loginButton = document.getElementById('login-button');
    const signupButton = document.getElementById('signup-button');
    const errorMessages = document.querySelectorAll('[id$="msg"]');

    for (let i = 0; i < errorMessages.length; i++)
        errorMessages[i].style.display = 'none';

    loginButton.style.display = 'none';

    loginSwitch.addEventListener('click', () => {
        if (!loginSwitch.classList.contains('active')) {
            signupSwitch.classList.toggle('active');
            loginSwitch.classList.toggle('active');
            nameFields.style.display = 'none';
            loginButton.style.display = '';
            signupButton.style.display = 'none';
        }
    });
    signupSwitch.addEventListener('click', () => {
        if (!signupSwitch.classList.contains('active')) {
            loginSwitch.classList.toggle('active');
            signupSwitch.classList.toggle('active');
            nameFields.style.display = '';
            signupButton.style.display = '';
            loginButton.style.display = 'none';
        }
    });

    document.getElementById('signin-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const action = event.submitter.value;
        const firstName = document.getElementById('first-name-field').value.trim();
        const lastName = document.getElementById('last-name-field').value.trim();
        const email = document.getElementById('email-field').value.trim();
        const password = document.getElementById('password-field').value.trim();
        let msgIndex = 0;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email || !password) {
            errorMessages[msgIndex].style.display = '';
            return;
        }
        errorMessages[msgIndex].style.display = 'none';
        msgIndex++;
        if (!emailPattern.test(email)) {
            errorMessages[msgIndex].style.display = '';
            return;
        }
        errorMessages[msgIndex].style.display = 'none';
        msgIndex++;
        if (action === 'login') {
            const loginData = new FormData();
            loginData.append('email', email);
            loginData.append('password', password);
            fetch('../Backend/Database/login.php', {
                method: 'POST',
                body: loginData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(text => {
                console.log(text);
                if (text === 'true') {
                    window.location.href = 'profile.php';
                    return;
                }
                else {
                    errorMessages[msgIndex].style.display = '';
                    return;
                }
            });
            return;
        }
        errorMessages[msgIndex].style.display = 'none';
        msgIndex++;
        if (action === 'signup') {
            if (password.length < 8) {
                errorMessages[msgIndex].style.display = '';
                return;
            }
            errorMessages[msgIndex].style.display = 'none';
            msgIndex++;
            const signupData = new FormData();
            signupData.append('firstName', firstName);
            signupData.append('lastName', lastName);
            signupData.append('email', email);
            signupData.append('password', password);
            fetch('../Backend/Database/signup.php', {
                method: 'POST',
                body: signupData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(text => {
                if (text === 'true') {
                    window.location.href = 'quiz.php';
                    return;
                }
                if (text === 'false') {
                    errorMessages[msgIndex].style.display = '';
                    return;
                }
                errorMessages[msgIndex].style.display = 'none';
                msgIndex++;
                if (text === 'failed') {
                    errorMessages[msgIndex].style.display = '';
                    return;
                }
                errorMessages[msgIndex].style.display = 'none';
            });
            return;
        }
    });
});
