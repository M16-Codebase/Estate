<?php
	/**
     * Шаблон вывода списка модулей
     * 
     * $mas - массив модулей | название, описание, статус
     * 
    **/                           
?>

<div class="new-row twelve-columns ten-columns-mobile with-padding">
    <h2 class="thin underline relative">
    Список модулей
    <span class="button-group absolute-right">
    	<a href="<?php echo BASEURL; ?>/module/admin/index/generate_modules" class="button icon-plus-round"> Добавить модуль</a>    	
    </span>
    </h2>
    
    <table class="new-row twelve-columns simple-table responsive-table twelve-columns-mobile" id="sorting-example2">
    
    	<thead>
    		<tr>
    			<th scope="col">Модуль</th>	
                <th scope="col" width="45%" class="">Название</th>					
    			<th scope="col" width="20%" class="">Статус</th>
    			<th scope="col" width="15%" class="align-right">Действие</th>
    		</tr>
    	</thead>
    
    	<tbody>
    		<?php if($mas != ''): ?>
            <?php foreach($mas as $mod):?>
            <tr class="new-row md_<?php echo $mod['name']; ?>">
    			<th scope="row">
    				<a href="<?php echo BASEURL; ?>/<?php echo $mod['name']; ?>/admin/index" target="_blank"><?php echo $mod['name']; ?></a>				
    			</th>
    			<td><?php echo $mod['title']; ?></td>			
    			<td class="td_<?php echo $mod['name']; ?>"><span class="tag with-small-padding <?php if($mod['status'] == 'Не активен'): ?>blue<?php elseif($mod['status'] == 'Активен'): ?>green<?php else: ?>red<?php endif; ?>-bg"><?php echo $mod['status']; ?></span></td>
    			<td class="align-right vertical-center relative">
    				<span class="button-group compact">					    					
                        <?php if($mod['status'] != 'Ядро'): ?>
                            <a href="javascript:void(0);" data-module="<?php echo $mod['name']; ?>" data-eq='1' class="button actionButtonModule green-active icon-tick with-tooltip green" title="Активировать"></a>
                        	<a href="javascript:void(0);" data-module="<?php echo $mod['name']; ?>" data-eq='2' class="button actionButtonModule red-active icon-cross icon-size2 with-tooltip red" title="Отключить"></a>                            					
                            <a href="javascript:void(0);" data-module="<?php echo $mod['name']; ?>" class="button delete-module icon-trash with-tooltip confirm red-active" title="Полное удаление модуля"></a>                                                                                                                
                        <?php endif; ?>
                        <a href="javascript:void(0);" data-module="<?php echo $mod['name']; ?>" class="button icon-gear with-tooltip menu-tooltip" title="Дополнительные опции"></a>
    				</span>
    			</td>
    		</tr>            	
            <?php endforeach; ?>
            <?php endif; ?>	
    	</tbody>
    
    </table>
    
</div>

<div id="loadSettings"></div>

<div>
    <div id="menu-content-block">
        <div class="compact button-options clearfix">
            <a href="javascript:void(0);" data-index="1" class="button with-tooltip icon-tools" title="Параметры модуля"></a>
        	<a href="javascript:void(0);" data-index="2" class="button with-tooltip icon-page-list" title="Страница модуля"></a>
        	<a href="javascript:void(0);" data-index="3" class="button with-tooltip icon-database" title="Поля модуля"></a>        	        
        </div>
    </div>
</div>

<script>      

	$(document).ready(function() {                
        
        
	    // Удаление
		$('.simple-table .delete-module').data('confirm-options', {			
            onConfirm: function()
			{
				var loads = '<img id="ajax_img_load" src="/assets/admin_img/loading.gif"/>';
                modName = $(this).attr('data-module');
                
                timeOutId('/module/admin/index/drop_module',modName,1);
                
                $(this).closest('tr').fadeAndRemove();                
				return false;
			}
		});
        
        // Кликаем по опциях выпадающего меню
        $('.actionButtonModule').click(function(){
           var eqq = $(this).data('eq');
           var modName = $(this).data('module');
           
           if(eqq == '1') // Активация
           {                
                $.modal.confirm('Активировать модуль?', function()
    			{    				
                    timeOutId('/'+modName+'/admin/index/install',modName,1);
    
    			}, function()
    			{
    				
    			});
           }
           else if(eqq == '2') // Деактивация
           {
                $.modal.confirm('Отключить модуль?', function()
    			{    				
                    timeOutId('/'+modName+'/admin/index/uninstall',modName,1);
    
    			}, function()
    			{
    				
    			});
           }
        });
        
        // Дополнительные опции
        $('.menu-tooltip').menuTooltip($('#menu-content-block').hide());
                        
        $('.menu-tooltip').on('click', function(){
           $('#timeoutId').parent().remove();
           var $module = $(this).data('module');
           $('#menu-content-block').data('moduls', $module);
           $(this).removeClass('tracked');           
        });
        
        $('#menu-content-block').find('a').on('click', function(){
           
           var  $this = $(this),
                $index = $this.data('index'),
                $mdl = $this.closest('#menu-content-block').data('moduls');                                 
           addSettingRow($mdl, $index);        
        });
        
        $('.icon-save, .icon-cross-round').on('click', function(){
            setTimeout(function(){                            
                removesTimeout();
            }, 1500);
        });                                    
     });
     

function timeOutId(url, module, elem)
{
    removesTimeout();
    $('.md_'+module).after('<tr><td colspan="4" id="timeoutId"></td></tr>');

    var dat = {'moduleName':module};    
    var container = '#timeoutId';
    
    $.ajax({        
        url: url,    
        data: {params: dat},
        success: function(data) { // когда AJAX запрос завершился успешно   
            $(container).empty().append(data.ok);   
            if(elem == '1') { removeMessages($('#timeoutId').parent(), true, 3000); } 
            if(data.td)
            {
                if(data.td != ''){
                    $('.md_'+module+' .td_'+module).html(data.td);
                }
            }                                                             
        },        
        beforeSend: function() { // после отправки запроса
            $(container).appendSpinner({spinner:'loader', text:'Загрузка<br /><br /><br />'});
        },
        error: function() { // когда AJAX запрос завершится ошибкой
            $('#timeoutId').parent().remove();
            notifyMes('red', 'Ошибка загрузки');                
        }                
    });    
}    

// Добвление строки таблицы
function addSettingRow($mdl, $index)
{      
   if($index == 1) {
        removesTimeout();
        $('.md_' + $mdl).after('<tr><td colspan="4" id="timeoutId"><div id="loadSettings"></div></td></tr>');
        loadSettingDiv('<?php echo BASEURL; ?>/' + $mdl);
   } else if($index == 2) {
        timeOutId('<?php echo BASEURL; ?>/module/admin/index/list_language', $mdl);    
   } else if($index == 3) {        
        timeOutId('<?php echo BASEURL; ?>/admin/list_fields/' + $mdl, $mdl);
        setTimeout(function(){ 
            $('.absolute-right').find('.icon-redo').remove(); 
            $('.absolute-right').find('.icon-page-list').after('<a href="javascript:void(0);" onclick="removesTimeout();" class="button red-gradient glossy icon-cross-round"> Закрыть</a>');
            $('.absolute-right').find('.icon-page-list').remove(); 
        }, 2000);
        
   }
}

function removesTimeout()
{
    $('#timeoutId').parent().remove();
    $('.message').hide();    
}
     
  
</script>