<style> #messagess_modal { position: relative; width: 34px; height: 0px; } #ajax_img_load { position: absolute; left: 150px; top: -29px; } </style>

<div class="block">
	<h3 class="block-title">Парсер квартир под определенный комплекс</h3>
	<div class="with-padding">

          <div class="wrapped margin-bottom big-left-icon icon-gear">
              <h4 class="no-margin-bottom blue">Информационное сообщение</h4>
              <p class="red">После нажатия кнопки "<strong class="anthracite">Загрузить</strong>", - <span class="">все квартиры выбранного комплекса будут <u>удалены</u> и <u>загружены новые</u> </span> из файла.</p>
          </div>

          <br>

          <ul class="list spaced">
            <li>
                <h4 class="no-margin-bottom">Ссылка на xml</h4>
                <p class="button-height">
                    <input type="text" class="input six-columns" id="link-xml" value="SiteDataEstate.xml" />
                </p>
            </li>
          	<li>
                 <a href="javascript:void(0)" id="button-load-confirm" class="button margin-right">
                  	<span class="button-icon blue-gradient glossy icon-download"></span>
                  	Загрузить
                  </a>
                  <a href="javascript:void(0)" id="button-load-complex" class="button margin-right" style="display: none;">

                    Обновить комплексы и квартиры
                  </a>
                  <br>
                  <div id="messagess_modal"></div>
            </li>
              <li>
                  <a href="#" class="deselect_all">Снять выделение</a>
              </li>
            <li id="result-parser">

            </li>
          </ul>

    </div>
</div>

<script>
$(document).ready(function(){
    $('#button-load-confirm').on('click', function(){
        //var $complex = '"' + $('#complex option:selected').text() + '"';
        if (confirm("Действительно загрузить xml?")) {
            ajaxComplexLoad();
      	}
    });

    $('.deselect_all').on('click', function(e){
        e.preventDefault();
        $(".checksb").removeAttr('checked');
    });

    $('#button-load-complex').on('click', function(e){
        e.preventDefault();
        var loads = '<img id="ajax_img_load" src="/asset/admin/img/standard/loaders/loading32.gif"/>';
        $('#messagess_modal').html(loads).css({'display':'block'});
        $(".checksb:checked").each(function(i,val) {
            ajaxComplexSave($(this).val());
        });
        $('#ajax_img_load').remove();
    });
});


function ajaxComplexLoad()
{
  var message = '',
      link_xml = '/var/www/estate/data/www/m16-estate.ru/shared/asset/uploads/crm/' + $('#link-xml').val();
      clearResult();
      ajaxFunc(link_xml);
}

function ajaxComplexSave(id_complex)
{
    var message = '',
        link_xml = '/var/www/estate/data/www/m16-estate.ru/shared/asset/uploads/crm/' + $('#link-xml').val();
    ajaxFuncSave(link_xml, id_complex);
}

function messageResult(message) {
  $('#result-parser').append('<p class="big-message"> ' + message + '</p>');
}

function clearResult() {
  $('#result-parser').empty();
}

function ajaxFunc(link_xml, calllbackFunc)
{
    var loads = '<img id="ajax_img_load" src="/asset/admin/img/standard/loaders/loading32.gif"/>';
    $("#button-load-complex").hide();

    $.ajax({
        type: 'POST',
        url: '/admin/parser/ajax_complex',
        dataType: 'json',
        data: { files: link_xml },
        success: function(data) {
            $('#ajax_img_load').remove();
            //setTimeout(mes_hide,100);

            clearResult();
            messageResult(data.ok);
            messageResult(data.complex);
            $("#button-load-complex").show();
            //messageResult(data.list);
        },
        beforeSend: function() {
            $('#messagess_modal').html(loads).css({'display':'block'});;
        },
        error: function() {
            $('#ajax_img_load').remove();
            //setTimeout(mes_hide,100);
        }
    });
}

function ajaxFuncSave(link_xml, id_complex, calllbackFunc)
{

    $("#button-load-complex").hide();

    $.ajax({
        type: 'POST',
        url: '/admin/parser/ajax_complex_save',
        dataType: 'json',
        data: { files: link_xml, complex: id_complex },
        success: function(data) {
            $('#ajax_img_load').remove();
            $('#build-'+id_complex).append(' <span class="rok"> '+data.ok+'</span> - <span class="dok">Удалено квартир: '+data.delrooms+'</span> - <span class="iok">Добавлено квартир: '+data.insrooms+'</span> - <span class="uok">Изменено квартир: '+data.updrooms+'</span>');

            //messageResult(data.list);
        },
        beforeSend: function() {
           // $('#messagess_modal').html(loads).css({'display':'block'});
        },
        error: function() {
           // $('#ajax_img_load').remove();
            //setTimeout(mes_hide,100);
        }
    });
}

</script>