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
                    <div class="six-columns">
                        <?php echo $iform->init('tema'); ?>
                    </div>
                    <div class="six-columns"> 
                        <?php echo $iform->init('send'); ?>
                    </div>                    
                    <div class="twelve-columns">
                        <div class="wrapped left-icon icon-info-round red white-bg">
                            Текстовые переменные которые возможно использовать в шаблоне.<br /><br />                            
                            <div class="columns">                                                                
                                <div class="four-columns">
                                    <dl class="definition blue">                                    	                                        
                                        <dt>{header}</dt>
                                    	<dd>Заголовок страницы с которой отправлено письмо</dd>
                                    </dl>
                                </div>                                
                                <div class="four-columns">
                                    <dl class="definition blue">                                    	                                                                                
                                        <dt>{url}</dt>
                                    	<dd>Ссылка страницы с которой отправлено письмо</dd>
                                    </dl>
                                </div>
                                <div class="four-columns">
                                    <dl class="definition blue">                                    	                                        
                                        <dt>{site}</dt>
                                    	<dd>Полный адресс сайта</dd>
                                    </dl>
                                </div>                                
                            </div>                                                        
                        </div>
                    </div>                                                                                                  
                    <?php echo $iform->init('text'); ?><br /><br />
                    <?php echo $iform->init('textadmin'); ?>                                                                                  
                </div>                                        
            </div>                
            <div class="clear-both"></div> 
		</div><!-- ** #Tab1 ** -->                                                         
        <div class="clear-both"></div>            
    </div> <!-- ** -------------------------- #Tabs content --------------------------- ** -->        
  </div>        
</div>     
<?php echo form_hidden('id', $tableId); ?>      
<?php echo form_close(); ?>

<script>	    
function editorMessage(id)
{         
    var editor = CKEDITOR.replace(id,
    {
        language: 'ru',
        toolbar: 
        [
  		    { name: 'document', items: [ 'Source','-','RemoveFormat'] },	
    		['Undo','Redo'],			
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Image','Table','Bold','Italic','Underline','Strike','Subscript','Superscript','TextColor','BGColor'],
            ['SpecialChar'],			
    		['Link','Unlink','CreateDiv'],            		
            ['Styles', 'Format', 'FontSize', 'Font','youtube']           
	    ],
        
        height: 500,
        width: 765,
        bodyClass: 'mainMessage'
    });                        
	CKFinder.setupCKEditor(editor, '/asset/ckfinder/');    
}
$(document).ready(function() {             
    $.template.init(); // Шаблон инициализации      
    $('#templ_text').find('a').click();
    $('#templ_textadmin').find('a').click();
    setTimeout(function(){            
        $('.tabs-content div').refreshTabs();
    }, 1000);    
    $('.tabs-content > div').on('showtab', function()
    {
    	$(this).refreshTabs();
    });                       
});
</script>
<?php echo $valid; // Валидация ?>