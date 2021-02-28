<?php

$rayon_array = array();
$metro_array = array();

$this->load->model('metro/metro_model','metro_model');
$metro = $this->metro_model->getAll();
$inmetro = $this->metro_model->getAllFilter(1);

$this->load->model('rayon/rayon_model','rayon_model');
$rayons = $this->rayon_model->getAllFilter(1);
$spb = array(); $lenobl = array();
foreach ($rayons as $v){
    if ($v['is_city'] == 1)
        $spb[] = $v;
    else
        $lenobl[] = $v;
}

$rayon_LO = array(12,2,5,6,25,13,28,19,21,26,27);
foreach($d_rayon as $k=>$v) { if(in_array($k, $rayon_LO)) { unset($d_rayon[$k]); } }
$metro_building = array(51);
foreach($d_metro as $k=>$v) { if(in_array($k, $metro_building)) { unset($d_metro[$k]); } }

if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['metro'])) { $params['metro'] = '0'; } else {$metro_array = explode(",", $params['metro']);}
if(empty($params['adress'])) { $params['adress'] = ''; }
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['square_from'])) { $params['square_from'] = $filter->minMaxS['min']; }
if(empty($params['square_to'])) { $params['square_to'] = $filter->minMaxS['max']; }
if(empty($params['room'])) { $params['room'] = '0'; } else {$room_array = explode(",", $params['room']);}
if(empty($params['ipoteka'])) { $params['ipoteka'] = ''; }
if(empty($params['name'])) { $params['name'] = ''; }
if(empty($params['type'])) { $params['type'] = '0'; } else {$type_array = explode(",", $params['type']);}
if(empty($params['bank'])) { $params['bank'] = '0'; $bank_array = array();} else {$bank_array = explode(",", $params['bank']);}

$long = 0;
if (!empty($params['adress']) || $params['square_from'] > $filter->minMaxS['min'] ||
    $params['square_to'] < $filter->minMaxS['max']) $long = 1;

if(count($metro_array) == 0){$metro_text = 'Метро: не выбрано';} else {$metro_text = 'Метро: выбрано - '.count($metro_array);}
if(count($rayon_array) == 0){$rayon_text = 'Район: не выбрано';} else {$rayon_text = 'Район: выбрано - '.count($rayon_array);}

if(empty($room_array)) {
	$room_array = array();
}

?>



