<?php /** Настройки модуля **/
$table = '';
$iform = $this->elem;
$iform->initial($table);
echo form_open_multipart('',array('class'=>'setting_form')); ?> 
<div class="with-padding box-shadow wrapped white-gradient margin-bottom">
<h3 class="thin underline blue four-columns new-row float-left lite-text-shadow"><strong>Настройки модуля</strong></h3>

    <div class="twelve-columns">        
        <div class="button-group compact float-right">
           <button type="submit" class="button green-gradient glossy icon-save"> сохранить</button>
           <a href="javascript:void(0);" onclick="$('#loadSettings').empty(); $('#timeoutId').remove();" class="button red-gradient glossy icon-cross-round"> закрыть</a>
       </div>                        
    </div>        
    <div class="clear-both"></div>    
    <div class="columns">
        
        <?php if(empty($table)): ?>
        <div class="big-message margin-bottom eleven-columns"><span class="icon-info-round margin-right icon-size2"></span>Настроек для данного модуля нет<span class="block-arrow"></span></div>    
        <?php else: ?>
        
        <div class="four-columns left-border">
            <?php $tableField = 'Paging'; if($table[$tableField]) { echo $iform->init($tableField); } ?>
            <?php $tableField = 'perPaging'; if($table[$tableField]) { echo $iform->init($tableField); } ?>                              
        </div>   

        <div class="four-columns left-border">
            <?php $tableField = 'nextPaging'; if($table[$tableField]) { echo $iform->init($tableField); } ?>
            <?php $tableField = 'prevPaging'; if($table[$tableField]) { echo $iform->init($tableField); } ?>                         
        </div>

        <div class="three-columns left-border">
            <?php $tableField = 'sortList'; if($table[$tableField]) { echo $iform->init($tableField); } ?>                         
        </div>

        <?php endif; ?>
    </div> 
</div>
<?php echo form_close(); ?>
<script>$(document).ready(function() { $('.setting_form').validationEngine('attach', { onValidationComplete: function(form, status){ if(status) { send_onValidComplete('.setting_form','<?php echo BASEURL.'/'.$moduleName; ?>/admin/index/config','#loadSettings'); } } }); });</script>