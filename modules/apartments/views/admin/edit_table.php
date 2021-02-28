<style>
    #presfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
    #ofileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 34px; z-index: 100;}
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
                        <?php echo $iform->init(array('novostroy_id','room_id','otdelka_id','sort','banned'), 'Главный блок'); ?>
                    </div>
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('title','keywords','description', 'link'), 'SEO блок'); ?>
                    </div>
                                                                                                    
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('price','floor','square_all','square_life','square_cook'), 'Дополнительный блок'); ?>
                        <details class="details margin-bottom" open="">
                            <summary class="blue">Планировка</summary>
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

                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('text','xml_text'), 'Текстовый блок'); ?>
                    </div>
                    
                    <div class="twelve-columns hidden">
                        <?php //echo $iform->init(array('short_text','text'), 'Текстовый блок', true); ?>
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
    maxLen('title', 80); // Title
    // Обновление высоты вкладок при переключении        
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });

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

    $('body').on('click', '.delpres', function(e){
        e.preventDefault();
        $('.presval').val('');
        $('.linkpres').empty();

    });

});
</script>

<?php echo $valid; // Валидация ?>