document.addEventListener('DOMContentLoaded', () => {
    // get the table with all 
    // user info
    const userManagementTable = document.getElementById('user-management-table');

    // add table headers
    userManagementTable.innerHTML = `
        <thead>
            <tr>
                <th>User ID</th>
                <th>Profile Image URL</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Password</th>
                <th>Bio</th>
                <th>Bio Approved</th>
                <th>Account Active</th>
                <th>Edit</th>
                <th>Delete</th>
            <tr>
        </thead>
    `;

    // add row for each user with
    // their personal details to the table
    // also add buttons for edit and delete functionality
    fetch(`../Backend/Database/admin.php`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(users => {
        console.log(users);
        userManagementTable.innerHTML += `<tbody>`;
        userManagementTable.innerHTML += users.map(user => `
            <tr>
                <td>${user.id}</td>
                <td>${user.pfpUrl}</td>
                <td>${user.first_name}</td>
                <td>${user.last_name}</td>
                <td>${user.phone_number}</td>
                <td>${user.email}</td>
                <td>${user.pwd}</td>
                <td>${user.bio}</td>
                <td>${user.bio_approved == 1 ? "Yes" : "No"}</td>
                <td>${user.account_active == 1 ? "Yes" : "No"}</td>
                <td>
                    <button class="edit-user table-button" user-id="${user.id}">Edit</button>
                </td>
                <td>
                    <button class="delete-user table-button" user-id="${user.id}">Delete</button>
                </td>
            </tr>
        `).join('');
        userManagementTable.innerHTML += `</tbody>`;

        // for deleting a user from the table
        // if the admin clicks on the delete button
        const deleteButtons = userManagementTable.querySelectorAll('.delete-user');
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                // get the user's id
                const userId = button.getAttribute('user-id');

                // use it to delete the user
                fetch(`../Backend/Database/admin.php?userId=${userId}`, {
                    method: 'DELETE'
                });

                // refresh the page automatically
                window.location.reload();
            });
        });

        // for editing any user info from the
        // table if the admin clicks on the edit button
        const editButtons = userManagementTable.querySelectorAll('.edit-user');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                // get the user's id
                const userId = button.getAttribute('user-id');

                // redirect to the edit user page,
                // so admin can make changes there
                window.location.href = `edit-user.php?userId=${userId}`;
            });
        });

        // for adding a user to the table
        // if the admin clicks on the add button, 
        // located below the table
        const addUserButton = document.getElementById('add-user-button');
        addUserButton.addEventListener('click', () => {
            // redirect to the add user page,
            // so admin can add user details there
            window.location.href = 'add-user.php';
        });
    });
});