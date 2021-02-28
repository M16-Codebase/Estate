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
                        <?php echo $iform->init(array('name','link'), 'Главный блок'); ?>
                    </div>
                                                                                                    
                    <div class="six-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet"> 
                        <?php echo $iform->init(array('banned','sort'), 'Дополнительный блок'); ?>
                    </div>

                    <div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('buildings_htag','buildings_title','buildings_keywords','buildings_description', 'buildings_seotext'), 'SEO для Новостроек'); ?>
                    </div>

                    <div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('resale_htag','resale_title','resale_keywords','resale_description', 'resale_seotext'), 'SEO для Городской'); ?>
                    </div>

                    <div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('residential_htag','residential_title','residential_keywords','residential_description', 'residential_seotext'), 'SEO для Загородной'); ?>
                    </div>

                    <div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('elite_htag','elite_title','elite_keywords','elite_description', 'elite_seotext'), 'SEO для Элитной'); ?>
                    </div>

					<div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('commercial_htag','commercial_title','commercial_keywords','commercial_description', 'commercial_seotext'), 'SEO для Коммерческой'); ?>
                    </div>
					
					<div class="twelve-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                        <?php echo $iform->init(array('assignment_htag','assignment_title','assignment_keywords','assignment_description', 'assignment_seotext'), 'SEO для Переуступок'); ?>
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
});
</script>

<?php echo $valid; // Валидация ?>