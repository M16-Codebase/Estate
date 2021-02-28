<?php
if(empty($params['forwhat'])) { $params['forwhat'] = '0'; } else {$forwhat_array = explode(",", $params['forwhat']);}
if(empty($params['city'])) { $params['city'] = '0'; } else {$city_array = explode(",", $params['city']);}
if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['sdelka'])) { $params['sdelka'] = '0'; } else {$sdelka_array = explode(",", $params['sdelka']);}
if(empty($params['type'])) { $params['type'] = '0'; } else {$type_array = explode(",", $params['type']);}
if(empty($params['address'])) { $params['address'] = ''; }
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['distance_from'])) { $params['distance_from'] = ''; }
if(empty($params['distance_to'])) { $params['distance_to'] = ''; }
if(empty($params['square_from'])) { $params['square_from'] = $filter->minMaxS['min']; }
if(empty($params['square_to'])) { $params['square_to'] = $filter->minMaxS['max']; }
?>

<div class="mainhead block-novostoy-intro" style="background: url('/asset/assets/img/landwide.jpg') no-repeat top center">
    <div class="contain">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <ul class="tabs">
                    <li><a href="/buildings" class="tabcc buildtab" id="selcont-1">Новостройки</a></li>
                    <li><a href="/resale" class="tabcc resaletab" id="selcont-2">Вторичная</a></li>
                    <li><a href="/residential" class="tabcc residtab" id="selcont-3">Загородная</a></li>
                    <li><a href="/abroad" class="tabcc abroadtab" id="selcont-4">Зарубежная</a></li>
                    <li><a href="/elite" class="tabcc elitetab" id="selcont-5">Элитная</a></li>
                    <li><a href="/commercial" class="tabcc commtab" id="selcont-6">Коммерческая</a></li>
                    <li><a href="/land" class="tabcc active landtab" id="selcont-7">Земельные массивы</a></li>
                    <li><a href="/arenda" class="tabcc arendatab" id="selcont-8">Аренда</a></li>
                </ul>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">
                    <form method="get" class="land-form" action="/land">
                        <div class="nfilter">
                            <div class="short-filter clearfix">
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(млн.руб)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider-land" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="lPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="lPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop30">
                                        <label>площадь <span>(Га)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-land" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="square_from" id="lSquare-from" class="price_input" value="<?php echo $params['square_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="square_to" id="lSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter wd235">
                                        <input type="hidden" name="city" value="<?=$params['city']?>" class="sel_land_city" />
                                        <select class="mland_city" multiple="multiple">
                                            <?php foreach($filter->city as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['city'] && in_array($k, $city_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="rayon" value="<?=$params['rayon']?>" class="sel_land_rayon" />
                                        <select class="mland_rayon" multiple="multiple">
                                            <?php foreach($filter->rayon as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['rayon'] && in_array($k, $rayon_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter wd235">
                                        <input type="hidden" name="sdelka" value="<?=$params['sdelka']?>" class="sel_land_sdelka" />
                                        <select class="mland_sdelka" multiple="multiple">
                                            <?php foreach($filter->sdelka as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['sdelka'] && in_array($k, $sdelka_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="tokad" value="<?=$params['tokad']?>" class="sel_land_tokad" />
                                        <select class="mland_tokad">
                                            <?php foreach($filter->tokad as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['tokad'] && in_array($k, $tokad_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="type" value="<?=$params['type']?>" class="sel_land_type" />
                                        <select class="mland_type" multiple="multiple">
                                            <?php foreach($filter->type as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['type'] && in_array($k, $type_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
<!--noindex-->
                            <div class="botfilter">
                                <a href="#" class="show-hide short">Расширенный поиск</a>
                                <div class="action-filter">
                                    <a href="#">Сбросить</a>
                                    <button type="submit">Искать</button>
                                </div>
                            </div>
<!--/noindex-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
