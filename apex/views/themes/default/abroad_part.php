
<section class="container-fluid fil-result-nov">
    <div class="">

            <div class="contain">
                <a href="/sell-appart" class="comission inset row">
            Хотите <span>продать</span> квартиру?
        </a>
                <div class="relrow">
                    <div class="main_nav_block">
                        <div class="filter-result-sort">
                            <a href="#" class="fastss">Быстрый поиск</a>
                            <select class="sortings-filt-date">
                                <option value="asc" <?php if ($this->session->userdata('sort_price_abroad') == 'asc'){?>selected<?php } ?>>цена по возрастанию</option>
                                <option value="desc" <?php if ($this->session->userdata('sort_price_abroad') == 'desc'){?>selected="selected"<?php } ?>>цена по убыванию</option>
                            </select>
                            <!--<a rel="nofollow" href="/buildings/sort/korpus_value" class="filter-result-sort-link <?php //if($_SESSION[uri(1)] == 'korpus_value') echo 'active'; ?>">дата завершения строительства</a>-->
                            <div class="show_right_sort">
                                <a href="#" class="<?php if ($this->session->userdata('plitter_abroad') == 'list'){?>chplit<?php } ?> plit plist" data-value="plit">Плитка</a>
                                <a href="#" class="<?php if ($this->session->userdata('plitter_abroad') == 'plit'){?>chplit<?php } ?> lisst plist" data-value="list">Список</a>
                                <a href="#abroadlink" class="onmap">На карте</a>
                            </div>
                        </div>
                        <?php if(count($rows) == 0 || empty($rows)): ?>
                            <p class="notfnd">По вашему запросу ничего не найдено <a href="/abroad">показать все варианты</a></p>
                        <?php endif; ?>
                        <div class="filter-result-paging sm_selectpage headpag" data-category="SelectPage" data-label="buildings">
                            <?php if($params['page'] !== 'all' && !empty($pagination)): ?>
                                <?php echo $pagination ?>
                                <br><br><a href="/<?php echo uri(1); ?>/?<?=$show_all?>" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="buildings" style="margin-top: 10px; font-size: 13px; margin-left: 0;">Показать все</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php $this->load->view('themes/default/tmpl/fastsearch_abroad'); ?>

                    <?php if ($this->session->userdata('plitter_abroad') == 'plit'){?>
                        <div class="filter-result">
                            <div class="">
                                <div class="mrow clearfix">
                                    <?php $k = 1; ?>
                                    <?php foreach ($rows as $r):?>
                                    <div class="onecol mcol-4<?php if($k == 4):?> nomargin<?php endif;?>">
                                        <a href="<?=$r->link?>" class="object-item sm_search" data-category="SelectObject" data-label="buildings">
                                            <div class="img_fil">
                                                <img class="filter-result-item-img" src="<?php echo image($r->foto, "small", false); ?>" alt="Life">
                                                <!--<span class="signcat <?//=$r->sign['class']?>"><?//=$r->sign['name']?></span>-->
                                            </div>
                                            <div class="priceres"><?php if($r->price > 0) { echo '<span>'.$r->price.'</span>'; } else { echo 'по запросу'; } ?></div>
                                            <div class="filter-result-item-body">
                                                <p class="filter-result-item-rayon"><?=$r->country?></p>
                                                <p class="filter-result-item-name"><?=$r->name?></p>
                                                <?php if(isset($r->estate)):?><p class="filter-result-item-size"><?=$r->estate?></p><?php endif;?>
                                                <div class="addbott">
                                                    <div class="filter-result-item-address"><?=$r->address?></div>
                                                </div>
                                            </div>
                                            <div class="viewob">просмотр объекта</div>
                                        </a>
                                    </div>
                                    <?php $k++; if($k > 4) { $k = 1; } ?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    <?php } else {?>
                        <div class="filter-result plitter">

                            <?php foreach($rows as $r): ?>

                                <div class="filter-result-row clearfix">

                                    <a href="<?php echo $r->link; ?>" class="filter-result-item-plit sm_search" data-category="SelectObject" data-label="buildings">
                                        <div class="img_fil">

                                            <p class="filter-result-item-name"><?php echo $r->name; ?></p>
                                            <img class="filter-result-item-img" src="<?php echo image($r->foto, "medium", false); ?>" alt="<?php echo $r->name; ?>">
                                        </div>

                                        <div class="filter-result-item-body">
                                            <div class="priceres"><?php if($r->price > 0) { echo '<span>'.$r->price.'</span>'; } else { echo 'по запросу'; } ?></div>
                                            <div class="infoit">
                                                <div class="initem"><label>Страна</label><p class="filter-result-item-address"><?php echo $r->country; ?></p></div>
                                                <div class="initem"><label>Адрес</label><p class="filter-result-item-address"><?php echo $r->address; ?></p></div>
                                                <div class="initem"><label>Тип недвижимости</label><p class="filter-result-item-address"><?php echo $r->estate; ?></p></div>

                                            </div>
                                            <span class="plit-more">Подробнее</span>
                                        </div>
                                        <div class="viewob">просмотр объекта</div>
                                    </a>

                                </div>

                            <?php endforeach; ?>
                        </div>
                    <?php }?>

                    <div class="filter-result-paging sm_selectpage" data-category="SelectPage" data-label="buildings">
                        <?php if($params['page'] !== 'all' && !empty($pagination)): ?>
                            <?php echo $pagination ?>
                            <br><br><a href="/<?php echo uri(1); ?>/?<?=$show_all?>" rel="nofollow" class="filter-result-paging-link sm_search" data-category="ShowAll" data-label="buildings" style="margin-top: 10px; font-size: 13px; margin-left: 0;">Показать все</a>
                        <?php endif; ?>
                    </div>
                </div>

        </div>
    </div>

</section>