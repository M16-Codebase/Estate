<?php
/**
 * Шаблон вывода таблицы пагинации 
**/
echo $moduleName;
?>

<?php if($table != ''): ?>
    <?php foreach($table as $key=>$tbl):?>
    <tr class="dlt<?php echo $key; ?>">
        <?php foreach($tbl as $t):?>
            <td><?php echo $t; ?></td>
        <?php endforeach; ?>
    				
    	<td class="align-right">
            <div class="button-group compact children-tooltip" data-tooltip-options='{"position":"right"}'>
                <a href="<?php echo BASEURL; ?>/<?php echo $moduleName; ?>/admin/index/edit/<?php echo $key; ?>" class="button icon-pencil" title="<?php echo $alang->admin_edit; ?>"></a>
                <a href="javascript:void(0);" data-links="<?php if(isset($identifier[$key])) echo $identifier[$key]; ?>" data-id="<?php echo $key; ?>" class="delRow button icon-trash confirm" title="<?php echo $alang->admin_delete; ?>"></a>                                              		                                                  
            </div> 
            <input type="checkbox" name="checked[]" id="check-<?php echo $key; ?>" value="<?php echo $key; ?>|<?php if(isset($identifier[$key])) echo $identifier[$key]; ?>" class="checks" style="width:24px;"/>                   
        </td>
        
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="<?php echo count($headingTable)+1; ?>">
            <p class="big-message red-gradient white"><span class="icon-info-round margin-right icon-size2"></span><?php echo $this->alang->admin_15; ?></p>
        </td>
    </tr>
<?php endif; ?>

<script>
switchesActionAjax();
$('#sorting').responsiveTable();
</script>
<?php echo templ(); ?>