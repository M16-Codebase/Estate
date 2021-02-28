<style>#card_map { height: 600px !important; width: 90%; text-align: center; margin: 0 auto; }
#mfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 24px; z-index: 100;}
#ofileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
#gfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
#presfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<?php

$rzd = array(
    '0' => 'buildings',
    '1' => 'resale',
    '2' => 'residential',
    '3' => 'elite',
    '4' => 'commercial',
    '5' => 'world',
    '6' => 'land',
    '7' => 'residential',
    '9' => 'exclusive'
);
$razd = (int)$table['razdelu']['value'][0];
/** Шаблон редактирования / добавления */
$iform = $this->elem;
$iform->initial($table);
$tab1 = array(
    array('id' => 'tab-1', 'name' => 'Русский', 'class' => 'active')
);
echo form_open_multipart('',array('class'=>'serial_form')); ?>        
    <!-- Head panel fixed -->
    <div class="panel-control align-right white-gradient">        
        <div id="load_button_action" class="float-right">
           <?php if(!empty($table['id']['value'])): ?>
           <a class="button anthracite-gradient glossy compact" target="_blank" href="/<?php echo $rzd[$razd]; ?>/<?php echo $table['link']['value']; ?>">Перейти на сайт к записи</a>
           <?php endif; ?>
           <div  class="button-group align-right">
               <?php echo $iform->fm_topMenu($button, $uri_list, $table['id']['value']); ?>
           </div>
        </div>
    </div>            
    <div class="with-padding scrollable"> <!-- Content block panel -->                                         
      
      <div class="standard-tabs same-height same-width">                
    	<?php echo $iform->fm_createTabLi($tab1); // Создание блока Вкладок (названия вкладок для переключения)  ?>
        <div class="tabs-content"> <!-- ** -------------------------- Tabs content ----------------------------- ** -->                		                        
            
            <div id="<?php echo $tab1[0]['id']; ?>" class="with-padding"> <!-- ** Tab1 ** -->                                                
                <div class="columns">

                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('razdelu','area','locality_name','metro_id','dop_metro', 'rayon_id','dop_rayon','builder_id', 'type_id','room_id','ipoteka', 'rassrochka', 'price','price_arenda','name','link','alias_name', 'adress','interest','special', 'military', 'commerce', 'banks', 'floor','floors','square_all','square_life','square_cook','typeprodazha_id','otdelka_id','planirovka_id','vhod_id','okna_id'), 'Главный блок'); ?>
                    </div>


                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('title','keywords','description','banned','sort', 'consultant_id'), 'SEO блок'); ?>
                    </div>

                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                      <div class="block-label mid-margin-bottom  button-height twelve-columns">
          				<p class="button-height inline-label">
  							<span class="label">Срок сдачи</span>

  							<span class="button-group">
  								<label for="button-radio-1" class="button green-active active">
  									<input type="radio" name="button-radio" id="button-radio-1" value="0" checked="">
  									Сдан
  								</label>
  								<label for="button-radio-2" class="button green-active">
  									<input type="radio" name="button-radio" id="button-radio-2" value="2">
  									Собственность
  								</label>
  								<label for="button-radio-3" class="button green-active">
  									<input type="radio" name="button-radio" id="button-radio-3" value="3">
  									Сдача
  								</label>
  							</span>
  						</p>
                          <?php echo $iform->init(array('class', 'fz214', 'parking'), 'Новостройки'); ?>
                      </div>

                      <div class="columns">
                            <?php echo $iform->init(array('korpus','korpus_value','map')); ?>
                      </div>
                       <div class="columns">
                            <?php echo $iform->init(array('otd_metro','otd_metro_value')); ?>
                      </div>
                       <br>
                      <div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                          <?php echo $iform->init(array('vid','club', 'elite_type','user_price_for_meter'), 'Элитная недвижимость'); ?>
                      </div>

                    </div>

                    <div class="twelve-columns">

                        <details class="details margin-bottom" open="">
                            <summary class="blue">Главное фото</summary>
                            <div class="with-padding columns">

                                <div id="templ_foto" class="block-label mid-margin-bottom button-height twelve-columns">
                                    <label for="foto" class="label mid-margin-top">
                                        <small class="" id="small-foto"></small>
                                    </label>
                                    <div class="" style="position: relative; overflow: hidden; width: 144px; height: 34px; display: inline-block;">
                                        <a href="javascript:void(0);" class="loadfiles green-gradient button icon-save compact">Загрузить файл</a>
                                        <input type="file" id="ofileupload" />
                                    </div>
                                    <ul class="gallery">
                                        <li>
                                            <img src="<?=image_resize($table['mainfoto']['value'], 'preview', false);?>" class="framed cursor-pointer with-tooltip" id="mainfoto">
                                            <input type="hidden" name="mainfoto" value="<?=$table['mainfoto']['value']?>" class="framed with-tooltip input_mainfoto">
                                            <div class="controls">
                                                 <span class="button-group compact children-tooltip">
                                                      <a href='<?=$table['mainfoto']['value']?>' target="_blank" class='button icon-pages blue-gradient' title='Открыть файл в новом окне'>ССЫЛКА</a>
                                                 </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                </div>
                            </details>
                        </div>

                    <div class="twelve-columns">

                        <details class="details margin-bottom" open="">
                            <summary class="blue">Дополнительные фото</summary>
                            <div class="with-padding columns">

                                <div id="templ_foto" class="block-label mid-margin-bottom  button-height twelve-columns">
                                    <label for="foto" class="label mid-margin-top">
                                        <small class="" id="small-foto"></small>
                                    </label>
                                    <div class="" style="position: relative; overflow: hidden; width: 144px; height: 24px; display: inline-block;">
                                        <a href="javascript:void(0);" class="loadfiles green-gradient button icon-save compact">Загрузить файлы</a>
                                        <input type="file" id="mfileupload" multiple />
                                    </div>
                                    <div id="progressb" style="width: 400px; height: 20px; display: inline-block; margin-left: 40px;"></div>
                                    <div id="fotocont" class="columns" style="padding: 40px 0;">
                                        <?php $fotos = unserialize($table['foto']['value']);
                                        if (count($fotos['foto']) > 0){
                                            foreach ($fotos['foto'] as $k => $v){
                                                ?>
                                                <div id="fotomt<?=$k?>_li" class="block-label fixed-size-columns left-border boxed wrapped align-center no-padding">
                                                    <ul class="gallery">
                                                        <li>
                                                            <img src="<?=image_resize($v, 'preview', false);?>" class="framed large-margin-left" id="fotomt<?=$k?>">
                                                            <input type="hidden" name="foto[]" value="<?=$v?>" id="input_fotomt<?=$k?>">
                                                            <div class="controls margin-left">
                                                                <span class="button-group compact children-tooltip">
                                                                    <a href='<?=$v?>' target="_blank" class='button icon-pages blue-gradient' title='Открыть файл в новом окне'>ССЫЛКА</a>
                                                                    <a href="javascript:void(0);" data-id="fotomt<?=$k?>" class="button icon-trash" onclick="deletersLi(&quot;fotomt<?=$k?>_li&quot;);" title="Удалить"></a>
                                                                </span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <input type="text" name="foto_alt" value="<?=$fotos['alt'][$k]?>" placeholder="Описание изображения" class="full-width input">
                                                </div>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="clear-both"></div>
                            </div>
                        </details>
                    </div>

                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('typesdelka_id', 'comm_forwhat', 'comm_type', 'comm_vid', 'comm_bussines', 'comm_pay', 'comm_square'), 'Коммерческая недвижимость'); ?>
                    </div>

                    <!--<div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?//php echo $iform->init(array('square_zemlya','ot_kad','uc_electrika','vodoprovod_id','uc_gas','razdel_uchastok_id'), 'Земельные участки'); ?>
                    </div>-->



                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('cottage', 'is_cottage', 'square_dom','square_uchastok','otdalennost','matherial_id','res_type', 'vodoem_id','infrostructura_id','z_gas','z_water','z_electric','z_desc'), 'Загородная недвижимость'); ?>
                    </div>

                    <div class="twelve-columns">

                        <details class="details margin-bottom" open="">
                            <summary class="blue">Фото генплана (коттеджные посёлки)</summary>
                            <div class="with-padding columns">

                                <div id="templ_foto" class="block-label mid-margin-bottom button-height twelve-columns">
                                    <label for="foto" class="label mid-margin-top">
                                        <small class="" id="small-foto"></small>
                                    </label>
                                    <div class="" style="position: relative; overflow: hidden; width: 144px; height: 34px; display: inline-block;">
                                        <a href="javascript:void(0);" class="loadfiles green-gradient button icon-save compact">Загрузить файл</a>
                                        <input type="file" id="gfileupload" />
                                    </div>
                                    <ul class="gallery">
                                        <li>
                                            <img src="<?=image_resize($table['plan_photo']['value'], 'preview', false);?>" class="framed cursor-pointer with-tooltip" id="plan_photo">
                                            <input type="hidden" name="plan_photo" value="<?=$table['plan_photo']['value']?>" class="framed with-tooltip input_plan_photo">
                                            <div class="controls">
                                                 <span class="button-group compact children-tooltip">
                                                      <a href='<?=$table['plan_photo']['value']?>' target="_blank" class='button icon-pages blue-gradient' title='Открыть файл в новом окне'>ССЫЛКА</a>
                                                 </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="twelve-columns">
                        <?php echo $iform->init(array('cottages_square', 'cottages_count', 'cottages_square_land', 'cottages_vodoem', 'plan', 'location'), 'Блок для коттеджных поселков'); ?>
                    </div>

                    <div class="twelve-columns"> 
                        <?php echo $iform->init(array('text','xml_text','video_code', 'video_desc', 'otdelka', 'foto_otdelka', 'transport', 'infrastruct', 'ipoteka_text'), 'Блок галереи и текста'); ?>
                    </div>

                    <div class="twelve-columns">
                        <?php echo $iform->init(array('tour'), '3D Тур'); ?>
                    </div>

                    <div class="twelve-columns">

                        <details class="details margin-bottom" open="">
                            <summary class="blue">Презентация</summary>
                            <div class="with-padding columns">

                                <div id="templ_presentation" class="block-label mid-margin-bottom button-height twelve-columns">
                                    <label for="presentation" class="label mid-margin-top">
                                        <small class="" id="small-presentation"></small>
                                    </label>
                                    <div class="" style="position: relative; overflow: hidden; width: 144px; height: 34px; display: inline-block;">
                                        <a href="javascript:void(0);" class="loadpresentation green-gradient button icon-save compact">Загрузить файл</a>
                                        <input type="file" id="presfileupload" />
                                    </div>
                                    <div class="linkpres">
                                        <?php if(!empty($table['presentation']['value'])):?>
                                            <a href="<?=$table['presentation']['value']?>" target="_blank">http://m16-estate.ru<?=$table['presentation']['value']?></a>
                                            <a href="#" class="delpres" style="display: block;">Удалить презентацию</a>
                                        <?php endif;?>
                                    </div>
                                    <input type="hidden" name="presentation" class="presval" value="<?=$table['presentation']['value']?>" />
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="twelve-columns">
                        <div id="card_map"></div>
                    </div>

                    <div class="twelve-columns">
                        <?php echo $iform->init(array('likeas'), 'Похожие объекты'); ?>
                    </div>

                </div>                
                <div class="clear-both"></div> 
    		</div><!-- ** #Tab1 ** -->                   
                               
            <div class="clear-both"></div>            
   	    </div> <!-- ** -------------------------- #Tabs content --------------------------- ** -->        
      </div> 
           
    </div>     
    <?php echo form_hidden('id', $table['id']['value']);      
