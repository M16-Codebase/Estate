<?php
/**
 * Шаблон редактирования / добавления
**/

$table['name']['value'] = explode('|' ,$table['name']['value']);
$table['link']['value'] = explode('|' ,$table['link']['value']);
/*
echo "<div class='with-padding'><pre class='prettyprint'>";
print_r($table);
echo "</pre></div>";
*/
$iform = $this->elem;

$tab1 = array(
    array(
            'id' => 'tab-1',
            'name' => 'Общее',
            'class' => 'active'
        ),
    array(
            'id' => 'tab-2',
            'name' => 'Второстепенное',
            'class' => ''
        )
);  

?>

<?php echo form_open_multipart('',array('class'=>'serial_form')); ?>    
    <!-- Head panel fixed -->
    <div class="panel-control align-right white-gradient">
        <!-- -------------------------------------------------------------------- -->
        <div id="load_button_action" class="float-right">                       
           <div  class="button-group align-right">         
               <?php if(!isset($table['id']['value'])) $tableId = ''; ?>                        
               <?php echo $iform->fm_topMenu($button, $uri_list, $tableId); ?>                           
           </div>
        </div>
        <!-- -------------------------------------------------------------------- -->	               		
    </div>        
        
    <div class="with-padding scrollable"> <!-- Content block panel -->                                         
      
      <div class="standard-tabs same-height same-width">        
        <!-- Tabs -->
    	<ul class="tabs"> <!-- * -------------------------------------------------------------------- * -->     		           
           
            <?php foreach($tab1 as $value): ?>
            <li class="<?php echo $value['class']; ?>"><a href="#<?php echo $value['id']; ?>"><?php echo $value['name']; ?></a></li>
            <?php endforeach; ?>
    		    
                                
	    </ul> <!-- * -------------------------------------------------------------------- * -->            
        <!-- Content -->
        <div class="tabs-content"> <!-- ** -------------------------- Tabs content ----------------------------- ** -->                		            
            
            <div id="<?php echo $tab1[0]['id']; ?>" class="with-padding"> <!-- ** Tab1 ** -->                                
                
                <style>
                .name-link {
                    height: 30px;
                    line-height: 30px;
                }
                </style>
                
                <div class="columns">                
                    
                    <div class="twelve-columns"> 
                        <div class="columns">
                            <div class="five-columns">
                                <p><strong>Название</strong></p>
                                <div id="nl-name">
                                    <?php if(!empty($table['name']['value'])): ?>                                         
                                        <?php foreach($table['name']['value'] as $key=>$item): ?>
                                            <?php $tbls = $table['name']; $tbls['value'] = $table['name']['value'][$key]; ?>
                                            <p class="name-link nl-action-<?php echo $key; ?>"><?php echo $iform->fm_input($tbls); ?></p>
                                        <?php endforeach; ?>                                    
                                    <?else:?>
                                    <p class="name-link nl-action-1"><?php echo $iform->fm_input($table['name']); ?></p>
                                    <?php endif; ?>
                                </div>                                                                 
                            </div>
                            <div class="five-columns">
                                <p><strong>Ссылка</strong></p>                                 
                                <div id="nl-link">                                    
                                    <?php if(!empty($table['name']['value'])): ?>                                         
                                        <?php foreach($table['link']['value'] as $key=>$item): ?>
                                            <?php $tbls = $table['link']; $tbls['value'] = $table['link']['value'][$key]; ?>
                                            <p class="name-link nl-action-<?php echo $key; ?>"><?php echo $iform->fm_input($tbls); ?></p>
                                        <?php endforeach; ?>                                    
                                    <?else:?>
                                    <p class="name-link nl-action-<?php echo $key; ?>"><?php echo $iform->fm_input($table['link']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="two-columns">
                                <p><strong>Действие</strong></p>                                
                                <div id="nl-action">
                                    <?php if(!empty($table['name']['value'])): ?>                                         
                                        <?php foreach($table['link']['value'] as $key=>$item): ?>
                                            <?php $tbls = $table['link']; $tbls['value'] = $table['link']['value'][$key]; ?>
                                            <p class="name-link nl-action-<?php echo $key; ?>">
                                                <a href="javascript:void(0);" class="button blue-gradient icon-plus-round with-tooltip" onclick="adds()" title="Добавить строку"></a>
                                                <?php if($key !== 0): ?> <a href="javascript:void(0);" class="button red-gradient icon-cross-round with-tooltip" onclick="removes(<?php echo $key; ?>)" title="Удалить строку"></a> <?php endif; ?>
                                            </p>    
                                        <?php endforeach; ?>                                    
                                    <?else:?>
                                    <p class="name-link nl-action-1"><a href="javascript:void(0);" class="button blue-gradient icon-plus-round with-tooltip" onclick="adds()" title="Добавить строку"></a></p>
                                    <?php endif; ?>                                    
                                </div>
                            </div>
                        </div>                                                                                                                         
                    </div>
                                                             
                    <div class="twelve-columns">                        
                        <?php 
                        $this->load->library('admin/Selectlink', $table['link_id']['value'], 'slink');                       
                        $this->slink->addParams('pages', 'Страницы');                        
                        $this->slink->initialize(); 
                        ?>                   
                    </div>                       
                                                        
                </div>
                
                <div class="clear-both"></div> 
    		</div><!-- ** #Tab1 ** -->    
            
            <div id="<?php echo $tab1[1]['id']; ?>" class="with-padding"> <!-- ** Tab1 ** -->
                <div class="columns">                            
                    <div class="six-columns"><?php echo $iform->fm_select($table['banned'], $iform->fm_label($table['banned'])); ?></div>
                </div>
            </div>                                    	
                                    
            <div class="clear-both"></div>            
   	    </div> <!-- ** -------------------------- #Tabs content --------------------------- ** -->        
      </div> 
           
    </div>     
    <?php echo form_hidden('id', $tableId); ?>
      
<?php echo form_close(); ?>

<div class="hidden">
    <div class="nl-name"><p class="name-link "><?php $table['name']['value'] = $table['link']['value'] = ''; echo $iform->fm_input($table['name']); ?></p></div>
    <div class="nl-link"><p class="name-link "><?php echo $iform->fm_input($table['link']); ?></p></div>
    <div class="nl-action">
        <p class="name-link "><a href="javascript:void(0);" class="button blue-gradient icon-plus-round with-tooltip" onclick="adds()" title="Добавить строку"></a>
        <a href="javascript:void(0);" class="button red-gradient icon-cross-round with-tooltip" onclick="removes()" title="Удалить строку"></a></p>    
    </div>
</div>

<script>	 

function adds()
{
    var nlName      = $('.nl-name').html(),
        nlLink      = $('.nl-link').html(),
        nlAction    = $('.nl-action').html(),
        $numb = parseInt((Math.random())*123);                
    
    $('#nl-name').append(nlName).find('p:last').addClass('nl-action-'+$numb);
    $('#nl-link').append(nlLink).find('p:last').addClass('nl-action-'+$numb);
    $('#nl-action').append(nlAction).find('p:last').addClass('nl-action-'+$numb).find('.red-gradient').attr('onclick','removes('+$numb+')');
    
    $('#tooltips').empty();
}
function removes(id)
{
    $('.nl-action-' + id).remove();
    $('#tooltips').empty();
}
   
$(document).ready(function() {             
    $.template.init(); // Шаблон инициализации                   
                
});
</script>

<?php echo $valid; // Валидация ?>