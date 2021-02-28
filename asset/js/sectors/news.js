$(document).ready(function() {
    var unisender = {
        url: 'https://api.unisender.com/LANG/api/METHOD?format=FORMAT&api_key=KEY',
        methodAlias: 'METHOD',
        lang: 'ru',
        format: 'json',
        api_key: '5f36zqctha3jd4na8kc1q783iq9jihdh4hnfxtfy',
        params: {},
        prepareUrl: function (method) {
            var method = method || '';
            var url = this.url.replace(/METHOD/g, method);
            url = url.replace(/LANG/g, this.lang);
            url = url.replace(/KEY/g, this.api_key);
            url = url.replace(/FORMAT/g, this.format);
            return url;
        },
        prepareParams: function (params, paramsObject) {
            paramsObject = paramsObject || {};
            params = params || {};

            $.each(params, function(param, value) {
                paramsObject[param] = value;
            });
            return paramsObject;
        },
        subscribe: function(params, callBack) {
            var method = 'subscribe';
            this.execute(method, callBack, params);
        },
        getLists: function(callBack) {
            callBack = callBack || ef();
            var method = 'getLists';
            this.execute(method, callBack);
        },
        execute: function(method, callBack, params) {
            callBack = callBack || this.ef();
            params = params || {};
            var data = this.prepareParams(params, this.params);
            var url = this.prepareUrl(method)
            $.ajax({
                url: url,
                type: "GET",
                data,
                error: function(xhr, error){
                    console.debug(xhr); console.debug(error);
                },
                success: function(data) {
                    callBack(data);
                }
            });
        },
        ef: function () {}
    };

    var formWrap =  $('.form_uni_blog'),
        form = formWrap.find('form[name="subscribtion_form"]'),
        submit = form.find('input[type=submit]'),
        subscribeTitle = "Основной список",
        subscribeID = 6866094;

        $('#content').on('click', '.subscribe-form-item--btn-submit', function (e) {
            e.preventDefault();
            var email = form.find('input[name=email]').val();
            var name = form.find('input[name=f_4448066]').val();

            if ('' === email || '' === name) {
                $.growl.error({ title: "Ошибка:", message: "Заполните все поля" });
                return;
            }

            subscribeID = $('.form_uni_blog').find('input[name=default_list_id]').val();

            var params = {
                fields: {email: email, name: name},
                list_ids: subscribeID,
                double_optin: 0
            };
            if ('userIP' in window) {
                params.request_ip = window.userIP;
            }
            unisender.subscribe(params, function(result) {
                if ('error' in result) {
                    $.growl.error({ title: "Ошибка:", message: "Что-то пошло не так." });
                } else {
                    if ('result' in result && typeof result === "object") {
                        if ('person_id' in result['result']) {
                            if ('yaCounter29760432' in window) {
                                window.yaCounter29760432.reachGoal('PODPISKA_BLOG');
                            }
                            if ('ga' in window) {
                                window.ga('send', 'event', 'podpiska_blog', 'succes_send','send');
                            }
                            $.growl.notice({ title: "Сообщение:", message: "Спасибо!<br>На указанный адрес электронной почты отправлено письмо со ссылкой на подтверждение подписки. " });
                        }
                    }
                }
                return;
            });
        });

});
