<?php
if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['matherial'])) { $params['matherial'] = '0'; } else {$matherial_array = explode(",", $params['matherial']);}
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['vodoem'])) { $params['vodoem'] = ''; }
if(empty($params['infrostr'])) { $params['infrostr'] = ''; }
if(empty($params['sq_dom_from'])) { $params['sq_dom_from'] = $filter->minMaxS['min']; }
if(empty($params['sq_dom_to'])) { $params['sq_dom_to'] = $filter->minMaxS['max']; }
if(empty($params['sq_uch_from'])) { $params['sq_uch_from'] = $filter->minMaxSS['min']; }
if(empty($params['sq_uch_to'])) { $params['sq_uch_to'] = $filter->minMaxSS['max']; }
if(empty($params['rassrochka'])) { $params['rassrochka'] = ''; }
if(empty($params['gas'])) { $params['gas'] = ''; }
if(empty($params['water'])) { $params['water'] = ''; }
if(empty($params['electric'])) { $params['electric'] = ''; }
if(empty($params['typeob'])) { $params['typeob'] = 0; }
if(empty($params['name'])) { $params['name'] = ''; }
if(empty($params['type'])) { $params['type'] = '0'; } else {$type_array = explode(",", $params['type']);}
$long = 0;
if (!empty($params['matherial']) || $params['sq_dom_from'] > $filter->minMaxS['min'] ||
    $params['sq_dom_to'] < $filter->minMaxS['max'] || $params['sq_uch_from'] > $filter->minMaxSS['min'] ||
    $params['sq_uch_to'] < $filter->minMaxSS['max'] || !empty($params['rassrochka']) ||
    !empty($params['water']) || !empty($params['electric']) || !empty($params['vodoem']) ||
    !empty($params['infrostr']) || !empty($params['gas'])) $long = 1;
?>
<?php
// оставляем только необходимые районы. в данном случае районы ЛО

?>
<?php
// оставляем только необходимые районы. в данном случае районы ЛО
$rayon_LO = array(12,2,5,6,25,13,28,19,21);
foreach($d_rayon as $k=>$v) { if(!in_array($k, $rayon_LO)) { unset($d_rayon[$k]); } }
?>

<div class="mainhead block-novostoy-intro" style="background: url('/asset/assets/img/residentialwide.jpg') no-repeat top center">
    <div class="contain">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <?php echo $this->load->view(getPath(__FILE__,'/parth_filter_menu'), array('current' => 'residential'), TRUE); ?>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">

                    <form method="get" class="residential-form" action="/residential">
                        <div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
                            <div class="short-filter clearfix">
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(млн.руб)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider-residential" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="resPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="resPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop30">
                                        <label>площадь дома <span>(м<sup>2</sup>)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-residential" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="sq_dom_from" id="resSquare-from" class="price_input" value="<?php echo $params['sq_dom_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="sq_dom_to" id="resSquare-to" class="price_input" value="<?php echo $params['sq_dom_to']; ?>" />
                                        </div>
                                    </div>

                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter  wd235">
                                        <input type="hidden" name="rayon" value="<?=$params['rayon']?>" class="sel_rayon" />
                                        <select class="multiselect-rayon-residential" multiple="multiple">
                                            <?php foreach($filter->rayon as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['rayon'] && in_array($k, $rayon_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop57">
                                        <label>площадь участка <span>(сот)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="squareu-slider-residential" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv squareu-b" data-min="<?php echo $filter->minMaxSS['min']; ?>" data-max="<?php echo $filter->minMaxSS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="sq_uch_from" id="resSquareu-from" class="price_input" value="<?php echo $params['sq_uch_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="sq_uch_to" id="resSquareu-to" class="price_input" value="<?php echo $params['sq_uch_to']; ?>" />
                                        </div>
                                    </div>

                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter  wd235">
                                        <input type="hidden" name="type" value="<?=$params['type']?>" class="sel_type" />
                                        <select class="multiselect-type-residential block-right" multiple="multiple">
                                            <?php foreach($filter->type as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['type'] && in_array($k, $type_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter left-al ml22 mtop30">
                                        <label for="checkbox-2" class="custom-checkbox-wrap">
                                            <input id="checkbox-2" value="1" <?php if($params['infrostr'] == 1) echo 'checked'; ?> name="infrostr" type="checkbox" class="residential-check">
                                            <span></span>Инфраструктура
                                        </label>
                                        <label for="checkbox-3" class="custom-checkbox-wrap">
                                            <input id="checkbox-3" value="1" <?php if($params['gas'] == 1) echo 'checked'; ?> name="gas" type="checkbox" class="residential-check">
                                            <span></span>Газ
                                        </label>
                                        <label for="checkbox-4" class="custom-checkbox-wrap">
                                            <input id="checkbox-4" value="1" <?php if($params['water'] == 1) echo 'checked'; ?> name="water" type="checkbox" class="residential-check">
                                            <span></span>Водоснабжение
                                        </label>
                                        <label for="checkbox-5" class="custom-checkbox-wrap">
                                            <input id="checkbox-5" value="1" <?php if($params['electric'] == 1) echo 'checked'; ?> name="electric" type="checkbox" class="residential-check">
                                            <span></span>Электричество
                                        </label>
                                        <label for="checkbox-6" class="custom-checkbox-wrap">
                                            <input id="checkbox-6" value="1" <?php if($params['vodoem'] == 1) echo 'checked'; ?> name="vodoem" type="checkbox" class="residential-check">
                                            <span></span>Наличие водоема
                                        </label>
                                    </div>


                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="text" id="residcity" placeholder="Название" name="name" value="<?=$params['name']?>" class="filter-input filter-input-large resid_txt">
                                    </div>
                                    <div class="clearfix one-filter long-filter  wd235">
                                        <input type="hidden" name="matherial" value="<?=$params['matherial']?>" class="sel_matherial" />
                                        <select class="multiselect-matherial" multiple="multiple">
                                            <?php foreach($filter->matherial as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['matherial'] && in_array($k, $matherial_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="clearfix one-filter long-filter left-al mtop30">
                                        <label for="checkbox-1" class="custom-checkbox-wrap">
                                            <input id="checkbox-1" value="1" <?php if($params['rassrochka'] == 1) echo 'checked'; ?> name="rassrochka" type="checkbox" class="residential-check">
                                            <span></span>Рассрочка
                                        </label>
                                    </div>
                                </div>
                            </div>
<!--noindex-->
                            <div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
                                <a href="#" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
                                <div class="action-filter">
                                    <a href="/residential">Сбросить</a>
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

