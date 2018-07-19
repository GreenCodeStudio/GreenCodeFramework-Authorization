import {Ajax} from "../../Core/js/ajax";

document.querySelectorAll('.loginForm').foreach(x => x.addEventListener('submit', async e => {
    e.preventDefault();
    try {
        let data = await Ajax.Authorization.login();
    } catch (ex) {
    }
}));