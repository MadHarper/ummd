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

                if (userProfile && need_check_user) {
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    $.ajax('/toris/default/check', {
                        method: 'POST',
                        data: {
                            _csrf :  csrfToken,
                            userData: {
                                token:          userProfile.data.AISTOKEN,
                                bx_id:          userProfile.data.USER_BX_ID,
                                fio:            userProfile.data.USER_FIO,
                                rank:           userProfile.data.USER_POSITION,
                                phone:          userProfile.data.USER_PHONE,
                                email:          userProfile.data.USER_EMAIL,
                                login:          userProfile.data.USER_ESOV_LOGIN,
                                iogv_id:        userProfile.data.ORG_CODE_IOGV,
                                iogv_name:      userProfile.data.ORG_NAME,
                                org_address:    userProfile.data.ORG_ADDRESS,
                                org_phone:      userProfile.data.ORG_PHONE,
                                org_email:      userProfile.data.ORG_EMAIL,
                                org_okpo:       userProfile.data.ORG_OKPO,
                                org_okogu:      userProfile.data.ORG_OKOGU,
                                org_ogrn:       userProfile.data.ORG_OGRN,
                                esedd_uid:      userProfile.data.USER_ESEDD_UID,
                                esedd_org_uid:  userProfile.data.ORG_ESEDD_UID,
                                roles:          userProfile.data.USER_ROLES,
                                esov_uid:       userProfile.data.USER_ESOV_UID,
                                aistoken:       userProfile.data.AISTOKEN
                            }
                        }
                    }).done(function(data) {
                        if (data.need_redirect) {
                            window.location = data.redirect;
                        } else {
                            TorisWidget.audit();
                        }
                    });
                }
            });
        });
    },
    audit: function () {
        var audit = $("#torisAudit").val();
        if (audit != undefined) {
            audit = JSON.parse(audit);
            TORIS.audit(audit.event, T_COMMON.serialize(audit.params));
        }
        var audit_after_action = $("#torisAuditAfterAction").val();
        if (audit_after_action != undefined) {
            audit_after_action = JSON.parse(audit_after_action);
            TORIS.audit(audit_after_action.event, T_COMMON.serialize(audit_after_action.params));
        }
    }
};