import {Ajax} from "../../Core/js/ajax";

document.querySelectorAll('.loginForm').forEach(x => x.addEventListener('submit', async e => {
    e.preventDefault();
    try {
        var form = document.querySelector('.loginForm');
        let data = await Ajax.Authorization.login(form.username.value, form.password.value);
        document.location = '/';
    } catch (ex) {
        form.querySelector('.error').textContent = 'Błąd';
        form.querySelector('.error').classList.remove('hidden');
    }
}));
