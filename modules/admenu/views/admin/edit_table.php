<?php
/** Шаблон редактирования / добавления */
$iform = $this->elem;
$iform->initial($table);
$tab1 = array(
    array('id' => 'tab-1', 'name' => 'Общее', 'class' => 'active')
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
                    
                    <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('parent_id','name','link'), 'Главный блок'); ?>                        
                    </div>
                                        
                    <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('name_en','sort'), 'Второстепенный блок'); ?>                        
                    </div>  
                                      
                    <div class="three-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('icon','is_super','banned'), 'Дополнительный блок', true); ?>                        
                    </div>                    
                                        
                </div>                
                <div class="clear-both"></div> 
    		</div><!-- ** #Tab1 ** -->                                    
                                    
            <div class="clear-both"></div>            
   	    </div> <!-- ** -------------------------- #Tabs content --------------------------- ** -->        
      </div>            
    </div>     
    <?php echo form_hidden('id',$table['id']['value']);
echo form_close(); ?>
<script>	    
$(document).ready(function() {             
    $.template.init(); // Шаблон инициализации                        
});
</script>
<?php echo $valid; // Валидация ?>