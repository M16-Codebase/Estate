<style>#card_map { height: 600px !important; width: 90%; text-align: center; margin: 0 auto; }
    #mfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 24px; z-index: 100;}
    #ofileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
    #presfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<?php
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
                        <?php echo $iform->init(array('name','link','address','price','square','city_id','rayon_id','tokad','banned','type_id','forwhat'), 'Главный блок'); ?>
                    </div>
                                                                                                    
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('title','keywords','description'), 'SEO блок'); ?>
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
                                            <img src="<?=image_resize($table['main_foto']['value'], 'preview', false);?>" class="framed cursor-pointer with-tooltip" id="mainfoto">
                                            <input type="hidden" name="main_foto" value="<?=$table['main_foto']['value']?>" class="framed with-tooltip input_mainfoto">
                                            <div class="controls">
                                                 <span class="button-group compact children-tooltip">
                                                      <a href='<?=$table['main_foto']['value']?>' target="_blank" class='button icon-pages blue-gradient' title='Открыть файл в новом окне'>ССЫЛКА</a>
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
                    
                    <div class="twelve-columns"> 
                        <?php echo $iform->init(array('text','xml_text'), 'Текстовый блок'); ?>
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
                        <?php echo $iform->init(array('likeas'), 'Похожие объекты'); ?>
                    </div>

                    <div class="twelve-columns">
                        <?php echo $iform->init(array('map'), 'Координаты'); ?>
                    </div>

                    <div class="twelve-columns">
                        <div id="card_map"></div>
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
    // Ограничение по символам
    maxLen('link', 60); // ЧПУ
    maxLen('title', 80); // Title
    maxLen('description', 220); // Описание Description
    maxLen('keywords', 160); // Ключевые слова Keywords                            
    // Обновление высоты вкладок при переключении        
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });                       
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

        $( "#fotocont" ).sortable({
            cursor: 'move',
            update: function(event, ui){
                $('.sortimg li').each(function(i,val){
                    $(this).find('.sorti').val(i);
                });
            }
        });
        $( "#fotocont" ).disableSelection();

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
        
    </script>

<?php echo $valid; // Валидация ?>