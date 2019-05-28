import {Ajax} from "../../../Core/js/ajax";

export default class {
    constructor(page, data) {
        page.querySelectorAll('.loginForm').forEach(x => x.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                var form = document.querySelector('.loginForm');
                await Ajax.Authorization.login(form.username.value, form.password.value);
                document.location = '/';
            } catch (ex) {
                if (ex.type === "Authorization\\Exceptions\\BadAuthorizationException")
                    form.querySelector('.error').textContent = 'Zły login lub hasło';
                else
                    form.querySelector('.error').textContent = 'Błąd';
                form.querySelector('.error').classList.remove('hidden');
            }
        }));
        page.querySelectorAll('.registerForm').forEach(x => x.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                var form = document.querySelector('.registerForm');
                let data = await Ajax.User.register(form.mail.value, form.password.value, form.password2.value);
                document.location = '/';
            } catch (ex) {
                if (ex.type === "Authorization\\Exceptions\\BadAuthorizationException")
                    form.querySelector('.error').textContent = 'Zły login lub hasło';
                else
                    form.querySelector('.error').textContent = 'Błąd';
                form.querySelector('.error').classList.remove('hidden');
            }
        }));

        page.querySelectorAll('input').forEach(x => x.addEventListener('input', e => {
            if (e.target.value == "")
                e.target.classList.remove('notEmpty');
            else
                e.target.classList.add('notEmpty');
        }));
    }
}