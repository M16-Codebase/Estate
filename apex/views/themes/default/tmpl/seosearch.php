<?php $this->load->module($moduleName)->$filterFunc(); ?>

<div class="main-content nopadd">
    <div class="contain">
        <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
    </div>
</div>


<section class="container-fluid fil-result-nov main-content">
    <div class="relrow contain">
        <div class="main_nav_block">
            <a href="#" class="fastss">Быстрый поиск</a>
        </div>
        <?php $this->load->view('themes/default/tmpl/fastsearch_'.$category, array('link' => $linkto)); ?>
    </div>

    <div class="contain fscontain">
        <div class="">
            <h1><?=$htag?></h1>
            <div class="filter-result">
                <div class="">
                    <div class="mrow clearfix">
                        <?php $k = 1; ?>
                        <?php foreach ($rows as $r):?>
                            <div class="onecol mcol-4<?php if($k == 4):?> nomargin<?php endif;?>">
                                <a href="<?=$r->link?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                                    <div class="img_fil">
                                        <img class="filter-result-item-img" src="<?php echo image($r->foto, "small"); ?>" alt="Life">
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
                                        <?php if(isset($r->srok)):?><p class="filter-result-item-size"><?=$r->srok?></p><?php endif;?>
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
                </div>
            </div>
        </div>

</section>

<?php if(empty($page) && !empty($seotext)): ?>
    <br><br>
    <section class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="row seotext">
                     <?php echo $seotext; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>