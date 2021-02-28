listTablePagination(); // Пагинация списка таблицы вывода
showHideOptions(); // Скрыть/Показать настройки
actionSubmitOn(); // Множественные действия с записями
switchesActionAjax(); // Передача параметров для простого редактирования определенных данных по типу: видимость, сортировка, title
searchAjaxTable(); // ajax Поиск данных в таблице
$('#sorting').tablesorter(); // сортировка для таблицы
//$('.loadField').contentLoad();

$(document).ready(function() {

    $('[class*="maxlength"]').each(function(){
        var myClasses = $(this).attr("class").split(' ');
        var el = $(this);
        $.each(myClasses, function(i, val){
            var spl = val.split("maxlength");
            if (spl.length > 1) {
                var sp = parseInt(spl[1]);
                el.attr("maxlength", sp);
                var len = el.val().length;
                var desc = sp - len;
                el.closest("div").find("label").find("span").find("span").text(desc);
            }
        });
    });
    
    $('[class*="maxlength"]').keyup(function() {
        var maxLength = $(this).attr('maxlength');
        var curLength = $(this).val().length;
        var remaning = maxLength - curLength;
        if (remaning < 0) remaning = 0;
        $(this).closest("div").find("label").find("span").find("span").text(remaning);
    });
    
    

});
