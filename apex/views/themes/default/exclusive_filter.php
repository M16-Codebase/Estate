<?php
$ray = array(26,27);
foreach($d_rayon as $k=>$v) { if(in_array($k, $ray)) { unset($d_rayon[$k]); } }
$roo = array(26,27);
foreach($d_rayon as $k=>$v) { if(in_array($k, $ray)) { unset($d_rayon[$k]); } }

$rayon_array = array();
$metro_array = array();

$this->load->model('metro/metro_model','metro_model');
$metro = $this->metro_model->getAll();
$inmetro = $this->metro_model->getAllFilter(9);

$this->load->model('rayon/rayon_model','rayon_model');
$rayons = $this->rayon_model->getAllFilter(9);
$spb = array(); $lenobl = array();
foreach ($rayons as $v){
    if ($v['is_city'] == 1)
        $spb[] = $v;
    else
        $lenobl[] = $v;
}

if(empty($params['rayon'])) { $params['rayon'] = '0'; } else {$rayon_array = explode(",", $params['rayon']);}
if(empty($params['price_from'])) { $params['price_from'] = $filter->minMax['min']; }
if(empty($params['price_to'])) { $params['price_to'] = $filter->minMax['max']; }
if(empty($params['room'])) { $params['room'] = '0'; } else {$room_array = explode(",", $params['room']);}
if(empty($params['type'])) { $params['type'] = '0'; } else {$type_array = explode(",", $params['type']);}
if(empty($params['square_from'])) { $params['square_from'] = $filter->minMaxS['min']; }
if(empty($params['square_to'])) { $params['square_to'] = $filter->minMaxS['max']; }
if(empty($params['adress'])) { $params['adress'] = ''; }
if(empty($params['name'])) { $params['name'] = ''; }

if(count($metro_array) == 0){$metro_text = 'Метро: не выбрано';} else {$metro_text = 'Метро: выбрано - '.count($metro_array);}
if(count($rayon_array) == 0){$rayon_text = 'Район: не выбрано';} else {$rayon_text = 'Район: выбрано - '.count($rayon_array);}

$long = 0;

if (count($metro_array) > 0 || count($rayon_array) > 0 ||
    !empty($params['adress']) || !empty($params['name'])) $long = 1;
?>


