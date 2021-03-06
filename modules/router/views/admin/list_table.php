<?php
/**
 * Шаблон вывода списка записей модуля
**/
?>

<div class="panel-control align-right white-gradient">
    
    <div id="load_button_action" class="float-right">                       
       <div  class="button-group align-right">                                         
           <a href="<?php echo BASEURL; ?>/<?php echo $moduleName; ?>/admin/index/add" class="button icon-plus-round white-gradient sets_add glossy"> Добавить перенаправление</a>                                              
           
           <a href="javascript:void(0);" onclick="loadDataContainer('<?php echo BASEURL; ?>/<?php echo $moduleName; ?>/admin/index/set_router/true', '#ajaxNotification'); $('#loadSettings').empty();" class="button gray-gradient glossy with-tooltip icon-save" title="Записать данные в файл router.php"></a>           
           <a title="Страница модуля" href="javascript:loadLanguageTable('<?php echo BASEURL; ?>/module/admin/index/list_language','<?php echo $moduleName; ?>');" class="button anthracite-gradient icon-page-list sets hidden with-tooltip"></a>
           <a title="Параметры модуля" href="javascript:void(0);" onclick="loadSettingDiv('<?php echo BASEURL; ?>/<?php echo $moduleName; ?>');" class="button anthracite-gradient icon-tools sets hidden with-tooltip"></a>           
           
           <a href="javascript:void(0);" class="button icon-gear white-gradient sets_setting glossy with-tooltip" title="Выбор настроки"></a>                           
       </div>
    </div>
    
    <?php if(is_super()): ?>
    <div class="float-left">
        <div  class="button-group">
            <a href="<?php echo BASEURL; ?>/admin/list_fields/<?php echo $moduleName; ?>" class="button gray-gradient glossy">Поля</a>
            <a href="javascript:void(0);" onclick="loadDataContainer('<?php echo BASEURL; ?>/<?php echo $moduleName; ?>/admin/index/updateConfig', '#ajaxNotification'); $('#loadSettings').empty();" class="button gray-gradient glossy">Обновить настройки</a>
        </div>
    </div>
    <?php endif; ?>
                  		
</div>

