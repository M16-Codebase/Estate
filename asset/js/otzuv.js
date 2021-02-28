$(document).ready(function() {

    var form = $('#send-review');
    
    form.submit(function(e) {
        e.preventDefault();
        var c1 = $('form#send-review input[name=c1]').val();
        var c2 = $('form#send-review input[name=c2]').val();
        var manager = $('form#send-review input[name=manager]').val();
        var c3 = $('form#send-review textarea[name=c3]').val();

        if(c1 === '') {
            $.growl.error({ title: "Сообщение:", message: "Заполните пожалуйста поле Имя" });
            return false
        }

        if (manager !== '') {
            wc = manager.split(' ');
            wcount = wc.length;
            
            if (wcount !== 2) {
                $.growl.error({ title: "", message: "Пожалуйста, введите полное имя и фамилию менеджера, чтобы мы могли опубликовать отзыв на сайте. Спасибо!" });
                return false;
            }
            console.log(wc);
        } else {
            $.growl.error({ title: "", message: "Пожалуйста, введите полное имя и фамилию менеджера, чтобы мы могли опубликовать отзыв на сайте. Спасибо!" });
            return false;
        }
        

        if(c3 === '') {
            $.growl.error({ title: "Сообщение:", message: "Заполните пожалуйста поле <br>Текст отзыва" });
            return false
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/ajax_otzuv',
            dataType: 'json',
            data: {
                c1: c1,
                c2: c2,
                c3: c3,
                manager: manager
            },
            success: function(data) {
                $.growl.notice({ title: "Сообщение:", message: "Спасибо!<br>Ваш отзыв отправлен. " });
                $('form#send-review input[name=c1]').val('');
                $('form#send-review input[name=c2]').val('');
                $('form#send-review textarea[name=c3]').val('');
            }
        });
    });
});

