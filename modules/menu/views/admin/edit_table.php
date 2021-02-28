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
                    
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('parent_id','type_link','link_page','link_http','link_site','name','region'), 'Главный блок'); ?>                        
                    </div>                    
                    
                     <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('banned','sort','attach','class'), 'Второстепенный блок', true); ?>                        
                    </div>
          
                </div>
                
                <div class="clear-both"></div> 
    		</div><!-- ** #Tab1 ** -->                                                                        
            <div class="clear-both"></div>            
   	    </div> <!-- ** -------------------------- #Tabs content --------------------------- ** -->        
      </div>            
    </div>     
    <?php echo form_hidden('id',$table['id']['value']); ?>      
<?php echo form_close(); ?>
<script>	    
$(document).ready(function() {             
    $.template.init(); // Шаблон инициализации                         
    $('.tabs-content > div').on('showtab', function() { $(this).refreshTabs(); });
    
    var tm = $('select[name=type_link] option:selected').val();
    $('#templ_'+tm).removeClass('hidden');
            
    $('select[name=type_link]').change(function(){           
       var tm = $(this).val();           
       if(tm == '0') 
         { $('#templ_link_http, #templ_link_site, #templ_link_page, #templ_link_module').addClass('hidden'); } 
       else 
         { $('#templ_link_http, #templ_link_site, #templ_link_page, #templ_link_module').addClass('hidden'); $('#templ_'+tm).removeClass('hidden'); }            
    });
    
    $('select[name=link_page]').change(function(){
      var $text = $('select[name=link_page] option:selected').text();
      if($(this).val() != 0)
        { if($('#name').val() == '') { $('#name').val($text); } }     
      else
        { $('#name').val(''); }                
    }); 
    
    $('select[name=link_module]').change(function(){
      var $text = $('select[name=link_module] option:selected').text();
      if($(this).val() != 0)
        { if($('#name').val() == '') { $('#name').val($text); } }     
      else
        { $('#name').val(''); }                   
    });
                        
});
</script>
<?php echo $valid; // Валидация ?>