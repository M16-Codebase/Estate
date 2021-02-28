<?php
	/**
     * Шаблон добавления настройки в конфигурационный файл определенного модуля
     * 
     * $valid - переменная в которую подгружается шаблон валидации а также функция для отправки данных через json
     * 
    **/
?>

<div id="messagess_modal" class="with-small-padding one-columns one-columns-mobile"></div>
<form  class="add_form" method="post">            
    <h2 class="thin underline relative">
    Добавление настройки
        <span class="button-group absolute-right">
        	<button type="submit" class="button green-active icon-tick with-tooltip" title="Добавить"></button>                                	
        </span>
    </h2>
    <dl class="definition">            
        
        <?php if(!isset($option)): ?>
            <input type="hidden" name="moduleName" id="moduleName" value="<?php echo $moduleName; ?>" />
        <?php else: ?>
            <dt>Выбрать класс модуля</dt>
                <dd>                
                    <select  name="moduleName" id="moduleName" class="input select full-width">
        				<?php echo $option; ?>
        			</select>
                </dd>
        <?php endif; ?>
        <dt>Название настройки</dt>
            <dd>
                <input type="text" name="conf_title" id="conf_title" class="input full-width validate[required]" value="" />
            </dd>
        <dt>Название переменной</dt>
            <dd>
                <input type="text" name="conf_name" id="conf_name" class="input full-width validate[required]" value="" />
            </dd>
        <dt>Значение настройки</dt>
            <dd>
                <input type="text" name="conf_value" id="conf_value" class="input full-width validate[required]" value="" />
            </dd>
    </dl>
</form>

<?php echo $valid; ?>