<?php if($table != ''): ?>
    <?php foreach($table as $key=>$tbl):?>
    <tr class="dlt<?php echo $key; ?>">
        <?php foreach($tbl as $t):?>
            <td><?php echo $t; ?></td>
        <?php endforeach; ?>
	</tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="<?php echo count($headingTable)+1; ?>">
        <p class="big-message blue-gradient white"><span class="icon-info-round margin-right icon-size2"></span><?php echo $alang->admin_15; ?></p>
    </td>
</tr>
<?php endif; ?>