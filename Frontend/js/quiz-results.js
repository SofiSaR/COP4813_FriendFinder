document.addEventListener('DOMContentLoaded', () => {
    const viewProfileButtons = document.querySelectorAll('[class$="view-profile"]');

    for (let i = 0; i < viewProfileButtons.length; i++) {
        viewProfileButtons[i].addEventListener('click', function(event) {
            window.location.href = 'profile.php';
        });
    }
});