<style>
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
                        <?php echo $iform->init(array('name','category', 'likns','sort','banned'),'Блок данных'); ?>
                    </div>

                    <div class="twelve-columns">

                        <details class="details margin-bottom" open="">
                            <summary class="blue">Фото</summary>
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
                                        <li><img src="<?=image_resize($table['mainfoto']['value'], 'preview', false);?>" class="framed cursor-pointer with-tooltip" id="mainfoto">
                                            <input type="hidden" name="mainfoto" value="<?=$table['mainfoto']['value']?>" class="framed with-tooltip input_mainfoto">
                                        </li>
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
    // Обновление высоты вкладок при переключении        
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });

    $('body').on('change', "#ofileupload", function(e){
        var data = new FormData();
        $.each(jQuery('#ofileupload')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        $.ajax({
            type: 'POST',
            url: '/ajax/ajax_supload',
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
                    $(".input_mainfoto").val('/asset/uploads/images/slider/'+data.data.file_name);
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


});
</script>

<?php echo $valid; // Валидация ?>