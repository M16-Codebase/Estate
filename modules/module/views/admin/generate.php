<?php
	/**
     * Шаблон создания модуля
     * 
     * $valid - переменная в которую подгружается шаблон валидации а также функция для отправки данных через json
     * 
    **/
?>

<style>
.field-block { padding-left: 250px; }
.field-block .label { width: 220px; margin: 0 0 0 -240px; }
.fieldset.fields-list { background: none; }
</style>
<form  class="serial_form with-padding" method="post">
    <div class="twelve-columns ten-columns-mobile">
     
    <h2 class="thin underline relative">
        Создания модуля
        <span class="button-group absolute-right">
        	<button type="submit" class="button green-active icon-tick with-tooltip"> Создать модуль</button>         
            <a href="<?php echo BASEURL; ?>/module/admin/index/index" title="Список модулей" class="button icon-numbered-list with-tooltip"></a>        	
        </span>
    </h2>  
         
    		<fieldset class="fieldset fields-list">            
				
				<div class="field-block button-height">						
					<label for="moduleName" class="label">
                        <span><strong>Название</strong>
                            <span class="info-spot">
        					    <span class="icon-info-round"></span>
            					<span class="info-bubble">
            						Название также будет служить ссылкой на модуль.
            					</span>
            				</span>
                        </span>                        
                    </label>					                      
                    <input type="text" name="moduleName" id="moduleName" value="" class="input validate[required]"/>                        
				</div>
                
                <div class="field-block button-height">													                    
                    <label for="moduleTables" class="label"><strong>Имя таблицы</strong></label>                    
                    <input type="text" name="moduleTables" id="moduleTables" value="" style="width: 35%;" class="input validate[required]"/>                                                                           
				</div>
                                
                <div class="field-block button-height">													                    
                    <label for="moduleTitles" class="label"><strong>Описание модуля</strong></label>                    
                    <input type="text" name="moduleTitles" id="moduleTitles" value="" style="width: 35%;" class="input validate[required]"/>                                                                           
				</div>                                 
                
                <div class="field-block button-height">													                    
                    <label for="moduleRouter" class="label"><strong>Перелинковка</strong></label>                    
                    <input type="text" name="moduleRouter" id="moduleRouter" value="" class="input"/>                                                                           
				</div>
                
                <div class="field-block button-height">													                    
                    <label for="moduleMenu" class="label"><strong>Захватывать ли в меню</strong></label>                    
                    <select class="select" name="moduleMenu" id="moduleMenu">
                        <option value="1">Только ссылка на модуль</option>
                        <option value="3">Ссылка на модуль + записи модуля</option>
                        <option value="2">Только записи модуля</option>
                        <option value="0">Не заносить модуль в меню</option>
                    </select>                                                                                            
				</div>
                                               
                
                <!-- Кнопки -->
				<div class="field-block button-height hidden">										
                                        
				</div>

			</fieldset>

    </div>
</form>
	
<?php echo $valid; ?>
