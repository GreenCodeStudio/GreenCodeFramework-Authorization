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
        page.querySelectorAll('.loginByFacebook').forEach(x => x.onclick = () => facebook.startLogin());


        page.querySelectorAll('.resetPasswordForm').forEach(x => x.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                let form = page.querySelector('.resetPasswordForm');
                await Ajax.Authorization.resetPassword(form.username.value);
                await modal(t('passwordResetInfo'), 'info');
                document.location = '/Authorization/resetPassword2/' + encodeURIComponent(form.username.value);
            } catch (ex) {
                modal(t('errorOccured'), 'error');
            }
        }));
        page.querySelectorAll('.resetPasswordForm2').forEach(x => x.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                let form = page.querySelector('.resetPasswordForm2');
                if(form.password.value !== form.password2.value){
                    modal(t('PasswordsNotEqual'), 'error');
                    return;
                }
                await Ajax.Authorization.resetPassword2(form.username.value, form.code.value, form.password.value);
                await modal(t('passwordResetInfo2'), 'info');
                document.location = '/';
            } catch (ex) {
                modal(t('errorOccured'), 'error');
            }
        }));
    }
}
