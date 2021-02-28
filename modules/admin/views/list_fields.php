<?php /** Шаблон полей */
    
    // TODO Создание полей опций
    
    $types = array(
        'INT' => 'INT (числовое поле)',
        'TINYINT' => 'TINYINT (числовое поле)',
        'VARCHAR' => 'VARCHAR (символьное поле)',
        'TEXT' => 'TEXT (текстовое поле)',
        'TIMESTAMP' => 'TIMESTAMP (дата или время в секундах)',
        'DATETIME' => 'DATETIME (дата и время 0000-00-00 00:00:00)',
        'DATE' => 'DATE (дата 0000-00-00)'
    );
      
    $arrayBlock_1 = array(           
        'input' => 'Поле', // +
        'input_pseudo' => 'Поле + Кнопка в поле справа', // +       
        'input_info' => 'Поле + Блок информации справа', // +            
        'input_icon' => 'Поле + Иконка слева', // +
        'input_switch' => 'Поле переключатель', // +          
        'input_hidden' => 'Скрытое поле', // +
        'input_enum' => 'Выпадающий список' // +
    );
    
    $arrayBlock_2 = array(           
        'input' => 'Поле', // +
        'input_pseudo' => 'Поле + Кнопка в поле справа', // +       
        'input_info' => 'Поле + Блок информации справа', // +         
        'input_icon' => 'Поле + Иконка слева', // +
        'input_image' => 'Выбор изображения', // +
        'input_enum' => 'Выпадающий список', // +                
        'input_files' => 'Выбор файлов', // +
        'input_date' => 'Поле даты', // +
        'input_hidden' => 'Скрытое поле', // +
        'input_multi_enum' => 'Множественный выпадающий список' // +              
    );
    
    $arrayBlock_3 = array(                   
        'textarea_wysyvig' => 'Визуальный редактор', // +
        'textarea' => 'Большое поле (textarea)', // +
        'input_multi_image' => 'Множественный выбор изображений', // +
        'input_files' => 'Выбор файлов', // +
        'input_hidden' => 'Скрытое поле' // +
    );
    
    $arrayBlock_4 = array(           
        'input' => 'Поле', // +
        'input_pseudo' => 'Поле + Кнопка в поле справа', // +           
        'input_info' => 'Поле + Блок информации справа', // +       
        'input_icon' => 'Поле + Иконка слева', // +
        'input_hidden' => 'Скрытое поле', // + 
        'input_date' => 'Поле даты' // +
    );
    
    $optionsType = array(
        'INT'       => $arrayBlock_1,
        'TINYINT'   => $arrayBlock_1,
        'VARCHAR'   => $arrayBlock_2,
        'TEXT'      => $arrayBlock_3,
        'DATE'      => $arrayBlock_4,
        'DATETIME'  => $arrayBlock_4,
        'TIMESTAMP'  => $arrayBlock_4
    );
    
    $Tinfo = "
            <span class=\"info-spot\">
			    <span class=\"icon-info-round\"></span>
				<span class=\"info-bubble\">
					%s
				</span>
			</span>
            ";
?>

<style>
.block-title, details.details > summary {padding: 7px 10px;}
.field-block .label, .field-drop .label {text-align: left;}
</style> 

<?php if(isset($dynamicSend)): ?>

