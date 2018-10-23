import {Ajax} from "../../Core/js/ajax";

document.querySelectorAll('.loginForm').forEach(x => x.addEventListener('submit', async e => {
    e.preventDefault();
    try {
        var form = document.querySelector('.loginForm');
        let data = await Ajax.Authorization.login(form.username.value, form.password.value);
        document.location = '/';
    } catch (ex) {
        if (ex.type == "Authorization\\Exceptions\\BadAuthorizationException")
            form.querySelector('.error').textContent = 'Zły login lub hasło';
        else
            form.querySelector('.error').textContent = 'Błąd';
        form.querySelector('.error').classList.remove('hidden');
    }
}));
