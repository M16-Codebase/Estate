<?php
/**  Шаблон подгружаемой строки к таблице */
$iform = $this->elem;
?>

<?php echo form_open_multipart('',array('class'=>'row_form')); ?>
<div class="columns">
    <!-- кнопка сохранить -->
    <div class="twelve-columns align-right">
        <div class="button-group compact">                                                       	
            <a href="javascript:void(0);" data-id="<?php echo $id; ?>" data-table="<?php echo $tables; ?>" data-module="<?php echo $modules; ?>" onclick="saveRowForm($(this));" class="saveRow green-gradient glossy button icon-save"> Сохранить</a>
            <a href="javascript:void(0);" onclick="$('.dlt<?php echo $id; ?>').find('td').first().click();" class="red-gradient glossy button icon-cross"> Закрыть</a>
        </div>
    </div>

    <div class="six-columns">
        <p class="big-text underline white">Введите перевод</p>
        
        <?php echo $iform->fm_input($table['nm_ru'], $iform->fm_label($table['nm_ru'])); ?>
    </div>
    <div class="six-columns">
        <p class="big-text underline white">Текст для перевода</p>
        
        <label class="label mid-margin-top">
            <span>
                <strong><?php echo $table['nm']['label']; ?></strong>
            </span>                                           
        </label><br />
        <div class="mid-margin-top">
            <?php echo $table['nm']['value']; ?>
        </div>
    </div>
        
    <?php if(!empty($table['txt']['value'])): ?>
    <div class="twelve-columns no-margin-bottom">&nbsp;</div>
        
    <div class="six-columns">            
        <?php echo $iform->fm_input($table['txt_ru'], $iform->fm_label($table['txt_ru'])); ?>
    </div>
    <div class="six-columns">
        <label class="label mid-margin-top">
            <span>
                <strong><?php echo $table['txt']['label']; ?></strong>
            </span>                                           
        </label><br />
        <div class="mid-margin-top">
            <?php echo $table['txt']['value']; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php echo form_hidden('id', $id); ?>  
<?php echo form_close(); ?>