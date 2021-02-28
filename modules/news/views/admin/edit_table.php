<?php
function sqlGetFull($table,$subject,$where){
	$mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
	if ($mysqli->connect_error) {
		die('Ошибка подключения (' . $mysqli->connect_errno . ') '
				. $mysqli->connect_error);
	}

	$mysqli->query("SET NAMES 'utf8mb4'");
	$result=array();
	//echo("SELECT ".$subject." FROM ".$table." WHERE ".$where);
	//echo'<br>';
	if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table." WHERE ".$where)) {
		if (($data->num_rows)==1){
			$row = $data->fetch_array(MYSQLI_ASSOC);
			$result[]=$row;
			return $result;
		}elseif(($data->num_rows)==0){
			return null;
		}else{
			while ($row = $data->fetch_assoc()){
				array_push($result,$row);
			}
			return $result;
		}
	}else{
		$result[]=$mysqli->error;
		return $result;
	}
	$data->close();
	$mysqli->close();
}
$idd=(int)$table['id']['value'];
$authorS=sqlGetFull('ci_news','`author`',"`id`='$idd'");
$authr=$authorS[0]['author'];
?>
<style>#card_map { height: 600px !important; width: 90%; text-align: center; margin: 0 auto; }
    #mfileupload{opacity:0;position: absolute;left:0;top:0; width: 144px; height: 24px; z-index: 100;}
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
                        <?php echo $iform->init(array('name','link','banned','date'), 'Главный блок'); ?>
                    </div>
					<div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('tag', 'ncategory'), ''); ?>
                        <div id="author" style="clear:both;">
                            <label for="tag" class="label mid-margin-top">
                                <span>
                                    <span class="info-spot">
                                        <span class="icon-info-round"></span>
                                            <span class="info-bubble">
                                                Выбрать автора из списка
                                            </span>
                                    </span>
                                <strong>Автор</strong>
                                </span>
                                <small class="" id="small-tag"></small>
                            </label>
                            <p style="padding-top: 10px;">
                                <select id="authh" onchange="updAuth()">
                                    <option id="0">Александр Марков</option>
                                    <option id="1">Дарья Гурьева</option>
                                    <option id="2">Никита Евтушенко</option>
                                </select>
                            </p>
                            <script>
                                var a = document.getElementById('authh');
                                var au='<?php echo $authr; ?>';
                                if(au=='1'){
                                    a.options[0].selected=true;
                                }if(au=='2'){
                                    a.options[1].selected=true;
                                }if(au=='3'){
                                    a.options[2].selected=true;
                                }
                                function updAuth() {
                                    var sel=a.options.selectedIndex;

                                    snd(<?php echo $idd; ?>,sel);
                                }
                                function snd(id,sel) {
                                    $.ajax({
                                        url: '/uath.php',
                                        type: "POST",
                                        data: {'id':id,'aid':sel},
                                        error: function(error){
                                            console.log(error);
                                        },
                                        success: function(data) {
                                            console.log(data);
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                    <div class="twelve-columns">

                        <details class="details margin-bottom" open="">
                            <summary class="blue">Миниатюра</summary>
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
                                                                                                    
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('title','keywords','description'), 'SEO блок'); ?>
                    </div>
                    
                    <div class="twelve-columns"> 
                        <?php echo $iform->init(array('shorting','text'), 'Текстовый блок'); ?>
                    </div>
                    
                    <div class="twelve-columns"> 
                        <?php echo $iform->init(array('overrride'), 'Переопределение'); ?>
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
    maxLen('title', 255); // Title
    maxLen('description', 220); // Описание Description
    maxLen('keywords', 160); // Ключевые слова Keywords                            
    // Обновление высоты вкладок при переключении        
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });                       
});
</script>

<script>
    $(document).ready(function() {

        var imid = 111;

        $('body').on('change', "#ofileupload", function(e){
            var data = new FormData();
            $.each(jQuery('#ofileupload')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });

            $.ajax({
                type: 'POST',
                url: '/ajax/ajax_noupload',
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
                        $(".input_mainfoto").val('/asset/uploads/images/news/'+data.data.file_name);
                    }
                },
                error: function(data) {
                    console.log(data);
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