<div class="mainhead block-novostoy-intro" style="background: url('/asset/assets/img/elitewide.jpg') no-repeat top center">
    <div class="contain">
        <div class="mainfilter">
            <div class="filterchoose clearfix">
                <?php echo $this->load->view(getPath(__FILE__,'/parth_filter_menu'), array('current' => 'exclusive'), TRUE); ?>
            </div>
            <div class="filtercont">
                <div class="filter-display active" id="cont-1">

                    <form method="get" class="exclusive-form" action="/exclusive">
                        <div class="nfilter<?php if($long==1):?> showlong<?php endif; ?>">
                            <div class="short-filter clearfix">
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>стоимость <span>(млн.руб)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="price-slider-exclusive" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv price-b" data-min="<?php echo $filter->minMax['min']; ?>" data-max="<?php echo $filter->minMax['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="price_from" id="exclPrice-from" class="price_input" value="<?php echo $params['price_from']; ?>" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="price_to" id="exclPrice-to" class="price_input" value="<?php echo $params['price_to']; ?>" />
                                        </div>
                                    </div>

                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter-price">
                                        <label>площадь <span>(м<sup>2</sup>)</span></label>
                                        <div class="filter-slider-container">
                                            <div id="square-slider-exclusive" class="filter-slider range"></div>
                                        </div>
                                        <div class="prdiv square-b" data-min="0" data-max="<?php echo $filter->minMaxS['max']; ?>">
                                            <span class="sq_uch">от </span>
                                            <input type="text" name="square_from" id="exclSquare-from" class="price_input" value="0" />
                                            <span class="sq_uch"> до </span>
                                            <input type="text" name="square_to" id="exclSquare-to" class="price_input" value="<?php echo $params['square_to']; ?>" />
                                        </div>
                                    </div>


                                    <div class="clearfix one-filter long-filter mtop37">
                                        <input type="text" placeholder="Введите адрес" name="adress" value="<?php echo $params['adress'];?>" class="filter-input exclusive_txt" id="aadrr" data-name="elite">
                                    </div>
                                </div>
                                <div class="lcolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="hidden" name="rayon" value="<?=$params['rayon']?>" class="sel_build_rayon" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal distrolist" data-id="districts"><?=$rayon_text?></a>
                                    </div>
                                    <div class="clearfix one-filter wd235 long-filter">
                                        <input type="hidden" name="type" value="<?=$params['type']?>" class="sel_type" />
                                        <select class="multiselect-type-elite block-right" multiple="multiple">
                                            <?php foreach($filter->elite_type as $k=>$r): ?>
                                                <option value="<?php echo $k; ?>" <?php if($params['type'] && in_array($k, $type_array)) echo 'selected'; ?>><?php echo $r; ?></option>
                                            <?php endforeach; ?>

                                        </select>
                                    </div>

                                    <div class="clearfix one-filter long-filter">
                                        <input type="hidden" name="metro" value="<?=$params['metro']?>" class="sel_build_metro" />
                                        <a href="#metro_n_districts" class="shpopupa popup-modal metrolist" data-id="metro"><?=$metro_text?></a>
                                    </div>
                                </div>
                                <div class="scolumn fcolumn">
                                    <div class="clearfix one-filter">
                                        <input type="hidden" name="room" value="<?=$params['room']?>" class="sel_room" />
                                        <div class="btn-group chk-btn" data-toggle="buttons">


                                            <label class="btn btn-primary<?php if(in_array(2, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="2" <?php if(in_array(2, $room_array)) echo 'checked'; ?> class="exclusive-check">1
                                            </label>

                                            <label class="btn btn-primary<?php if(in_array(3, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="3,16" <?php if(in_array(3, $room_array)) echo 'checked'; ?> class="exclusive-check">2
                                            </label>
                                            <label class="btn btn-primary<?php if(in_array(4, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="4,17" class="exclusive-check" <?php if(in_array(4, $room_array)) echo 'checked'; ?>>3
                                            </label>
                                            <label class="btn btn-primary<?php if(in_array(5, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" value="5,18" class="exclusive-check" <?php if(in_array(5, $room_array)) echo 'checked'; ?>>4
                                            </label>
                                            <label class="btn btn-primary<?php if(in_array(6, $room_array)) echo ' active'; ?>">
                                                <input type="checkbox" class="exclusive-check" value="6,7,32,31,21" <?php if(in_array(6, $room_array)) echo 'checked'; ?>>5+
                                            </label>
                                        </div>
                                    </div>
                                    <div class="clearfix one-filter long-filter">
                                        <input type="hidden" name="country" value="0" class="sel_abroad_country" />
                                        <input type="text" id="exclusivecity" placeholder="ЖК" name="name" value="<?=$params['name']?>" class="filter-input filter-input-large elite_txt">
                                    </div>
                                </div>
                            </div>
<!--noindex-->
                            <div class="botfilter<?php if($long==1):?> longs<?php endif; ?>">
                                <a href="#" class="show-hide<?php if($long==0):?> short<?php endif; ?>"><?php if($long==0):?>Расширенный поиск<?php else: ?>Свернуть<?php endif; ?></a>
                                <div class="action-filter">
                                    <a href="/exclusive">Сбросить</a>
                                    <button type="submit">Искать</button>
                                </div>
                            </div>
<!--/noindex-->
                        </div>

                    </form>

<!--noindex-->
                    <div id="metro_n_districts" class="mfp-hide white-popup-block distromet" data-cat="exclusive">
                        <div class="tabs-popup">
                            <a href="#" class="chpopsh" id="sdistricts">Район</a>
                            <a href="#" class="chpopsh active" id="smetro">Метро</a>
                        </div>
                        <div class="close-popup"></div>
                        <div class="mapmetro popsh active" id="pop-smetro">
                            <?php foreach($metro as $value):?>
                                <div class="label-metro" style="top: <?=$value['top']?>px; left: <?=$value['left']?>px; <?php if ($value['width'] > 0):?>width:<?=$value['width']?>px;<?php endif;?> <?php if (!empty($value['align'])):?>text-align:<?=$value['align']?>;<?php endif;?> <?php if (!empty($value['letter_spacing'])):?>letter-spacing:<?=$value['letter_spacing']?>px;<?php endif;?>">
                                    <?php if(in_array($value['id'], $inmetro)):?>
                                        <a href="#" data-id="<?=$value['id']?>" id="metro_item_<?=$value['id']?>" class="<?php if(in_array($value['id'], $metro_array)):?>select <?php endif;?><?php if (!empty($value['class'])):?><?=$value['class']?><?php endif;?>"><?=$value['label']?></a>
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
                                    <div class="clearfix">
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
                                    </div>
                                <?php endif; ?>
                                <?php if(count($lenobl > 0)):?>
                                    <?php $half = (int)ceil(count($lenobl) / 2);?>
                                    <p class="h3">Ленинградская область</p>
                                    <div class="clearfix">
                                    <ul>
                                    <?php foreach($lenobl as $k => $value):?>
                                        <?php if($k == $half):?>
                                            </ul>
                                            <ul>
                                        <?php endif;?>
                                        <li data-id="<?=$value['id']?>" class="distr_item_<?=$value['id']?><?php if(in_array($value['id'], $rayon_array)):?> select<?php endif;?>"><a href="#" data-name="<?=$value['name']?>"><?=$value['name']?> район</a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                    </div>
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


