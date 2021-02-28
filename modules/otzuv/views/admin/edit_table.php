<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/asset/assets/taginput/jquery.tagsinput.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="/asset/assets/taginput/jquery.tagsinput.js"></script>

<style>
    .sortimg {
        width: 600px;
        list-style: none;
    }
    .sortvideo {
        width: 600px;
        list-style: none;
    }
    .sortaudio {
        width: 400px;
        list-style: none;
    }
    .sortvideo li {
        width: 600px;
        margin-bottom: 10px;
        height: 320px;
        border: 1px solid #cccccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        padding: 10px;
        position: relative;
    }
    .sortaudio li {
        width: 400px;
        margin-bottom: 10px;
        height: 80px;
        border: 1px solid #cccccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        padding: 10px;
        position: relative;
        padding-left: 100px;
        background: url('/asset/assets/img/audiop.png') no-repeat left 10px top 10px;
    }
    .sortimg li {
        float: left;
        width: 265px;
        margin-right: 10px;
        margin-bottom: 10px;
        height: 80px;
        position: relative;
        border: 1px solid #cccccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        padding: 10px;
    }
    .sortimg li img{
        display: inline-block;
        vertical-align: top;
        max-height: 80px;
    }
    .sortimg li textarea{
        height: 72px;
        width: 120px;
        margin-left: 10px;
        position: absolute;
        top: 10px;
        left: 120px;
    }
    .sortimg li .delfoto{
        position: absolute;
        cursor: pointer;
        right: 7px;
        line-height: 6px;
        top: 7px;
    }

    .sortvideo li .delvideo{
        position: absolute;
        cursor: pointer;
        right: 7px;
        line-height: 6px;
        top: 7px;
    }
    .sortaudio li .delaudio{
        position: absolute;
        cursor: pointer;
        right: 7px;
        line-height: 6px;
        top: 7px;
    }

    #rmfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 24px; z-index: 100;}
    #audiofileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 24px; z-index: 100;}