<div class="twelve-columns ten-columns-mobile with-padding">
    
    <div id="topMenu">
    <h3 class="thin underline relative white_bg">
        Поля таблицы  "<?php echo $header ?>"
        <span class="button-group absolute-right">
        	<button type="submit" class="button green-active icon-tick glossy with-tooltip" title="Сохранить">Сохранить</button>            
            <a id="adds" href="javascript:void(0);" title="Добавить поле" class="button icon-plus-round with-tooltip hidden"></a>        	            
            <a href="<?php echo BASEURL; ?>/admin/list_fields/<?php echo $uri_list; ?>" title="Обновить" class="button icon-redo with-tooltip"></a>
            <a href="<?php echo BASEURL; ?>/<?php $ul = explode('_',$uri_list); echo $ul[0]; ?>/admin/index" title="Список" class="button icon-page-list with-tooltip"></a>
        </span>
    </h3>
    </div>
    
    <br />
    
    <fieldset class="fieldset fields-list">    
        
        <?php foreach($dynamicSend as $key=>$c): ?>                                                          
            <div class="field-block button-height">						
                <label class="label">                    
                    <input type="checkbox" class="check-del" value="<?php echo $key; ?>"> удалить <strong><?php echo $key; ?></strong>
                    <br />                                                
                    <?php echo sprintf($Tinfo, 'Тип поля в базе данных: <br />'. $types[mb_strtoupper($c['type'])]); ?>                                                                                                                              
                </label>					                                                              			            

            <?php
                $in_lbl = array(                    
                    'value' => $c['label'],                        
                    'class' => 'input full-width action-form',
                    'data-key' => $key,
                    'data-form' => 'label'                   
                );
                
                $in_lbl2 = array(                    
                    'value' => $c['label_en'],                        
                    'class' => 'input full-width',
                    'class' => 'input full-width action-form',
                    'data-key' => $key,
                    'data-form' => 'label_en'  
                );
                
                $in_lbl3 = array(                    
                    'value' => $c['placeholder'],                                            
                    'class' => 'input full-width action-form',
                    'data-key' => $key,
                    'data-form' => 'placeholder'  
                );
                
                $in_lbl4 = array(                    
                    'value' => $c['info'],                        
                    'class' => 'input full-width action-form',
                    'data-key' => $key,
                    'data-form' => 'info'          
                );
                
                $in_type = array(                    
                    'value' => $c['type'],
                    'class' => 'input full-width disabled action-form',
                    'data-key' => $key,
                    'data-form' => 'type'           
                );                                                                                                                                                               
            ?> 
            
            <div class="columns">
                <div class="six-columns">
                    <?php
                        echo '<small class="input-info blue">Название</small>';
                        echo form_input($in_lbl);
                        
                        //echo '<small class="input-info">Название поля (EN)</small>';
                        echo form_hidden($in_lbl2);                        
                        
                        echo '<small class="input-info blue">Выбрать тип поля отображения в форме</small>';
                        echo form_dropdown('new_type['.$key.']', $optionsType[mb_strtoupper($c['type'])], $c['new_type'], "data-key='$key' class='select check-list expandable-list typeSelect twelve-columns'");
                    ?>
                </div>
                <div class="six-columns">
                    <?php
                        echo '<small class="input-info blue">Info (подсказка)</small>';
                        echo form_input($in_lbl4);
        
                        
                        echo '<small class="input-info blue">Placeholder</small>';
                        echo form_input($in_lbl3);
                    ?>
                </div>
                <div class="twelve-columns">
                    <details class="details margin-bottom">
                    	<summary>Дополнительные настройки поля</summary>
                    	<div class="with-padding">                    
                    		<div data-keys="<?php echo $key; ?>" class="columns with-padding options-<?php echo $key?>"></div>                    
                    	</div>
                    </details>                    
                </div>
            </div>
            
            
            </div>                
        <?php endforeach; ?>                           

    </fieldset>
</div>

<?php endif; ?>


<div class="hidden">
    <!-- -->
    <div id="input">            
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-class hidden">       
            <small class="input-info blue">Ввести дополнительный класс</small>
            <input type="text" data-option="class" class="input full-width fi-input" />
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-label_class hidden">       
            <small class="input-info blue">Ввести дополнительный класс для Label</small>
            <input type="text" data-option="label_class" class="input full-width fi-input" />
        </div>  
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-text_button hidden">       
            <small class="input-info blue">Ввести название кнопки</small>
            <input type="text" data-option="text_button" class="input full-width fi-input" />
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-text_icon hidden">
            <small class="input-info blue">Ввести название иконки</small>
            <input type="text" data-option="text_icon" class="input full-width fi-input" />       
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-select_chosen hidden">
            <small class="input-info blue">Использовать ли choosen вместо select</small>
            <input type="text" data-option="select_chosen" class="input full-width fi-input" />
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-switch_text hidden">
            <small class="input-info blue">Текст дя переключателя ВКЛ|ОТКЛ</small>
            <input type="text" data-option="switch_text" class="input full-width fi-input" />
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-editor_function hidden">
            <small class="input-info blue">Название функции настройки редактора</small>
            <input type="text" data-option="editor_function" class="input full-width fi-input" />
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-img_path hidden">
            <small class="input-info blue">Название папки (путь к папке)</small>
            <input type="text" data-option="img_path" class="input full-width fi-input" />
        </div>
        <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet form-option-width hidden">
            <small class="input-info blue">Ширина блока поля</small>
            <input type="text" data-option="width" class="input full-width fi-input" />
        </div>
    </div>
    <!-- -->
