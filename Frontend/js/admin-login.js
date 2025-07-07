document.addEventListener('DOMContentLoaded', () => {

    const errorMessages = document.querySelectorAll('[id$="msg"]');

    for (let i = 0; i < errorMessages.length; i++)
        errorMessages[i].style.display = 'none';

    document.getElementById('signin-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const action = event.submitter.value;
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
            fetch('../Backend/Database/admin-login.php', {
                method: 'POST',
                body: loginData,
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(text => {
                console.log(text);
                if (text === 'true') {
                    window.location.href = 'admin.php';
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
    });
});
