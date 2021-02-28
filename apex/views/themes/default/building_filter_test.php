<?php
$type_building = array(2,3,6);
$rayon_array = array();
$metro_array = array();

$this->load->model('metro/metro_model','metro_model');
$metro = $this->metro_model->getAll();

$this->load->model('rayon/rayon_model','rayon_model');
$rayons = $this->rayon_model->getAll();
$spb = array(); $lenobl = array();
foreach ($rayons as $v){
    if ($v['is_city'] == 1)
        $spb[] = $v;
    else
        $lenobl[] = $v;
}

foreach($d_type as $k=>$v) { if(in_array($k, $type_building)) { unset($d_type[$k]); } }
$ray = array(26,27,11);
foreach($d_rayon as $k=>$v) { if(in_array($k, $ray)) { unset($d_rayon[$k]); } }
$metro_building = array(51);
foreach($d_metro as $k=>$v) { if(in_array($k, $metro_building)) { unset($d_metro[$k]); } }
if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['metro'])) { $params['metro'] = '0'; } else {$metro_array = explode(",", $params['metro']);}
if(empty($params['adress'])) { $params['adress'] = ''; }
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['square_from'])) { $params['square_from'] = ''; }
if(empty($params['square_to'])) { $params['square_to'] = ''; }
if(empty($params['room'])) { $params['room'] = '0'; } else {$room_array = explode(",", $params['room']);}
if(empty($params['ipoteka'])) { $params['ipoteka'] = ''; }
if(empty($params['rassrochka'])) { $params['rassrochka'] = ''; }
if(empty($params['name'])) { $params['name'] = ''; }
if(empty($params['type'])) { $params['type'] = '0'; } else {$type_array = explode(",", $params['type']);}
if(empty($params['srok'])) { $params['srok'] = '0'; } else {$srok_array = explode(",", $params['srok']);}

if(count($metro_array) == 0){$metro_text = 'Метро: не выбрано';} else {$metro_text = 'Метро: выбрано '.count($metro_array);}
if(count($rayon_array) == 0){$rayon_text = 'Район: не выбрано';} else {$rayon_text = 'Район: выбрано '.count($rayon_array);}
?>

