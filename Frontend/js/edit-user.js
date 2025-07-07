import { fetchUserProfile } from '../../Backend/BusinessLogic/backend-functions.js';

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('userId');

    const allFormElements = document.querySelectorAll('input, textarea');
    const formElementsArray = Array.from(allFormElements);

    fetchUserProfile(userId)
    .then(user => {
        const userInfo = [user[0].pfpUrl, user[0].first_name, user[0].last_name, user[0].phone_number, user[0].email, user[0].pwd, user[0].bio, user[0].bio_approved, user[0].account_active];
        formElementsArray.forEach((element, index) => {
            if (index < userInfo.length) {
                if (element.type === 'checkbox') {
                    element.checked = userInfo[index] === '1';
                } else {
                    element.value = userInfo[index];
                }
            }
        });
    });
});