</div>

<script>
$(document).ready(function(){
    // заносим данные в объект        
    dynamicSend = <?php echo json_encode($dynamicSend); ?>;    
    
    <?php foreach($dynamicSend as $key=>$item): ?>
        viewOptionField('<?php echo $key; ?>', '<?php echo $item['new_type']; ?>');
    <?php endforeach; ?>
    
    // Выбор типа поля
    $('.typeSelect').on('change', function(){
       var  $this = $(this),
            $key = $this.data('key'),
            $val = $this.val();   
          
       dynamicSend[$key]['new_type'] = $val;       
       viewOptionField($key, $val); 
       updateInputAction();             
    });
    
    // Вытаскивание данных из форм
    $('.action-form').on('blur', function(){
       var  $this = $(this),
            $key = $this.data('key'),
            $form = $this.data('form'),
            $val = $this.val();
            
       dynamicSend[$key][$form] = $val;
    });
    
    // Отлов чекбокса - нужно удалить поле или нет
    $('.check-del').on('click', function(){
        var $this = $(this),            
            $key = $this.val();
            
        if($this.is(':checked')) 
            { dynamicSend[$key]['del'] = true; } 
        else 
            { dynamicSend[$key]['del'] = false; }
    });
            
    // отправка данных в БД    
    $('button[type=submit]').on('click', function() {
                
        $.ajax({
            url: '/<?php echo $this->uri->uri_string(); ?>',
            data: {
                dani: dynamicSend
            },
            success: function(data) {                                                                   
                $('#ajaxNotification').empty().append(data.ok);
                removeMessages('#ajaxNotification',false, 3000);                                           
            },
            beforeSend: function() {                
                $('#ajaxNotification').appendSpinner();
                $('.message').hide();
            },
            error: function() {
                $('#ajaxNotification').empty().append('<p class="red big-text">Ошибка отправки данных</p>');
                removeMessages('#ajaxNotification',false, 3000);
            }
        }); 
        
        return false;       
    });                
    
    updateInputAction();
            
});

// Вывод поле опций
function viewOptionField($key, $type)
{    
    // берем тип поля и вытаскиваем вставляя в блок
    var $form = $('#input').html(),        
        $class = [],
        $showClass = '',
        $optKey = $('.options-' + $key);
    
    $optKey.empty().append($form);
        
    if($type == 'input') { $class = ['class','width','label_class']; }            
    else if($type == 'input_icon') { $class = ['class','width','text_icon','label_class']; }    
    else if($type == 'input_date') { $class = ['class','width','label_class']; }
    else if($type == 'input_hidden') { $class = ['']; }
    else if($type == 'input_pseudo') { $class = ['class','width','text_button','label_class']; }
    else if($type == 'input_info') { $class = ['class','width','label_class']; }
    else if($type == 'input_enum') { $class = ['class','width','select_chosen','label_class']; }
    else if($type == 'input_multi_enum') { $class = ['class','width','label_class']; }
    else if($type == 'input_switch') { $class = ['class','width','switch_text','label_class']; }
    else if($type == 'textarea') { $class = ['class','width','label_class']; }
    else if($type == 'textarea_wysyvig') { $class = ['class','width','editor_function','label_class']; }
    else if($type == 'input_image') { $class = ['class','width','img_path','label_class']; }
    else if($type == 'input_multi_image') { $class = ['class','width','img_path','label_class']; }
    else if($type == 'input_files') { $class = ['class','width','img_path','label_class']; }    
    
    var $ln = $class.length,
        $ii = 0,
        $comma = '',
        $finds = '';        
    
    for($ii; $ii < $ln; $ii++)
    {   
        $comma = ''; if($ii != 0) { $comma = ', ' }
        $finds = '.form-option-' + $class[$ii];
        $showClass += $comma + $finds;        
        $optKey.find($finds).find('input').val(dynamicSend[$key]['options'][$class[$ii]]);
    }
                
    $optKey.find($showClass).removeClass('hidden');        
}

// обновление действия для инпутов
function updateInputAction()
{
    $('.fi-input').on('blur', function() {
        var $this = $(this),
            $val = $this.val(),
            $key = $this.closest('.columns').data('keys'),
            $option = $this.data('option');
            
        dynamicSend[$key]['options'][$option] = $val;                 
    });
}

</script>