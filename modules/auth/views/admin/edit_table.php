<?php
/** Шаблон редактирования / добавления */
$iform = $this->elem;
$iform->initial($table);
$tab1 = array(
    array('id' => 'tab-1', 'name' => 'Общее', 'class' => 'active'),
    array('id' => 'tab-2', 'name' => 'Профиль', 'class' => '')
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
                        <?php echo $iform->init(array('username','email')); ?>  
                        <?php if(uri(4) == 'add'): ?>                            
                        <?php else: ?>
                            <label><input name="change_password" class="checkbox" type="checkbox" value="1" id="change_password" /> Изменить пароль</label>
                        <?php endif; ?> 
                        <?php echo $iform->init('password'); ?>                      
                    </div>
                                                                                                    
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('role_id','banned','ban_reason')); ?> 
                    </div>                                        
                </div>                
                <div class="clear-both"></div> 
    		</div><!-- ** #Tab1 ** -->                                                                          
            <div id="<?php echo $tab1[1]['id']; ?>" class="with-padding"> <!-- ** Tab2 ** -->
                
                <div class="columns">
                    
                    <div class="six-columns">
                        <?php echo $iform->init(array('name','last_name','city')); ?>                        
                    </div>
                    
                    <div class="six-columns">
                        <?php echo $iform->init('avatar'); ?>                        
                    </div>
                    
                </div>
                
                
                <div class="clear-both"></div>          
    		</div> <!-- ** #Tab2 ** -->
            <div class="clear-both"></div>
   	    </div> <!-- ** -------------------------- #Tabs content --------------------------- ** -->        
      </div> 
           
    </div>     
    <?php echo form_hidden('id', $table['id']['value']);      
echo form_close(); ?>


<script>	    
	$(document).ready(function() {             
        $.template.init(); // Шаблон инициализации  
        
        // Ограничение по символам
        maxLen('username', 25);
        maxLen('password', 34);
        maxLen('email', 100);
        
        maxLen('name', 20);
        maxLen('last_name', 50);
        maxLen('city', 50);
        
        // функция конвертирования имени в ссылку #name => #link
       /* $('.serial_form').liTranslit({
            elName: '#name',        //Класс елемента с именем
            elAlias: '#link'        //Класс елемента с алиасом
        }); */
        
        $('.tabs-content > div').on('showtab', function()
        {
        	$(this).refreshTabs();
        });   
        
         if($('#password').val() != '')
         {
            $('#templ_password').addClass('hidden');   
         }
         
         $('#change_password').click(function(){
            $('#password').val('');
            $('#templ_password').toggleClass('hidden');
         });
                             
    });
</script>

<?php echo $valid; // Валидация ?>