</style>
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
                        <?php echo $iform->init(array('name','date', 'email','manager_name','text','banned','sort', 'tags'), 'Главный блок'); ?>
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
                                        <input type="file" id="rmfileupload" multiple accept="image/*" />
                                    </div>
                                    <div id="progressb" style="width: 400px; height: 20px; display: inline-block; margin-left: 40px;"></div>
                                    <ul id="sortable" class="sortimg">
                                        <?php $fotos = unserialize($table['foto']['value']);
                                            if (count($fotos['foto']) > 0){
                                                foreach ($fotos['foto'] as $k => $v){
                                        ?>
                                            <li>
                                                <img src="<?=image_resize($v, 'preview', false);?>" />
                                                <input type="hidden" class="sortic" name="foto[]" value="<?=$v?>" />
                                                <input type="hidden" class="sorti" name="fotosort[]" value="<?=$k?>" />
                                                <textarea name="fotodesc[]"><?=$fotos['desc'][$k]?></textarea>
                                                <span class="delfoto">x</span>
                                            </li>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="twelve-columns">
                        <details class="details margin-bottom" open="">
                            <summary class="blue">Видео</summary>
                            <div class="with-padding columns">
                                <div id="templ_foto" class="block-label mid-margin-bottom  button-height twelve-columns">
                                    <label for="foto" class="label mid-margin-top">
                                        <small class="" id="small-foto"></small>
                                    </label>
                                    <div class="" style="position: relative; overflow: hidden; width: 144px; height: 24px; display: inline-block;">
                                        <a href="javascript:void(0);" class="loadfiles green-gradient button icon-save compact loadvideo">Добавить видео</a>
                                    </div>
                                    <ul id="sortablevideo" class="sortvideo">
                                        <?php

                                        $videos = unserialize($table['video']['value']);
                                        if (count($videos['video']) > 0){
                                            foreach ($videos['video'] as $k => $v){
                                                ?>
                                                <li>
                                            <input type="hidden" class="sortiv" name="video[]" value="<?=$v?>" />
                                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?=$v?>" frameborder="0" allowfullscreen></iframe>
                                            <span class="delvideo">x</span>
                                                </li>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="twelve-columns">
                        <details class="details margin-bottom" open="">
                            <summary class="blue">Аудио</summary>
                            <div class="with-padding columns">
                                <div id="templ_foto" class="block-label mid-margin-bottom  button-height twelve-columns">
                                    <label for="foto" class="label mid-margin-top">
                                        <small class="" id="small-foto"></small>
                                    </label>
                                    <div class="" style="position: relative; overflow: hidden; width: 144px; height: 24px; display: inline-block;">
                                        <a href="javascript:void(0);" class="loadfiles green-gradient button icon-save compact loadaudio">Загрузить аудио</a>
                                        <input type="file" id="audiofileupload" multiple accept="audio/*" />
                                    </div>
                                    <div id="progressa" style="width: 400px; height: 20px; display: inline-block; margin-left: 40px;"></div>
                                    <ul id="sortableaudio" class="sortaudio">
                                        <?php $audios = unserialize($table['audio']['params']);

                                        if (count($audios['audio']) > 0){
                                            foreach ($audios['audio'] as $k => $v){
                                                ?>
                                                <li>
                                                    <input type="hidden" class="sortica" name="audio[]" value="<?=$v?>" />
                                                    <p><?=$audios['name'][$k]?></p>
                                                    <span class="delaudio" data-id="<?=$v?>">x</span>
                                                </li>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </details>
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

<script>	    
$(document).ready(function() {             
    // Шаблон инициализации
    $.template.init();                                     
    // Ограничение по символам
    maxLen('link', 60); // ЧПУ
    maxLen('title', 80); // Title
    maxLen('description', 220); // Описание Description
    maxLen('keywords', 160); // Ключевые слова Keywords
    var count_img = $('.sortimg li').length;
    // Обновление высоты вкладок при переключении        
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });
    $( "#sortable" ).sortable({
        cursor: 'move',
        update: function(event, ui){
            $('.sortimg li').each(function(i,val){
                $(this).find('.sorti').val(i);
            });
        }
    });
    $( "#sortable" ).disableSelection();
editorFull('text');
    $( "#sortablevideo" ).sortable({
        cursor: 'move',
        update: function(event, ui){
            $('.sortimg li').each(function(i,val){
                $(this).find('.sorti').val(i);
            });
        }
    });
    $( "#sortablevideo" ).disableSelection();

    $('body').on('click', ".delfoto", function(e){
        e.preventDefault();
        $(this).closest('li').remove();
    });

    $('body').on('click', ".delvideo", function(e){
        e.preventDefault();
        $(this).closest('li').remove();
    });

    $('body').on('click', ".delaudio", function(e){
        e.preventDefault();
        $(this).closest('li').remove();
    });

    $('body').on('change', "#rmfileupload", function(e){
        console.log(1);
        var data = new FormData();
        $.each(jQuery('#rmfileupload')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });
        $("#progressb").show();
        $("#progressb").progressbar();
        $.ajax({
            type: 'POST',
            url: '/ajax/ajax_rmupload',
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
                        count_img++;
                        str = '';
                        str += '<li>';
                        str += '<img src="'+val.preview_name+'"/>';
                        str += '<input type="hidden" class="sortic" name="foto[]" value="/asset/uploads/reviews/temp/'+val.file_name+'" />';
                        str += '<input type="hidden" class="sorti" name="fotosort[]" value="'+count_img+'" />';
                        str += '<textarea name="fotodesc[]"></textarea>';
                        str += '<span class="delfoto">x</span>';
                        str += '</li>';
                        $(".sortimg").append(str);
                    }
                });
                $("#progressb").hide();
            },
            error: function(data) {
                alert('Не удалось загрузить изображение!');
            }
        });
        $("#rmfileupload").replaceWith($("#rmfileupload").clone());

        //
    });

    function showProgress(evt) {
        if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            console.log(percentComplete);
            $('#progressb').progressbar("option", "value", percentComplete );
        }
    }

    function showProgressa(evt) {
        if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            console.log(percentComplete);
            $('#progressa').progressbar("option", "value", percentComplete );
        }
    }

    $("#progressb").hide();
    $("#progressa").hide();

    $('body').on('click', ".loadvideo", function(e){
        e.preventDefault();
        var youtube = prompt('Вставьте ссылку на youtube ролик:');
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        var match = youtube.match(regExp);
        if (match&&match[7].length==11){
            var str = '<li>';
            str += '<input type="hidden" class="sortiv" name="video[]" value="'+match[7]+'" />';
            str += '<iframe width="560" height="315" src="https://www.youtube.com/embed/tQyon2LShag" frameborder="0" allowfullscreen></iframe>';
            str += '<span class="delvideo">x</span>';
            str += '</li>';
            $(".sortvideo").append(str);
        }else{
            alert("Url incorrecta");
        }
    });

    $('body').on('change', "#audiofileupload", function(e){
        console.log(1);
        var data = new FormData();
        $.each(jQuery('#audiofileupload')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });
        $("#progressa").show();
        $("#progressa").progressbar();
        $.ajax({
            type: 'POST',
            url: '/ajax/ajax_audioupload',
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',showProgressa, false);
                } else {
                    console.log("Uploadress is not supported.");
                }
                return myXhr;
            },
            success: function(data) {
                $.each(data, function(i, val){
                    console.log(val);
                    if (val.error == 0){

                        str = '';
                        str += '<li>';
                        str += '<input type="hidden" class="sortica" name="audio[]" value="'+val.id+'" />';
                        str += '<p>'+val.client_name+'</p>';
                        str += '<span class="delaudio" data-id="'+val.id+'">x</span>';
                        str += '</li>';
                        $(".sortaudio").append(str);
                    }
                });
                $("#progressa").hide();
            },
            error: function(data) {
                alert('Не удалось загрузить Аудио!');
            }
        });
        $("#audiofileupload").replaceWith($("#audiofileupload").clone());

        //
    });

    $('.tagsinput').tagsInput({
        'height':'100px',
        'width':'100%',
        'interactive':true,
        'defaultText':'Введите тэг',
        'delimiter': ',',   // Or a string with a single delimiter. Ex: ';'
        'removeWithBackspace' : true,
        'minChars' : 0,
        'maxChars' : 0, // if not provided there is no limit
        'placeholderColor' : '#666666'
    });

});
</script>

<?php echo $valid; // Валидация ?>