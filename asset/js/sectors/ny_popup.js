/*
$(function() {

    var ny_popup = $('#ny_popup');
    var dialog = ny_popup.find('.modal-dialog');
    var content = ny_popup.find('.modal-content');
    setTimeout(function(){
        ny_popup.modal()
    }, 20000);

    ny_popup.on('show.bs.modal', function (e) {

        var wh = $(window).height();
        var mh = content.height();

        if (wh > mh) {
            var top = (wh - mh) / 2 ;
            dialog.css('margin-top', top + 'px');
        }
        var promo = generatePromo();

        ny_popup.find('#promo').empty().text(promo);
    });

    function generatePromo()
    {
        var result       = '';
        var words        = '0123456789qwertyuiopasdfghjklzxcvbnm';
        var max_position = words.length - 1;
        for( i = 0; i < 3; ++i ) {
            position = Math.floor ( Math.random() * max_position );
            result = result + words.substring(position, position + 1);
        }
        return result;
    }
});
*/