echo form_close(); ?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="/asset/js/gmap3.js"></script>

<script>
$(document).ready(function() {
    // Шаблон инициализации
    $.template.init();
    // Обновление высоты вкладок при переключении
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });
    $('input[name=button-radio]').on('click', function(){
        if($(this).val() == 3) { $('#templ_korpus_value').removeClass('hidden'); }
        else { $('#templ_korpus_value').addClass('hidden'); }
    });
    <?php if($table['korpus']['value'] == 3): ?>
        $('#templ_korpus_value').removeClass('hidden');
        $('#button-radio-3').click();
    <?php endif; ?>
    <?php if($table['korpus']['value'] == 2): ?>
        $('#button-radio-2').click();
    <?php endif; ?>
});
</script>

<script>
	$(document).ready(function() {

        var imid = 111;

        $('body').on('change', "#mfileupload", function(e){
            var data = new FormData();
            $.each(jQuery('#mfileupload')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });
            $("#progressb").show();
            $("#progressb").progressbar();
            $.ajax({
                type: 'POST',
                url: '/ajax/ajax_mupload',
                data: data,
                processData: false,
                contentType: false,
                dataType: "json",
                xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress',showProgress, false);
                    } else {
                        console.log("Uploadress is not supported.");
                    }
                    return myXhr;
                },
                success: function(data) {
                    $.each(data, function(i, val){
                        console.log(val);
                        if (val.error == 0){
                            imid++;
                            str = '';
                            str += '<div id="fotomt'+imid+'_li" class="block-label fixed-size-columns left-border boxed wrapped align-center no-padding">';
                            str += '<ul class="gallery">';
                            str += '<li>';
                            str += '<img src="'+val.preview_name+'" class="framed large-margin-left" id="fotomt'+imid+'">';
                            str += '<input type="hidden" name="foto[]" value="/asset/uploads/images/buildings/'+val.file_name+'" id="input_fotomt'+imid+'">';
                            str += '<div class="controls margin-left"><span class="button-group compact children-tooltip">';
                            str += '<a href="javascript:void(0);" data-id="fotomt'+imid+'" class="button icon-trash" onclick="deletersLi(&quot;fotomt'+imid+'_li&quot;);" title="Удалить блок с изображением"></a></span>';
                            str += '</div>';
                            str += '</li>';
                            str += '</ul>';
                            str += '<input type="text" name="foto_alt" placeholder="Описание изображения" class="full-width input">';
                            str += '</div>';
                            $("#fotocont").append(str);
                        }
                    });
                    $("#progressb").hide();
                },
                error: function(data) {
                    alert('Не удалось загрузить изображение!');
                }
            });
            $("#mfileupload").replaceWith($("#mfileupload").clone());

            //
        });

        $('body').on('change', "#ofileupload", function(e){
            var data = new FormData();
            $.each(jQuery('#ofileupload')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });

            $.ajax({
                type: 'POST',
                url: '/ajax/ajax_oupload',
                data: data,
                processData: false,
                contentType: false,
                dataType: "json",
                xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
//myXhr.upload.addEventListener('progress',showProgress, false);
                    } else {
                        console.log("Uploadress is not supported.");
                    }
                    return myXhr;
                },
                success: function(data) {
                    if (data.error == 0){
                        $("#mainfoto").attr('src', data.data.preview_name);
                        $(".input_mainfoto").val('/asset/uploads/images/buildings/'+data.data.file_name);
                    }
                },
                error: function(data) {
                    alert('Не удалось загрузить изображение!');
                }
            });
            $("#ofileupload").replaceWith($("#ofileupload").clone());

