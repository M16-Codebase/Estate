<?php
	/**
     * Шаблон загрузки конфигурационных данных с файла конфигурации
     * 
     * $conf - массив файла конфигурции     
     * $valid - переменная в которую подгружается шаблон валидации а также функция для отправки данных через json
     * 
    **/
    if($this->dx_auth->is_super()) { $superAdmin = true; } else { $superAdmin = false; } 
    
    $kilk = 0;
    foreach($conf as $item=>$value)
    {
        if(!empty($item))
        {
            $kilk = 1;
        }
    }
    
?>
<form  class="setting_form" method="post">    
    <span class="float-left blue"><h4>Настройки</h4></span>
    <span class="button-group compact float-right">	
        
        <?php if($kilk == 1): ?><button type="submit" class="button">Сохранить</button><?php endif; ?>
        <?php if($superAdmin): ?>
            <a id="adds" href="javascript:void(0);" title="Добавить настройку" class="button icon-plus-round with-tooltip"></a>
        <?php endif; ?>
        <button type="button" onclick="$('#timeoutId').parent().remove()" class="button with-tooltip" title="Скрыть">X</button>        
    </span>    
    
    <br /><br /><br />
    
    <?php if($kilk == 1): ?>
    <dl class="definition">
    <?php  foreach($conf as $item=>$value):?>        
               
        <dt class="conf_del_<?php echo $item; ?> md_<?php echo $item; ?>"><?php if($superAdmin): ?><a data-conf="<?php echo $item; ?>" data-module="<?php echo $conf['module_name']['name']; ?>" href="" title="Удалить настройку" class="button icon-trash with-tooltip red del_conf compact confirm small"></a>&nbsp;<?php endif; ?><?php echo $value['title']; ?></dt>
            <dd class="conf_del_<?php echo $item; ?>">
                <?php
                    
                    if($value['name'] == true and $value['name'] == 1) 
                    {                         
                        $value['name'] = 'true';                        
                    }
                    if($value['name'] == false) { $value['name'] = 'false'; }
                    
                ?>                
                <input type="text" name="<?php echo $item; ?>" class="input full-width" value='<?php echo $value['name']; ?>' />                
            </dd>               				
    <?php endforeach; ?>
    <input type="hidden" name="module_name" value='<?php echo $module_name; ?>' />
    </dl>
    <?php else: ?>
     <p>Для этого модуля настроек нет</p>
     <?php endif; ?> 
</form>

<?php echo $valid; ?>

<script>
	$(document).ready(function() { 
        // Demo modal
		$('#adds').click(function(){
			$.modal({		                							
                position: 'top',
                url: '/module/admin/index/add_config/<?php echo $conf['module_name']['name']; ?>',                
				width: 400,
                scroll: true,              
                onClose: function() { $('.formError').remove(); }
			});
		});
        
        // Удаление
		$('.del_conf').data('confirm-options', {			
            onConfirm: function()
			{
				var loads = '<img id="ajax_img_load" src="/assets/admin_img/loading.gif"/>';
                params = $(this).attr('data-conf');
                modules = $(this).attr('data-module');
    
                timeOutId_del('/module/admin/index/del_config/'+modules,params,1);
                
                $('.conf_del_'+params).foldAndRemove();                
				return false;
			},
            onCancel: function()
			{			
                params = $(this).attr('data-conf');

                $('.conf_del_'+params).shake();                
				return false;
			}
		});
    });
</script>