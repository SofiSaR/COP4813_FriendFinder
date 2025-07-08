// import fetch user profile function from backend
import { fetchUserProfile } from '../../Backend/BusinessLogic/backend-functions.js';

document.addEventListener('DOMContentLoaded', () => {
    // get add-user-form and use submit event listener
    document.getElementById('add-user-form').addEventListener('submit', function(event) {
        // prevent default form submission behavior
        event.preventDefault();

        // create object to capture form fields
        const formData = new FormData(this);

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
            method: 'POST',
            body: formData
        });

        // redirect to admin.php, so we can see
        // the table with the new user
        window.location.href = 'admin.php';
    });
});