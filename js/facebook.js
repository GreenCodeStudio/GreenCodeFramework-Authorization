export default {
    init() {
        return new Promise((resolve, reject) => {
            window.fbAsyncInit = function () {
                FB.init({
                    appId: '1208259882905898',
                    cookie: true,
                    xfbml: true,
                    version: 'v2.7'
                });

                FB.AppEvents.logPageView();
                resolve(FB);
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        })
    },
    async startLogin() {
        await this.init();
        const result = await this.executeLogin();
        console.log(result);
        Ajax.Authorization.facebook();

    },
    executeLogin() {
        return new Promise((resolve, reject) => {
            FB.login(resolve);
        })
    }
}