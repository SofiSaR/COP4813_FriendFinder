// import fetch user profile function from backend
import { fetchUserProfile } from '../../Backend/BusinessLogic/backend-functions.js';

document.addEventListener('DOMContentLoaded', () => {
    // get the user id from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('userId');

    // create array with all the 
    // form elements
    const allFormElements = document.querySelectorAll('input, textarea');
    const formElementsArray = Array.from(allFormElements);

    // use user's id to fetch
    // their information
    fetchUserProfile(userId)
    .then(user => {
        // get user's current info
        // and fill form elements with it
        const userInfo = [user[0].pfpUrl, user[0].first_name, user[0].last_name, user[0].phone_number, 
        user[0].email, user[0].pwd, user[0].bio, user[0].bio_approved, user[0].account_active];

        // loop through each form element
        formElementsArray.forEach((element, index) => {
            if (index < userInfo.length) {
                // if the form element
                // contains a checkbox
                if (element.type === 'checkbox') {
                    // set checkbox to true if 1
                    // and false if 0
                    element.checked = userInfo[index] === '1';
                } else {
                    // set the value for non-checkbox
                    // form elements
                    element.value = userInfo[index];
                }
            }
        });
    });

    // get edit-user-form and use submit event listener
    document.getElementById('edit-user-form').addEventListener('submit', function(event) {
        // prevent default form submission behavior
        event.preventDefault();

        // create object to capture form data
        const formData = new FormData(this);

        // append user's id to the form
        formData.append('id', userId); 

        // get checkbox inputs in the form 
        // (bio approved and account active)
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
        // loop through each checkbox
        checkboxes.forEach(checkbox => {
            // if name isn't in the formData
            if (!formData.has(checkbox.name))
                // add checkbox (value 1 = checked, value 0 = unchecked)
                formData.append(checkbox.name, checkbox.checked ? 1 : 0); 
            else
                // if name exists, update its value
                formData.set(checkbox.name, checkbox.checked ? 1 : 0); 
        });

        // log formData to console for debugging
        console.log('Form Data:', Object.fromEntries(formData.entries()));

        // send formData to admin.php script
        fetch(`../Backend/Database/admin.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData.entries()))
        });

        // redirect to admin.php, so we can see
        // the table with the updated user info
        window.location.href = 'admin.php';
    });
});