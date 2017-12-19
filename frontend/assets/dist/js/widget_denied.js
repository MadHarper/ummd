var TorisWidget = {
    init: function (login_redirect_url,logout_redirect_url, need_check_user, domain, sys_id) {
        TORIS.setOptions({
            sys_id: sys_id,
            is_toris: true,
            login_redirect_url: login_redirect_url,
            logout_redirect_url: logout_redirect_url,
            domain: domain,
            domain_proto: '',
            show_auth: true
            //redirect_to_auth: true
        });
        console.info('attempt call TORIS.init...');
        TORIS.init(function (message) {
            console.info('Результат первичной инициализации:', message);
        });
        /***
         * Событие полной загрузки виджета.
         * Можно начинать юзать методы API
         ***/
        window.addEventListener('TORISWidgetInitComplete', function (e) {
            /*** Получаем профиль пользователя ***/
            TORIS.userProfile(function (userProfile) {
                console.info("Профиль пользователя:", userProfile);


            });
        });
    },
};