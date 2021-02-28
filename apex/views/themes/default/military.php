<?php $this->load->module('buildings')->getFilterBuildings(); ?>

<div class="main-content nopadd">
    <div class="contain">
        <a href="/sell-appart" class="comission">
            Хотите <span>продать</span> квартиру?
        </a>
    </div>
</div>

<section class="container-fluid fil-result-nov main-content">
    <div class="container">
        <div class="row">
            <h2>интересные предложения</h2>
            <div class="filter-result">
                <div class="contain">
                    <div class="mrow clearfix">
                        <?php $k = 1; ?>
                        <?php foreach ($rows as $r):?>
                            <div class="onecol mcol-4<?php if($k == 4):?> nomargin<?php endif;?>">
                                <a href="<?=$r->link?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                                    <div class="img_fil">
                                        <img class="filter-result-item-img" src="<?php echo image($r->foto, "small"); ?>" alt="Life">
                                        <span class="signcat <?=$r->sign['class']?>"><?=$r->sign['name']?></span>
                                        <div class="labels">
                                            <?php
                                            if (!empty($r->mortgage))    echo '<span class="mortgage">Ипотека</span>';
                                            if (!empty($r->fz214))       echo '<span class="fz214">ФЗ-214</span>';
                                            if (!empty($r->parking))     echo '<span class="parking">Паркинг</span>';
                                            if (!empty($r->rassrochka))  echo '<span class="installments">Рассрочка</span>';
                                            if (!empty($r->otdelka))     echo '<span class="finishing">Отделка</span>';
                                            ?>
                                        </div>
                                    </div>
                                    <div class="priceres"><?=$r->price?></div>
                                    <div class="filter-result-item-body">
                                        <p class="filter-result-item-rayon"><?=$r->rayon?></p>
                                        <p class="filter-result-item-name"><?=$r->name?></p>
                                        <?php if(isset($r->deadline)):?><p class="filter-result-item-size"><?=$r->deadline?></p><?php endif;?>
                                        <div class="addbott">
                                            <div class="filter-result-item-address"><?=$r->adress?>
                                                <?php if (!empty($r->metro)):?><span class="filter-result-item-metro"><span><?=$r->metro?></span><?php endif; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="viewob">просмотр объекта</div>
                                </a>
                            </div>
                            <?php $k++; if($k > 4) { $k = 1; } ?>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="filter-result-paging sm_selectpage" data-category="SelectPage" data-label="buildings">
                    <?php echo $pagination ?>
                    <?php if(uri(2) != 999 && !empty($pagination)): ?>
                        <br><br><a href="/<?php echo uri(1); ?>/999" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="buildings" style="margin-top: 10px; font-size: 13px; margin-left: -14px;">Показать все</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>