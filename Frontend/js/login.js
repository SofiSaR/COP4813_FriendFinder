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
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        errorMessages.forEach(msg => msg.style.display = 'none');

        if (!email || !password) {
            document.getElementById('empty-fields-msg').style.display = '';
            return;
        }
        document.getElementById('empty-fields-msg').style.display = 'none';
        if (!emailPattern.test(email)) {
            document.getElementById('invalid-email-msg').style.display = '';
            return;
        }
        document.getElementById('invalid-email-msg').style.display = 'none';
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
                if (text === 'inactive') {
                    document.getElementById('inactive-acc-msg').style.display = '';
                    return;
                }
                document.getElementById('inactive-acc-msg').style.display = 'none';
                if (text === 'false') {
                    document.getElementById('invalid-login-msg').style.display = '';
                    return;
                }
            });
            return;
        }
        document.getElementById('invalid-login-msg').style.display = 'none';
        if (action === 'signup') {
            if (password.length < 8) {
                document.getElementById('invalid-password-msg').style.display = '';
                return;
            }
            document.getElementById('invalid-password-msg').style.display = 'none';
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
                    document.getElementById('existing-email-msg').style.display = '';
                    return;
                }
                document.getElementById('existing-email-msg').style.display = 'none';
                if (text === 'failed') {
                    document.getElementById('login-failure-msg').style.display = '';
                    return;
                }
                document.getElementById('login-failure-msg').style.display = 'none';
            });
            return;
        }
    });
});