<div class="mainhead block-novostoy-intro" style="background: url('/asset/uploads/images/slider/mil.jpg') no-repeat top center">

    <div class="contain">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <?php
                $current = 'resale';
                $tabs = array(
                    'buildings'		=> array('id' => 1, 'class' => 'buildtab',	'name' => 'Новостройки'),
                    'resale'		=> array('id' => 2, 'class' => 'resaletab',	'name' => 'Вторичная')
                );
                ?>
                <ul class="tabs">
                    <?php foreach ($tabs as $key => $tab) { $active = ($key == $current)?' active':''; ?>
                        <li><a href="/military/active_tab/<?php echo $key ?>" class="tabcc <?php echo $tab['class'].$active; ?>" id="selcont-<?php echo $tab['id']; ?>"><?php echo $tab['name']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">

                    <form method="get" class="resale-form" action="/military">
                        <div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
                            <div class="short-filter clearfix">
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(млн.руб)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider-resale" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="rPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="rPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter-price long-filter mtop30">
                                        <label>площадь <span>(м<sup>2</sup>)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-resale" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="<?php echo $filter->minMaxS['min']; ?>" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="square_from" id="rSquare-from" class="price_input" value="<?php echo $params['square_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="square_to" id="rSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="hidden" name="rayon" value="<?=$params['rayon']?>" class="sel_build_rayon" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal distrolist" data-id="districts"><?=$rayon_text?></a>
                                    </div>

                                    <div class="clearfix one-filter long-filter">
                                        <input type="text" placeholder="Введите адрес" name="adress" value="<?php echo $params['adress'];?>" class="filter-input resale_txt" id="aadrr" data-name="resale">
                                    </div>

                                    <div class="clearfix one-filter long-filter wd235">
                                        <input type="hidden" name="bank" value="<?php echo $params['bank']; ?>" class="sel_build_bank" />
                                        <select class="mbankbuildings block-right" multiple="multiple">
                                            <?php foreach($filter->bank as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if (in_array($k, $bank_array)):?>selected="selected"<?php endif;?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="hidden" name="metro" value="<?=$params['metro']?>" class="sel_build_metro" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal metrolist" data-id="metro"><?=$metro_text?></a>
                                    </div>
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter rooms-filt">
                                        <input type="hidden" name="room" value="<?=$params['room']?>" class="sel_room" />
                                        <div class="btn-group chk-btn" data-toggle="buttons">
                                            <label class="btn btn-primary<?php if(in_array(1, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="1" <?php if(in_array(1, $room_array)) echo 'checked'; ?> class="resale-check">Студия
                                            </label>

                                            <label class="btn btn-primary<?php if(in_array(2, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="2" <?php if(in_array(2, $room_array)) echo 'checked'; ?> class="resale-check">1
                                            </label>

                                            <label class="btn btn-primary<?php if(in_array(3, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="3,16" <?php if(in_array(3, $room_array)) echo 'checked'; ?> class="resale-check">2
                                            </label>
                                            <label class="btn btn-primary<?php if(in_array(4, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="4,17" class="resale-check" <?php if(in_array(4, $room_array)) echo 'checked'; ?>>3
                                            </label>
                                            <label class="btn btn-primary<?php if(in_array(5, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="5,18" class="resale-check" <?php if(in_array(5, $room_array)) echo 'checked'; ?>>4
                                            </label>
                                            <label class="btn btn-primary<?php if(in_array(6, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" class="resale-check" value="6,7,32,31,21" <?php if(in_array(6, $room_array)) echo 'checked'; ?>>5+
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--noindex-->
                            <div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
                                <a href="#" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
                                <div class="action-filter">
                                    <a href="/military">Сбросить</a>
                                    <button type="submit">Искать</button>
                                </div>
                            </div>
<!--/noindex-->
                        </div>

                    </form>

<!--noindex-->
                    <div id="metro_n_districts" class="mfp-hide white-popup-block distromet" data-cat="resale">
                        <div class="tabs-popup">
                            <a href="#" class="chpopsh" id="sdistricts">Район</a>
                            <a href="#" class="chpopsh active" id="smetro">Метро</a>
                        </div>
                        <div class="close-popup"></div>
                        <div class="mapmetro popsh active" id="pop-smetro">
                            <?php foreach($metro as $value):?>
                                <div class="label-metro" style="top: <?=$value['top']?>px; left: <?=$value['left']?>px; <?php if ($value['width'] > 0):?>width:<?=$value['width']?>px;<?php endif;?> <?php if (!empty($value['align'])):?>text-align:<?=$value['align']?>;<?php endif;?> <?php if (!empty($value['letter_spacing'])):?>letter-spacing:<?=$value['letter_spacing']?>px;<?php endif;?>">
                                    <?php if(in_array($value['id'], $inmetro)):?>
                                        <a href="#" data-id="<?php echo $value['id']; ?>" id="metro_item_<?php echo $value['id']; ?>" class="<?php if(in_array($value['id'], $metro_array)):?>select <?php endif;?><?php if (!empty($value['class'])):?><?=$value['class']?><?php endif;?>"><?php echo $value['label']; ?></a>
                                    <?php else: ?>
                                        <span><?=$value['label']?></span>
                                    <?php endif;?>
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
                                            <li data-id="<?=$value['id']?>" class="metro_item_<?=$value['id']?><?php if(in_array($value['id'], $metro_array)):?> select<?php endif;?>">
                                                <?php if(in_array($value['id'], $inmetro)):?>
                                                    <a href="#"><?=$value['name']?></a>
                                                <?php else: ?>
                                                    <span><?=$value['name']?></span>
                                                <?php endif;?>
                                            </li>
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
                                    <img src="/asset/assets/img/d<?php echo $value['id']; ?>.png" width="532" height="672" usemap="#map" class="area_region area_region_<?php echo $value['id']; ?>" style="display: <?php echo (in_array($value['id'], $rayon_array))?'block':'none'; ?>;">
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
                                        <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#" data-name="<?=$value['name']?>"><?=$value['name']?> район</a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                    <div class="clearfix"></div>
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
                                        <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#" data-name="<?=$value['name']?>"><?=$value['name']?> район</a></li>
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
                </div>

            </div>
        </div>
    </div>
</div>