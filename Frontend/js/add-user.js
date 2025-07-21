// import fetch user profile function from backend
import { fetchUserProfile } from '../../Backend/BusinessLogic/backend-functions.js';

document.addEventListener('DOMContentLoaded', () => {
    // get add-user-form and use submit event listener
    document.getElementById('add-user-form').addEventListener('submit', function(event) {
        // prevent default form submission behavior
        event.preventDefault();

        // create object to capture form fields
        const formData = new FormData(this);

        if (formData.get('pfpUrl') === '') {
            // if pfpUrl is empty, set it to a default image
            formData.set('pfpUrl', 'pink-profile-icon.webp');
        }

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

        // send formData to admin.php script
        fetch(`/Backend/BusinessLogic/admin.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', ...Object.fromEntries(formData.entries()) })
        })
        .then(response => response.json())
        .then(result => {
            // handle the response from the server
            if (result.success) {
                // user added successfully
                console.log('User added successfully:', result.message);
            }
            else {
                // handle errors
                console.error('Error adding user:', result.message);
            }
        });

        // redirect to admin.php, so we can see
        // the table with the new user
        window.location.href = '/Frontend/admin.php';
    });
});