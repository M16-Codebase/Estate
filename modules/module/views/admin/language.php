<?php
	/**
     * Шаблон загрузки данных языкового файла _lang.php
     * 
     * $lang - массив данных типа - $lang['переменная'] = 'значение';
     * $valid - переменная в которую подгружается шаблон валидации а также функция для отправки данных через json
     * 
    **/
    
    $haystack = array(    
        'apex_title',
        'apex_keywords',
        'apex_description',
        'apex_delimiter',
        'md_header',
        'md_title',
        'md_description',
        'md_keywords',
        'md_breadcrumbs'                            
    );
    
?>

<form  class="language_form with-padding box-shadow wrapped white-gradient margin-bottom" method="post">
    
    <div class="twelve-columns">

        <h3 class="thin underline blue four-columns new-row float-left lite-text-shadow"><strong><?php echo $this->alang->admin_40; ?></strong></h3>

        <div class="button-group compact float-right">
           <button type="submit" class="button green-gradient glossy icon-save"> <?php echo $this->alang->admin_saved; ?></button>
           <a href="javascript:void(0);" onclick="$('#timeoutId').remove();" class="button red-gradient glossy icon-cross-round"> <?php echo $this->alang->admin_close; ?></a>
       </div>

    </div>
        
    <div class="clear-both"></div> 
    
    <dl class="definition">
    <?php  foreach($lang as $item=>$value):?>

        <dt><?php echo $value['title']; ?></dt>
            <dd>
                <textarea name="<?php echo $item; ?>" id="<?php echo $item; ?>" class="input full-width"><?php echo $value['name']; ?></textarea>                
                <input type="hidden" name="<?php echo $item.'_title'; ?>" class="input full-width" value="<?php echo $value['title']; ?>" />
                <?php if($item == 'md_text' or $item == 'apex_404_text'): ?>
                <a href="javascript:void(0);" onclick="<?php if($item == 'md_text' or $item == 'apex_404_text'): ?>editorFull<?php else: ?>editorLanguage<?php endif; ?>('<?php echo $item; ?>'); $(this).remove();" class="button white-gradient compact"><?php echo $this->alang->admin_active_editor; ?></a>
                <?php endif; ?>                                
            </dd>
            
    					
    <?php endforeach; ?>
    </dl>
</form>

<?php echo $valid; ?>