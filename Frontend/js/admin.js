document.addEventListener('DOMContentLoaded', () => {
    const userManagementTable = document.getElementById('user-management-table');

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

        const deleteButtons = userManagementTable.querySelectorAll('.delete-user');
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('user-id');
                fetch(`../Backend/Database/admin.php?userId=${userId}`, {
                    method: 'DELETE'
                });
                window.location.reload();
            });
        });
        const editButtons = userManagementTable.querySelectorAll('.edit-user');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('user-id');
                window.location.href = `edit-user.php?userId=${userId}`;
            });
        });
        const addUserButton = document.getElementById('add-user-button');
        addUserButton.addEventListener('click', () => {
            window.location.href = 'add-user.php';
        });
    });
});