<div class="with-padding scrollable">

    <div id="loadSettings"></div>    
    <div id="loadLanguages"></div>
    
    <?php if(!empty($information)): ?>
        <p class="big-message margin-bottom"><span class="icon-info-round margin-right icon-size2"></span><?php echo $information; ?><span class="close show-on-parent-hover simpler inset">✕</span><span class="block-arrow"><span></p>
    <?php endif; ?>    
    
    
    <?php echo form_open('','class="serial_form"'); ?>   
    
    <div class="table-header button-height">
		<div class="float-right">
			Поиск&nbsp;<input type="text" name="table_search" id="table_search" value="" class="input mid-margin-left"/>
		</div>
        
        <div class="captions">
            <?php echo $captionTable; ?>
		</div>                
	</div>
    
    <div class="table-footer button-height no-margin-bottom hidden" style="border-radius: 0 !important;">
    	
        <?php if($count_rows > 0): ?>
        <div class="float-right children-tooltip paginationButtons" data-tooltip-options='{"position":"right"}'>    
                <a href="0" title="Предыдущая страница" class="button blue-gradient glossy hidden prev_rows"><span class="icon-backward"></span></a>
    			<a href="2" title="Следующая страница" class="button blue-gradient glossy next_rows"><span class="icon-forward"></span></a>    			                                
    	</div>
        <?php endif; ?>
    
    	<div>&nbsp;</div>
        
    </div>    
    <table class="table responsive-table" id="sorting">

    	<thead>
    		<tr>    			
    			<?php foreach($headingTable as $cl=>$th):?>
                    <th scope="col" width="<?php echo $th['width']; ?>" class="align-center <?php echo $th['class']; ?>"><?php echo $cl; ?></th>
                <?php endforeach; ?>
    			<th scope="col" width="100" class="align-right">Действия</th>
    		</tr>
    	</thead>
    
    	<tfoot>
    		<tr>
    			<td colspan="<?php echo count($headingTable)+1; ?>" class="vertical-center">
    				<div class="float-right">
                        <label for="button-checkbox-all"> Отметить все <input type="checkbox" name="button-checkbox-all" id="button-checkbox-all" style="width:24px;" value=""/></label>
                    </div>
                    
                    <div class="countPaginationRows">
                    <?php if($count_rows > 0): ?>
                        Показано записей: с <span id="first_rows"> 1 </span> по <span id="second_rows"> <?php echo count($table); ?> </span> из <?php echo $count_rows;?>
                    <?php else: ?>
                        Показано записей: 0
                    <?php endif; ?>
                    </div>
                    
    			</td>
    		</tr>
    	</tfoot>
    
    	<tbody id="loadPagination">
    		
            <?php if($table != ''): ?>
                <?php foreach($table as $key=>$tbl):?>
                <tr class="dlt<?php echo $key; ?>">
                    <?php foreach($tbl as $t):?>
                        <td><?php echo $t; ?></td>
                    <?php endforeach; ?>
        						
        			<td class="align-right">
                        <div class="button-group compact children-tooltip" data-tooltip-options='{"position":"right"}'>
                            <a href="<?php echo BASEURL; ?>/<?php echo $moduleName; ?>/admin/index/edit/<?php echo $key; ?>" class="button icon-pencil" title="Редактировать"></a>
                            <a href="javascript:void(0);" data-links="<?php if(isset($identifier[$key])) echo $identifier[$key]; ?>" data-id="<?php echo $key; ?>" class="delRow button icon-trash confirm" title="Удалить"></a>                                              		                                                  
                        </div> 
                        <input type="checkbox" name="checked[]" id="check-<?php echo $key; ?>" value="<?php echo $key; ?>|<?php if(isset($identifier[$key])) echo $identifier[$key]; ?>" class="checks" style="width:24px;"/>                   
                    </td>
                    
        		</tr>
                <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="<?php echo count($headingTable)+1; ?>">
                    <p class="big-message blue-gradient white"><span class="icon-info-round margin-right icon-size2"></span>Данных для отображения нет!</p>
                </td>
            </tr>
            <?php endif; ?>
             		
    	</tbody>

    </table>
    
    <div class="table-footer button-height large-margin-bottom">
    	
        <?php if($count_rows > 0): ?>
        <div class="float-right children-tooltip paginationButtons" data-tooltip-options='{"position":"right"}'>    
                <a href="0" title="Предыдущая страница" class="button blue-gradient glossy hidden prev_rows"><span class="icon-backward"></span></a>
    			<a href="2" title="Следующая страница" class="button blue-gradient glossy next_rows"><span class="icon-forward"></span></a>    			                                
    	</div>
        <?php endif; ?>
    
    	<select name="action" class="select blue-gradient glossy ">
			<option value="0"> -- Общие действия -- </option>
            <option value="1">Удалить</option>
		</select>
		<button type="submit" id="actionSubmit" class="button blue-gradient glossy">Применить</button>
    </div>	            
    
    <?php echo form_close(); ?>

</div>

<div class="hidden">
    <span id="perPaging"><?php echo $per_paging_rows; ?></span>
    <span id="allRows"><?php echo $count_rows; ?></span>
    <span id="countPages"><?php echo $count_pages; ?></span>
    <span id="countHeading"><?php echo count($headingTable)+1; ?></span>
    <span id="urlData"><?php echo BASEURL; ?>/<?php echo $url; ?></span>
    <span id="uriData"><?php echo BASEURL; ?>/<?php echo $uri; ?></span>    
    <span id="moduleName" data-module="<?php echo $moduleName; ?>"  data-table="<?php echo $moduleTable; ?>"></span>
</div>

<script>    	                                                                                                                
    $(document).ready(function(){                
        $.template.init(); // Шаблон инициализации
    });    
</script>