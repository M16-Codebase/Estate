<div class="panel-control align-right white-gradient">
    <div id="load_button_action" class="float-right">
       <div  class="button-group align-right">

           <a title="<?php echo $alang->admin_38; ?>" href="javascript:void(0);" onclick="loadSettingDiv('<?php echo BASEURL; ?>/<?php echo $moduleName; ?>');" class="button anthracite-gradient icon-tools sets with-tooltip"></a>

       </div>
    </div>
</div>

<div class="with-padding scrollable">

    <div id="loadSettings"></div>
    <div id="loadLanguages"></div>
    <table class="tables">
        <tr class="md_<?php echo $moduleName; ?>"></tr>
    </table>

    <?php if(!empty($information)): ?>
        <p class="big-message margin-bottom"><span class="icon-info-round margin-right icon-size2"></span><?php echo $information; ?><span class="close show-on-parent-hover simpler inset">✕</span><span class="block-arrow"><span></p>
    <?php endif; ?>


    <div class="table-header button-height">
        <div class="captions">
            <?php echo $captionTable; ?>
		</div>
	</div>

    <table class="table responsive-table" id="sorting">

    	<thead>
    		<tr>
    			<?php foreach($headingTable as $cl=>$th):?>
                    <th scope="col" width="<?php echo $th['width']; ?>" class="align-center <?php echo $th['class']; ?>"><?php echo $cl; ?></th>
                <?php endforeach; ?>                                                
    		</tr>
    	</thead>

    	<tbody id="loadPagination">

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

    	</tbody>

    </table>

</div>

<div class="hidden">
    <span id="urlData"><?php echo BASEURL; ?>/military/admin/index/delete</span>
    <span id="uriData"><?php echo BASEURL; ?>/military/admin/index</span>
    <span id="moduleName" data-module="military" data-table="military"></span>
</div>