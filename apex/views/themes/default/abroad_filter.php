<?php

if(empty($params['country'])) { $params['country'] = '0'; } else {$country_array = explode(",", $params['country']);}
if(empty($params['city'])) { $params['city'] = '0'; } else {$city_array = explode(",", $params['city']);}
if(empty($params['estate'])) { $params['estate'] = '0'; } else {$estate_array = explode(",", $params['estate']);}
if(empty($params['rooms'])) { $params['rooms'] = '0'; } else {$rooms_array = explode(",", $params['rooms']);}
if(empty($params['sdelka'])) { $params['sdelka'] = '0'; } else {$sdelka_array = explode(",", $params['sdelka']);}
if(empty($params['address'])) { $params['address'] = ''; }
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['square_from'])) { $params['square_from'] = $filter->minMaxS['min']; }
if(empty($params['square_to'])) { $params['square_to'] = $filter->minMaxS['max']; }
if(empty($params['land_from'])) { $params['land_from'] = $filter->minMaxSS['min']; }
if(empty($params['land_to'])) { $params['land_to'] = $filter->minMaxSS['max']; }

$long = 0;

if ($params['square_from'] > $filter->minMaxS['min'] || $params['square_to'] < $filter->minMaxS['max'] ||
    $params['land_from'] > $filter->minMaxSS['min'] || $params['land_to'] < $filter->minMaxSS['max'] ||
    !empty($params['address']) || !empty($params['rooms']) || !empty($params['sdelka'])) $long = 1;
?>
<div class="mainhead block-novostoy-intro" style="background: url('/asset/assets/img/abroadwide.jpg') no-repeat top center">
    <div class="contain">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <?php echo $this->load->view(getPath(__FILE__,'/parth_filter_menu'), array('current' => 'abroad'), TRUE); ?>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">

                    <form method="get" class="abroad-form" action="/abroad">
                        <div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
                            <div class="short-filter clearfix">
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(тыс. €)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider-abroad" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="aPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="aPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop30">
                                        <label>Общая площадь <span>(м<sup>2</sup>)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-abroad" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="square_from" id="aSquare-from" class="price_input" value="<?php echo $params['square_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="square_to" id="aSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
                                        </div>
                                    </div>


                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter wd235">
                                        <input type="hidden" name="country" value="<?=$params['country']?>" class="sel_abroad_country" />
                                        <select class="mabroad_country" multiple="multiple">
                                            <?php foreach($filter->country as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['country'] && in_array($k, $country_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop57">
                                        <label>Площадь участка <span>(сот)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-abroad-land" class="filter-slider range"></div>
                                        </div>


                                        <div class="prdiv square-ba" data-min="<?php echo $filter->minMaxSS['min']; ?>" data-max="<?php echo $filter->minMaxSS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="land_from" id="alSquare-from" class="price_input" value="<?php echo $params['land_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="land_to" id="alSquare-to" class="price_input" value="<?php echo $params['land_to']; ?>" />
                                        </div>
                                    </div>

                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter wd235">
                                        <input type="hidden" name="city" value="<?=$params['city']?>" class="sel_abroad_city" />
                                        <select class="mabroad_city" multiple="multiple">
                                            <?php foreach($filter->city as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['city'] && in_array($k, $city_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter">
                                        <input type="text" placeholder="Введите адрес" name="address" value="<?php echo $params['address'];?>" class="filter-input abroad_address">
                                    </div>
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="rooms" value="<?=$params['rooms']?>" class="sel_abroad_rooms" />
                                        <select class="mabroad_rooms block-right" multiple="multiple">
                                            <?php foreach($filter->rooms as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['rooms'] && in_array($k, $rooms_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter wd235">
                                        <input type="hidden" name="estate" value="<?=$params['estate']?>" class="sel_abroad_estate" />
                                        <select class="mabroad_estate" multiple="multiple">
                                            <?php foreach($filter->estate as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['estate'] && in_array($k, $estate_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="sdelka" value="<?=$params['sdelka']?>" class="sel_abroad_sdelka" />
                                        <select class="mabroad_sdelka block-right" multiple="multiple">
                                            <?php foreach($filter->sdelka as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['sdelka'] && in_array($k, $sdelka_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
                                <a href="#" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
                                <div class="action-filter">
                                    <a href="/abroad">Сбросить</a>
                                    <button type="submit">Искать</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</div>



