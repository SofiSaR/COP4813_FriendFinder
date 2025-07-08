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

    document.getElementById('edit-user-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        formData.append('id', userId); // Append userId to the form data
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
        checkboxes.forEach(checkbox => {
            if (!formData.has(checkbox.name))
                formData.append(checkbox.name, checkbox.checked ? 1 : 0); // or 0
            else
                formData.set(checkbox.name, checkbox.checked ? 1 : 0); // Ensure boolean values are stored as 1 or 0
        });

        console.log('Form Data:', Object.fromEntries(formData.entries()));
        fetch(`../Backend/Database/admin.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData.entries()))
        });
        window.location.href = 'admin.php';
    });
});