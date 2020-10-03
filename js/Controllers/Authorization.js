import {Ajax} from "../../../Core/js/ajax";
import {modal} from "../../../Core/js/modal";
import facebook from "../facebook";
import {t} from "../../i18n.xml"

export default class {
    constructor(page, data) {
        page.querySelectorAll('.loginForm').forEach(x => x.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                let form = page.querySelector('.loginForm');
                await Ajax.Authorization.login(form.username.value, form.password.value);
                document.location = '/';
            } catch (ex) {
                if (ex.type === "Authorization\\Exceptions\\BadAuthorizationException")
                    modal(t('badLoginOrPassword'), 'error');
                else
                    modal(t('errorOccured'), 'error');
            }
        }));
        page.querySelectorAll('.registerForm').forEach(x => x.addEventListener('submit', async e => {
            e.preventDefault();
            let form = document.querySelector('.registerForm');
            try {
                await Ajax.User.register(form.mail.value, form.password.value, form.password2.value);
                document.location = '/';
            } catch (ex) {
                if (ex.type === "User\\Exceptions\\PasswordsNotEqualException")
                    form.querySelector('.error').textContent = t('PasswordsNotEqual');
                else if (ex.type === "User\\Exceptions\\UserExistsException")
                    form.querySelector('.error').textContent = t('UserExists');
                else
                    form.querySelector('.error').textContent = t('errorOccured');
                form.querySelector('.error').classList.remove('hidden');
            }
        }));

        page.querySelectorAll('input').forEach(x => x.addEventListener('input', e => {
            if (e.target.value === "")
                e.target.classList.remove('notEmpty');
            else
                e.target.classList.add('notEmpty');
        }));
        page.querySelectorAll('.loginByFacebook').forEach(x=>x.onclick=()=>facebook.startLogin());
    }
}