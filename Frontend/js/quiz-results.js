// import getId function from backend-functions.js
import { getId } from 'https://friendshipmatchmaking.infinityfreeapp.com/Backend/BusinessLogic/backend-functions.js';
document.addEventListener('DOMContentLoaded', async () => {
    const userId = await getId();
    document.querySelector('results-section').setAttribute('user-id', userId);
});