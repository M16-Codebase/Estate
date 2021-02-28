<?php
/** Шаблон редактирования / добавления */
$iform = $this->elem;
$iform->initial($table);
$tab1 = array(
    array('id' => 'tab-1', 'name' => 'Общее', 'class' => 'active')
);

if($this->dx_auth->is_super()) { $superAdmin = true; } else { $superAdmin = false; } 

function sets($ktb, $perm, $prm)
{
   $return = 0;
   $chek = '';      
   
   if(isset($prm[$ktb][$perm]))
    { $return = $prm[$ktb][$perm]; }         
   if($return == 1)
    { $chek = 'checked="checked"'; }
   
   return array('val' => $return, 'chek' => $chek);
}
?>

<style> .hov:hover, .hovRow:hover, .noneCheck:hover { cursor: pointer; text-decoration: underline; } </style>

<?php echo form_open_multipart('',array('class'=>'serial_form')); ?>    
    <!-- Head panel fixed -->
    <div class="panel-control align-right white-gradient">        
        <div id="load_button_action" class="float-right">                       
           <div  class="button-group align-right">                                
               <?php echo $iform->fm_topMenu($button, 'auth/admin/roles/index', $table['id']['value']); ?>                           
           </div>
        </div>        	               		
    </div>                
    <div class="with-padding scrollable"> <!-- Content block panel -->                                               
      <div class="standard-tabs same-height same-width">        
        <?php echo $iform->fm_createTabLi($tab1); // Создание блока Вкладок (названия вкладок для переключения)  ?>
        <div class="tabs-content"> <!-- ** -------------------------- Tabs content ----------------------------- ** -->                		                        
            <div id="<?php echo $tab1[0]['id']; ?>" class="with-padding"> <!-- ** Tab1 ** -->                                
                
                <div class="columns">                
                    
                    <div class="six-columns"> <?php echo $iform->init('name'); ?> </div>                    
                    <div class="six-columns"> <?php echo $iform->init('title'); ?> </div>                    
                    <div class="twelve-columns">                        
                        <h3 class="thin underline"><?php echo $alang->admin_33; ?></h3>    
                        <table class="twelve-columns simple-table responsive-table twelve-columns-mobile">
                        
                        	<thead>
                        		<tr>
                        			<th scope="col"><span class="text-shadow"><?php echo $alang->admin_34; ?></span></th>	
                                    <th scope="col" data-type="add_" class="hov"><?php echo $alang->admin_35; ?></th>
                                    <th scope="col" data-type="edit_" class="hov"><?php echo $alang->admin_36; ?></th>
                                    <th scope="col" data-type="del_" class="hov"><?php echo $alang->admin_37; ?></th>            
                                    <th scope="col" data-type="set_" class="with-tooltip hov <?php if(!$superAdmin): ?>hidden<?php endif; ?>" title="<?php echo $alang->admin_38; ?>"><span class="icon-info-round"><?php echo $alang->admin_39; ?></span></th>
                                    <th scope="col" data-type="lang_" class="with-tooltip hov <?php if(!$superAdmin): ?>hidden<?php endif; ?>" title="<?php echo $alang->admin_40; ?>"><span class="icon-info-round"><?php echo $alang->admin_41; ?></span></th>					
                        		</tr>
                        	</thead>
                        
                        	<tbody>
                            <?php foreach($table_permisions as $ktb=>$tb):?>
                                <tr class="new-row">
                        			<?php                
                                        $add = sets($ktb,'add',$prm);                   
                                        $edit = sets($ktb,'edit',$prm);
                                        $del = sets($ktb,'del',$prm);
                                        $set = sets($ktb,'set',$prm);
                                        $lang = sets($ktb,'lang',$prm);           
                                    ?>            
                                    
                                    <th scope="row" class="hovRow blue" data-type="<?php echo $ktb; ?>"> <input type="hidden" name="table-<?php echo $ktb; ?>" value="<?php echo $ktb; ?>" /> <?php echo $tb; ?> </th>				
                        			<td><input type="checkbox" class="checks add_" name="add-<?php echo $ktb; ?>"  value="<?php echo $add['val']; ?>" <?php echo $add['chek']; ?> /></td>
                                    <td><input type="checkbox" class="checks edit_" name="edit-<?php echo $ktb; ?>" value="<?php echo $edit['val']; ?>" <?php echo $edit['chek']; ?> /></td>
                                    <td><input type="checkbox" class="checks del_" name="del-<?php echo $ktb; ?>"  value="<?php echo $del['val']; ?>" <?php echo $del['chek']; ?> /></td>
                                    <td><input type="checkbox" class="checks <?php if(!$superAdmin): ?>hidden<?php endif; ?> set_" name="set-<?php echo $ktb; ?>"  value="<?php echo $set['val']; ?>" <?php echo $set['chek']; ?> /></td>
                                    <td><input type="checkbox" class="checks <?php if(!$superAdmin): ?>hidden<?php endif; ?> lang_" name="lang-<?php echo $ktb; ?>" value="<?php echo $lang['val']; ?>" <?php echo $lang['chek']; ?> /></td>
                        		</tr>            	
                            <?php endforeach; ?>
                        	</tbody>
                        
                        </table>                                                                       
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
        
        // Ограничение по символам
        maxLen('name', 30);
        maxLen('title', 50);        
        
        $('.tabs-content > div').on('showtab', function()
        {
        	$(this).refreshTabs();
        });   
        
        $('.noneCheck').click(function(){
            $('.checks').removeAttr('checked');
            $('.checks').val('0');
        });
                    
        $('.hov').click(function() {
            var cls = $(this).data('type');                                
            
            $('.'+cls).attr('checked','checked');
            $('.'+cls).val('1');
        });
        
        $('.hovRow').click(function() {
            var cls = $(this).data('type');
            
            $('input:checkbox[name=add-'+cls+']').attr('checked','checked');
            $('input:checkbox[name=add-'+cls+']').val('1');  
            
            $('input:checkbox[name=edit-'+cls+']').attr('checked','checked');
            $('input:checkbox[name=edit-'+cls+']').val('1');
            
            $('input:checkbox[name=del-'+cls+']').attr('checked','checked');
            $('input:checkbox[name=del-'+cls+']').val('1');
            
            $('input:checkbox[name=set-'+cls+']').attr('checked','checked');
            $('input:checkbox[name=set-'+cls+']').val('1');
            
            $('input:checkbox[name=lang-'+cls+']').attr('checked','checked');
            $('input:checkbox[name=lang-'+cls+']').val('1');                              
        });
        
        $('.checks').click(function(){
           if($(this).is(':checked'))
           {
                $(this).val('1');
                $(this).attr('checked','checked');
           } 
           else
           {
                $(this).val('0');
                $(this).removeAttr('checked');
           }
        });
                             
    });
</script>

<script>
    $(document).ready(function() { 
        $('.serial_form').validationEngine('attach', {
          onValidationComplete: function(form, status){
            if(status)
            {
                <?php if(uri(5) != ''): ?>
                send_onValidComplete('.serial_form','/auth/admin/roles/<?php echo uri(4); ?>/<?php echo uri(5); ?>','#timeoutId');
                <?php else: ?>
                send_onValidComplete('.serial_form','/auth/admin/roles/<?php echo uri(4); ?>','#timeoutId');
                <?php endif; ?>
            }
          }  
        });       
    });
</script>