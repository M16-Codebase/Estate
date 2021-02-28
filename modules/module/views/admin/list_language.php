<?php
/**
 * Шаблон вывода языков определенного модуля
 * 
 * $mas - массив языков
 * 
**/
?>



<?php  foreach($mas as $it=>$value):?>                
    <a data-eq="<?php echo $value['name']; ?>" href="javascript:void(0)" class="button list_language white compact blue silver-gradient"> <span class="button-icon red-bg"><span class=""></span><?php echo mb_substr($value['name'],0,2); ?></span> <?php echo $value['name']; ?> </a>						
<?php endforeach; ?>
<br /><br />
<script>
	$(document).ready(function() { 
        // Кликаем по опциях выпадающего меню
        $('.list_language').click(function(){
           var eqq = $(this).attr('data-eq'); // вытакскиваем язык          
                timeOutId('/module/admin/index/list_language/'+eqq,'<?php echo $moduleName?>'); // передаем язык + модуль и загружаем языковый файл
        });
    });
</script>