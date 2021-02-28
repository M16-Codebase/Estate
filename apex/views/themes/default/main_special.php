<div class="contain">
    <div class="mrow clearfix">
        <p class="p-header">спецпредложения</p>
        <a href="/special" class="blocks-in">показать больше</a>
        <?php foreach ($data as $key => $value) { ?>
        <div class="onecol mcol-4<?php echo ($key == 3 || $key == 7)?' nomargin':''; ?>">
            <a href="<?php echo $value['link']; ?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                <div class="img_fil">
                    <img class="filter-result-item-img" src="<?php echo image($value['foto'], "small"); ?>" alt="<?=$value['name']?>">
                    <span class="signcat <?=$value['sign']['class']?>"><?=$value['sign']['name']?></span>
                    <div class="labels">
                        <?php
                        if (!empty($value['mortgage']))    echo '<span class="mortgage">Ипотека</span>';
                        // if (!empty($value['fz214']))       echo '<span class="fz214">ФЗ-214</span>';
                        // if (!empty($value['parking']))     echo '<span class="parking">Паркинг</span>';
                        // if (!empty($value['rassrochka']))  echo '<span class="installments">Рассрочка</span>';
                        // if (!empty($value['otdelka']))     echo '<span class="finishing">Отделка</span>';
                        ?>
                    </div>
                </div>
                <div class="priceres"><?=$value['price']?></div>
                <div class="filter-result-item-body">
                    <p class="filter-result-item-rayon"><?=$value['rayon']?></p>
                    <p class="filter-result-item-name"><?=$value['name']?></p>
                    <?php if(isset($value['deadline'])):?><p class="filter-result-item-size"><?=$value['deadline']?></p><?php endif;?>
                    <div class="addbott">
                        <div class="filter-result-item-address"><?=$value['address']?>
                            <?php if (!empty($value['metro'])):?><span class="filter-result-item-metro"><span><?=$value['metro']?></span><?php endif; ?></span>
                        </div>
                    </div>
                </div>
                <div class="viewob">просмотр объекта</div>
            </a>
        </div>
        <?php } ?>
    </div>
</div>