//
        });

        $('body').on('change', "#gfileupload", function(e){
            var data = new FormData();
            $.each(jQuery('#gfileupload')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });

            $.ajax({
                type: 'POST',
                url: '/ajax/ajax_oupload',
                data: data,
                processData: false,
                contentType: false,
                dataType: "json",
                xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
//myXhr.upload.addEventListener('progress',showProgress, false);
                    } else {
                        console.log("Uploadress is not supported.");
                    }
                    return myXhr;
                },
                success: function(data) {
                    if (data.error == 0){
                        $("#plan_photo").attr('src', data.data.preview_name);
                        $(".input_plan_photo").val('/asset/uploads/images/buildings/'+data.data.file_name);
                    }
                },
                error: function(data) {
                    alert('Не удалось загрузить изображение!');
                }
            });
            $("#gfileupload").replaceWith($("#gfileupload").clone());

//
        });

        function showProgress(evt) {
            if (evt.lengthComputable) {
                var percentComplete = (evt.loaded / evt.total) * 100;
                console.log(percentComplete);
                $('#progressb').progressbar("option", "value", percentComplete );
            }
        }

        $("#progressb").hide();


         $("#card_map").gmap3({
          marker:{

            <?php if(!empty($table['map']['value'])): ?>
            <?php $lat = explode('|', $table['map']['value']); ?>
            latLng: [<?php echo $lat[0]; ?>,<?php echo $lat[1]; ?>],
            <?php else: ?>
            address: "Санкт-Петербург",
            <?php endif; ?>
            options:{
              draggable:true
            },
            events:{
              dragend: function(marker){
                $(this).gmap3({
                  getaddress:{
                    latLng:marker.getPosition(),
                    callback:function(results){

                      var value = marker.getPosition().lat() + '|' + marker.getPosition().lng();
                      $('#map').val(value);

                      var map = $(this).gmap3("get"),
                        infowindow = $(this).gmap3({get:"infowindow"}),
                        content = results && results[1] ? results && results[1].formatted_address : "no address";
                      if (infowindow){
                        infowindow.open(map, marker);
                        infowindow.setContent(content);
                      } else {
                        $(this).gmap3({
                          infowindow:{
                            anchor:marker,
                            options:{content: content}
                          }
                        });
                      }
                    }
                  }
                });
              }
            }
          },
          map:{
            options:{
              zoom: 13,
              mapTypeControl: false,
              navigationControl: false,
              streetViewControl: false
            }
          }
        });



    });


    $('body').on('change', "#presfileupload", function(e){
        var data = new FormData();
        $.each(jQuery('#presfileupload')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        $.ajax({
            type: 'POST',
            url: '/ajax/ajax_presupload',
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                } else {
                    console.log("Uploadress is not supported.");
                }
                return myXhr;
            },
            success: function(data) {
                if (data.error == 0){
                    $('.linkpres').empty();
                    str = '<a href="/asset/uploads/presentations/'+data.data.file_name+'" target="_blank">http://m16-estate.ru/asset/uploads/presentations/'+data.data.file_name+'</a>';
                    str += '<a href="#" class="delpres" style="display: block;">Удалить презентацию</a>';
                    $(".linkpres").append(str);
                    $('.presval').val('/asset/uploads/presentations/'+data.data.file_name);
                }
            },
            error: function(data) {
                alert('Не удалось загрузить файл!');
            }
        });
        $("#presfileupload").replaceWith($("#presfileupload").clone());

//
    });

    $('body').on('click', '.delpres', function(e){
        e.preventDefault();
        $('.presval').val('');
        $('.linkpres').empty();

    });

    $( "#fotocont" ).sortable({
        cursor: 'move',
        update: function(event, ui){
            $('.sortimg li').each(function(i,val){
                $(this).find('.sorti').val(i);
            });
        }
    });
    $( "#fotocont" ).disableSelection();
</script>

<?php echo $valid; // Валидация ?>