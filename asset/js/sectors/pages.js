$(document).ready(function() {

    var formWrap =  $('.subscribe'),
        form = formWrap.find('form'),
        submit = form.find('input[type=submit]'),
        subscribeTitle = "Основной список",
        subscribeID = 6866094;


    formWrap.on('click', submit, function(e) {
        e.preventDefault();
        console.log('click');
    });
    submit.on('click', function(e){
        e.preventDefault();
        console.log('unisender' in window);
        var email = form.find('input.email').val();
        if (! 'unisender' in window) {
            console.log("unisender not detected!");
            $.growl.error({ title: "Ошибка:", message: "Что-то пошло не так." });
            return;
        }
        var params = {
            fields: {email: email},
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


