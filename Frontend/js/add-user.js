import { fetchUserProfile } from '../../Backend/BusinessLogic/backend-functions.js';

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('add-user-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
        checkboxes.forEach(checkbox => {
            if (!formData.has(checkbox.name))
                formData.append(checkbox.name, checkbox.checked ? 1 : 0); // or 0
            else
                formData.set(checkbox.name, checkbox.checked ? 1 : 0); // Ensure boolean values are stored as 1 or 0
        });

        console.log('Form Data:', Object.fromEntries(formData.entries()));
        fetch(`../Backend/Database/admin.php`, {
            method: 'POST',
            body: formData
        });
        window.location.href = 'admin.php';
    });
});