<form method="get" class="buildings-form" action="/buildings">
<div class="nfilter">
    <div class="short-filter clearfix">
        <div class="scolumn fcolumn">
            <div class="clearfix one-filter-price">
                <label>стоимость <span>(млн.руб)</span></label>
                <div class="filter-slider-container">
                    <div id="price-slider-abroad" class="filter-slider range"></div>
                </div>
                <div class="prdiv" data-min="0" data-max="220">
                    <span class="sq_uch">от </span>
                    <input type="text" name="price_from" id="aPrice-from" class="price_input" value="2" />
                    <span class="sq_uch"> до </span>
                    <input type="text" name="price_to" id="aPrice-to" class="price_input" value="90" />
                </div>
            </div>
            <div class="clearfix one-filter-price long-filter">
                <label>площадь <span>(м2)</span></label>
                <div class="filter-slider-container">
                    <div id="price-slider-resale" class="filter-slider range"></div>
                </div>
                <div class="prdiv" data-min="0" data-max="220">
                    <span class="sq_uch">от </span>
                    <input type="text" name="price_from" id="rPrice-from" class="price_input" value="2" />
                    <span class="sq_uch"> до </span>
                    <input type="text" name="price_to" id="rPrice-to" class="price_input" value="90" />
                </div>
            </div>
        </div>
        <div class="lcolumn fcolumn">
            <div class="clearfix one-filter">
                <input type="hidden" name="rayon" value="<?=$params['rayon']?>" class="sel_build_rayon" />
                <a href="#metro_n_districts" class="shpopupa popup-modal distrolist" data-id="districts"><?=$rayon_text?></a>
            </div>
            <div class="clearfix one-filter long-filter">
                <input type="hidden" name="metro" value="<?=$params['metro']?>" class="sel_build_metro" />
                <a href="#metro_n_districts" class="shpopupa popup-modal metrolist" data-id="metro"><?=$metro_text?></a>
            </div>
            <div class="clearfix one-filter long-filter">
                <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                <select class="mabroad_country" multiple="multiple">
                    <option value="1" >Страна</option>
                </select>
            </div>
        </div>
        <div class="lcolumn fcolumn">
            <div class="clearfix one-filter">
                <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                <select class="mabroad_country" multiple="multiple">
                    <option value="1" >Страна</option>
                </select>
            </div>
            <div class="clearfix one-filter long-filter">
                <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                <select class="mabroad_country" multiple="multiple">
                    <option value="1" >Страна</option>
                </select>
            </div>
            <div class="clearfix one-filter long-filter">
                <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                <select class="mabroad_country" multiple="multiple">
                    <option value="1" >Страна</option>
                </select>
            </div>
            <div class="clearfix one-filter long-filter">
                <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                <select class="mabroad_country" multiple="multiple">
                    <option value="1" >Страна</option>
                </select>
            </div>
            <div class="clearfix one-filter long-filter">
                <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                <select class="mabroad_country" multiple="multiple">
                    <option value="1" >Страна</option>
                </select>
            </div>
        </div>
        <div class="scolumn fcolumn">
            <div class="clearfix one-filter">
                <input type="hidden" name="room" value="0" class="sel_room" />
                <div class="btn-group chk-btn" data-toggle="buttons">
                    <label class="btn btn-primary">
                        <input type="checkbox" value="1"  class="resale-check">Студия
                    </label>

                    <label class="btn btn-primary">
                        <input type="checkbox" value="2" class="resale-check">1
                    </label>

                    <label class="btn btn-primary">
                        <input type="checkbox" value="3,16" class="resale-check">2
                    </label>
                    <label class="btn btn-primary">
                        <input type="checkbox" value="4,17" class="resale-check">3
                    </label>
                    <label class="btn btn-primary">
                        <input type="checkbox" value="5,18" class="resale-check" >4
                    </label>
                    <label class="btn btn-primary">
                        <input type="checkbox" class="resale-check" value="6,7,32,31,21">5+
                    </label>
                </div>
            </div>
            <div class="clearfix one-filter long-filter left-al">
                <label for="checkbox-1" class="custom-checkbox-wrap">
                    <span></span>Ипотека
                    <input id="checkbox-1" value="1" <?php if($params['ipoteka'] == 1) echo 'checked'; ?> name="ipoteka" type="checkbox" class="build-check">
                </label>
                <label for="checkbox-2" class="custom-checkbox-wrap">
                    <span></span>рассрочка
                    <input id="checkbox-2" value="1" <?php if($params['rassrochka'] == 1) echo 'checked'; ?> name="rassrochka" type="checkbox" class="build-check">
                </label>
                <label for="checkbox-3" class="custom-checkbox-wrap">
                    <span></span>без отделки
                    <input id="checkbox-3" value="1" <?php if($params['rassrochka'] == 1) echo 'checked'; ?> name="rassrochka" type="checkbox" class="build-check">
                </label>
                <label for="checkbox-4" class="custom-checkbox-wrap">
                    <span></span>214 фз
                    <input id="checkbox-4" value="1" <?php if($params['rassrochka'] == 1) echo 'checked'; ?> name="rassrochka" type="checkbox" class="build-check">
                </label>
                <label for="checkbox-5" class="custom-checkbox-wrap">
                    <span></span>паркинг
                    <input id="checkbox-5" value="1" <?php if($params['rassrochka'] == 1) echo 'checked'; ?> name="rassrochka" type="checkbox" class="build-check">
                </label>
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
<!--noindex-->
<div id="metro_n_districts" class="mfp-hide white-popup-block distromet">
    <div class="tabs-popup">
        <a href="#" class="chpopsh" id="sdistricts">Район</a>
        <a href="#" class="chpopsh active" id="smetro">Метро</a>
    </div>
    <div class="close-popup"></div>
    <div class="mapmetro popsh active" id="pop-smetro">
        <?php foreach($metro as $value):?>
            <div class="label-metro" style="top: <?=$value['top']?>px; left: <?=$value['left']?>px; <?php if ($value['width'] > 0):?>width:<?=$value['width']?>px;<?php endif;?> <?php if (!empty($value['align'])):?>text-align:<?=$value['align']?>;<?php endif;?> <?php if (!empty($value['letter_spacing'])):?>letter-spacing:<?=$value['letter_spacing']?>px;<?php endif;?>">
                <a href="#" data-id="<?=$value['id']?>" id="metro_item_<?=$value['id']?>" class="<?php if(in_array($value['id'], $metro_array)):?>select <?php endif;?><?php if (!empty($value['class'])):?><?=$value['class']?><?php endif;?>"><?=$value['label']?></a>
            </div>
        <?php endforeach; ?>
        <div class="distr-list metro-list">
            <div class="inner-metro">
                <?php if(count($metro > 0)):?>
                    <?php $half = (int)ceil(count($metro) / 2);?>
                    <ul>
                    <?php foreach($metro as $k => $value):?>
                        <?php if($k == $half):?>
                            </ul>
                            <ul>
                        <?php endif;?>
                        <li data-id="<?=$value['id']?>" class="metro_item_<?=$value['id']?><?php if(in_array($value['id'], $metro_array)):?> select<?php endif;?>"><a href="#"><?=$value['name']?></a></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="bot-pops">
            <a href="#" class="clearmetros" id="refresh-metro">Сбросить</a>
            <button>Показать</button>
        </div>
    </div>
    <div class="districts-popup popsh" id="pop-sdistricts">
        <div class="area_maps clearfix">
            <?php

            ?>
            <img src="/asset/assets/img/map-distr.jpg" alt="" usemap="#map">
            <map name="map">
                <?php foreach($rayons as $value):?>
                    <area shape="poly" data-id="<?=$value['id']?>" alt="<?=$value['name']?>" title="<?=$value['name']?>" coords="<?=$value['poly']?>" />
                <?php endforeach; ?>
            </map>
            <?php foreach($rayons as $value):?>
                <img src="/asset/assets/img/d<?=$value['id']?>.png" width="532" height="672" usemap="#map" class="area_region area_region_<?=$value['id']?>" style="display: <?php if(in_array($value['id'], $rayon_array)){?>block<?php }else{ ?>none<?}?>;">
            <?php endforeach; ?>
        </div>
        <div class="distr-list regions-list">
            <?php if(count($spb > 0)):?>
                <?php $half = (int)ceil(count($spb) / 2);?>
                <p class="h3">Санкт-Петербург</p>
                <ul>
                <?php foreach($spb as $k => $value):?>
                    <?php if($k == $half):?>
                        </ul>
                        <ul>
                    <?php endif;?>
                    <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#"><?=$value['name']?> район</a></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if(count($lenobl > 0)):?>
                <?php $half = (int)ceil(count($lenobl) / 2);?>
                <p class="h3">Ленинградская область</p>
                <ul>
                <?php foreach($lenobl as $k => $value):?>
                    <?php if($k == $half):?>
                        </ul>
                        <ul>
                    <?php endif;?>
                    <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#"><?=$value['name']?></a></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="bot-pops">
                <a href="#" class="clearmetros"  id="refresh-rayon">Сбросить</a>
                <button>Показать</button>
            </div>
        </div>

    </div>
</div>
<!--/noindex-->